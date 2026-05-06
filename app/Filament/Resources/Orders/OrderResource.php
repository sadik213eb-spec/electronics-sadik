<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function generateOrderNumber(): string
    {
        $last = Order::latest('id')->first();
        $nextNumber = $last ? ((int) substr($last->order_number, 2)) + 1 : 10000;

        return 'SA'.$nextNumber;
    }

    protected static function recalculateTotals(callable $get, callable $set): void
    {
        $items = $get('../../items') ?? [];
        $totalSale = 0;
        foreach ($items as $item) {
            $totalSale += floatval($item['subtotal'] ?? 0);
        }
        $discount = floatval($get('../../discount_amount') ?? 0);
        $shipping = floatval($get('../../shipping_cost') ?? 0);
        $set('../../total_amount', $totalSale);
        $set('../../grand_total', $totalSale - $discount + $shipping);
    }

    protected static function recalculateTotalsFromRoot(callable $get, callable $set): void
    {
        $items = $get('items') ?? [];
        $totalSale = 0;
        foreach ($items as $item) {
            $totalSale += floatval($item['subtotal'] ?? 0);
        }
        $discount = floatval($get('discount_amount') ?? 0);
        $shipping = floatval($get('shipping_cost') ?? 0);
        $set('total_amount', $totalSale);
        $set('grand_total', $totalSale - $discount + $shipping);
    }

    protected static function resolveShippingCost(string $zone, array $items): float
    {
        $totalShipping = 0;
        $field = $zone === 'outside_dhaka' ? 'shipping_outside_dhaka' : 'shipping_inside_dhaka';
        foreach ($items as $item) {
            $productId = $item['product_id'] ?? null;
            $quantity = intval($item['quantity'] ?? 1);
            if ($productId) {
                $rate = Product::where('id', $productId)->value($field) ?? 0;
                $totalShipping += floatval($rate) * $quantity;
            }
        }

        return $totalShipping;
    }

    protected static function fillShippingFromAddress(?object $address, callable $set): void
    {
        if ($address) {
            $set('shipping_name', $address->name);
            $set('shipping_phone', $address->mobile);
            $set('shipping_address', $address->address);
            $set('shipping_city', $address->city);
        } else {
            $set('shipping_name', null);
            $set('shipping_phone', null);
            $set('shipping_address', null);
            $set('shipping_city', null);
        }
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ---- ORDER INFO ----
                Section::make('Order Information')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('order_number')
                            ->required()
                            ->default(fn () => static::generateOrderNumber())
                            ->disabled()
                            ->dehydrated()
                            ->unique(ignoreRecord: true),

                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->default('pending'),

                        Select::make('order_status')
                            ->options([
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('processing'),

                        // ✅ Only cod and pfs
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cod' => 'Cash on Delivery',
                                'pfs' => 'Pickup from Showroom',
                            ])
                            ->required()
                            ->default('cod')
                            ->native(false),

                    ])->columns(2),

                // ---- ORDER ITEMS ----
                Section::make('Order Items')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->columnSpan(2)
                                    ->options(function () {
                                        return Product::all()->mapWithKeys(function ($product) {
                                            $label = $product->name;
                                            if ($product->stock_status === 'out_of_stock' || $product->stock <= 0) {
                                                $label .= ' — ⚠ Out of Stock';
                                            }

                                            return [$product->id => $label];
                                        });
                                    })
                                    ->required()
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('mrp_price', $product->price);
                                            $set('unit_price', $product->sale_price ?? $product->price);
                                            $set('available_stock', $product->stock <= 0 ? '⚠ Out of Stock' : $product->stock);
                                            $qty = $get('quantity') ?? 1;
                                            $price = $product->sale_price ?? $product->price;
                                            $set('subtotal', floatval($price) * intval($qty));
                                        }
                                        $zone = $get('../../shipping_zone') ?? 'inside_dhaka';
                                        $items = $get('../../items') ?? [];
                                        $set('../../shipping_cost', static::resolveShippingCost($zone, $items));
                                        static::recalculateTotals($get, $set);
                                    }),

                                TextInput::make('mrp_price')
                                    ->label('MRP (৳)')
                                    ->prefix('৳')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(false),

                                TextInput::make('unit_price')
                                    ->label('SP (৳)')
                                    ->prefix('৳')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $qty = $get('quantity') ?? 1;
                                        $set('subtotal', floatval($state) * intval($qty));
                                        static::recalculateTotals($get, $set);
                                    }),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->minValue(1)
                                    ->reactive()
                                    ->rules([
                                        function (callable $get) {
                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                $product = Product::find($get('product_id'));
                                                if (! $product) {
                                                    return;
                                                }
                                                if ($product->stock_status === 'out_of_stock' || $product->stock <= 0) {
                                                    $fail("❌ \"{$product->name}\" is out of stock.");

                                                    return;
                                                }
                                                if (intval($value) > $product->stock) {
                                                    $fail("Only {$product->stock} units available.");
                                                }
                                            };
                                        },
                                    ])
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $up = $get('unit_price') ?? 0;
                                        $set('subtotal', floatval($up) * intval($state));
                                        $zone = $get('../../shipping_zone') ?? 'inside_dhaka';
                                        $items = $get('../../items') ?? [];
                                        $set('../../shipping_cost', static::resolveShippingCost($zone, $items));
                                        static::recalculateTotals($get, $set);
                                    }),

                                TextInput::make('subtotal')
                                    ->label('Subtotal (৳)')
                                    ->prefix('৳')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('available_stock')
                                    ->label('In Stock')
                                    ->disabled()
                                    ->dehydrated(false),

                            ])->columns(7)
                            ->addActionLabel('+ Add Product')
                            ->columnSpanFull(),
                    ]),

                // ---- ORDER TOTALS ----
                Section::make('Order Totals')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Total Sale Amount (৳)')
                            ->numeric()
                            ->prefix('৳')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('discount_amount')
                            ->label('Discount (৳)')
                            ->numeric()
                            ->prefix('৳')
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                static::recalculateTotalsFromRoot($get, $set);
                            }),

                        Select::make('shipping_zone')
                            ->label('Shipping Zone')
                            ->options([
                                'inside_dhaka' => 'Inside Dhaka',
                                'outside_dhaka' => 'Outside Dhaka',
                            ])
                            ->default('inside_dhaka')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $items = $get('items') ?? [];
                                $set('shipping_cost', static::resolveShippingCost($state, $items));
                                static::recalculateTotalsFromRoot($get, $set);
                            }),

                        TextInput::make('shipping_cost')
                            ->label('Shipping Cost (৳)')
                            ->numeric()
                            ->prefix('৳')
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                static::recalculateTotalsFromRoot($get, $set);
                            }),

                        TextInput::make('grand_total')
                            ->label('Grand Total (৳)')
                            ->numeric()
                            ->prefix('৳')
                            ->disabled()
                            ->dehydrated(),

                    ])->columns(2),

                // ---- CUSTOMER SELECTION ----
                Section::make('Customer')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('customer_id')
                            ->label('Select Customer')
                            ->relationship('customer', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} — {$record->mobile}")
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('selected_address_id', null);
                                $set('shipping_name', null);
                                $set('shipping_phone', null);
                                $set('shipping_address', null);
                                $set('shipping_city', null);

                                $customer = Customer::find($state);
                                if ($customer) {
                                    $default = $customer->addresses()->where('is_default', 1)->first()
                                        ?? $customer->addresses()->first();
                                    static::fillShippingFromAddress($default, $set);
                                    if ($default) {
                                        $set('selected_address_id', $default->id);
                                    }
                                }
                            })
                            ->createOptionForm([
                                Grid::make(2)->schema([
                                    Section::make('Customer Information')->schema([
                                        TextInput::make('name')->label('Full Name')->required()->maxLength(255),
                                        TextInput::make('mobile')->label('Mobile')->required()->maxLength(20),
                                        TextInput::make('email')->label('Email')->email()->nullable(),
                                        TextInput::make('city')->label('City')->required(),
                                        Textarea::make('address')->label('Address')->rows(3)->columnSpanFull(),
                                    ])->columns(1),
                                ]),
                            ])
                            ->createOptionAction(function ($action) {
                                $action->modalHeading('Create New Customer')->modalWidth('2xl');
                            }),

                        Select::make('selected_address_id')
                            ->label('Select Shipping Address')
                            ->options(function (callable $get) {
                                $customerId = $get('customer_id');
                                if (! $customerId) {
                                    return [];
                                }
                                $customer = Customer::find($customerId);
                                if (! $customer) {
                                    return [];
                                }
                                $typeLabels = [
                                    'home' => '🏠 Home',
                                    'office' => '🏢 Office',
                                    'others' => '📍 Others',
                                ];

                                return $customer->addresses->mapWithKeys(function ($addr) use ($typeLabels) {
                                    $type = $typeLabels[$addr->type] ?? ucfirst($addr->type);
                                    $label = "{$type} — {$addr->name}, {$addr->city}";

                                    return [$addr->id => $label];
                                })->toArray();
                            })
                            ->native(false)
                            ->placeholder('— Pick a saved address —')
                            ->reactive()
                            ->visible(fn (callable $get) => filled($get('customer_id')))
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) {
                                    return;
                                }
                                $address = CustomerAddress::find($state);
                                static::fillShippingFromAddress($address, $set);
                            }),

                    ])->columns(2),

                // ---- SHIPPING ADDRESS ----
                Section::make('Shipping Address')
                    ->columnSpanFull()
                    ->description('Auto-filled from selected address. You can edit manually if needed.')
                    ->schema([
                        TextInput::make('shipping_name')
                            ->label('Recipient Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('shipping_phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(15),

                        TextInput::make('shipping_city')
                            ->label('City')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('shipping_zip')
                            ->label('ZIP / Postal Code')
                            ->maxLength(20),

                        Textarea::make('shipping_address')
                            ->label('Street Address')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                    ])->columns(2),

                // ---- CUSTOMER NOTE ----
                Section::make('Customer Note')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('customer_note')
                            ->placeholder('Enter any special instructions or notes...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('customer.mobile')
                    ->label('Phone No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('grand_total')
                    ->label('Total (৳)')
                    ->numeric()
                    ->prefix('৳')
                    ->sortable()
                    ->weight('bold')
                    ->toggleable(),

                // ✅ Payment Method badge column
                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cod' => 'warning',
                        'pfs' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cod' => 'Cash on Delivery',
                        'pfs' => 'Pickup from Showroom',
                        default => $state,
                    })
                    ->toggleable(),

                TextColumn::make('order_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'processing' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d/m/y')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),

                Action::make('printInvoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Order $record): string => route('orders.invoice', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_orders');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_orders');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit_orders');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_orders');
    }
}

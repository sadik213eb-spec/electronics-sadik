<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
// use Filament\Schemas\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
// use Filament\Tables\Columns\IconColumn;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ---- BASIC INFORMATION ----
                Section::make('Basic Information')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $set('slug', Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable(),

                        Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->required()
                            ->searchable(),

                        TextInput::make('sold_by')
                            ->label('Sold By')
                            ->maxLength(255)
                            ->placeholder('e.g. Haier Bangladesh'),

                    ])->columns(2),

                // ---- PRICING & INVENTORY ----
                Section::make('Pricing & Inventory')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('price')
                            ->label('Regular Price (৳)')
                            ->numeric()
                            ->prefix('৳')
                            ->required()
                            ->reactive(),

                        TextInput::make('sale_price')
                            ->label('Sale Price (৳)')
                            ->numeric()
                            ->prefix('৳')
                            ->nullable()
                            // ✅ Sale price must be less than or equal to MRP
                            ->rules([
                                function (callable $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        $mrp = floatval($get('price') ?? 0);
                                        if ($value !== null && $value !== '' && floatval($value) > $mrp) {
                                            $fail('Sale price must be less than or equal to the regular price (৳' . number_format($mrp, 2) . ').');
                                        }
                                    };
                                },
                            ]),

                        TextInput::make('stock')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->reactive()
                            ->minValue(0)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (intval($state) <= 0) {
                                    $set('stock_status', 'out_of_stock');
                                } else {
                                    $set('stock_status', 'in_stock');
                                }
                            }),

                        TextInput::make('sku')
                            ->label('Product SKU (Code)')
                            ->placeholder('Optional'),

                        // ✅ Stock status controls out of stock — separate from status
                        Select::make('stock_status')
                            ->label('Stock Status')
                            ->options([
                                'in_stock' => 'In Stock',
                                'out_of_stock' => 'Out of Stock',
                            ])
                            ->default('in_stock')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // ✅ Does NOT touch the status field anymore
                                // stock_status is the only source of truth for stock
                            }),

                    ])->columns(2),

                // ---- SHIPPING COST ----
                Section::make('Shipping Cost')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('shipping_inside_dhaka')
                            ->label('Inside Dhaka (৳)')
                            ->numeric()
                            ->prefix('৳')
                            ->default(60)
                            ->required(),

                        TextInput::make('shipping_outside_dhaka')
                            ->label('Outside Dhaka (৳)')
                            ->numeric()
                            ->prefix('৳')
                            ->default(120)
                            ->required(),
                    ])->columns(2),

                // ---- MEDIA & DESCRIPTION ----
                Section::make('Media & Description')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('images')
                            ->label('Product Images')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->openable()
                            ->downloadable()
                            ->panelLayout('grid')
                            ->directory('products')
                            ->maxFiles(10)
                            ->columnSpanFull(),

                        Textarea::make('short_description')
                            ->maxLength(500)
                            ->rows(5)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h1',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                                'table',
                            ])
                            ->extraInputAttributes(['style' => 'min-height: 400px;'])
                            ->columnSpanFull(),

                        Textarea::make('warranty')
                            ->label('Warranty Information')
                            ->placeholder('e.g. (Official) 12 years compressor, 5 years spare parts & free service warranty....')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                // ---- STATUS & VISIBILITY ----
                Section::make('Status & Visibility')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Featured Product'),

                        Select::make('status')
                            ->options([
                                'active' => 'Published',
                                'inactive' => 'Draft',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Image')
                    ->circular()
                    ->stacked()
                    ->limit(1),

                TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    // ->formatStateUsing(function ($record) {
                    //     return $record->category->parent
                    //     ? "{$record->category->parent->name} > {$record->category->name}"
                    //     : $record->category->name;
                    // })
                    ->sortable()
                    ->searchable(),

                // TextColumn::make('brand.name')
                //     ->label('Brand')
                //     ->sortable(),

                TextColumn::make('price')
                    ->label('Price (৳)')
                    ->prefix('৳')
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label('Sale Price (৳)')
                    ->prefix('৳')
                    ->sortable(),

                // TextColumn::make('shipping_inside_dhaka')
                //     ->label('Inside Dhaka (৳)')
                //     ->prefix('৳')
                //     ->sortable()
                //     ->toggleable(),

                // TextColumn::make('shipping_outside_dhaka')
                //     ->label('Outside Dhaka (৳)')
                //     ->prefix('৳')
                //     ->sortable()
                //     ->toggleable(),

                // Stock with color badge
                // TextColumn::make('stock')
                //     ->sortable()
                //     ->badge()
                //     ->color(fn ($state): string => match (true) {
                //         $state <= 0 => 'danger',
                //         $state <= 5 => 'warning',
                //         default => 'success',
                //     })
                //     ->formatStateUsing(fn ($state): string => $state <= 0
                //         ? 'Out of Stock'
                //         : $state.' in stock'
                //     ),

                // Stock status badge
                TextColumn::make('stock_status')
                    ->label('Stock Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'in_stock' => 'success',
                        'out_of_stock' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'in_stock' => 'In Stock',
                        'out_of_stock' => 'Out of Stock',
                        default => $state,
                    }),

                // IconColumn::make('is_featured')
                //     ->label('Featured')
                //     ->boolean(),

                // TextColumn::make('status')
                //     ->badge()
                //     ->color(fn (string $state): string => match ($state) {
                //         'active' => 'success',
                //         'inactive' => 'danger',
                //         'out_of_stock' => 'warning',
                //         default => 'gray',
                //     }),

                TextColumn::make('created_at')
                    ->label('Publish Date')
                    ->date('d/m/y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_products');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_products');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit_products');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_products');
    }
}

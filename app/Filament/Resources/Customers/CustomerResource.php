<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Models\Customer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction as TableDeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction as TableEditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Customers';

    protected static ?string $modelLabel = 'Customer';

    protected static ?int $navigationSort = 4;

    protected static UnitEnum|string|null $navigationGroup = null;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ---- CUSTOMER INFORMATION ----
                Section::make('Customer Information')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('mobile')
                            ->label('Mobile Number')
                            ->required()
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->nullable()
                            ->maxLength(255),

                        TextInput::make('city')
                            ->label('City')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                // ---- SHIPPING INFORMATION ----
                // Uses tab-style toggle: Home | Office | Others
                // Each type can only be added once
                Section::make('Shipping Information')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('addresses')
                            ->relationship('addresses')
                            ->label('')
                            ->addActionLabel('+ Add Address')
                            ->maxItems(3)
                            ->collapsible()
                            ->itemLabel(function (array $state): string {
                                $labels = [
                                    'home' => '🏠 Home',
                                    'office' => '🏢 Office',
                                    'others' => '📍 Others',
                                ];
                                $type = $labels[$state['type'] ?? ''] ?? 'Address';
                                $name = $state['name'] ?? '';

                                return $name ? "{$type} — {$name}" : $type;
                            })
                            ->schema([

                                // Toggle-style type selector
                                Select::make('type')
                                    ->label('Address Type')
                                    ->options([
                                        'home' => '🏠 Home',
                                        'office' => '🏢 Office',
                                        'others' => '📍 Others',
                                    ])
                                    ->default('home')
                                    ->required()
                                    ->native(false)
                                    ->columnSpanFull()
                                    ->rules([
                                        function ($get, $livewire) {
                                            return function (string $attribute, $value, \Closure $fail) use ($livewire) {
                                                $addresses = collect($livewire->data['addresses'] ?? []);
                                                $typeCounts = $addresses->pluck('type')->filter()->countBy()->toArray();
                                                if (($typeCounts[$value] ?? 0) > 1) {
                                                    $fail("A '{$value}' address already exists. Each type can only be added once.");
                                                }
                                            };
                                        },
                                    ]),

                                TextInput::make('name')
                                    ->label('Recipient Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('mobile')
                                    ->label('Mobile Number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),

                                TextInput::make('city')
                                    ->label('City')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('address')
                                    ->label('Full Address')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Toggle::make('is_default')
                                    ->label('Set as Default Address')
                                    ->default(false)
                                    ->columnSpanFull(),

                            ])->columns(2),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mobile')
                    ->label('Mobile')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Mobile copied!'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('orders_count')
                    ->label('No of Orders')
                    ->counts('orders')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->date('d/m/y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                TableEditAction::make(),
                TableDeleteAction::make(),
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_customers');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_customers');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit_customers');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_customers');
    }
}

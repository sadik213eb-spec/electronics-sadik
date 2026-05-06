<?php

namespace App\Filament\Resources\Coupons;

use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Resources\Coupons\Pages\ListCoupons;
use App\Models\Coupon;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Coupon Code';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Marketing';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Coupon Information')
                    ->columnSpanFull()
                    ->schema([

                        TextInput::make('code')
                            ->label('Coupon Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('e.g. EB1000')
                            ->extraAttributes(['style' => 'text-transform:uppercase'])
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state)),

                        Select::make('type')
                            ->label('Discount Type')
                            ->options([
                                'flat'       => 'Flat (Fixed Amount ৳)',
                                'percentage' => 'Percentage (%)',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('amount')
                            ->label('Discount Amount')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        DateTimePicker::make('start_date')
                            ->label('Start Date')
                            ->required()
                            ->native(false),

                        DateTimePicker::make('end_date')
                            ->label('End Date')
                            ->required()
                            ->native(false)
                            ->after('start_date'),

                    ])->columns(2),

                // ✅ Product Search & List Section
                Section::make('Apply to Specific Products')
                    ->columnSpanFull()
                    ->description('Search and select products this coupon applies to. Leave empty to apply to all products.')
                    ->schema([

                        Select::make('product_ids')
                            ->label('Search & Select Products')
                            ->multiple()
                            ->options(function () {
                                return Product::where('status', 'active')
                                    ->get()
                                    ->mapWithKeys(fn ($p) => [
                                        $p->id => $p->name
                                    ]);
                            })
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->helperText('Select one or more products. The coupon will only work for selected products.'),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->formatStateUsing(fn ($state) => '#' . $state)
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'flat'       => 'info',
                        'percentage' => 'success',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'flat'       => 'Flat',
                        'percentage' => 'Percentage',
                        default      => $state,
                    }),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(function ($record) {
                        return $record->type === 'percentage'
                            ? $record->amount . '%'
                            : '৳' . number_format($record->amount);
                    })
                    ->sortable(),

                TextColumn::make('product_ids')
                    ->label('Products')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return 'All Products';
                        $ids = is_array($state) ? $state : json_decode($state, true);
                        return collect($ids)->implode(', ');
                    }),

                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('End Date')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->defaultSort('id', 'desc')
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
            'index'  => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit'   => EditCoupon::route('/{record}/edit'),
        ];
    }
}

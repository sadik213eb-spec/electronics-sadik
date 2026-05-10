<?php

namespace App\Filament\Resources\Offers;

use App\Filament\Resources\Offers\Pages\CreateOffer;
use App\Filament\Resources\Offers\Pages\EditOffer;
use App\Filament\Resources\Offers\Pages\ListOffers;
use App\Models\Offer;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Offer';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'Marketing';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([

                // ---- LEFT: Main Info (3 cols) ----
                Section::make('Offer Information')
                    ->columnSpan(3)
                    ->schema([

                        TextInput::make('name')
                            ->label('Offer Name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('sort_order')
                            ->label('Sorting Order')
                            ->numeric()
                            ->default(0),

                        TextInput::make('seo_title')
                            ->label('SEO Title')
                            ->maxLength(255),

                        Textarea::make('seo_description')
                            ->label('SEO Description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Select::make('product_ids')
                            ->label('Search Product')
                            ->multiple()
                            ->options(function () {
                                return Product::where('status', 'active')
                                    ->get()
                                    ->mapWithKeys(fn($p) => [$p->id => $p->name]);
                            })
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->helperText('Select products to include in this offer.'),

                    ])->columns(2),

                // ---- RIGHT: Publish + Images (1 col) ----
                Section::make('Publish')
                    ->columnSpan(1)
                    ->schema([

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required()
                            ->native(false),

                        Toggle::make('show_timer')
                            ->label('Show Timer')
                            ->default(false),

                        Toggle::make('show_on_page')
                            ->label('Show on Offer Page')
                            ->default('true'),

                        DateTimePicker::make('start_date')
                            ->label('Start Date')
                            ->required()
                            ->native(false),

                        DateTimePicker::make('end_date')
                            ->label('End Date')
                            ->required()
                            ->native(false)
                            ->after('start_date'),

                        FileUpload::make('image')
                            ->label('Offer Image (Thumbnail)')
                            ->image()
                            ->disk('public')
                            ->directory('offers')
                            ->nullable(),

                        FileUpload::make('banner')
                            ->label('Offer Banner')
                            ->image()
                            ->disk('public')
                            ->directory('offers')
                            ->nullable(),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image')
                    ->label('Thumbnail')
                    ->square()
                    ->size(60),

                TextColumn::make('name')
                    ->label('Offer Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('End Date')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
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
            'index' => ListOffers::route('/'),
            'create' => CreateOffer::route('/create'),
            'edit' => EditOffer::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\HomeSections;

use App\Filament\Resources\HomeSections\Pages\CreateHomeSection;
use App\Filament\Resources\HomeSections\Pages\EditHomeSection;
use App\Filament\Resources\HomeSections\Pages\ListHomeSections;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Media;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class HomeSectionResource extends Resource
{
    protected static ?string $model = HomeSection::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Layout Manage';

    protected static string|UnitEnum|null $navigationGroup = 'Web Manage';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            // ---- SELECT TYPE ----
            Section::make('Select Content Type')
                ->columnSpanFull()
                ->schema([
                    Radio::make('type')
                        ->label('')
                        ->options([
                            'image' => 'Image',
                            'simple_product' => 'Simple Product',
                            'category_list' => 'Category List',
                            'offer_product' => 'Offer Product',
                        ])
                        ->inline()
                        ->required()
                        ->live(),
                ]),

            // ---- STATUS & PRIORITY ----
            Section::make('Publish')
                ->columnSpanFull()
                ->schema([
                    Select::make('is_active')
                        ->label('Status')
                        ->options([
                            1 => 'Active',
                            0 => 'Inactive',
                        ])
                        ->default(1)
                        ->required(),

                    TextInput::make('priority')
                        ->label('Priority')
                        ->numeric()
                        ->default(0)
                        ->required(),
                ])->columns(2),

            // ---- IMAGE SECTION ----
            Section::make('Image List')
                ->columnSpanFull()
                ->visible(fn (Get $get) => $get('type') === 'image')
                ->schema([
                    Repeater::make('items')
                        ->label('')
                        ->relationship('items')
                        ->schema([
                            // Replace FileUpload with this in the image repeater:
                            Select::make('image')
                                ->label('Select Image from Media')
                                ->options(function () {
                                    return Media::all()->mapWithKeys(function ($media) {
                                        return [$media->path => $media->name];
                                    })->toArray();
                                })
                                ->searchable()
                                ->nullable(),
                            TextInput::make('link')
                                ->label('Link URL')
                                ->nullable(),

                            TextInput::make('sort')
                                ->label('Sort')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(3)
                        ->addActionLabel('Add Image')
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                            $data['type'] = 'image';

                            return $data;
                        }),
                ]),
            // ---- SIMPLE PRODUCT SECTION ----
            Section::make('Product Slider Management')
                ->columnSpanFull()
                ->visible(fn (Get $get) => $get('type') === 'simple_product')
                ->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->required(),

                    TextInput::make('url')
                        ->label('View All URL')
                        ->nullable(),

                    Repeater::make('items')
                        ->label('Product List')
                        ->relationship('items')
                        ->schema([
                            Select::make('reference_id')
                                ->label('Product')
                                ->options(Product::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),

                            TextInput::make('sort')
                                ->label('Sort')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(2)
                        ->addActionLabel('Add Product')
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                            $data['type'] = 'product';

                            return $data;
                        }),
                ]),

            // ---- CATEGORY LIST SECTION ----
            Section::make('Category List')
                ->columnSpanFull()
                ->visible(fn (Get $get) => $get('type') === 'category_list')
                ->schema([
                    TextInput::make('title')
                        ->label('Section Title')
                        ->required(),

                    TextInput::make('description')
                        ->label('Section Description')
                        ->nullable(),

                    Repeater::make('items')
                        ->label('Category List')
                        ->relationship('items')
                        ->schema([
                            Select::make('reference_id')
                                ->label('Category')
                                ->options(Category::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),

                            TextInput::make('sort')
                                ->label('Sort')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(2)
                        ->addActionLabel('Add Category')
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                            $data['type'] = 'category';

                            return $data;
                        }),
                ]),

            // ---- OFFER PRODUCT SECTION ----
            Section::make('Offer Product Management')
                ->columnSpanFull()
                ->visible(fn (Get $get) => $get('type') === 'offer_product')
                ->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->required(),

                    TextInput::make('url')
                        ->label('View All URL')
                        ->nullable(),

                    Repeater::make('items')
                        ->label('Product List')
                        ->relationship('items')
                        ->schema([
                            Select::make('reference_id')
                                ->label('Product')
                                ->options(
                                    Product::whereNotNull('sale_price')
                                        ->where('sale_price', '>', 0)
                                        ->pluck('name', 'id')
                                )
                                ->searchable()
                                ->required(),

                            TextInput::make('sort')
                                ->label('Sort')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(2)
                        ->addActionLabel('Add Offer Product')
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                            $data['type'] = 'product';

                            return $data;
                        }),
                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'image' => 'info',
                        'simple_product' => 'success',
                        'category_list' => 'warning',
                        'offer_product' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'image' => 'Image',
                        'simple_product' => 'Simple Products',
                        'category_list' => 'Category List',
                        'offer_product' => 'Offer Products',
                        default => $state,
                    }),

                TextColumn::make('title')
                    ->label('Name')
                    ->default('—')
                    ->searchable(),

                TextColumn::make('priority')
                    ->label('Priority')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Active'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('priority', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomeSections::route('/'),
            'create' => CreateHomeSection::route('/create'),
            'edit' => EditHomeSection::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['Admin', 'Super Admin']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole(['Admin', 'Super Admin']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasRole(['Admin', 'Super Admin']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole(['Admin', 'Super Admin']);
    }
}

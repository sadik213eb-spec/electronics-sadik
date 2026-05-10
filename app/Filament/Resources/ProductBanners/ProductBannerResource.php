<?php

namespace App\Filament\Resources\ProductBanners;

use App\Filament\Resources\ProductBanners\Pages\CreateProductBanner;
use App\Filament\Resources\ProductBanners\Pages\EditProductBanner;
use App\Filament\Resources\ProductBanners\Pages\ListProductBanners;
use App\Models\ProductBanner;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
//use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProductBannerResource extends Resource
{
    protected static ?string $model = ProductBanner::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Product Banner';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return 'Web Manage';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Product Banner')
                    ->columnSpanFull()
                    ->schema([

                        FileUpload::make('image')
                            ->label('Banner Image')
                            ->image()
                            ->disk('public')
                            ->directory('product-banners')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('link')
                            ->label('Banner Link URL')
                            ->placeholder('e.g. /offers/samsung-sale or https://...')
                            ->nullable()
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Show on Product Pages')
                            ->default(true),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image')
                    ->label('Banner')
                    ->width(200)
                    ->height(60),

                TextColumn::make('link')
                    ->label('Link URL')
                    ->limit(50)
                    ->default('—'),

                ToggleColumn::make('is_active')
                    ->label('Active'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d/m/Y')
                    ->sortable(),

            ])
            ->defaultSort('created_at', 'desc')
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
            'index'  => ListProductBanners::route('/'),
            'create' => CreateProductBanner::route('/create'),
            'edit'   => EditProductBanner::route('/{record}/edit'),
        ];
    }
}

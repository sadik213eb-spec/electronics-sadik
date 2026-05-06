<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use App\Models\Media;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
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

                        // ✅ Select from Media Library
                        Select::make('image')
                            ->label('Category Image')
                            ->options(
                                Media::all()->mapWithKeys(fn ($media) => [
                                    $media->path => $media->name,
                                ])->toArray()
                            )
                            ->searchable()
                            ->nullable()
                            ->helperText('Select an image from the Media Library'),

                        Select::make('banner')
                            ->label('Category Banner')
                            ->options(
                                Media::all()->mapWithKeys(fn ($media) => [
                                    $media->path => $media->name,
                                ])->toArray()
                            )
                            ->searchable()
                            ->nullable()
                            ->helperText('Select an image from the Media Library'),

                        Select::make('parent_id')
                            ->label('Parent Category')
                            ->relationship('parent', 'name', function ($query, $record) {
                                if ($record) {
                                    $descendantIds = $record->descendants()->pluck('id')->toArray();

                                    return $query->where('id', '!=', $record->id)
                                        ->whereNotIn('id', $descendantIds);
                                }

                                return $query;
                            })
                            ->searchable()
                            ->placeholder('Select a parent')
                            ->nullable(),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),

                // ✅ Fixed image column
                ImageColumn::make('image')
                    ->disk('public')
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->image),

                TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->default('—')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_categories');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_categories');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit_categories');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_categories');
    }
}

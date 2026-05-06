<?php

namespace App\Filament\Resources\Media;

use App\Filament\Resources\Media\Pages\CreateMedia;
use App\Filament\Resources\Media\Pages\EditMedia;
use App\Filament\Resources\Media\Pages\ListMedia;
use App\Models\Media;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction as TableDeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction as TableEditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use UnitEnum;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Media';

    protected static ?string $modelLabel = 'Media';

    protected static ?string $pluralModelLabel = 'Media';

    protected static ?int $navigationSort = 1;

    protected static UnitEnum|string|null $navigationGroup = null;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('path')
                    ->label('Images')
                    ->image()
                    ->multiple()
                    ->disk('public')
                    ->directory('media')
                    ->visibility('public')
                    ->openable()
                    ->downloadable()
                    ->reorderable()
                    ->panelLayout('grid')
                    ->maxFiles(50)
                    ->maxParallelUploads(5)
                    // ✅ Keep original file name
                    ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file): string => str($file->getClientOriginalName())
                            ->beforeLast('.')
                            ->slug()
                            ->append('.'.$file->getClientOriginalExtension())
                            ->toString()
                    )
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('path')
                    ->label('Image')
                    ->disk('public')
                    ->width(200)
                    ->url(fn ($record) => $record->url),
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
            'index' => ListMedia::route('/'),
            'create' => CreateMedia::route('/create'),
            'edit' => EditMedia::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_media');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_media');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit_media');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_media');
    }
}

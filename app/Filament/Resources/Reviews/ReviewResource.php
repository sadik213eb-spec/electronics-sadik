<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Models\Review;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
//use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Reviews';

    protected static ?string $modelLabel = 'Review';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Review Information')
                    ->columnSpanFull()
                    ->schema([

                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('name')
                            ->label('Reviewer Name')
                            ->required()
                            ->maxLength(255),

                        Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '⭐ 1 - Poor',
                                2 => '⭐⭐ 2 - Fair',
                                3 => '⭐⭐⭐ 3 - Good',
                                4 => '⭐⭐⭐⭐ 4 - Very Good',
                                5 => '⭐⭐⭐⭐⭐ 5 - Excellent',
                            ])
                            ->required()
                            ->native(false)
                            ->default(5),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('approved')
                            ->required()
                            ->native(false),

                        Textarea::make('comment')
                            ->label('Review Comment')
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('Review Image (Optional)')
                            ->image()
                            ->disk('public')
                            ->directory('reviews')
                            ->nullable()
                            ->columnSpanFull(),

                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // ImageColumn::make('image')
                //     ->label('Photo')
                //     ->circular()
                //     ->defaultImageUrl(asset('images/no-image.png')),

                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('name')
                    ->label('Reviewer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (int $state): string => str_repeat('⭐', $state))
                    ->sortable(),

                TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(40)
                    ->toggleable(),

                // ✅ Status as inline dropdown
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d/m/y')
                    ->sortable()
                    ->toggleable(),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                SelectFilter::make('rating')
                    ->options([
                        1 => '⭐ 1',
                        2 => '⭐⭐ 2',
                        3 => '⭐⭐⭐ 3',
                        4 => '⭐⭐⭐⭐ 4',
                        5 => '⭐⭐⭐⭐⭐ 5',
                    ]),
            ])
            ->actions([

                // ✅ Status dropdown — updates immediately
                Action::make('changeStatus')
                    ->label('Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->form([
                        Select::make('status')
                            ->label('Change Status')
                            ->options([
                                'pending' => '🟡 Pending',
                                'approved' => '🟢 Approved',
                                'rejected' => '🔴 Rejected',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->fillForm(fn (Review $record) => ['status' => $record->status])
                    ->action(function (Review $record, array $data) {
                        $record->update(['status' => $data['status']]);
                    })
                    ->modalHeading('Update Review Status')
                    ->modalWidth('sm')
                    ->modalSubmitActionLabel('Update'),

                // ✅ Edit as modal/popup
                EditAction::make()
                    ->modalHeading('Edit Review')
                    ->modalWidth('2xl'),

                // ✅ Delete
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
            'index' => ListReviews::route('/'),
            // ✅ No separate create/edit pages — use modals
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_reviews');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_reviews');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit_reviews');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_reviews');
    }
}

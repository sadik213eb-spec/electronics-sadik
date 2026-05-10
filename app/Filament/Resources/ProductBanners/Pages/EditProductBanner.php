<?php

namespace App\Filament\Resources\ProductBanners\Pages;

use App\Filament\Resources\ProductBanners\ProductBannerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductBanner extends EditRecord
{
    protected static string $resource = ProductBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

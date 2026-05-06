<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $mediaImages = json_decode($data['media_library_images'] ?? '[]', true);
        $uploadedImages = $data['images'] ?? [];
        $data['images'] = array_values(array_unique(array_merge($uploadedImages, $mediaImages)));
        unset($data['media_library_images']);

        return $data;
    }
}

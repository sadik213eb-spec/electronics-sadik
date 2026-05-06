<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    // ✅ Pre-fill path as array for the multiple uploader
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // ✅ Wrap single path in array for multiple FileUpload
        if (isset($data['path']) && is_string($data['path'])) {
            $data['path'] = [$data['path']];
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $paths = $this->data['path'] ?? [];

        if (is_array($paths) && count($paths) > 0) {
            $path = $paths[0]; // take first on edit
            $fullPath = Storage::disk('public')->path($path);

            $record->update([
                'name' => pathinfo($path, PATHINFO_FILENAME),
                'path' => $path,
                'mime_type' => file_exists($fullPath) ? mime_content_type($fullPath) : null,
                'size' => file_exists($fullPath) ? filesize($fullPath) : 0,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

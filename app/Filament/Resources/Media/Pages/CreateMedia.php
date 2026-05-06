<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use App\Models\Media;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    // ✅ Override — save each file as separate DB record
    protected function handleRecordCreation(array $data): Model
    {
        $paths = $data['path'] ?? [];

        // ✅ If only one file, paths may be a string
        if (is_string($paths)) {
            $paths = [$paths];
        }

        $lastRecord = null;

        foreach ($paths as $path) {
            $fullPath = Storage::disk('public')->path($path);
            $fileName = pathinfo($path, PATHINFO_FILENAME);
            $mimeType = file_exists($fullPath) ? mime_content_type($fullPath) : null;
            $size = file_exists($fullPath) ? filesize($fullPath) : 0;

            $lastRecord = Media::create([
                'name' => $fileName,
                'path' => $path,
                'mime_type' => $mimeType,
                'size' => $size,
            ]);
        }

        // ✅ Return last record (Filament requires a model returned)
        return $lastRecord ?? new Media;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

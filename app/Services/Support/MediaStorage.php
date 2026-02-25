<?php

namespace App\Services\Support;

use App\Exceptions\MediaUploadException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MediaStorage
{
    public function storePublic(UploadedFile $file, string $dir): string
    {
        try {
            $path = $file->store($dir, 'public');

            if (! $path) {
                throw MediaUploadException::failed('public', $dir, [
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }

            return $path;
        } catch (MediaUploadException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Upload store failed', [
                'dir' => $dir,
                'original_name' => $file->getClientOriginalName(),
                'exception' => $e,
            ]);

            throw MediaUploadException::failed('public', $dir, [
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }

    public function deletePublic(?string $path): void
    {
        if (! $path) return;

        try {
            $disk = Storage::disk('public');
            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        } catch (\Throwable $e) {
            Log::warning('Upload delete failed', [
                'path' => $path,
                'exception' => $e,
            ]);
            // delete failures should not break the main operation
        }
    }
}

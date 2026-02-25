<?php

namespace App\Http\Controllers;

use App\Support\HandlesControllerErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Serve ficheiros do disco "public" (storage/app/public) quando o symlink
 * public/storage não existe.
 *
 * Mantém compatibilidade com asset('storage/...') sem mexer no front-end.
 */
class StorageController extends Controller
{
    use HandlesControllerErrors;

    public function show(Request $request, string $path): StreamedResponse
    {
        // Proteção simples contra path traversal
        if (str_contains($path, '..')) {
            abort(404);
        }

        try {
            $disk = Storage::disk('public');

            if (! $disk->exists($path)) {
                abort(404);
            }

            $fullPath = $disk->path($path);
$mime = @mime_content_type($fullPath) ?: 'application/octet-stream';

// Whitelist de tipos permitidos (evita servir ficheiros perigosos por engano)
$allowed = [
    'image/jpeg',
    'image/png',
    'image/webp',
    'image/gif',
    'application/pdf',
];

if (! in_array($mime, $allowed, true)) {
    abort(404);
}

$filename = basename($path);

return response()->file($fullPath, [
    'Content-Type' => $mime,
    'Content-Disposition' => 'inline; filename="'.$filename.'"',
    'X-Content-Type-Options' => 'nosniff',
]);

        } catch (\Throwable $e) {
            Log::error('Storage file serving failed', [
                'path' => $path,
                'exception' => $e,
            ]);

            abort(404);
        }
    }
}

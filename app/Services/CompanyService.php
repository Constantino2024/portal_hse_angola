<?php

namespace App\Services;

use App\Models\User;
use App\Services\Support\Sanitizer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CompanyService extends BaseService
{

    /**
     * Store a company logo cropped to a square and resized to 256x256 for consistent UI.
     * Falls back to the default Laravel storage when GD is not available.
     */
    private function storeCompanyLogo(UploadedFile $file): string
    {
        // Fallback if GD is unavailable
        if (!extension_loaded('gd')) {
            return $file->storePublicly('company_logos', 'public');
        }

        $tmpPath = $file->getRealPath();
        if (!$tmpPath || !is_file($tmpPath)) {
            return $file->storePublicly('company_logos', 'public');
        }

        $mime = $file->getMimeType() ?: '';
        $src = null;

        try {
            if (str_contains($mime, 'png')) {
                $src = imagecreatefrompng($tmpPath);
            } elseif (str_contains($mime, 'jpeg') || str_contains($mime, 'jpg')) {
                $src = imagecreatefromjpeg($tmpPath);
            } elseif (str_contains($mime, 'webp') && function_exists('imagecreatefromwebp')) {
                $src = imagecreatefromwebp($tmpPath);
            } elseif (str_contains($mime, 'gif')) {
                $src = imagecreatefromgif($tmpPath);
            }
        } catch (\Throwable $e) {
            $src = null;
        }

        if (!$src) {
            return $file->storePublicly('company_logos', 'public');
        }

        $w = imagesx($src);
        $h = imagesy($src);
        $side = min($w, $h);

        // Center-crop to square
        $srcX = (int) floor(($w - $side) / 2);
        $srcY = (int) floor(($h - $side) / 2);

        $dstSize = 256;
        $dst = imagecreatetruecolor($dstSize, $dstSize);

        // Preserve alpha for PNG/WebP when possible
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $dstSize, $dstSize, $transparent);

        imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $dstSize, $dstSize, $side, $side);

        // Generate a deterministic-ish name
        $hash = sha1_file($tmpPath) ?: sha1(uniqid('', true));
        $dir = 'company_logos';

        if (function_exists('imagewebp')) {
            $rel = $dir . '/' . $hash . '.webp';
            $abs = Storage::disk('public')->path($rel);
            @mkdir(dirname($abs), 0775, true);
            // Quality 80 gives good size/quality tradeoff
            imagewebp($dst, $abs, 80);
        } else {
            // JPEG fallback (no alpha): draw on white background
            $rel = $dir . '/' . $hash . '.jpg';
            $abs = Storage::disk('public')->path($rel);
            @mkdir(dirname($abs), 0775, true);

            $jpg = imagecreatetruecolor($dstSize, $dstSize);
            $white = imagecolorallocate($jpg, 255, 255, 255);
            imagefilledrectangle($jpg, 0, 0, $dstSize, $dstSize, $white);
            imagecopy($jpg, $dst, 0, 0, 0, 0, $dstSize, $dstSize);

            imagejpeg($jpg, $abs, 85);
            imagedestroy($jpg);
        }

        imagedestroy($dst);
        imagedestroy($src);

        return $rel;
    }


    public function createCompanyUser(array $validated): User
    {
        $name = Sanitizer::plain($validated['name'] ?? null, 255);
        $email = strtolower(trim($validated['email'] ?? ''));

        $companyLogoPath = null;
        if (($validated['company_logo'] ?? null) instanceof UploadedFile) {
            $companyLogoPath = $this->storeCompanyLogo($validated['company_logo']);
        }

        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($validated['password']),
            'role' => 'empresa',
            'company_logo' => $companyLogoPath,
        ]);
    }

    public function updateCompanyUser(User $company, array $validated): User
    {
        $update = [
            'name' => Sanitizer::plain($validated['name'] ?? null, 255),
            'email' => strtolower(trim($validated['email'] ?? '')),
        ];

        if (!empty($validated['password'])) {
            $update['password'] = Hash::make($validated['password']);
        }

        // Remover logo atual (checkbox)
        if (!empty($validated['remove_logo'])) {
            if (!empty($company->company_logo)) {
                Storage::disk('public')->delete($company->company_logo);
            }
            $update['company_logo'] = null;
        }

        // Substituir logo (upload)
        if (($validated['company_logo'] ?? null) instanceof UploadedFile) {
            if (!empty($company->company_logo)) {
                Storage::disk('public')->delete($company->company_logo);
            }
            $update['company_logo'] = $this->storeCompanyLogo($validated['company_logo']);
        }

        $company->update($update);
        return $company;
    }
}

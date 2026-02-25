<?php

namespace App\Exceptions;

class MediaUploadException extends DomainException
{
    public static function failed(string $disk, string $dir, array $context = []): self
    {
        return new self(
            'Falha ao guardar o ficheiro enviado. Tente novamente.',
            0,
            null,
            array_merge($context, ['disk' => $disk, 'dir' => $dir])
        );
    }
}

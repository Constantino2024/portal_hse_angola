<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Base exception for business/domain rule failures.
 *
 * Throw this (or subclasses) for “expected” failures that should be handled
 * gracefully (flash message / JSON error) instead of producing a 500.
 */
class DomainException extends RuntimeException
{
    /** @var array<string,mixed> */
    protected array $context = [];

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(string $message = 'Operação inválida.', int $code = 0, ?\Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /** @return array<string,mixed> */
    public function context(): array
    {
        return $this->context;
    }
}

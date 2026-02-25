<?php

namespace App\Services;

use App\Exceptions\DomainException;
use Illuminate\Support\Facades\Log;

/**
 * Base Service with consistent error handling.
 *
 * - Re-throws DomainException (expected/business errors).
 * - Wraps unexpected errors into DomainException with logging.
 */
abstract class BaseService
{
    /**
     * @template T
     * @param callable():T $fn
     * @return T
     */
    protected function guard(callable $fn, string $operation = ''): mixed
    {
        try {
            return $fn();
        } catch (DomainException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Service failure', [
                'operation' => $operation ?: static::class,
                'exception' => $e,
            ]);

            throw new DomainException(
                'Ocorreu um erro interno ao processar a operação. Tente novamente.',
                0,
                $e,
                ['operation' => $operation ?: static::class]
            );
        }
    }
}

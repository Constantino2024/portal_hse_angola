<?php

namespace App\Support;

use App\Exceptions\DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

trait HandlesControllerErrors
{
    /**
     * Executes an action with consistent error handling.
     * For domain errors: redirect back with message.
     * For unexpected errors: log and redirect back with a generic message.
     */
    protected function runWithErrors(callable $fn, string $genericMessage = 'Ocorreu um erro interno. Tente novamente.'): mixed
    {
        try {
            return $fn();
        } catch (DomainException $e) {
            Log::warning('Domain failure', [
                'message' => $e->getMessage(),
                'context' => $e->context(),
                'exception' => $e,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (AuthorizationException $e) {
            // Let Laravel render a proper 403 page instead of masking as "internal error".
            throw $e;
        } catch (HttpExceptionInterface $e) {
            // Keep correct HTTP error pages (404/403/500 etc.)
            throw $e;
        } catch (\Throwable $e) {
            $ref = (string) \Illuminate\Support\Str::uuid();
            Log::error('Unhandled controller failure', [
                'ref' => $ref,
                'exception' => $e,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', $genericMessage." (Ref: {$ref})");
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProfessionalMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Faça login para continuar.');
        }

        $user = auth()->user();

        // Admin pode ver tudo
        if (!$user->isAdmin() && $user->role !== 'profissional') {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}

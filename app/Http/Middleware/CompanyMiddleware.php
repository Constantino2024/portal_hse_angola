<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CompanyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Faça login para aceder à área da empresa.');
        }

        $user = auth()->user();

        if (!$user->isCompany() && !$user->isAdmin()) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}

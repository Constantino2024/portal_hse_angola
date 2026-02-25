<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redireciona por role
            if ($user->isAdmin() || $user->isEditor()) {
                // Primeiro acesso vai para o dashboard (menos sujeito a falhas de queries)
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->isCompany()) {
                return redirect()->intended(route('partner.dashboard'));
            }

            // profissional (padrão)
            return redirect()->intended(route('talent.profile.show'));
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas. Verifica o email e a palavra-passe.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

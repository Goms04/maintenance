<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomAuth
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request); // utilisateur normal
        }

        // Vérifie la session admin personnalisée
        if (session()->has('utilisateur') && session('utilisateur')->role === 'admin') {
            return $next($request); // admin statique
        }

        return redirect('/login')->with('error', 'Accès interdit. Connectez-vous.');
    }
}

<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Tentukan ke mana user diarahkan jika mereka belum login (Unauthenticated).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika permintaan bukan JSON (akses lewat browser biasa), 
        // maka lempar user ke route bernama 'login'.
        if (! $request->expectsJson()) {
            return route('login');
        }

        return null;
    }
}
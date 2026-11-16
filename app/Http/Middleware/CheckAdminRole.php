<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Otorisasi sederhana: hanya user dengan role "admin" yang boleh lewat.
     * Dipasang SETELAH middleware "auth", jadi user dijamin sudah login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role !== 'admin') {
            abort(403, 'Halaman ini hanya bisa diakses oleh admin.');
        }

        return $next($request);
    }
}

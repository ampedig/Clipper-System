<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    /**
     * Handle an incoming request.
     *
     * Memastikan user yang terautentikasi memiliki status akun aktif (is_active = true).
     * Jika akun ditangguhkan di tengah sesi aktif, sesi segera dihentikan dan user diredirect ke login.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && ! Auth::user()->is_active) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda sedang ditangguhkan. Silakan hubungi admin untuk informasi lebih lanjut.');
        }

        return $next($request);
    }
}

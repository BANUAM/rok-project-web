<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasBracketMembership
{
    /**
     * Allow access only to users with active ROK Challenger / ROK Immortal membership (or Admin).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'))
                ->with('error', 'Silakan masuk terlebih dahulu menggunakan akun Membership ROK Challenger atau ROK Immortal untuk mengakses Bracket Studio.');
        }

        if (!Auth::user()->hasBracketAccess()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Fitur Bracket Studio memerlukan paket membership ROK Challenger atau ROK Immortal.',
                ], 403);
            }

            return redirect()->route('membership.payment', ['plan' => 'challenger'])
                ->with('error', 'Halaman Khusus Bracket Studio hanya tersedia bagi pemilik paket membership ROK Challenger atau ROK Immortal.');
        }

        return $next($request);
    }
}

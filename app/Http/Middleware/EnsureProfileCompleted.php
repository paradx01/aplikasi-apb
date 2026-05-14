<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    /**
     * Halaman yang dikecualikan dari pengecekan (agar tidak redirect loop)
     */
    protected $except = [
        'profile/complete',
        'profile/complete/store',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Hanya cek untuk role buyer
            if ($user->hasRole('buyer')) {
                // Cek apakah profil sudah lengkap (age dan gender wajib diisi)
                if (is_null($user->age) || is_null($user->gender)) {
                    // Jangan redirect jika sudah di halaman complete profile
                    foreach ($this->except as $pattern) {
                        if ($request->is($pattern)) {
                            return $next($request);
                        }
                    }
                    
                    return redirect()->route('profile.complete');
                }
            }
        }

        return $next($request);
    }
}

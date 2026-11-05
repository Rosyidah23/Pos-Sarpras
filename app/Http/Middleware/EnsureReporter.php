<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Area pelapor (siswa & guru). Petugas tidak membuat laporan, tugasnya memverifikasi,
 * jadi petugas diarahkan ke dashboard petugas (dan permintaan POST ditolak).
 */
class EnsureReporter
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isPetugas()) {
            abort_unless($request->isMethod('GET'), 403);

            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}

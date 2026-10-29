<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')
                             ->with('error', 'Akses ditolak. Hanya admin yang dapat melakukan tindakan ini.');
        }
        return $next($request);
    }
}
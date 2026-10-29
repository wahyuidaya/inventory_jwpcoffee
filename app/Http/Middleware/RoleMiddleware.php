public function handle($request, Closure $next, $role)
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    if (auth()->user()->role !== $role) {
        abort(403, 'Akses ditolak');
    }

    return $next($request);
}
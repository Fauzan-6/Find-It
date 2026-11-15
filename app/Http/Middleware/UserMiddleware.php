<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Admin diarahkan ke panelnya sendiri
        if ($request->user()?->role === 'admin') {
            return redirect()->route('admin.index');
        }

        return $next($request);
    }
}

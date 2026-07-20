<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    





    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            
            if (auth()->check()) {
                $userRole = auth()->user()->role;
                if ($userRole === 'admin') {
                    return redirect('/admin');
                } elseif ($userRole === 'employer') {
                    return redirect('/pemberi-kerja');
                } else {
                    return redirect('/pencari-kerja');
                }
            }
            
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}

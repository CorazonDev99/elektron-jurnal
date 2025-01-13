<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isSuperuser()) {
            return $next($request);
        }

        return redirect()->route('no-access'); // редирект при отсутствии прав
    }
}

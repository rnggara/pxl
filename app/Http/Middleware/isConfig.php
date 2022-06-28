<?php

namespace App\Http\Middleware;

use Closure;

class isConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (get_config() == 1){
            return redirect('login');
        }
        return $next($request);
    }
}

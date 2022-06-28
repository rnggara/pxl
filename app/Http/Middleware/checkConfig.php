<?php

namespace App\Http\Middleware;

use Closure;

class checkConfig
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
        if (get_config() == 0){
            return redirect('install');
        }
        return $next($request);
    }
}

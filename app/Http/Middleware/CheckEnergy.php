<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CheckEnergy
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
        $user = User::find(Auth::id());
        if($user->attend_code <= 0){
            return redirect()->route("home")->with("energy", 0);
        }

        $user->attend_code = $user->attend_code - 1;
        // dd($user);
        $user->save();
        return $next($request);
    }
}

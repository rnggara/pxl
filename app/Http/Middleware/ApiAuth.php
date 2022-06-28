<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ApiAuth
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
        $user = User::where('api_token', "$request->token")->first();
        $auth = false;
        if(!empty($user)){
            $auth = Auth::loginUsingId($user->id);
        }
        if ($auth) {
            return $next($request);
        }

        return Response::json([
            'error' => [
                'message' => 'invalid credentials',
                'status_code' => 403
            ]
        ], 403, []);
    }
}

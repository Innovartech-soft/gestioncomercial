<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo(Request $request): string
    {
         if (! $request->expectsJson()) {
            return route('login');
         }else{
            if ($token = $request->cookie('cookie')) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
           // 
           $this->authenticate($request, $guards);
           return $next($request);
        }else{
            return response()->json([
                "Message" => "No autorizado"
            ]);
            }
        }
    }

}

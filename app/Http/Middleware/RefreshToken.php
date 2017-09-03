<?php

namespace App\Http\Middleware;

use Closure;

class RefreshToken extends \Tymon\JWTAuth\Middleware\BaseMiddleware
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
        try {
            $newToken = $this->auth
                             ->setRequest($request)
                             ->parseToken()
                             ->refresh();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $this->respond(
                'tymon.jwt.expired',
                'refresh_ttl_finished',
                $e->getStatusCode(),
                [$e]
            );
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $this->respond(
                'tymon.jwt.invalid',
                'token_invalid',
                $e->getStatusCode(),
                [$e]
            );
        }

        return response()->json([
            'token' => $newToken,
        ], 201, [], JSON_PRETTY_PRINT);
    }
}
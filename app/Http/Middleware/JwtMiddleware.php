<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

class JwtMiddleware extends \PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
                return respuesta(null, ['general'=>'Token incorrecto'], 450);
            } else if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
                return respuesta(null, ['general'=>'Token finalizado'], 450);
            } else {
                return respuesta(null, ['general'=>'Token sin permisos'], 450);
            }
        }
        return $next($request);
    }
}
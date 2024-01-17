<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        /* Cuando el usuario no se encuentra autorizado */
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return respuesta(null, 'No posee permisos', 403);
        });

        /* Cuando no encuentra una ruta HTTP */
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            return respuesta(null, $e->getMessage(), 404);
        });

        /* Método HTTP no permitido */
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
            return respuesta(null, 'Método HTTP no permitido', 405);
        });

        /* Cuando ocurre una error en la base de datos */
        $this->renderable(function (\Illuminate\Database\QueryException $e, $request) {
            return respuesta(null, $e->getMessage(), 301);
        });

        /* $this->renderable(function (AuthenticationException $e, $request){
            return respuesta(null, $e->getMessage(), 422);
        }); */
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return respuesta(null, $exception->errors(), $exception->status);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use ReflectionClass;

class DocumentacionController extends Controller
{
    private $controllers_no_visibles = [
        "Sanctum",
        "Spatie",
        "AuthController"
    ];

    public function verificar_controllador($controller)
    {
        $no_contiene = true;
        foreach ($this->controllers_no_visibles as $key => $value) {
            if (str_contains($controller, $value)) {
                $no_contiene = false;
            }
        }
        return $no_contiene;
    }

    public function routes(Request $request)
    {
        try {
            //verificar el app name
            $hash = $request->token;
            $name = Crypt::decrypt($hash);
            if ($name === env('APP_NAME')) {
                //obtener todas las rutas
                $routes = Route::getRoutes()->getRoutes();
                $routesAndMethods = [];
                foreach ($routes as $route) {
                    $action = $route->getAction();

                    if (isset($action['controller'])) {
                        $controller = $action['controller'];

                        if (str_contains($controller, '@')) {
                            [$class, $method] = explode('@', $controller);
                        }
                        //verificar si el controlador es alguno de los bloqueados
                        if ($this->verificar_controllador($class)) {
                            $reflectionClass = new ReflectionClass($class);
                            $methodo = $reflectionClass->getMethod($method);
                            $params = $methodo->getParameters();
                            $params_return = [];
                            foreach ($params as $key => $value) {
                                $params_return[] = $value->name;
                            }
                            $rules = [];
                            foreach ($params as $att) {
                                if (str_contains($att, 'Requests')) {
                                    $clase = $att->getClass();
                                    $name = $clase->getName();
                                    $name = explode('\\', $name);
                                    $name = array_reverse($name);
                                    if ($name[0] != 'Request') {
                                        $rules[] = $clase->getName()::$rules;
                                    }
                                }
                            }
                            //obtener middlewares
                            $middlewares = $route->getAction()['middleware'];
                            $return_middlewares = [];
                            foreach ($middlewares as $val) {
                                $return_middlewares[] = $val;
                            }

                            $routesAndMethods[] = [
                                'uri' => $route->uri,
                                'Name' => $route->getName(),
                                'Method-HTTP' => $route->methods(),
                                /* 'Method-Controller' => $method, */
                                'Rules' => $rules,
                                'Params' => $params_return,
                                'Middlewares' => $return_middlewares
                            ];
                        }
                    }
                }
                return $routesAndMethods;
            } else {
                return respuesta(null, ['general'=>'Se requieren permisos'], 403);
            }
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    public function obtener_hash()
    {
        $app_name = env('APP_NAME');
        $hash = Crypt::encrypt($app_name);
        return respuesta($hash);
    }
}

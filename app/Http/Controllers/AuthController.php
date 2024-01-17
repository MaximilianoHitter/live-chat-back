<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    /* public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    } */

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = request(['email', 'password']);

            $usuario_encontrado = User::where('email', request(['email']))->first();

            if ($usuario_encontrado != null) {
                if (!$token = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::attempt($credentials)) {
                    return respuesta(null, ['general'=>'Credenciales inválidas'], 401);
                }

                //permisos y roles del usuario
                $roles = $usuario_encontrado->getRoleNames();
                $permisos = $usuario_encontrado->getAllPermissions();
                $usuario_encontrado->roles = $roles;
                $usuario_encontrado->permisos = $permisos;
                return $this->respondWithToken($token, $usuario_encontrado);
            } else {
                return respuesta(null, ['email' => 'Usuario no encontrado'], 404);
            }
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $credentials = request(['name', 'email', 'password']);

            $usuarios_duplicados = User::where('email', request(['email']))->get();
            if (count($usuarios_duplicados) > 0) {
                return respuesta(null, ['general'=>'Ya hay otro usuario registrado con ese email'], 422);
            }

            $user = User::create($credentials);

            /* $verif = new VerificationController(); */
            VerificationController::sendVerificationLink($user);
            if (!$token = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::attempt($credentials)) {
                return respuesta(null, ['general'=>'Credenciales inválidas'], 401);
            }

            //permisos y roles del usuario
            $roles = $user->getRoleNames();
            $permisos = $user->getAllPermissions();
            $user->roles = $roles;
            $user->permisos = $permisos;
            return $this->respondWithToken($token, $user);
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            return respuesta(auth()->user());
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            $user = Auth::user();
            if ($user != null) {
                Auth::logout();
                auth()->logout();
                return respuesta('Adios!');
            } else {
                return respuesta(null, ['general'=>'No se ha iniciado sesión'], 404);
            }
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $JWTAuth = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::class;

            $data = [
                'token' => $JWTAuth::refresh(),
                'expires_in' => ($JWTAuth::factory()->getTTL() * 60 * 1000) + round(microtime(true) * 1000)
            ];

            return respuesta($data);
            //return $this->respondWithToken(auth()->refresh());
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        $JWTAuth = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::class;
        return respuesta([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => ($JWTAuth::factory()->getTTL() * 60 * 1000) + round(microtime(true) * 1000),
            'user' => $user
        ]);
    }
}

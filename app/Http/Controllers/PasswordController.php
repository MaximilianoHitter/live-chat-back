<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Mail\SendMailReset;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    //enviar mail
    public function sendEmail(Request $request)
    {
        try {
            if (!$this->validateEmail($request->email)) {
                return respuesta(null, ['email'=>'Email no encontrado en nuestros registros'], 404);
            }
            $this->send($request->email);
            return respuesta('Email enviado correctamente');
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    public function send($email)
    {
        try {
            $token = $this->createToken($email);
            $url_front = env('APP_FRONT');
            $str_token = "$url_front/response-passsword-reset/token=$token";
            Mail::to($email)->send(new SendMailReset($str_token, $email));
            return respuesta('Email enviado correctamente');
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    public function createToken($email)
    {
        try {
            $oldToken = DB::table('password_reset_tokens')->where('email', $email)->first();
            if ($oldToken != null) {
                return $oldToken->token;
            }
            $token = Str::random(40);
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            return $token;
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    public function validateEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    //cambiar contraseña
    public function updatePassword($request)
    {
        return DB::table('password_reset_tokens')->where([
            'email' => $request->email,
            'token' => $request->token
        ]);
    }

    public function resetPassword(UpdatePasswordRequest $request)
    {
        try {
            $userData = User::whereEmail($request->email)->first();
            $solicitud = $this->updatePassword($request);
            if ($userData != null) {
                try {
                    if ($solicitud->first()->token != null) {
                        $userData->update([
                            'password' => bcrypt($request->password)
                        ]);
                        $solicitud->delete();
                        return respuesta('Contraseña actualizada correctamente');
                    } else {
                        return respuesta(null, ['general'=>'Este usuario no ha solicitado un cambio de contraseña'], 404);
                    }
                } catch (\Exception $e) {
                    return respuesta(null, ['general'=>'Este usuario no ha solicitado un cambio de contraseña'], 404);
                }
            } else {
                return respuesta(null, ['general'=>'Usuario no encontrado'], 404);
            }
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }
}

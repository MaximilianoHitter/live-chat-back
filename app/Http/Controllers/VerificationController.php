<?php

namespace App\Http\Controllers;

use App\Mail\VerificationEmail;
use App\Models\EmailToken;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class VerificationController extends Controller
{
    public static function generateVerificationLink($email)
    {
        try {
            $checkToken = EmailToken::where('email', $email)->first();
            if ($checkToken) {
                $checkToken->delete();
            }
            $token = Str::random(40);
            $url = env('APP_FRONT') . '/verification-email/token=' . $token . '/email=' . $email;
            $saveToken = EmailToken::create([
                'email' => $email,
                'token' => $token,
                'expired_at' => now()->addMinutes(60)
            ]);
            return $url;
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    public static function sendVerificationLink(User $user)
    {
        try {
            //Notification::send($user, new EmailVerificationNotification($this->generateVerificationLink($user->email)));
            Mail::to($user->email)->send(new VerificationEmail(VerificationController::generateVerificationLink($user->email), $user->email));
            return respuesta('Email enviado');
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    public function resendEmail()
    {
        try {
            $user = Auth::user();
            $user = User::find($user->id);
            if ($user->email_verified_at != null) {
                return respuesta(null, ['general'=>'El email ya ha sido verificado'], 422);
            }
            VerificationController::sendVerificationLink($user);
            return respuesta('Email de verificación enviado nuevamente');
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }

    public function verification(Request $request)
    {
        try {
            Carbon::setLocale('es');
            $user = User::where('email', $request->email)->first();
            //$user = Auth::user();
            //$user = User::find($user->id);
            if ($user->email_verified_at != null) {
                return respuesta(null, ['general'=>'Usuario ya verificado'], 422);
            }
            $tokens = EmailToken::where('email', $user->email)->first();
            if ($tokens != null) {
                $fecha = Carbon::now();
                if ($tokens->token == $request->email_token && $tokens->expired_at >= $fecha) {

                    $user->email_verified_at = $fecha;
                    $user->save();
                    $tokens->delete();
                    return respuesta('Email verificado');
                } else {
                    return respuesta(null, ['general'=>'Token inválido'], 422);
                }
            } else {
                return respuesta(null, ['general'=>'No se ha encontrado un token de verificación para este usuario'], 404);
            }
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }
}

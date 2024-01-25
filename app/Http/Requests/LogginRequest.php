<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogginRequest extends FormRequest
{
    use TraitRequest;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'=>'email|required|exists:users,email',
            'password'=>'required|min:4',
            'cpassword'=>'required|min:40'
        ];
    }

    public function messages()
    {
        return [
            'email.email'=>'El formato de email es inválido',
            'email.required'=>'El email es requerido',
            'email.exists'=>'No posee una cuenta para este email',
            'password.required'=>'La contraseña es requerida',
            'password.min'=>'La contraseña debe poseer mínimo 4 caracteres',
            'cpassword.min'=>'Las contraseñas deben ser iguales',
            'cpassword.required'=>'La verificación de contraseña es requerida'
        ];
    }
}

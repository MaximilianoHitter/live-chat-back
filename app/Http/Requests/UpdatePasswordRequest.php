<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    use TraitRequest;
    public static $rules = [
        'email'=>'required|email',
        'password'=>'required|confirmed'
    ];
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
            'email'=>'required|email',
            'password'=>'required|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'email.required'=>'El email es requerido',
            'email.email'=>'Formato inválido de email',
            'password.required'=>'La contraseña es requerida',
            'password.confirmed'=>'Las constraseñas no coinciden'
        ];
    }
}

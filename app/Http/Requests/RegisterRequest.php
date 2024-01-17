<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use TraitRequest;
    public static $rules = [
        'name'=>'required',
        'email'=>'required|email',
        'password'=>'required'
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
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'El nombre es requerido',
            'email.required'=>'El email es requerido',
            'email.email'=>'Formato de email inválido',
            'password.required'=>'La contraseña es requerida'
        ];
    }
}

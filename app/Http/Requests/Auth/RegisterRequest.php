<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:6|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
            'phone' => 'required|regex:/^\d{10,11}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'password.required' => 'O campo de senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, um número e um caractere especial.',
            'phone.required' => 'O campo de telefone é obrigatório.',
            'phone.regex' => 'O telefone deve ser válido e estar no formato: somente números, com DDD. Exemplo: 14912345678.',
        ];
    }
}

<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'O token é obrigatório. Verifique a caixa de correio do seu e-mail.',
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'password.required' => 'O campo de senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, um número e um caractere especial.',
        ];
    }
}

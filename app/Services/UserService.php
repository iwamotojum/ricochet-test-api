<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    protected $userRepository;
    
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    public function createUser(array $params)
    {
        $params['password'] = bcrypt($params['password']);
        $user = $this->userRepository->create($params);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->only(['name', 'email', 'phone'])
        ];
    }

    public function createSession(array $params)
    {
        if (!Auth::attempt($params)) {
            throw ValidationException::withMessages([
                'email' => ['Unauthorized.'],
            ]);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token', ['*'], now()->addDays(2));

        return [
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'user' => $user->only(['name', 'email', 'phone'])
        ];
    }

    public function destroySession(Request $request)
    {
        $request->user()->currentAccesToken()->delete();
        return [];
    }

    public function getUserDetails(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();

        return [
            'user' => $user->only(['id', 'name', 'email']),
            'token_details' => [
                'token_name' => $token->name,
                'created_at' => $token->created_at,
                'last_used_at' => $token->last_used_at
        ]];
    }

    public function forgotPassword(array $params)
    {
        $status = Password::sendResetLink($params);
        if ($status !== Password::RESET_LINK_SENT) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => trans($status)
            ], 400));
        }

        return 'Reset password link sent to your email';
    }

    public function resetPassword(array $params)
    {
        $status = Password::reset($params, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Unable to reset password'
            ], 400));
        }

        return 'Password reset successfully';
    }
}

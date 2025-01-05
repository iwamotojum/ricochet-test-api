<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use App\Http\Controllers\API\BaseController;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->userService->createUser($request->only(['name', 'email', 'password']));
        return $this->sendResponse($response, 'User register successfully.');
    }

    public function login(LoginRequest $request)
    {
        $response = $this->userService->createSession($request->only(['email', 'password']));
        return $this->sendResponse($response, 'Session created successfully.');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
          ], 200);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();

        return response()->json([
            'user' => $user->only(['id', 'name', 'email']),
            'token_details' => [
                'token_name' => $token->name,
                'created_at' => $token->created_at,
                'last_used_at' => $token->last_used_at
            ]
        ]);
    }
}

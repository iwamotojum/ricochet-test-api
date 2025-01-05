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
        $response = $this->userService->destroySession($request);
        return $this->sendResponse($response, 'Logged out successfully');
    }

    public function me(Request $request)
    {
        $response = $this->userService->getUserDetails($request);
        return $this->sendResponse($response, 'OK.');
    }
}

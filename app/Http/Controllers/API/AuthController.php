<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\API\BaseController;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\ResetRequest;

use App\Services\UserService;


class AuthController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->userService->createUser($request->only(['name', 'email', 'password', 'phone']));
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

    public function forgotPassword(ForgotRequest $request)
    {
        $response = $this->userService->forgotPassword($request->only('email'));
        return $this->sendResponse([], $response);
    }

    public function resetPassword(ResetRequest $request)
    {
        $response = $this->userService->resetPassword($request->only(['email', 'password', 'password_confirmation', 'token']));
        return $this->sendResponse([], $response);
    }

    public function showResetPasswordForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        return view('auth.passwords.reset', compact('token', 'email'));
    }
}

<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
  protected $userRepository;
  
  public function __construct(UserRepositoryInterface $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function createUser(array $params)
  {
    $params['password'] = bcrypt($params['password']);
    $user = $this->userRepository->create($params);
    $token = $user->createToken('auth_token')->plainTextToken;

    return [
      'access_token' => $token,
      'token_type' => 'Bearer',
      'user' => $user->only(['name', 'email'])
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
        'user' => $user->only(['name', 'email'])
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
}

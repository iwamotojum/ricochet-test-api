<?php

namespace App\Services;

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

    $response = [
      'access_token' => $token,
      'token_type' => 'Bearer',
      'user' => $user->only(['name', 'email'])
    ];

    return $token;
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

    $response = [
        'access_token' => $token->plainTextToken,
        'token_type' => 'Bearer',
        'user' => $user->only(['name', 'email'])
    ];

    return $response;
  }
}

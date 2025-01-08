<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\User as UserResource;

use App\Services\UserService;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $response = $this->userService->getAll();
        return $this->sendPaginatedResponse(UserResource::collection($response), $response, 'All calls returned successfully.');
    }
}

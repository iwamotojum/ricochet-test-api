<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Call as CallResource;

use App\Services\CallService;

class CallController extends BaseController
{
    protected $callService;

    public function __construct(CallService $callService)
    {
        $this->callService = $callService;
    }

    public function index(Request $request)
    {
        $response = $this->callService->getAll($request->all());
        return $this->sendPaginatedResponse(CallResource::collection($response), $response, 'All calls returned successfully.');
    }

    public function token(Request $request)
    {
        $response = $this->callService->generateToken();
        return $response;
    }

    public function voice(Request $request)
    {
        $response = $this->callService->createVoice($request->all());
        return $response;
    }

    public function callback(Request $request)
    {
        $response = $this->callService->statusCallback($request->all());
        return $response;
    }
}

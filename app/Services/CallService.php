<?php

namespace App\Services;

use App\Repositories\Contracts\CallRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class CallService
{
    protected $callRepository;
    protected $userRepository;

    public function __construct(CallRepositoryInterface $callRepository, UserRepositoryInterface $userRepository)
    {
        $this->callRepository = $callRepository;
        $this->userRepository = $userRepository;
    }

    public function getAll(array $params)
    {
        return $this->callRepository->getAll($params);
    }

    public function generateToken()
    {
        $token = $this->callRepository->generateToken();
        return [ 'token' => $token ];
    }

    public function createVoice(array $params)
    {
        $from_user_id = $this->userRepository->getUserByPhone($params['From'])->id;
        $to_user_id = $this->userRepository->getUserByPhone($params['To'])->id;

        $call_params = [
            'call_sid' => $params['CallSid'],
            'status' => $params['CallStatus'],
            'from_user_id' => $from_user_id,
            'to_user_id' => $to_user_id,
        ];

        $this->callRepository->create($call_params);
        return $this->callRepository->createVoice($params['To']);
    }

    public function statusCallback(array $params)
    {
        $sid = $params['CallSid'];
        $call = $this->callRepository->getCallBySid($sid);

        if (!$call) {
            return response()->json(['message' => 'Call not found'], 404);
        }

        $call_params = [
            'status' => $params['CallStatus'],
            'duration' => $params['Duration'],
        ];

        $this->callRepository->update($call, $call_params);
        return response()->json(['message' => 'Call updated'], 200);
    }
}

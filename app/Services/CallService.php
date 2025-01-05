<?php

namespace App\Services;

use App\Repositories\Contracts\CallRepositoryInterface;

class CallService
{
    protected $callRepository;

    public function __construct(CallRepositoryInterface $callRepository)
    {
        $this->callRepository = $callRepository;
    }

    public function getAll(array $params) {
        return $this->callRepository->getAll($params);
    }
}
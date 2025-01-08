<?php

namespace App\Repositories\Contracts;

interface CallRepositoryInterface
{
    public function getAll(array $params);
    public function create(array $params);
    public function generateToken();
    public function createVoice(string $toNumber);
    public function getCallBySid(string $sid);
    public function update(object $call, array $params);
}

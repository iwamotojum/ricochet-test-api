<?php

namespace App\Repositories;

use App\Repositories\Contracts\CallRepositoryInterface;

class CallRepository implements CallRepositoryInterface
{
    protected $model;

    public function __construct(Call $model)
    {
        $this->model = $model;
    }

    public function getAll(array $params)
    {
        $response = QueryBuilder::For(Call::class)->allowedFilters([AllowedFilter::exact('id'),
                                                                    AllowedFilter::exact('call_sid'),
                                                                    AllowedFilter::exact('status')])
                                                    ->defaultSort('-created_at')
                                                    ->allowedSorts('created_at', '-created_at')
                                                    ->paginate($params['per_page'])
                                                    ->appends(request()->query());
        return $response;
    }
}

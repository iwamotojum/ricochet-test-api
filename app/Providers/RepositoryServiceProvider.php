<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\CallRepositoryInterface;

use App\Repositories\UserRepository;
use App\Repositories\CallRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CallRepositoryInterface::class, CallRepository::class);
    }
}

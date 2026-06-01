<?php

namespace App\Providers;

use App\Interfaces\Repositories\Users\UserProfileRepositoryInterface;
use App\Interfaces\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Users\UserProfileRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $bindings = [
            UserRepositoryInterface::class => UserRepository::class,
            UserProfileRepositoryInterface::class => UserProfileRepository::class,
        ];
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

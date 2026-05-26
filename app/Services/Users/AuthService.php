<?php

namespace App\Services\Users;

use App\DTOs\Auth\UserRegisterDto;
use App\Interfaces\Repositories\Users\UserRepositoryInterface;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected UserRepositoryInterface $userRepo
    ) {
        //
    }

    /** ======================================================================
     *
     * ======================================================================*/
    public function register(UserRegisterDto $dto): array {}
}

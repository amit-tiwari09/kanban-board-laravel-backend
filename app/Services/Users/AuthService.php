<?php

namespace App\Services\Users;

use App\DTOs\Auth\UserRegisterDto;
use App\Interfaces\Repositories\Users\UserRepositoryInterface;
use App\Users\UserStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
    public function register(UserRegisterDto $dto): array
    {
        $this->userRepo->create([
            'uuid' => Str::uuid()->toString(),
            'first_name' => $dto->first_name,
            'last_name' => $dto->last_name ?? null,
            'email' => $dto->email,
            'username' => $dto->username,
            'phone_number' => $dto->phone_number,
            'country_code' => $dto->country_code,
            'country',
            'password' => Hash::make($dto->password),
            'status' => UserStatus::ACTIVE,
            'registered_at' => Carbon::now(),

        ]);
    }
}

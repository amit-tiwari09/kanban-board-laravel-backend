<?php

namespace App\Services\Users;

use App\DTOs\Auth\UserRegisterDto;
use App\Interfaces\Repositories\Users\UserProfileRepositoryInterface;
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
        protected UserRepositoryInterface $userRepo,
        protected UserProfileRepositoryInterface $userProfileRepo
    ) {
    }

    /** ======================================================================
     *
     * ======================================================================*/
    public function register(UserRegisterDto $dto): array
    {
        $registeredUser = $this->userRepo->create([
            'uuid' => Str::uuid()->toString(),
            'first_name' => $dto->first_name,
            'last_name' => $dto->last_name ?? null,
            'email' => $dto->email,
            'username' => $dto->username,
            'phone_number' => $dto->phone_number,
            'country_code' => $dto->country_code,
            'password' => Hash::make($dto->password),
            'status' => UserStatus::ACTIVE,
            'registered_at' => Carbon::now(),
        ]);

        if (! $registeredUser) {
            return [
                'success' => false,
                'message' => null,
                'errors' => ['User Registration Failed'],
                'code' => 500,
            ];
        }

        $this->userProfileRepo->insert([
            'user_id' => $registeredUser->id,
        ]);

        return [
            'success' => true,
            'message' => 'User Registered Successfully',
            'errors' => null,
            'code' => 200,
        ];

    }
}

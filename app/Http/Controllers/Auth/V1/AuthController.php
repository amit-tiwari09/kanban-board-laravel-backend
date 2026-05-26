<?php

namespace App\Http\Controllers\Auth\V1;

use App\DTOs\Auth\UserRegisterDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Services\Users\AuthService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function register(RegisterUserRequest $request)
    {
        $dto = UserRegisterDto::fromRequest($request->validated());

        $response = $this->authService->register($dto);

        return response()->json([
            'success' => $response['status'],
            'messages' => $response['messages'],
            'data' => $response['data'] ?? null,
            'errors' => $response['errors'] ?? null,
            'meta' => $response['meta'] ?? null,
        ], 422);
    }
}

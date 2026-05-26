<?php

namespace App\DTOs\Auth;

class UserRegisterDto
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?int $phone_number = null,
        public readonly ?string $country_code = null,
        public readonly ?string $last_name = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            first_name: $data['first_name'],
            email: $data['email'],
            password: $data['password'],
            phone_number: $data['phone_number'] ?? null,
            country_code: $data['country_code'] ?? null,
            last_name: $data['last_name'] ?? null
        );
    }
}

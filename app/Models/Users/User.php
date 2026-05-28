<?php

namespace App\Models\Users;

use App\Users\UserStatus;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Guarded(['id'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    protected $casts = [
        'status' => UserStatus::class,
    ];
}

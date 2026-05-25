<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;



#[Guarded(['id'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    //
}

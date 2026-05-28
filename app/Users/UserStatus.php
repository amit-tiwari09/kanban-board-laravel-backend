<?php

namespace App\Users;

enum UserStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;
}

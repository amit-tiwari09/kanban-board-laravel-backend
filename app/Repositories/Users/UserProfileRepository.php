<?php

namespace App\Repositories\Users;

use App\Interfaces\Repositories\Users\UserProfileRepositoryInterface;
use App\Models\Users\UserProfile;

class UserProfileRepository implements UserProfileRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /** ======================================================================
     *  insert user profile records in bulk.
     * ======================================================================*/
    public function insert(array $data)
    {
        return UserProfile::insert($data);
    }
}

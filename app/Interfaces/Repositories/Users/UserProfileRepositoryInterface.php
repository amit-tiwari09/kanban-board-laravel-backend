<?php

namespace App\Interfaces\Repositories\Users;

interface UserProfileRepositoryInterface
{
    /** ======================================================================
     *  insert user profile records in bulk.
     * ======================================================================*/
    public function insert(array $data);
}

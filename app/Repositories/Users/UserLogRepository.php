<?php

namespace App\Repositories\Users;

use App\Interfaces\Repositories\Users\UserLogRepositoryInterface;
use App\Models\Users\UserLog;

class UserLogRepository implements UserLogRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /** ======================================================================
     *  insert user log records in bulk.
     * ======================================================================*/
    public function insert(array $data)
    {
        return UserLog::insert($data);
    }
}

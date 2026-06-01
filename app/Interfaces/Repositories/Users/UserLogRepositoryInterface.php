<?php

namespace App\Interfaces\Repositories\Users;

interface UserLogRepositoryInterface
{
    /** ======================================================================
     *  insert user log records in bulk.
     * ======================================================================*/
    public function insert(array $data);
}

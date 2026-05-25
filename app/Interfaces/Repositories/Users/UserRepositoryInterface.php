<?php

namespace App\Interfaces\Repositories\Users;

use App\Models\Users\User;

interface UserRepositoryInterface
{
    /** ======================================================================
     *  Create new user record and return the created record.
     * ======================================================================*/
    public function create(array $data): User;

    /** ======================================================================
     *  Update the record of any specific user using its id.
     * ======================================================================*/
    public function update(int $id, array $data): bool;

    /** ======================================================================
     *  delete multiple records at once using id
     * ======================================================================*/
    public function destroy(array $ids): int;
}

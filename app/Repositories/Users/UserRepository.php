<?php

namespace App\Repositories\Users;

use App\Interfaces\Repositories\Users\UserRepositoryInterface;
use App\Models\Users\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    /** ======================================================================
     *  Create new user record and return the created record.
     * ======================================================================*/
    public function create(array $data): User
    {
        return User::create($data);
    }

    /** ======================================================================
     *  Update the record of any specific user using its id.
     * ======================================================================*/
    public function update(int $id, array $data): bool
    {
        return User::where('id', $id)->update();
    }

    /** ======================================================================
     *  delete multiple records at once using id
     * ======================================================================*/
    public function destroy(array $ids): int
    {
        return User::destroy($ids);
    }
}

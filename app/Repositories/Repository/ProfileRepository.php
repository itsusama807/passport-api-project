<?php

namespace App\Repositories\Repository;

use App\Models\User;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use Exception;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function find(int $id)
    {
        return User::find($id);
    }

    public function update(array $data, int $id)
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }
        return $user->update($data);
    }
}

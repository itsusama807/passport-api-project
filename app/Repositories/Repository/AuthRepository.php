<?php

namespace App\Repositories\Repository;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function find(int $id)
    {
        return User::find($id);
    }

    public function delete(int $id)
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }
        return $user->delete();
    }
}

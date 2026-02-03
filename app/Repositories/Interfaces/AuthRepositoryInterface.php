<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function create(array $data);
    public function find(int $id);
    public function delete(int $id);
}

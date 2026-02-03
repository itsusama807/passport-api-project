<?php

namespace App\Repositories\Interfaces;

interface ProfileRepositoryInterface
{
    public function find(int $id);
    public function update(array $data, int $id);
}

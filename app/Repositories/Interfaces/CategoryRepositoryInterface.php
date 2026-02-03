<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function create(array $data);
    public function all();
    public function find(int $id);
    public function update(array $data, int $id);
    public function delete(int $id);
}

<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function create(array $data);
    public function find($id);
    public function all();
    public function delete(Product $product);
    public function forceDelete(Product $product);
    public function restore(Product $product);
    public function trashed();
    public function update(array $data, int $id);

}

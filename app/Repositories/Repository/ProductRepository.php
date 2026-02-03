<?php

namespace App\Repositories\Repository;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductRepository
{
    public function create(array $data)
    {
        return Product::create($data);
    }

    public function find($id)
    {
        return Product::find($id);
    }

    public function all()
    {
        // return Product::with('user')->get(); for getting all uesrs products
        return Auth::user()->products()->with(['user', 'categories', 'images'])->get(); //for authenticated user products only
    }

    public function delete(Product $product)
    {
        $product->delete();
    }

    public function forceDelete(Product $product)
    {
        $product->forceDelete();
    }

    public function restore(Product $product)
    {
        $product->restore();
    }

    public function trashed()
    {
        return Product::onlyTrashed()->get();
    }

    public function update(array $data, $id)
    {
        $product = $this->find($id);
        if (!$product) {
            return false;
        }
        return $product->update($data);
    }
}

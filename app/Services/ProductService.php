<?php

namespace App\Services;

use Exception;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Repository\ProductRepository;

class ProductService
{
    protected $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createProduct(array $data)
    {
        DB::beginTransaction();

        try {
            $data['user_id'] = Auth::id();
            $category_ids = $data['category_ids'] ?? [];
            unset($data['category_ids']);
            $product = $this->repo->create($data);
            $product->categories()->sync($category_ids);
            DB::commit();
            return $product->load('categories', 'user', 'images');
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function deleteProduct(Product $product)
    {
        DB::beginTransaction();
        try {
            $product = $this->repo->delete($product);
            DB::commit();
            return $product;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function forceDeleteProduct(Product $product)
    {
        DB::beginTransaction();
        try {
            $product = $this->repo->forceDelete($product);
            DB::commit();
            return  $product;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function restoreProduct(Product $product)
    {
        DB::beginTransaction();
        try {
            $product = $this->repo->restore($product);
            DB::commit();
            return  $product;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function allProducts()
    {
        try {
            return $this->repo->all();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function trashedProducts()
    {
        try {
            return $this->repo->trashed();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateProduct(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $data['user_id'] = Auth::id();
            $category_ids = $data['category_ids'] ?? [];
            unset($data['category_ids']);
            $product = $this->repo->find($id);
            $product->categories()->sync($category_ids);
            $product->update($data);
            DB::commit();
            return $product->load('categories', 'user');
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}

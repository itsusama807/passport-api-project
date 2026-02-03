<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    protected $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $data['user_id'] = auth()->id();
            $category = $this->categoryRepo->create($data);
            DB::commit();
            return $category->load('user');
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function allCategories()
    {
        try {
            return $this->categoryRepo->all();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $category = $this->categoryRepo->update($data, $id);
            DB::commit();
            return $category;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $this->categoryRepo->delete($id);
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}

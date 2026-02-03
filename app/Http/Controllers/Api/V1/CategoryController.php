<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\Api\V1\Category\StoreCategoryRequest;
use App\Http\Requests\Api\V1\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index(): JsonResponse
    {
        try {
            $category = $this->categoryService->allCategories();
            return response()->json([
                'status' => true,
                'message' => 'Categories Fetched Successfully',
                'category' => CategoryResource::collection($category->load('products'))
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = $this->categoryService->store($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Category Crated Successfully',
                'category' => new CategoryResource($category)
            ], JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        try {
            $category = $this->categoryService->update($request->validated(), $id);
            return response()->json([
                'status' => true,
                'message' => 'Category Update Successfully',
                'category' => new CategoryResource($category)
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $this->categoryService->delete($id);
            return response()->json([
                'status' => true,
                'message' => 'Category Deleted Successfully',
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Product;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Api\V1\Product\StoreProductRequest;
use App\Http\Requests\Api\V1\Product\UpdateProductRequest;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        try {
            $products = $this->service->allProducts();
            return response()->json([
                'status' => true,
                'message' => 'Products fetched successfully',
                'products' => ProductResource::collection($products)
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = $this->service->createProduct($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Product Created Successfully',
                'product' => new ProductResource($product)
            ], JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function destroy($id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $this->service->deleteProduct($product);
            return response()->json([
                'status' => true,
                'message' => 'Product Deleted Successfully'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function trashed(): JsonResponse
    {
        try {
            $products = $this->service->trashedProducts();
            return response()->json([
                'status' => true,
                'message' => 'Trashed products fetched successfully',
                'products' => ProductResource::collection($products)
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function restore($id): JsonResponse
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);
            $this->service->restoreProduct($product);
            return response()->json([
                'status' => true,
                'message' => 'Product Restored Successfully'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function forceDelete($id): JsonResponse
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);
            $this->service->forceDeleteProduct($product);
            return response()->json([
                'status' => true,
                'message' => 'Product Deleted Permanently'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        try {
            $product = $this->service->updateProduct($request->validated(), $id);
            return response()->json([
                'status' => true,
                'message' => 'Product Updated With Category Successfully',
                'product' => new ProductResource($product->load('categories'))
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

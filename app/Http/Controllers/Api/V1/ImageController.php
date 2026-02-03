<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Image\StoreImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImageController extends Controller
{
    protected $imgService;

    public function __construct(ImageService $imgService)
    {
        $this->imgService = $imgService;
    }

    public function storeProductImage(StoreImageRequest $request, Product $product)
    {
        try {
            $image = $this->imgService->addImageToModel($request->file('url'), $product);
            return response()->json([
                'status' => true,
                'message' => 'Product Image Added Successully',
                'data' => new ImageResource($image)
             ], JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function storeCategoryImage(StoreImageRequest $request, Category $category)
    {
        try {
            $image = $this->imgService->addImageToModel($request->file('url'), $category);
            return response()->json([
                'status' => true,
                'message' => 'Category Image Added Successully',
                'data' => new ImageResource($image)
            ], JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

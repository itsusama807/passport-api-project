<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UpdateProfileResource;
use App\Http\Requests\Api\V1\Profile\UpdateProfileRequest;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function viewProfile($id)
    {
        try {
            $user = $this->profileService->viewProfile($id);
            return response()->json([
                'status' => true,
                'message' => 'User Profile Fetched Successfully',
                'user' => new UserResource($user)
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProfile(UpdateProfileRequest $request, $id): JsonResponse
    {
        try {
            $user = $this->profileService->updateProfile($request->validated(), $id);
            return response()->json([
                'status' => true,
                'message' => 'User Profile Updated Successfully',
                'user' => new UserResource($user)
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

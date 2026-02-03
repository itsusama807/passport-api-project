<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Services\PermissionService;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Api\V1\Permission\StorePermissionRequest;


class PermissionController extends Controller
{
    protected $permissionService;
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }
    public function assign(StorePermissionRequest $request):JsonResponse
    {
        try {
            $user = $this->permissionService->assign($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Permission Assgined Successfully',
                'permission' => $user->getPermissionNames()
            ], JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function revoke(StorePermissionRequest $request)
    {
        try {
            $permission = $this->permissionService->revoke($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Permission Revoked Successfully',
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

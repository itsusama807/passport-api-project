<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Api\V1\Auth\SendResetRequest;
use App\Http\Requests\Api\V1\Auth\RefreshTokenRequest;
use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'User Registered Successfully',
                'user' => new UserResource($user),
            ], JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->login($request->only(['email', 'password']));
            return response()->json([
                'status' => true,
                'message' => 'User Login Successfully',
                'user' => new UserResource($data['user']),
                'tokens' => [
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_in' => $data['expires_in'],
                    'token_type' => $data['token_type'],
                ],
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());
            return response()->json([
                'status' => true,
                'message' => 'User Logout Successfully'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {
        try {
            $tokens = $this->authService->refreshToken($request->refresh_token);
            return response()->json([
                'status' => true,
                'message' => 'Token refreshed successfully',
                'tokens' => [
                    'access_token' => $tokens['access_token'],
                    'refresh_token' => $tokens['refresh_token'],
                    'expires_in' => $tokens['expires_in'],
                    'token_type' => $tokens['token_type'],
                ],
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (str_contains($message, 'invalid_grant')) {
                $message = 'Your session has expired. Please login again.';
            }
            return response()->json([
                'status' => false,
                'error' => $message,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    public function sendResetLinkEmail(SendResetRequest $request)
    {
        try {
            $message = $this->authService->sendResetLink($request->email);
            return response()->json([
                'status' => true,
                'message' => $message
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $message = $this->authService->resetPassword($request->only(['email', 'password', 'password_confirmation', 'token']));
            return response()->json([
                'status' => true,
                'message' => $message
            ], JsonResponse::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}

<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use App\Jobs\PromoteUsersJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Permission;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class AuthService
{
    protected $authRepo;

    public function __construct(AuthRepositoryInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function register(array $data)
    {
        DB::beginTransaction();
        try {
            $data['password'] = Hash::make($data['password']);
            $user = $this->authRepo->create($data);
            $permissions = [
                'product.view',
                'product.create',
                'product.delete',
                'product.restore',
                'product.force-delete',
                'product.trashed.view',
                'product.update-category',

                'category.view',
                'category.create',
                'category.update',
                'category.delete',
            ];
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                ]);
            }
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            $user->givePermissionTo($permissions);
            DB::commit();
            return $user;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    // public function createToken(User $user, string $tokenName = 'authToken' ):string
    // {
    //     DB::beginTransaction();
    //     try {
    //         $token = $user->createToken($tokenName)->accessToken;
    //         DB::commit();
    //         return $token;
    //     } catch (Exception $ex) {
    //         DB::rollBack();
    //         throw $ex;
    //     }
    // }

    private function requestToken(array $data): array
    {
        try {
            $request = SymfonyRequest::create(
                '/oauth/token',
                'POST',
                $data
            );
            $response = app()->handle($request);
            if ($response->getStatusCode() !== 200) {
                throw new Exception('Token generation failed');
            }
            return json_decode($response->getContent(), true);
        } catch (Exception $ex) {
            throw new Exception('Failed to generate token: ' . $ex->getMessage());
        }
    }

    public function login(array $credentials): array
    {
        DB::beginTransaction();
        try {
            if (!Auth::attempt($credentials)) {
                throw new Exception('User not found / Invalid credentials');
            }

            $tokens = $this->requestToken([
                'grant_type'    => 'password',
                'client_id' => config('services.passport.password_client_id'),
                'client_secret' => config('services.passport.password_client_secret'),
                'username'      => $credentials['email'],
                'password'      => $credentials['password'],
                'scope'         => '',
            ]);
            DB::commit();
            return [
                'user' => Auth::user(),
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'expires_in' => $tokens['expires_in'],
                'token_type' => 'Bearer',
            ];
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    // login request not working without internal request need to discuss
    // public function login(array $credentials)
    // {
    //     try {
    //         if (!Auth::attempt($credentials)) {
    //             throw new Exception('User not found / Invalid credentials');
    //         }

    //         $response = Http::asForm()->post(config('app.url') . '/oauth/token', [
    //             'grant_type'    => 'password',
    //             'client_id' => config('services.passport.password_client_id'),
    //             'client_secret' => config('services.passport.password_client_secret'),
    //             'username'      => $credentials['email'],
    //             'password'      => $credentials['password'],
    //             'scope'         => '',
    //         ]);

    //         if ($response->failed()) {
    //             throw new Exception('Token generation failed');
    //         }

    //         $tokens = $response->json();

    //         return [
    //             'user' => Auth::user(),
    //             'access_token' => $tokens['access_token'],
    //             'refresh_token' => $tokens['refresh_token'],
    //             'expires_in' => $tokens['expires_in'],
    //             'token_type' => 'Bearer',
    //         ];

    //     } catch (Exception $ex) {
    //         throw $ex;
    //     }
    // }

    public function logout(User $user)
    {
        DB::beginTransaction();
        try {
            $user->token()->revoke();
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception('Failed to logout user: ' . $ex->getMessage());
        }
    }


    /**
     * Refresh access token using a refresh token
     */
    public function refreshToken(string $refreshToken): array
    {
        DB::beginTransaction();
        try {
            $data = [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => config('services.passport.password_client_id'),
                'client_secret' => config('services.passport.password_client_secret'),
                'scope'         => '',
            ];

            $request = SymfonyRequest::create('/oauth/token', 'POST', $data);
            $response = app()->handle($request);
            if ($response->getStatusCode() !== 200) {
                throw new Exception('Invalid refresh token or token expired');
            }
            DB::commit();
            return json_decode($response->getContent(), true);
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception('Failed to refresh token: ' . $ex->getMessage());
        }
    }

    public function sendResetLink(string $email)
    {
        DB::beginTransaction();
        try {
            $status = Password::sendResetLink(['email' => $email]);
            if ($status !== Password::RESET_LINK_SENT) {
                throw new Exception(__($status));
            }
            DB::commit();
            return __($status);
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception('Failed to send reset link: ' . $ex->getMessage());
        }
    }

    public function resetPassword(array $data): string
    {
        DB::beginTransaction();
        try {
            $status = Password::reset($data, function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
                $user->tokens()->delete();
            });
            if ($status !== Password::PASSWORD_RESET) {
                throw new Exception(__($status));
            }
            DB::commit();
            return __($status);
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception('Failed to reset password: ' . $ex->getMessage());
        }
    }
}

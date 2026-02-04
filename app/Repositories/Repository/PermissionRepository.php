<?php

namespace App\Repositories\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Repositories\Interfaces\PermissionRepositoryInterface;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function assignPermissionToAuthUser(string $permissionName)
    {
        $user = auth()->user();
        $user->givePermissionTo($permissionName);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        return $user->fresh();
    }

    public function revokePermissionFromAuthUser(string $permissionName)
    {
        $user = auth()->user();
        $user->revokePermissionTo($permissionName);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        return true;
    }
}

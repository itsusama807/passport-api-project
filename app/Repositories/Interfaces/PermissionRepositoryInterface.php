<?php

namespace App\Repositories\Interfaces;

interface PermissionRepositoryInterface
{
    public function assignPermissionToAuthUser(string $permissionName);
    public function revokePermissionFromAuthUser(string $permissionName);
}

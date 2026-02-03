<?php

namespace App\Services;

use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    protected $permissionRepo;

    public function __construct(PermissionRepositoryInterface $permissionRepo)
    {
        $this->permissionRepo = $permissionRepo;
    }

    public function assign(array $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->permissionRepo->assignPermissionToAuthUser($data['name']);
            DB::commit();
            return $user;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
    public function revoke(array $data)
    {
        DB::beginTransaction();
        try {
            $this->permissionRepo->revokePermissionFromAuthUser($data['name']);
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}

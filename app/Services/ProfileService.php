<?php

namespace App\Services;

use App\Repositories\Interfaces\ProfileRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;


class ProfileService
{
    protected $profileRepo;

    public function __construct(ProfileRepositoryInterface $profileRepo)
    {
        $this->profileRepo = $profileRepo;
    }

    public function viewProfile(int $id)
    {
        try {
            $user = $this->profileRepo->find($id);
            return $user;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function updateProfile(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->profileRepo->find($id);
            $user->update($data);
            DB::commit();
            return $user;
        } catch (Exception $ex) {
            DB::rollBack();
        }
    }
}

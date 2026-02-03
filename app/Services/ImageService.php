<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ImageService
{

    public function addImageToModel(UploadedFile $file, Model $model)
    {
        DB::beginTransaction();
        try {
            $path = $file->store('images', 'public');
            $image = $model->images()->create([
                'url' => $path
            ]);
            DB::commit();
            return $image;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

}

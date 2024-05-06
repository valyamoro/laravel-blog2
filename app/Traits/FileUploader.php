<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait FileUploader
{
    private function uploadImage(
        Request $request,
        string $filePath,
        string $fileDisk,
        string $fileImageName,
    ): Request
    {
        $imageNameFromForm = array_key_first($request->file());
        if ($request->hasFile($imageNameFromForm)) {
            $path = $request->file($imageNameFromForm)->store(
                $filePath,
                $fileDisk,
            );

            $request->merge([$fileImageName => $path]);
        }

        return $request;
    }

    private function deleteImage(
        Model $model,
        string $fileDisk,
        string $fileImageName,
    ): bool
    {
        return Storage::disk($fileDisk)->delete($model->{$fileImageName});
    }

}

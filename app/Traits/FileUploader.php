<?php

namespace App\Traits;

use Illuminate\Http\Request;

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

}

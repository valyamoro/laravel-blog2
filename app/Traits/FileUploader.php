<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait FileUploader
{
    private function uploadImage(
        Request $request,
        string $filepath,
        string $namedisk,
    ): ?string
    {
        $fileName = array_key_first($request->file());

        if ($request->hasFile($fileName)) {
            return $request->file($fileName)->store(
                $filepath,
                $namedisk,
            );
        }

        return null;
    }

    private function deleteImage(
        Model $model,
        string $namedisk,
        string $filename,
    ): bool
    {
        return Storage::disk($namedisk)->delete($model->{$filename});
    }

}

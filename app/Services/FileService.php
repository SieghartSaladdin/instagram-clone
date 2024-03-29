<?php

namespace App\Services;

use Image;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function updateFile($model, $request, $type)
    {
        if (!empty($model->file)) {
            $currentFile = public_path() . $model->file;

            if (file_exists($currentFile) && $currentFile != public_path() . '/user-placeholder.png') {
                unlink($currentFile);
            }
        }

        $file = null;

        if ($type === "user") {
            $file = Image::make($request->file('file'))->resize(400, 400);
        } else {
            $file = Image::make($request->file('file'));
        }

        $ext = $request->file('file');
        $extension = $ext->getClientOriginalExtension();
        $name = time() . '.' . $extension;

        Storage::disk('public')->put('file/' . $name, $file->encode());

        $model->file = '/storage/file/' . $name;

        return $model;
    }
}
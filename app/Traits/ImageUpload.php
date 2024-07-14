<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;

trait ImageUpload
{

    protected function uploadImage(UploadedFile $file, $directory = 'images')
    {
        // create new manager instance with desired driver
        $manager = ImageManager::gd();
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = public_path($directory);

        // Ensure the directory exists
        if (!File::exists($filePath)) {
            File::makeDirectory($filePath, 0755, true);
        }

        // Compress and save the image
        $image = $manager->read($file->getRealPath());
        $image->resize(500, 500)->save($filePath . '/' . $filename, 20); // 75 is the quality of the image (1-100)

        return $directory . '/' . $filename;
    }

    protected function deleteImage($path)
    {
        $fullPath = public_path($path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}

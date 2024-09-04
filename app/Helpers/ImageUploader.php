<?php

namespace App\Helpers;

use Faker\Core\File;
use Illuminate\Support\Facades\Storage;

trait ImageUploader
{
    public function uploadImage($file, $folder,$image_path = null) {


        if ($image_path != null) {
            $image_path = public_path().'/'.'uploads/'.$folder.'/'.$image_path;
            if(file_exists($image_path)) {
                unlink($image_path);
            }
        }
            $imageName = time() . '.' . $file->extension();
            $file->move(public_path('uploads'.'/'.$folder), $imageName);
            return $imageName;
        }

}

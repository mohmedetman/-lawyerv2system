<?php

namespace App\Helpers;

trait ImageUploader
{
    public function uploadImage($file, $folder, $imageName,$method = 'post',$id = null) {
        if ($method == 'post') {
            $imageName = time() . '.' . $file->extension();
            $file->move(public_path('uploads'.'/'.$folder), $imageName);
            return $imageName;
        }
    }

}

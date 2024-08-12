<?php

if (!function_exists('upload_image')) {
    function upload_image($image, $folder)
    {
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path($folder), $imageName);
        return $imageName;
    }
}

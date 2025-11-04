<?php

namespace App\Traits;

trait FileUpload{
    public function uplodFile($file, $path = 'upload'){
        $file_name = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("{$path}", $file_name);
        return 'storage/' .  $path;
    }
}

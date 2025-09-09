<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;

trait FileUploadTrait{
    /**
     * Store image in storage/app/public/$folder
     *
     * @param UploadedFile $image
     * @param string $folder
     * @return string path of stored image
     */

    public function storeFile(UploadedFile $file, string $folder, $width = null, $height = null,){
        $name = generate_random_key();
        $extension = strtolower($file->getClientOriginalExtension());
        $file_name = $name . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' .  $file_name;
        if($width || $height){
            $image = ImageManager::gd()->read($file);
            $image->resize($width ?? null, $height ?? null);
            switch ($extension) {
                case 'png':
                    $content = (string) $image->toPng();
                    break;
                case 'webp':
                    $content = (string) $image->toJpeg(90);
                    break;
                case 'jpg':
                case 'jpeg':
                default:
                    $content = (string) $image->toJpeg(90);
                    break;
            }

            Storage::disk('public')->put($path, $content);

        }else {
           Storage::disk('public')->putFileAs($folder, $file, $file_name);
        }

        return $path;
    }


    public function deleteFile($file_path){
        $fullPath = storage_path('app/public/' . $file_path);
        if (file_exists($fullPath)) {
            unlink($fullPath); // native PHP delete
        }
    }
}


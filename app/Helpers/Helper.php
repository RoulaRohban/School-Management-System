<?php
namespace App\Helpers;

use App\models\Image;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Helper
{
    public static function getFilePath($folder_name="", $file_name="") {
        return storage_path("app/public/$folder_name" . ($file_name ? "/$file_name" : ""));
    }

    public static function getFileUrl($folder_name="", $file_name="") {
        return url("storage/$folder_name" . ($file_name ? "/$file_name" : ""));
    }

    public static function uploadFileTo($file,$path)
    {

        $file_path = Storage::disk('public_images')->put($path, $file);

        return [
            'media_path' => $file_path,
            'media_url' => self::getMediaUrl($file_path)
        ];


    }

    public static function deleteFile($file_path)
    {
        return Storage::disk('public_images')->delete($file_path);
    }


    public static function getMediaUrl($path)
    {
        return url('/uploads/'.$path);
    }

    public static function getMediaPath($path)
    {
        return '/uploads/'.$path;
    }
    public static function handleImageReplace($request,$object,$path)
    {
        if ($request->hasFile('image')) {
            $image = $request->image;
            $image_data = self::uploadFileTo($image, $path);
            $image = Image::create([
                'image_path' => $image_data["media_path"],
                'is_primary' => 'yes'
            ]);
            if ($object->image()->count()) {
                self::deleteFile($object->image->image_path);
                $object->image()->delete();
            }
            $object->image()->save($image);
        }
    }
}

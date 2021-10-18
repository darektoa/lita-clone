<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageHelper{
  static public function put($path, $file) {
    try{
      $fileExt    = $file->getClientOriginalExtension();
      $fileName   = Str::uuid() . ".$fileExt";
      $pathName   = "$path/$fileName";
  
      Storage::disk('s3')->put($pathName, $file->getContent());

      return $pathName;
    } catch(Exception $err) {
      return null;
    }
  }
}
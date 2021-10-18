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


  static public function get($path) {
    $AWS_BUCKET         = env('AWS_BUCKET');
    $AWS_DEFAULT_REGION = env('AWS_DEFAULT_REGION');
    $AWS_DOMAIN         = 'amazonaws.com';

    return "https://$AWS_BUCKET.s3.$AWS_DEFAULT_REGION.$AWS_DOMAIN/$path";
  }
}
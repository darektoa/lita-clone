<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function PHPUnit\Framework\returnSelf;

class StorageHelper{
  static public function put($path, $file) {
    try{
      $fileExt    = $file->getClientOriginalExtension();
      $fileName   = Str::uuid() . ".$fileExt";
      $pathName   = "$path/$fileName";
  
      Storage::disk('s3')->put($pathName, $file->getContent(), 'public');

      return $pathName;
    } catch(Exception $err) {
      return null;
    }
  }


  static public function delete($path) {
    return Storage::disk('s3')->delete($path);
  }


  static public function get($path) {
    return Storage::disk('s3')->get($path);
  }


  static public function url($path) {
    $AWS_BUCKET         = env('AWS_BUCKET');
    $AWS_DEFAULT_REGION = env('AWS_DEFAULT_REGION');
    $AWS_DOMAIN         = 'amazonaws.com';

    return "https://$AWS_BUCKET.s3.$AWS_DEFAULT_REGION.$AWS_DOMAIN/$path";
  }
}
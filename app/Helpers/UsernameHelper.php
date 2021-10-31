<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsernameHelper{
  static public function make($string) {
    $string   = Str::limit($string, 30, '');
    $rules    = 'unique:users,username';
    $validate = Validator::make([$string], [0 => $rules]);
    $unique   = false;

    if($validate->fails()) $string = Str::limit($string, 20, '');
    else $unique = true;

    while(!$unique) {
      $newString  = $string . rand(1, 9999999999);
      $validate   = Validator::make([$newString], [0 => $rules]);
      if(!$validate->fails()) {
        $string = $newString;
        $unique = true;
      }     
    }

    return $string;
  }
}
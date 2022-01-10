<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsernameHelper{
  static public function make($string) {
    $string   = Str::slug(Str::limit($string, 15, ''), '.');

    $rules    = 'regex:/^[0-9a-z\._]{5,15}$/i|unique:username_exceptions,username|unique:users,username';
    $validate = Validator::make([$string], [0 => $rules]);
    $unique   = false;

    if($validate->fails()) $string = Str::limit($string, 10, '');
    else $unique = true;

    while(!$unique) {
      $newString  = $string . rand(1, 99999);
      $validates   = Validator::make([$newString], [0 => $rules]);

      if(!$validates->fails()){
        echo false == 0;
        $string = $newString;
        $unique = true;
      }
    }

    return $string;
  }
}
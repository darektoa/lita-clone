<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class UsernameHelper{
  static public function make($string) {
    $validateToUsername = Validator::make([$string], [
        0 => 'unique:users,username'
    ]);

    // $unique = false;

    // while(!$unique) {
      
    // }

    return $validateToUsername->fails() ? $string . rand(pow(10, 8 - 1), pow(10, 8) -1) : $string;
  }
}
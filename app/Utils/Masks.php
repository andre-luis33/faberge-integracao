<?php

namespace App\Utils;

class Masks {

   static function secret(string $string, int $visibleChars = 3, string $maskChar = '*') {
      $prefix = substr($string, 0, $visibleChars);
      $length = strlen($string) - $visibleChars;
      $suffix = str_repeat($maskChar, $length);
      return $prefix . $suffix;
   }

}

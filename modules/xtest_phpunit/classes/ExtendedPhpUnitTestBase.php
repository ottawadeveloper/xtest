<?php

class ExtendedPhpUnitTestBase extends PHPUnit_Framework_TestCase {
  
  protected function generateRandomString($prefix = '', $minLength = NULL, $maxLength = NULL, $charset = NULL) {
    if (empty($minLength)) {
      $minLength = 1;
    }
    if (empty($maxLength)) {
      $maxLength = 255;
    }
    if (empty($charset)) {
      $charset = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    }
    $length = rand($minLength, $maxLength);
    $str = '';
    for ($k = 0; $k < $length; $k++) {
      $str .= $charset[rand(0, strlen($charset) - 1)];
    }
    return $prefix . $str;
  }
  
}
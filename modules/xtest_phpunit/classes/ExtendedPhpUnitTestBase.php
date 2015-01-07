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
  
  public function addJunkData(&$badValues, $omitValues = array()) {
    $junkData = array(
      TRUE,
      FALSE,
      NULL,
      array(),
      (object) array(),
      0,
      1,
      '',
      'RANDOMJUNKNOSPACES',
      'THIS IS RANDOM JUNK DATA THAT SHOULD NOT MATCH ANYTHING - NOW WITH SPACES',
      'Special chars: é’!@#$%^&*()"\'\\/{}[]|<>/?.,`~_+-=',
      '1 or 1=1',
      '1; DROP TABLE users',
    );
    foreach ($junkData as $datum) {
      if (!in_array($datum, $omitValues, TRUE)) {
        $badValues[] = $junkData;
      }
    }
  }
  
}
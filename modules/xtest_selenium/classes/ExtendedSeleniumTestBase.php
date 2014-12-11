<?php

class ExtendedSeleniumTestBase extends PHPUnit_Extensions_Selenium2TestCase {
  
  protected function drupalUrl($url, $language = NULL) {
    return $this->url(url($url, array(
      'absolute' => TRUE,
      'language' => $language,
    )));
  }
  
  protected function setup() {
    global $base_url;
    $this->setBrowser('firefox');
    $this->setBrowserURL($base_url);
  }
  
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
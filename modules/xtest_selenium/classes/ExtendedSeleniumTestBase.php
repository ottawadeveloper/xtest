<?php

class ExtendedSeleniumTestBase extends PHPUnit_Extensions_Selenium2TestCase {
  
  protected function assertRgbColor($color, $expected, $message = NULL) {
    $cssColor = new SeleniumCssColor($color);
    $this->assertEquals(strtolower($expected), strtolower($cssColor->rgb()), $message);
  }
  
  protected function drupalNodeUrlByUuid($uuid) {
    $nodes = entity_uuid_load('node', array($uuid));
    $node = reset($nodes);
    $this->assertNotEmpty($node);
    $url = 'node/' . $node->nid;
    return $this->drupalUrl($url);
  }

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
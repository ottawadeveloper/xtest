<?php

class XTestPhpUnitTestRunner extends XTestAbstractTestRunner {
  
  private $junitTarget = FALSE;
  
  public function __construct($options = array()) {
    if (!empty($options['junit-output-file'])) {
      $this->junitTarget = $options['junit-output-file'];
    }
  }
  
  public function getTestName($full_path) {
    return pathinfo($full_path, PATHINFO_FILENAME);
  }
  
  public function executeTests($testCases) {
    global $base_url;
    $xml = $this->buildXMLFile($testCases);
    $xmlFile = $this->saveXML($xml);
    chdir(DRUPAL_ROOT);
    $command = 'DRUSH_XTEST_BASEURL="'.$base_url.'" phpunit --configuration ' . $xmlFile . '  --testsuite AutoXTestSuite';
    system($command);
  }
  
  private function saveXML($xml) {
    $temp_filename = tempnam(sys_get_temp_dir(), 'pux');
    file_put_contents($temp_filename, $xml);
    return $temp_filename;
  }
  
  private function buildXMLFile($testCases) {
    $bootstrapFile = DRUPAL_ROOT . DIRECTORY_SEPARATOR . drupal_get_path('module', 'xtest_phpunit') . DIRECTORY_SEPARATOR . 'phpunit-drupal-bootstrap.php';
    $xml = '<phpunit'
      . ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'
      . ' xsi:noNamespaceSchemaLocation="http://schema.phpunit.de/4.3/phpunit.xsd"'
      . ' bootstrap="' . $bootstrapFile . '"'
      . '>';
    $xml .= '  <testsuites>';
    $xml .= '    <testsuite name="AutoXTestSuite">';
    foreach ($testCases as $testCase) {
      $xml .= '      <file>' . check_plain($testCase['path']) . '</file>';
    }
    $xml .= '    </testsuite>';
    $xml .= '  </testsuites>';
    // @todo add support for code coverage exclusions here
    $xml .= '  <logging>';
    if (!empty($this->junitTarget)) {
      $xml .= '  <log type="junit" target="' . check_plain($this->junitTarget) . '" logIncompleteSkipped="false" />';
    }
    $xml .= '  </logging>';
    $xml .= '</phpunit>';
    return $xml;
  }
  
}

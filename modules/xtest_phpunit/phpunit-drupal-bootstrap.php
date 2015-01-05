<?php



$xtest_siteurl = getenv('DRUSH_XTEST_BASEURL');
$xtest_siteurl_pieces = parse_url($xtest_siteurl) + array(
  'path' => '/',
);

$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_HOST'] = $xtest_siteurl_pieces['host'];
$_SERVER['REQUEST_URI'] = $xtest_siteurl_pieces['path'];

/**
 * Root directory of Drupal installation.
 */
define('DRUPAL_ROOT', getcwd());


function _xtest_phpunit_handle_test_errors($errno, $errstr, $errfile, $errline, $errcontext) {
  
  $errors = array(
    E_ERROR => 'Error',
    E_WARNING => 'Warning',
    E_PARSE => 'Parser Error',
    E_NOTICE => 'Notice',
    E_CORE_ERROR => 'Core Error',
    E_CORE_WARNING => 'Core Warning',
    E_USER_DEPRECATED => 'User Deprecated',
    E_DEPRECATED => 'Deprecated',
    E_RECOVERABLE_ERROR => 'Recoverable Error',
    E_STRICT => 'Strict Notification',
    E_USER_NOTICE => 'User Notice',
    E_USER_WARNING => 'User Warning',
    E_USER_ERROR => 'User Error',
  );
  
  $error_type = 'Unknown - ' . $errno;
  if (isset($errors[$errno])) {
    $error_type = $errors[$errno];
  }
  
  echo 'Type:  ' . $error_type . PHP_EOL;
  echo 'Error: ' . $errstr . PHP_EOL;
  echo 'File:  ' . str_replace(DRUPAL_ROOT, '', $errfile) . PHP_EOL;
  echo 'Line:  ' . $errline . PHP_EOL;
  echo PHP_EOL;
}

set_error_handler('_xtest_phpunit_handle_test_errors');

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
set_error_handler('_xtest_phpunit_handle_test_errors');



global $base_url;
$base_url = $xtest_siteurl;

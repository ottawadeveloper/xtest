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

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

global $base_url;
$base_url = $xtest_siteurl;

<?php
/**
 * @file
 * Contains drush-related functionality.
 */

/**
 * Implements hook_drush_command().
 */
function xtest_drush_command() {
  return array(
    'xtest' => array(
      'options' => _xtest_drush_options(),
      'description' => 'Runs test cases',
      'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_FULL,
    ),
  );
}

/**
 * Command callback for xtest.
 */
function drush_xtest() {
  $options = array();
  foreach (array_keys(_xtest_drush_options()) as $option_name) {
    $options[$option_name] = drush_get_option($option_name);
  }
  $path = drupal_get_path('module', 'xtest');
  require_once $path . '/classes/XTestTestRunnerInterface.php';
  require_once $path . '/classes/XTestAbstractTestRunner.php';
  xtest_run_tests($options);
}

/**
 * List of options that can be used with this drush command.
 * 
 * @return array
 *   An array of options, where the keys are the option to be
 *   provided, and the values are the English text strings that
 *   describe them.
 */
function _xtest_drush_options() {
  return array(
    'type' => 'Limit tests to the specified test type.',
    'module' => 'Limit tests to the specified module.',
    'name' => 'Limit tests to the specified class name.',
    'junit-output-file' => 'Specify a file to store JUnit output from these tests, if supported by the test type.',
    'rebuild-tests' => 'Rebuild the test cache',
  );
}

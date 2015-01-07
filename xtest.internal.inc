<?php

function _xtest_instantiate_runner($runner_name, $options = array()) {
  $runner_def = _xtest_get_test_runners($runner_name);
  if (empty($runner_def)) {
    return NULL;
  }
  return new $runner_def['class']($options);
}

function _xtest_get_test_runners($runner_name = NULL) {
  $testRunners = &drupal_static(__FUNCTION__, array());
  if (empty($testRunners)) {
    $testRunners = _xtest_build_test_runner_list();
  }
  if (!empty($runner_name)) {
    if (isset($testRunners[$runner_name])) {
      return $testRunners[$runner_name];
    }
    return array();
  }
  return $testRunners;
}

function _xtest_build_test_runner_list() {
  return module_invoke_all('xtest_test_runners');
}

function _xtest_get_test_cases($type = NULL, $reset = FALSE) {
  $testCases = &drupal_static(__FUNCTION__, array());
  if ($reset || empty($testCases)) {
    $cached = cache_get(__FUNCTION__);
    if (!empty($cached->data)) {
      $testCases = $cached->data;
    }
    if ($reset || empty($testCases)) {
      $testCases = _xtest_build_test_case_list();
      cache_set(__FUNCTION__, $testCases);
    }
  }
  if (!empty($type)) {
    if (isset($testCases[$type])) {
      return array(
        $type => $testCases[$type],
      );
    }
    return array();
  }
  return $testCases;
}

function _xtest_build_test_case_list() {
  $testCases = array();
  $modules = module_list();
  foreach ($modules as $module) {
    $module_path = drupal_get_path('module', $module);
    $infoFile = $module_path . '/' . $module . '.info';
    if (file_exists($infoFile)) {
      $info = drupal_parse_info_file($infoFile);
      $testCases = array_merge_recursive($testCases, _xtest_build_test_case_list_for_module($module, $module_path, $info));
    }
  }
  return $testCases;
}

function _xtest_build_test_case_list_for_module($module, $path, $info) {
  $testCases = array();
  if (isset($info['xtests'])) {
    foreach ($info['xtests'] as $testType => $testFiles) {
      $runner = _xtest_instantiate_runner($testType);
      if (!empty($runner)) {
        foreach ($testFiles as $file) {
          if (!isset($testCases[$testType])) {
            $testCases[$testType] = array();
          }
          $testCases[$testType][] = _xtest_build_test_case_entry($file, $module, $path, $testType, $runner);
        }
      }
    }
  }
  return $testCases;
}

function _xtest_build_test_case_entry($file, $module, $module_path, $type, XTestTestRunnerInterface $runner) {
  $full_path = rtrim(DRUPAL_ROOT, DIRECTORY_SEPARATOR);
  $full_path .= DIRECTORY_SEPARATOR;
  $full_path .= trim($module_path, DIRECTORY_SEPARATOR);
  $full_path .= DIRECTORY_SEPARATOR;
  $full_path .= ltrim($file, DIRECTORY_SEPARATOR);
  return array(
    'path' => $full_path,
    'file' => $file,
    'module' => $module,
    'type' => $type,
    'name' => $runner->getTestName($full_path),
  );
}

function _xtest_get_filtered_test_cases($type = NULL, $module = NULL, $name = NULL, $reset = FALSE) {
  $testCases = xtest_test_cases($type, $reset);
  $filteredTestCases = array();
  foreach ($testCases as $type => $list) {
    foreach ($list as $testCase) {
      $moduleMatches = empty($module) || $testCase['module'] == $module;
      $nameMatches = empty($name) || $testCase['name'] == $name;
      if ($moduleMatches && $nameMatches) {
        if (!isset($filteredTestCases[$type])) {
          $filteredTestCases[$type] = array();
        }
        $filteredTestCases[$type][] = $testCase;
      }
    }
  }
  return $filteredTestCases;
}

function _xtest_execute_test_cases($testCases, $options) {
  foreach ($testCases as $runner_name => $cases) {
    $runner = _xtest_instantiate_runner($runner_name, $options);
    if (empty($runner)) {
      watchdog('xtest', 'Invalid runner type !type', array(
        '!type' => $runner_name,
      ));
      throw new Exception(t('Invalid runner type !type', array(
        '!type' => $runner_name,
      )));
    }
    $runner->executeTests($cases);
  }
}
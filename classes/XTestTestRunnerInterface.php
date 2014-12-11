<?php

interface XTestTestRunnerInterface {
  
  function getTestName($full_path);
  
  function executeTests($testCases);
  
}
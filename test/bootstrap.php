<?php

/**
 * Bootstrap file for PHPUnit tests
 */

/**
 * Set the error reporting to standards
 */
error_reporting(E_ALL | E_STRICT);

/**
 * Initialize Autoloader
 */
require __DIR__ . '/../vendor/autoload.php';

if (version_compare(phpversion(), '5.4.0', '<')) {
  interface JsonSerializable {
    function jsonSerialize();
  }
}

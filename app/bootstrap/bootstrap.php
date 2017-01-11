<?php

/**
 * Bootstrapping the configuration
 */
define('ROOT_PATH', realpath(__DIR__ . '/../../') . '/');
define('APP_PATH', realpath(__DIR__ . '/../../app/') . '/');
define('SRC_PATH', realpath(__DIR__ . '/../../src/') . '/');
define('VENDOR_PATH', realpath(__DIR__ . '/../../vendor/') . '/');
define('PUBLIC_PATH', realpath(__DIR__ . '/../../public/') . '/');
define('MODULE_PATH', realpath(__DIR__ . '/../../app/modules/') . '/');

require VENDOR_PATH . 'autoload.php';

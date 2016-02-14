<?php

/**
 * Bootstrapping the configuration
 */
define('ROOT_PATH', realpath(__DIR__ . '/../../') . '/');
define('VENDOR_PATH', realpath(__DIR__ . '/../../vendor/') . '/');
define('APP_PATH', realpath(__DIR__ . '/../../app/') . '/');
define('MODULE_PATH', realpath(__DIR__ . '/../../app/modules/') . '/');
define('PUBLIC_PATH', realpath(__DIR__ . '/../../public/') . '/');

require VENDOR_PATH . 'autoload.php';

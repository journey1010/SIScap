<?php

define ('_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] , false);
define ('_ROOT_CONTROLLER', _ROOT_PATH.'/controller/', false);
define ('_ROOT_MODEL', _ROOT_PATH.'/model/', false);
define ('_ROOT_VIEWS', _ROOT_PATH.'/views/', false);
define('PROTOCOL', (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === 'on' ? 'https' : 'http'), false);
define('_BASE_URL', PROTOCOL . '://' . $_SERVER['HTTP_HOST'], false);
define('_ROOT_ASSETS', _BASE_URL.'/assets/', false);
define('_ROOT_ASSETS_ADMIN', _BASE_URL.'/assets/admin/', false);
define('_ROOT_CACHE', _ROOT_PATH. '/resources/cache/', false);
define('_ROOT_FILES', _ROOT_PATH . '/files/', false);
define('_RTADMINLTE3', _BASE_URL . '/vendor/almasaeed2010/adminlte/', false);
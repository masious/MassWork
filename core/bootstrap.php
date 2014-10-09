<?php
// Dirs
define('ROOT',dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR );
define('CONTROLLER_DIR', ROOT . DS . 'controller');
define('VIEWS_DIR', ROOT . DS . 'views');
define('COMPONENTS_DIR', ROOT . DS . 'components');
define('LAYOUTS_DIR', ROOT . DS . 'layouts');
define('MODELS_DIR', ROOT . DS . 'model');
define('ASSETS_DIR', ROOT . DS . 'assets');

// site configuration
define('URLBASE', 'http://localhost');
define('SITE_TITLE','شاپ');
define('DEFAULT_LAYOUT','enzyme');
define('HOME_VIEW','pages/home');

// defaults
define('DEFAULT_VIEW','index');

// needed files
require_once ROOT.'/functions.php';
require 'ErrorHandler.php';
require 'RapnaRequest.php';

// DB
define('DB_ADDRESS','localhost');
define('DB_NAME','polymer_shop');
define('DB_USERNAME','root');
define('DB_PASSWORD','');

//URLs
define('CSS_URL',URLBASE . '/assets/css');
define('JS_URL',URLBASE . '/assets/js');
// there is no "default directory var" for core dir, because it's used before this file is loaded
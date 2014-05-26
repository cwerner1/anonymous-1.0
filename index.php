<?php

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', false);
date_default_timezone_set('America/Louisville');

$rootDir = realpath(dirname(dirname(__FILE__)));

//set_include_path("G:/xampp/htdocs/library/");
set_include_path($rootDir . '/anonymous/library/' .
                 PATH_SEPARATOR . './application/models' .
                 PATH_SEPARATOR . get_include_path())
                 ;

require_once 'Zend/Loader/Autoloader.php';
require_once 'Zend/Cache.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);

Zend_Controller_Action_HelperBroker::addPath('application/controllers/helpers', '');

// Create the rewrite router. This reroutes particular URLs to appropriate controllers/actions
$router = new Zend_Controller_Router_Rewrite();

// Load the configuration file. Change localhost to online to put online
$config = new Zend_Config_Ini('application/config.ini', 'localhost');

// Add the routes from the config file
$router->addConfig($config, 'routes');

// Put config in registry for access elsewhere in application
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);

$options = array(
    'layout'     => 'layout',
    'layoutPath' => 'application/layouts',
    'contentKey' => 'content', /* default is content */
);

$layout = Zend_Layout::startMvc($options);

$view = $layout->getView();

$view->doctype();
$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');

$view->inlineScript()->appendFile('/public/js/google-analytics.js');   

$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
$viewRenderer->setView($view);
Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

// Set up the database
$db = Zend_Db::factory($config->db->adapter, $config->db->config->toArray());
$db->query("SET NAMES 'utf8'"); 
Zend_Db_Table::setDefaultAdapter($db);

// Set up the controller
// $frontController = Zend_Controller_Front::getInstance();

$frontController = Zend_Controller_Front::getInstance();

$frontController->setBaseUrl('/');    

$frontController->throwExceptions(true);


$frontController->setControllerDirectory('application/controllers');



// Add the rewrite router
$frontController->setRouter($router);

// Set up the cache
$frontendOptions = array(
   'lifetime' => 720, // cache lifetime
   'automatic_serialization' => true
);
 
$backendOptions = array(
    'cache_dir' => 'application/cache' // Directory where to put the cache files
);

$cache = Zend_Cache::factory('Page',
                     'File',
                     $frontendOptions,
                     $backendOptions);
                     
$cache->start();

Zend_Registry::set('cache',$cache);

// Run the application
$frontController->dispatch();
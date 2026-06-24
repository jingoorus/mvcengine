<?php
@error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);

if (session_id() == '') session_start();

define('ROOT', dirname(__FILE__));
/**
 *Core
 **/
include 'core/classes/query.class.php';
include 'core/classes/route.class.php';
include 'core/classes/events.class.php';
include 'core/classes/controller.class.php';
include 'core/classes/model.class.php';
include 'core/classes/model.standart.class.php';
include 'core/classes/template.class.php';
include 'core/classes/view.class.php';
include 'core/classes/document.class.php';
include 'core/classes/dictionary.class.php';
include 'core/classes/config.class.php';

spl_autoload_register(function ($class_name) {

    if (file_exists(ROOT . '/library/' . strtolower($class_name) . '.class.php')) {

        require_once ROOT . '/library/' . strtolower($class_name) . '.class.php';

    } elseif (file_exists(ROOT . '/extensions/' . strtolower($class_name) . '.class.php')) {

        require_once ROOT . '/extensions/' . strtolower($class_name) . '.class.php';
    }
});

new Route;


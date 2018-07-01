<?php
@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

if(session_id() == '') session_start();

define ( 'ROOT', dirname ( __FILE__ ) );
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
/**
  *Extension loader for frontend classes (in admin mode classes loaded automaticaly by spl_autoload from 'library' folder)
  *Extension class load first extensions folder, then library, because user might be rewrite any classes by self
  **/
include 'core/classes/extension.class.php';

Route::index();
?>

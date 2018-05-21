<?php
if(session_id() == '') session_start();
define ( 'ROOT', dirname ( __FILE__ ) );
require_once 'core/classes/route.class.php';
require_once 'core/classes/model.class.php';
require_once 'core/classes/model.standart.class.php';
require_once 'core/classes/controller.class.php';
require_once 'core/classes/controller.standart.class.php';
require_once 'core/classes/template.class.php';
require_once 'core/classes/view.class.php';
Route::start();
?>

<?php
Event::bind('admin.editpage.init', function($page_name){

    $file_path = ROOT . '/core/events/event_'.$page_name.'.php';

    Controller_Admin::$page_extensions['edit_events'] = '<textarea name="event_data" class="form-control">'.file_get_contents($file_path).'</textarea>';

});

Event::bind('admin.savepage.init', function($page_name){

    $post = Query::$post;

    if ($post['event_data'] && $post['event_data'] != '') file_put_contents(ROOT . '/core/events/event_'.$page_name.'.php', $post['event_data']);

});

Event::bind('admin.editpageitem.init', function($page_name){

    //Query::$get;
    //Query::$post;
    //Route::$controller;
    //Route::$model;
    //Route::$routes;
    //Controller_Admin::$page_extensions;

});

Event::bind('admin.savepageitem.init', function($page_name){

    //Query::$get;
    //Query::$post;
    //Route::$controller;
    //Route::$model;
    //Route::$routes;

});
?>

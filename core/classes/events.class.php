<?php
/**
 *Event system is used for extend engine by users functions
 **/
final class Event
{
    public static $events = array();

    private final function __construct() {}

    public static function trigger($event, $args = array())
    {
        if(isset(self::$events[$event]))
        {
            foreach(self::$events[$event] as $func)
            {
                call_user_func($func, $args);
            }
        }

    }

    public static function bind($event, Closure $func)
    {
        if (!self::$events[$event]) self::$events[$event] = array();

        self::$events[$event][] = $func;
    }

    /**
     *Events load in route before init controller
     **/
    public static function load_events($page)
    {
        $events_file = ROOT . '/core/events/event_' . $page . '.php';

        if (file_exists($events_file)) include $events_file;
    }
}
?>

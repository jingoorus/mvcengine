<?php

final class Route
{
    protected static $errors = [];

    public static $routes = [];

    public static $controller = 'Main';

    public static $action = 'index';

    public static function exec()
    {
        if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {

            $request = explode('?', $_SERVER['REQUEST_URI']);

            $request = $request[0];

        } else {

            $request = $_SERVER['REQUEST_URI'];
        }

        self::$routes = $request == '/' ? self::$routes : explode('/', $request);

        array_shift(self::$routes);

        foreach (self::$routes as &$route) {

            $route = preg_replace('#[^\w]#', '', $route);

        }

        if (!empty(self::$routes[0])) {

            self::$controller = self::$routes[0];
        }

        if (!empty(self::$routes[1])) {

            self::$action = self::$routes[1];
        }

        Event::load_events(self::$controller);

        Event::trigger('route.getdata.convert.before');

        $query_array = [];

        if ($_SERVER['QUERY_STRING'] != '') {

            $_SERVER['QUERY_STRING'] = preg_replace('#[^\w\=\&]#', '', $_SERVER['QUERY_STRING']);

            parse_str($_SERVER['QUERY_STRING'], $query_array);

            $_SERVER['QUERY_STRING'] = '';
        }

        unset($_GET);

        Event::trigger('route.postdata.convert.before');

        $post_array = [];

        if ($_POST) {

            $post_array = $_POST;

            unset($_POST);
        }

        new Query($query_array, $post_array);

        unset($_REQUEST);

        $request_headers = getallheaders();

        if ($request_headers['X-Requested-With'] == 'XMLHttpRequest') {

            Event::trigger('route.xhttp.switch.before');

            if (file_get_contents('php://input')) {

                new Query($query_array, json_decode(file_get_contents('php://input')));
            }

            self::xhttp();

        } else {

            Event::trigger('route.http.switch.before');

            self::http();
        }
    }

    private static function http()
    {
        $model_name = 'Model_' . self::$controller;

        $controller_name = 'Controller_' . self::$controller;

        $action = 'action_' . self::$action;

        $model_path = ROOT . '/core/models/' . strtolower($model_name) . '.php';

        if (file_exists($model_path)) include $model_path;

        $controller_path = ROOT . '/core/controllers/' . strtolower($controller_name) . '.php';

        if (file_exists($controller_path)) {

            include $controller_path;

            Event::trigger('route.controller.load.before', $controller_name);

            $controller = new $controller_name;

        } else {

            include ROOT . '/core/classes/controller.standart.class.php';

            Event::trigger('route.controller.load.before', 'Controller_Standart');

            $controller = new Controller_Standart;
        }

        if (is_callable(array($controller, $action))) {

            Event::trigger('route.action.execute.before', $action);

            $controller->$action();

            Event::trigger('route.document.build.before', $controller);

            $controller->view->build_document();

            Event::trigger('route.document.echo.before');

            Doc::echo_document();

        } else {

            self::Page404('action not found');
        }
    }

    private static function xhttp()
    {
        $method = self::$controller;

        Doc::$headers[] = 'Content-Type: application/json';

        $api = new Extension('Api');

        if (method_exists($api, $method)) {

            Doc::addResult($api->$method(self::$action));

        } else {

            Doc::addResult(['answer' => 'error', 'data' => 'method not exists']);
        }

        Doc::echo_xhttp();
    }

    public static function Page404($message = null)
    {
        Doc::$headers[] = 'HTTP/1.0 404 Not Found';

        Doc::$headers[] = 'Status: 404 Not Found';

        if ($message) {

            echo $message;
        }
    }
}

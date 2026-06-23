<?php

final class Route
{
    public static $routes = [];

    public static $controller = 'main';

    public static $action = 'index';

    public function __construct()
    {
        $request = strpos($_SERVER['REQUEST_URI'], '?') !== false ? explode('?', $_SERVER['REQUEST_URI'])[0] : $_SERVER['REQUEST_URI'];

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

        new Query();

        Event::load_events(self::$controller);

        $model_name = 'Model_' . ucfirst(self::$controller);

        $controller_name = 'Controller_' . ucfirst(self::$controller);

        $action = 'action_' . self::$action;

        $model_path = ROOT . '/core/models/' . strtolower($model_name) . '.php';

        if (file_exists($model_path)) {

            include $model_path;
        }

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

        if (is_callable([$controller, $action])) {

            Event::trigger('route.action.execute.before', ['controller' => $controller, 'action' => $action]);

            $controller->$action();

            Event::trigger('route.document.build.before', ['controller' => $controller, 'action' => $action]);

            $controller->setResponse();

            Event::trigger('route.document.echo.before', ['controller' => $controller, 'action' => $action]);

            self::response();

        } else {

            self::Page404();
        }
    }

    public static function Page404()
    {
        Doc::setHeaders('HTTP/1.0 404 Not Found');

        Doc::setHeaders('Status: 404 Not Found');

        self::serverError('Page not found', 404);
    }

    public static function serverError($message, $code = 500)
    {
        http_response_code($code);

        $view = new View('default');

        if (getallheaders()['content-type'] == 'application/json') {

            $view->json([

                'error' => $message
            ]);

        } else {

            $view->generate('error.tpl', [

                'content' => $message
            ]);
        }

        $view->build_document();

        self::response();

        exit;
    }

    private static function response()
    {
        if (getallheaders()['content-type'] == 'application/json') {

            Event::trigger('route.xhttp.echo.before');

            Doc::echo_xhttp();

        } else {

            Event::trigger('route.http.echo.before');

            Doc::echo_document();
        }
    }
}

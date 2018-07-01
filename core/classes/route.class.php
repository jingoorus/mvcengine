<?php
final class Route
{
	protected static $errors = array();

	public static $routes = array();

	public static $controller = 'Main';

	public static $action = 'index';

	public static function index()
	{
		if ( strpos($_SERVER['REQUEST_URI'], '?') !== false ) {

			$request = explode('?', $_SERVER['REQUEST_URI']);

			$request = $request[0];

		} else $request = $_SERVER['REQUEST_URI'];

		self::$routes = $request == '/' ? self::$routes : explode('/', $request);

		if ( !empty(self::$routes[1]) ) self::$controller = self::$routes[1];

		if ( !empty(self::$routes[2]) ) self::$action = self::$routes[2];

		Event::load_events(self::$controller);

		Event::trigger('route.getdata.convert.before');

		if ( $_SERVER['QUERY_STRING'] != '' ) {

			parse_str($_SERVER['QUERY_STRING'], $query_array);

			Query::$get = $query_array;

			$_SERVER['QUERY_STRING'] = '';
		}

		Event::trigger('route.postdata.convert.before');

		if ( $_POST ) Query::$post = $_POST;

		$_GET = array();

		$_POST = array();

		$_REQUEST = array();

		$request_headers = getallheaders();

		if ($request_headers['X-Requested-With'] == 'XMLHttpRequest') {

			Event::trigger('route.xhttp.switch.before');

			self::xhttp();

		} else {

			Event::trigger('route.http.switch.before');

			self::http();
		}
	}

	private static function http()
	{
		$model_name = 'Model_'.self::$controller;

		$controller_name = 'Controller_'.self::$controller;

		$action = 'action_'.self::$action;

		$model_path = ROOT . '/core/models/'.strtolower($model_name).'.php';

		if ( file_exists($model_path) ) include $model_path;

		$controller_path = ROOT . '/core/controllers/'.strtolower($controller_name).'.php';

		if ( file_exists($controller_path) ) {

			include $controller_path;

			Event::trigger('route.controller.load.before', $controller_name);

			$controller = new $controller_name;

		} else {

			include ROOT . '/core/classes/controller.standart.class.php';

			Event::trigger('route.controller.load.before', 'Controller_Standart');

		    $controller = new Controller_Standart;
		}

		if ( method_exists($controller, $action) ) {

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

		$api = new Extension('Api');

		if ( method_exists($api, $method) ) {

			Doc::$result = $api->$method(self::$action);

		} else
		    Doc::$result = array('answer'=>'error','data'=>'method not exists');

		Doc::echo_xhttp();
	}

	public static function Page404($message = false)
	{
	    if ($message === false) $message = 'somthing went wrong';

		/*$logs = new Logs;

		$logs->logging(debug_backtrace());*/

        die('Died: '.$message);
    }
}
?>

<?php
class Route
{
	static public $get = array();

	static function start()
	{
		$controller_name = 'Main';

		$action_name = 'index';

		if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {

			$request = explode('?', $_SERVER['REQUEST_URI']);

			$request = $request[0];

		} else $request = $_SERVER['REQUEST_URI'];

		$routes = explode('/', $request);

		if ( !empty($routes[1]) ) $controller_name = $routes[1];

		if ( !empty($routes[2]) ) $action_name = $routes[2];

		if ( !empty($routes[3]) && $routes[3] != '' ) Route::$get['page'] = $routes[3];

		if ( $_SERVER['QUERY_STRING'] != '' ) {

			$query_string = explode('&', $_SERVER['QUERY_STRING']);

			$query_out = array();

			foreach ($query_string as $query) {

				$query_array = explode('=', $query);

				$query_out[$query_array[0]] = $query_array[1];
			}

			Route::$get['query'] = $query_out;

			$_SERVER['QUERY_STRING'] = '';
		}

		$_GET = array();

		$_POST = array();

		$model_name = 'Model_'.$controller_name;

		$controller_name = 'Controller_'.$controller_name;

		$action_name = 'action_'.$action_name;

		$model_path = ROOT . '/core/models/'.strtolower($model_name).'.php';

		if ( file_exists($model_path) ) include $model_path;

		$controller_path = ROOT . '/core/controllers/'.strtolower($controller_name).'.php';

		if ( file_exists($controller_path) ) {

			include $controller_path;

			$controller = new $controller_name;

		} else {

		    $controller = new Controller_Standart($routes[1]);
		}

		$action = $action_name;

		if ( method_exists($controller, $action) ) {

			$controller->$action();

            $controller->view->generate_index();

            echo $controller->view->result['index'];

		} else {

			Route::ErrorPage404();
		}

	}

	static function ErrorPage404()
	{
        die('something went wrong.');
    }
}
?>

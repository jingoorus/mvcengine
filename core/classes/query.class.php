<?php
final class Query
{
	static public $get = [];

	static public $post = [];

	static public $session = [];

	static public $server = [];

	static public $request = [];

	public function __construct()
	{
        Event::trigger('route.getdata.convert.before');

        if (!empty($_GET)) {

            foreach ($_GET as &$get) {

                $get = preg_replace('#[^\w]#', '', $get);
            }

            self::$get = $_GET;

            unset($_GET);
        }

        self::$request = $_REQUEST;

        unset($_REQUEST);

        Event::trigger('route.postdata.convert.before');

        if (getallheaders()['content-type'] == 'application/json'
            && !empty(file_get_contents('php://input'))) {

            self::$post = json_decode(file_get_contents('php://input'), true);

        } elseif (!empty($_POST)) {

            self::$post = $_POST;

            unset($_POST);
        }

        if (Route::$controller != 'admin') {

            self::$post = self::safequery(self::$post);
        }
	}

	public static function safequery($data)
	{
		if (is_array($data)) {

			foreach ($data as $key => $value) {

				unset($data[$key]);

				$data[strip_tags($key)] = Query::safequery($value);
			}

		} else {

            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }

		return $data;
	}

	public static function get($prop = null)
	{
		if (!empty($prop)) {

			if (!empty(self::$get[$prop])) {

				return self::$get[$prop];
			}

			return NULL;
		}

		return self::$get;
	}

	public static function post($prop = null)
	{
		if (!empty($prop)) {

			if (!empty(self::$post[$prop])) {

				return self::$post[$prop];
			}

			return NULL;
		}

		return self::$post;
	}
}

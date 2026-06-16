<?php
final class Query
{
	static public $get = [];

	static public $post = [];

	static public $session = [];

	static public $server = [];

	static public $request = [];

	public function __construct($get, $post)
	{
		self::$get = $get;

		self::$post = $post;
	}

	public static function safequery($data)
	{
		if (is_array($data)) {

			foreach ($data as $key => $value) {

				unset($data[$key]);

				$data[strip_tags($key)] = Query::safequery($value);
			}

		} else $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

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

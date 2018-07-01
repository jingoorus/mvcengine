<?php
final class Query
{
	static public $get = array();

	static public $post = array();

	static public $session = array();

	static public $server = array();

	static public $request = array();

	private function __construct() {}

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
}

?>

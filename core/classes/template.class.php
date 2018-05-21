<?php
abstract class Template
{
	protected $dir = '';

	protected $template = null;

	protected $current_template = null;

	protected $data = array ();

	public $result = array ();

	public function set($name, $var)
    {
		if( is_array( $var ) && count( $var ) ) {

			foreach ( $var as $key => $key_var ) {

				$this->set( $key, $key_var );

			}

		} else
			$this->data[$name] = $var;
	}


	public function load_template($tpl_name)
    {

		$url = @parse_url ( $tpl_name );

		$file_path = dirname ($url['path']);

		$tpl_name = pathinfo($url['path']);

		$tpl_name = $tpl_name['basename'];

		$type = explode( ".", $tpl_name );

		$type = strtolower( end( $type ) );

		if ($type != "tpl") return "";

		if ($file_path AND $file_path != ".") $tpl_name = $file_path."/".$tpl_name;

		if( stripos ( $tpl_name, ".php" ) !== false ) die( "Not Allowed Template Name: " . str_replace(ROOT, '', $this->dir)."/".$tpl_name );

		if( $tpl_name == '' || !file_exists( $this->dir . $tpl_name ) ) {

			echo "Template not found: " . str_replace(ENGINE, '', $this->dir).$tpl_name;

			return false;

		}

		$this->template = file_get_contents( $this->dir . $tpl_name );

		$this->current_template = $this->template;

		return true;
	}

	protected function _clear()
    {
		$this->data = array();

		$this->current_template = $this->template;
	}

	protected function clear()
    {
		$this->data = array();

		$this->current_template = null;

		$this->template = null;
	}

	protected function global_clear()
    {
		$this->data = array ();

		$this->result = array ();

		$this->current_template = null;

		$this->template = null;
	}

	public function compile($tpl)
    {
		foreach ( $this->data as $key_find => $key_replace ) {

			$find[] = $key_find;

			$replace[] = $key_replace;

		}

		$this->current_template = str_replace( $find, $replace, $this->current_template );

		if( isset( $this->result[$tpl] ) ) $this->result[$tpl] .= $this->current_template;

		else $this->result[$tpl] = $this->current_template;

		$this->_clear();
	}
}
?>

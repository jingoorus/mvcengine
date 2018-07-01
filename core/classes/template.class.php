<?php
abstract class Template
{
	protected $dir = ROOT . '/core/view/';

	private $template = null;

	private $current_template = null;

	protected $data = array();

	protected $result = array();

	final protected function load_template($tpl_name)
    {
		$url = @parse_url ( $tpl_name );

		$tpl_name = pathinfo( $url['path'] );

		$tpl_name = $tpl_name['basename'];

		$type = explode( '.', $tpl_name );

		$type = strtolower( end( $type ) );

		if ( $type != 'tpl' ) return 'only ".tpl" extension allowed';

		if ( $tpl_name == '' ) {

			echo 'Incorrect or blank template name: ' . $tpl_name;

			return false;

		} elseif (!file_exists( $this->dir . $tpl_name ) ) {

		    echo 'Template not found: ' . $tpl_name;

		    return false;
		}

		$this->template = file_get_contents( $this->dir . $tpl_name );

		//if (strpos($this->template, '{foreach:') !== false) $this->template = preg_replace_callback("#\\{foreach:(.*?)\\}#si", array(&$this, 'do_foreach'), $this->template );

		$this->current_template = $this->template;

		return true;
	}

	final protected function set($name, $var)
    {
		if( is_array( $var ) && count( $var ) ) {

			foreach ( $var as $key => $key_var ) {

				$this->set( $key, $key_var );

			}

		} else
			$this->data[$name] = $var;
	}

	final protected function _clear()
    {
		$this->data = array();

		$this->current_template = $this->template;
	}

	final protected function clear()
    {
		$this->data = array();

		$this->current_template = null;

		$this->template = null;
	}

	final protected function global_clear()
    {
		$this->data = array ();

		$this->result = array ();

		$this->current_template = null;

		$this->template = null;
	}

	final protected function compile($tpl)
    {
		foreach ( $this->data as $key_find => $key_replace ) {

			$find[] = $key_find;

			$replace[] = $key_replace;
		}

		$this->current_template = str_replace( $find, $replace, $this->current_template );

		if( isset( $this->result[$tpl] ) ) {

		    $this->result[$tpl] .= $this->current_template;

		} else
		    $this->result[$tpl] = $this->current_template;

		$this->_clear();
	}
}
?>

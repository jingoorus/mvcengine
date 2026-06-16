<?php

final class Template
{
    protected $dir = ROOT . '/view/';

    protected $theme = '';

    private $template = null;

    private $current_template = null;

    protected $data = array();

    protected $result = array();

    public function __construct($theme)
    {
        $this->theme = $theme;
    }

    final public function load_template($tpl_name)
    {
        $url = @parse_url($tpl_name);

        $tpl_name = pathinfo($url['path']);

        $tpl_name = $tpl_name['basename'];

        $type = explode('.', $tpl_name);

        $type = strtolower(end($type));

        if ($type != 'tpl') return 'only ".tpl" extension allowed';

        if ($tpl_name == '') {

            echo 'Incorrect or blank template name: ' . $tpl_name;

            return false;

        } elseif (!file_exists($this->dir . $this->theme . '/' . $tpl_name)) {

            echo 'Template not found: ' . $tpl_name;

            return false;
        }

        $this->template = file_get_contents($this->dir . $this->theme . '/' . $tpl_name);

        //if (strpos($this->template, '{foreach:') !== false) $this->template = preg_replace_callback("#\\{foreach:(.*?)\\}#si", array(&$this, 'do_foreach'), $this->template );

        $this->current_template = $this->template;

        return true;
    }

    final public function set($name, $var)
    {
        if (is_array($var) && count($var)) {

            foreach ($var as $key => $key_var) {

                $this->set($key, $key_var);

            }

        } else
            $this->data[$name] = $var;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {

            return $this->$property;
        }

        return null;
    }

    final public function _clear()
    {
        $this->data = array();

        $this->current_template = $this->template;
    }

    final public function clear()
    {
        $this->data = array();

        $this->current_template = null;

        $this->template = null;
    }

    final public function global_clear()
    {
        $this->data = array();

        $this->result = array();

        $this->current_template = null;

        $this->template = null;
    }

    final public function compile($tpl)
    {
        $find = [];

        $replace = [];

        foreach ($this->data as $key_find => $key_replace) {

            $find[] = $key_find;

            $replace[] = $key_replace;
        }

        if (count($find) && count($replace)) {

            $this->current_template = str_replace($find, $replace, $this->current_template);
        }

        if (isset($this->result[$tpl])) {

            $this->result[$tpl] .= $this->current_template;

        } else {

            $this->result[$tpl] = $this->current_template;
        }

        $this->_clear();

        return $this->result;
    }

    public function getTheme()
    {
        return $this->theme;
    }
}

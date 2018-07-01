<?php
class View extends Template
{
    public function __construct($theme = '')
    {
        $this->dir .= $theme != '' ? $theme . '/' : '';
	}

    public function __get($property)
    {
        if (property_exists($this, $property)) return $this->$property;

    }

    public function generate($template, $data = null, $compile_tag = 'content')
	{
		$this->load_template($template);

		if (is_array($data)) {

			foreach($data as $n => $v) $this->set('{'.$n.'}', $v);

		}

        $this->compile($compile_tag);

        $this->clear();
	}

	public function build_document()
	{
        Doc::$result = $this->result['content'];

        $this->global_clear();
	}

    public function tag($tag_name, $property = array(), $content = '', $close_tag = true)
    {
        $close_tag = $close_tag === true ? '</'.$tag_name.'>' : '' ;

        if (is_array($property) && count($property) > 0)
        {
            foreach ($property as $name => $value)
            {
                $properties .= ' '.$name.'="'.$value.'"';
            }

        } else $properties = '';

        return '<'.$tag_name.$properties.'>'.$content.$close_tag;
    }
}
?>

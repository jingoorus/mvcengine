<?php
class View
{
    private $template = null;

    private $result = [];

    public function __construct($theme = '')
    {
        $this->template = new Template($theme != '' ? $theme : '');

        Doc::$theme = $theme;
	}

    public function generate($template, $data = null, $compile_tag = 'content')
	{
        $this->template->load_template($template);

		if (is_array($data)) {

			foreach($data as $n => $v) {

                $this->template->set('{' . $n . '}', $v);
            }
		}

        $this->result = $this->template->compile($compile_tag);

        $this->template->clear();
	}

	public function build_document()
	{
        Doc::addResult($this->result['content']);

        $this->template->global_clear();
	}

    public function tag($tag_name, $property = array(), $content = '', $close_tag = true)
    {
        $close_tag = $close_tag === true ? '</'.$tag_name.'>' : '' ;

        $properties = '';

        if (is_array($property) && count($property) > 0)
        {
            foreach ($property as $name => $value)
            {
                $properties .= ' '.$name.'="'.$value.'"';
            }
        }

        return '<'.$tag_name.$properties.'>'.$content.$close_tag;
    }

    public function get($property)
    {
        return $this->result[$property] ?? null;
    }
}

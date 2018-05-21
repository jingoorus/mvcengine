<?php
class View extends Template
{
    function __construct()
    {
		$this->dir = ROOT . '/core/view/';
	}

	public function generate_index()
	{
		$this->load_template('index.tpl');

		$this->set('{content}', $this->result['content']);

		$this->set('{THEME}', '/core/view');

		$this->compile('index');
	}

    public function generate($template, $data = null)
	{
		$this->load_template($template);

		if(is_array($data)) {

			foreach($data as $n => $v) $this->set('{'.$n.'}', $v);

		}

		$this->set('{THEME}', '/core/view');

        $this->compile('content');

        $this->clear();
	}
}
?>

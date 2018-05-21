<?php
abstract class Controller {

	public $view;

	protected $model;

	function __construct()
	{
		$this->view = new View;
	}

    function action_index()
	{
		$data = $this->model->get_data();

        foreach ($data['content'] as $content_data) $this->view->generate($content_data['template'], $content_data['data']);

	}
}
?>

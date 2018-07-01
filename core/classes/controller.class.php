<?php
abstract class Controller
{
	public $view;

	protected $model;

	protected $data = array();

	public function __construct()
	{
		$this->view = new View;

		if ( file_exists(ROOT . '/core/models/model_' . strtolower(Route::$controller) . '.php') ) {

			$model = 'Model_' . mb_convert_case(Route::$controller, MB_CASE_TITLE, "UTF-8");

			$this->model = new $model;

		} else $this->model = new Model_Standart(Route::$controller);
	}

    public function action_index()
	{
		$this->data = $this->model->get_data();

		if (count($this->data['page-items'])) {

			foreach ($this->data['page-items'] as $page_name => $page_data){

				$this->view->generate('page-item.tpl', $page_data['data'], 'page-items');

			}

			$this->data['page-items'] = $this->view->result['page-items'];
		}

        $this->view->generate($this->data['template'], $this->data['data']);
	}
}
?>

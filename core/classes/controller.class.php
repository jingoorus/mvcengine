<?php
abstract class Controller
{
	public $view;

	protected $model;

	protected $data = array();

	public function __construct($theme)
	{
		$this->view = new View($theme);

		if ( file_exists(ROOT . '/core/models/model_' . strtolower(Route::$controller) . '.php') ) {

			$model = 'Model_' . mb_convert_case(Route::$controller, MB_CASE_TITLE, "UTF-8");

			$this->model = new $model(Route::$controller);

		} else {

			$this->model = new Model_Standart(Route::$controller);
		}
	}

    public function action_index($sub_tpl = 'page-item.tpl', $sort_sub = false)
	{
		$this->data = $this->model->get_data($sort_sub ?? false);

		if (empty($this->data)) {

			Route::Page404();

			$this->data = array('template' => 'error.tpl', 'data' => array('content' => 'Page not found'));
		}

		foreach (array('metatitle', 'keywords', 'description') as $meta_tag) {

            Doc::setMeta([$meta_tag => $this->data[$meta_tag]]);
        }

		if (!empty($this->data['page-items'])) {

			foreach ($this->data['page-items'] as $sub_page_name => $sub_page_data){

				$sub_page_data['data']['pageitem-link'] = '/' . Route::$controller . '/' . $sub_page_name . '.html';

				$this->view->generate($sub_tpl, $sub_page_data['data'], 'page-items');

			}

			$this->data['data']['page-items'] = $this->view->get('page-items');
		}

        $this->view->generate($this->data['template'], $this->data['data']);
	}
}

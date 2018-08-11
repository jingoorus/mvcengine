<?php
final class Admin_Control
{
    private $controllers_folder = ROOT . '/core/controllers/';

    private $models_folder = ROOT . '/core/models/';

    private $controllers = array();

    private $models = array();

    private $view;

	private $controller_type = 'Controller_Standart';

	private $model_type = 'Model_Standart';

	private $controller_list = '';

	private $model_list = '';

    private $login = false;

    function __construct()
    {
		$this->auth();
    }

	public function define_engine($page)
	{
        $this->view = new View_Admin;

		if (file_exists($this->controllers_folder . 'controller_'.$page.'.php'))
            $this->controller_type = 'Controller_'.mb_convert_case($page, MB_CASE_TITLE, "UTF-8");

        if (file_exists($this->models_folder . 'model_'.$page.'.php'))
            $this->model_type = 'Model_'.mb_convert_case($page, MB_CASE_TITLE, "UTF-8");

        if (!strpos($page, '.html')) {

            $this->scan_controllers($page);

            $this->scan_models($page);
        }

		$is_standart = $this->controller_type == 'Controller_Standart' ? ' checked=""' : '';

        $this->view->generate('page-label-controller.tpl',
            array(
                'controller_name' => 'Controller_Standart',
                'will' => 'will be used',
                'is_checked' => $is_standart
            ),
        'controller-standart');

		$is_standart = $this->model_type == 'Model_Standart' ? ' checked=""' : '';

        $this->view->generate('page-label-model.tpl',
            array(
                'model_name' => 'Model_Standart',
                'will' => 'will be used',
                'is_checked' => $is_standart
            ),
        'model-standart');

        $this->controller_list = $this->view->result['controller-standart'].
        $this->view->result['controller-list'].
        $this->view->tag('input',
            array(
                'type' => 'hidden',
                'name' => 'controller_current',
                'value' => $this->controller_type
            ), '', false
        );

        $this->model_list = $this->view->result['model-standart'].
        $this->view->result['model-list'].
        $this->view->tag('input',
            array(
                'type' => 'hidden',
                'name' => 'model_current',
                'value' => $this->model_type
            ), '', false
        );
	}

    private function scan_controllers($page, $build_engine = true)
    {
		foreach (glob($this->controllers_folder . 'controller_*.php') as $filename) {

            $first_char = explode('/', $filename);

			if ($filename == $this->controllers_folder . 'controller_admin.php' ||
            strpos('_', $first_char[count($first_char) - 1]) === 0)
                continue;

			$controller_file_name = str_replace('.php', '',
            str_replace($this->controllers_folder . 'controller_', '', $filename));

			$controller_name = 'Controller_' . mb_convert_case($controller_file_name,
            MB_CASE_TITLE, "UTF-8");

            $this->controllers[] = $controller_name;

			if ($build_engine === true) {

                $will = $controller_file_name == $page ? 'already created and will be applayed' : 'will be copied';

                $is_checked = $this->controller_type == $controller_name ? ' checked=""' : '';

                $this->view->generate(

                    'page-label-controller.tpl',

                    array(

                        'controller_name' => $controller_name,

                        'will' => $will,

                        'is_checked' => $is_checked
                    ),

                    'controller-list'
                );
            }
        }
    }

    private function scan_models($page, $build_engine = true)
    {
        foreach (glob($this->models_folder . 'model_*.php') as $filename) {

            $first_char = explode('/', $filename);

			if ($filename == $this->models_folder . 'model_admin.php' ||
            strpos('_', $first_char[count($first_char) - 1]) === 0)
                continue;

			$model_file_name = str_replace('.php', '',
            str_replace($this->models_folder . 'model_', '', $filename));

			$model_name = 'Model_' . mb_convert_case($model_file_name,
            MB_CASE_TITLE, "UTF-8");

            $this->models[] = $model_name;

			if ($build_engine === true) {

                $will = $model_file_name == $page ? 'already created and will be applayed' : 'will be copied';

                $is_checked = $this->model_type == $model_name ? ' checked=""' : '';

                $this->view->generate(

                    'page-label-model.tpl',

                    array(

                        'model_name' => $model_name,

                        'will' => $will,

                        'is_checked' => $is_checked
                    ),

                    'model-list'
                );
            }
        }

    }

    public function write_controller($page, $copy_controller)
    {
        $options = array();

        $this->scan_controllers($page, false);

        $options = array('page_name' => $page,
        'parent_name' => $copy_controller);

        if (in_array($copy_controller, $this->controllers)) {

            $options['parent'] = $this->controllers_folder .
            strtolower($copy_controller) . '.php';

        } elseif ($copy_controller == 'Controller_Standart') {

            $options['parent'] = ROOT . '/core/classes/controller.standart.class.php';

        } else return 'controller not found';

        Constructor::build_class('controller', $options);

        return Constructor::check_status();
    }

    public function write_model($page, $copy_model)
    {
        $options = array();

        $this->scan_models($page, false);

        if (in_array($copy_model, $this->models)  ||
        $copy_model == 'Model_Standart') {

            $options = array('parent' => $this->models_folder .
            strtolower($copy_model) . '.php', 'page_name' => $page,
            'parent_name' => $copy_model);

        } else return 'model not found';

        Constructor::build_class('model', $options);

        return Constructor::check_status();
    }

    public function delete_folder($path)
    {
        $errors = array();

        $path = ROOT . '/database/' . $path . '/';

        if (file_exists($path) && is_dir($path)) {

			$dir_handle = opendir($path);

			while (false !== ($file = readdir($dir_handle))) {

				if ($file != '.' && $file != '..') {

					$tmp_path = $path . '/' . $file;

					Event::trigger('admin.deletepage.deleteitem.before', $tmp_path);

					chmod($tmp_path, 0777);

					if(file_exists($tmp_path)) {

						if (!unlink($tmp_path)) $errors[] = $tmp_path;
					}
				}
			}

			closedir($dir_handle);

			Event::trigger('admin.deletepage.before', $page);

			if (file_exists($path)) {

				if (rmdir($path)) {

					return true;

				} elseif (count($errors)) {

                    $errors[] = $path;

                    return $errors . ', rmdir() failed';

                } else return 'rmdir() failed';

			} return 'file ' . $path . ' not exists';

		} elseif (!is_dir($path)) {

            return $path . ':  isn`t folder';

        } else return $path . ':  file not exists';
    }

    public function get_languages()
    {
        $langs = array();

        $lf = ROOT . '/core/language/';

        foreach(glob($lf . '*.php') as $file_path) {

            $langs[] =  str_ireplace('.class.php', '', str_ireplace($lf . 'adminlang.', '', $file_path));
        }

        return $langs;
    }

    private function auth()
    {
        $model = new Model_Admin;

        $users = $model->get_users();

        if ($_SESSION && $_SESSION['user_name'] != ''
        && $_SESSION['user_password'] != '') {

            if ($users[$_SESSION['user_name']] == $_SESSION['user_password'])
                $this->login = true;

        } elseif (Query::$post['user_name'] != ''
        && Query::$post['user_password'] != '') {

            $password = md5(Query::$post['user_password']);

            if ($users[Query::$post['user_name']] == $password) {

                $_SESSION['user_name'] = Query::$post['user_name'];

                $_SESSION['user_password'] = $password;

                $this->login = true;
            }
        }
    }

    public function get_config()
    {
        return json_decode( file_get_contents(ROOT . '/database/config.json'), true );
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) return $this->$property;
    }
}
?>

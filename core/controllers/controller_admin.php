<?php
class Controller_Admin extends Controller
{
	protected $admin = false;

	public static $page_extensions = array();

	public static $page_extensions_results = array();

	function __construct()
	{
		include ROOT . '/core/classes/admin.class.php';

		include ROOT . '/core/classes/admin.view.class.php';

		include ROOT . '/core/classes/constructor.class.php';

		$this->view = new View_Admin;

		$this->admin = new Admin_Control;

		$config = $this->admin->get_config();

		include ROOT . '/core/language/adminlang.'.$config['language'].'.class.php';

		spl_autoload_register(function ($class_name) {

		    include_once ROOT . '/core/library/' . strtolower($class_name) . '.class.php';

		});

		Doc::$metainfo = array('metatitle' => 'Admin zone / JMVC (JinGoo Flatcms)', 'keywords' => '', 'description' => '', 'info' => '');

		Doc::$metainfo['info'] = '';

		if ($this->admin->login === false && $_SERVER['REQUEST_URI'] != '/admin/') header('Location: /admin/');
	}

    public function action_index()
	{
		if ($this->admin->login === false) {

		    $this->view->generate('login.tpl');

		} elseif ($this->admin->login === true) {

		    header('Location: /admin/editpages/');
		}
	}

    public function action_login()
	{
		if ($this->admin->login === true) {

		    header('Location: /admin/editpages/');
		}
	}

    public function action_editpage()
	{
	    $page = Query::$get['page'];

		Event::trigger('admin.editpage.init', $page);

	    $this->model = new Model_Admin($page);

		$this->admin->define_engine($page);

		foreach ($this->model->database[$page]['data']['data'] as $tag_name => $tag_data)
		{
			$this->view->generate('page-pagedata.tpl', array(

				'tag' => $tag_name,

				'data' => $tag_data),

			'page_data');
		}

		if (count(self::$page_extensions)) {

			foreach (self::$page_extensions as $ext_name => $ext_data)
			{
				$extension_content .= $this->view->tag('div',

				    array(

						'class'=>'panel-title collapsed',

						'data-toggle'=>'collapse',

						'href'=>'#'.$ext_name),

					$this->view->tag('span',array('class'=>'fui-plus-circle'),'&nbsp;').Lang::get($ext_name, true)

				).$this->view->tag('div',array('id'=>$ext_name,'class'=>'panel-collapse collapse'), $ext_data);
			}
		}

		$page_data = array(

			'title' => $page,

			'metatitle' => $this->model->database[$page]['data']['metatitle'],

			'keywords' => $this->model->database[$page]['data']['keywords'],

			'description' => $this->model->database[$page]['data']['description'],

			'template' => $this->model->database[$page]['data']['template'],

			'content' => $this->view->result['page_data'],

			'engine' => $this->admin->controller_type,

			'engine_list' => $this->admin->controller_list,

			'model' => $this->admin->model_type,

			'model_list' => $this->admin->model_list,

			'extensions' => $this->view->tag('div', array('class'=>'col-xs-12'),$extension_content)
		);

		Event::trigger('admin.editpage.draw.before', $page);

		$this->view->generate('page.tpl', $page_data);
	}

    public function action_editpages()
	{
	    $this->model = new Model_Admin(true);

		if (Query::$get['info']) {

			if (Query::$get['info'] == 'ok'){

			    Doc::$metainfo['info'] = $this->view->build_alert('Success', 'success');

			} elseif (Query::$get['info'] == 'err')
			    Doc::$metainfo['info'] = $this->view->build_alert(Query::$get['error'], 'info');
		}

		foreach ($this->model->database as $page_name => $page_data)
		{
			$is_folder = (strpos($page_name,'.html') === false) ?  $this->view->tag('a', array('href'=>'/admin/addpageitem/?page='.$page_name), $this->view->tag('span', array('class'=>'fui-plus-circle'),'')) : $this->view->tag('span', array('class'=>'fui-document'),'');

			$sub_items = '';

			if (count($page_data) > 1) {

				foreach ($page_data as $data_name => $data) {

					if ($data_name == 'data') continue;

					$sub_items .= $this->view->tag(

							'li',

							array(),

							$this->view->tag(

									'span',

									array('class'=>'fui-document'),

									'&nbsp;'

							).$this->view->tag(

								    'a',

									array('href'=>'/admin/editpageitem/?page='.$page_name.'&item='.$data_name),

									$data_name
							)
					);
				}

				$sub_items = $this->view->tag('ul', array('class'=>'subItemLinks'), $sub_items);
			}

			$pages_list .= $this->view->tag(

				    'li',

					array(),

					$is_folder.' '.$this->view->tag(

						    'a',

							array('href'=>'/admin/editpage/?page='.$page_name),

							$page_name

					).$sub_items
			);
		}

		$this->view->generate('pages-list.tpl',

		    array(

			    'content' => $this->view->tag('ul', array('class'=>'board-dashed'), $pages_list),

			    'title' => 'Pages editor',

				'button' => $this->view->tag('div', array(), $this->view->tag('a', array('class'=>'btn btn-success','href'=>'/admin/addpage/'), 'Add page'))
		    )
	    );
	}

    public function action_addpage()
	{
		Event::trigger('admin.addpage.init');

		$this->admin->define_engine('standart');

		$page_data = array(

			'title' => '',

			'metatitle' => '',

			'keywords' => '',

			'description' => '',

			'template' => 'page.tpl',

			'content' => $this->view->build_taget_form(),

			'engine' => $this->admin->controller_type,

			'engine_list' => $this->admin->controller_list,

			'model' => $this->admin->model_type,

			'model_list' => $this->admin->model_list
		);

		Event::trigger('admin.addpage.draw.before');

		$this->view->generate('page-add.tpl', $page_data);
	}

    public function action_savepage()
	{
		if (!isset(Query::$get['page']) && !isset(Query::$post)) Route::page404('page name empty');

		if (isset(Query::$get['page']) &&  Query::$get['page'] != '') {

			$page = Query::$get['page'];

			$action = 'edit';

		} else {

			$page = Query::$post['page-name'];

			$action = 'add';
		}

		Event::trigger('admin.savepage.init', array('action'=>$action, 'page'=>$page));

		$post_data = Query::$post;

		$data_file = array(

			'template' => $post_data['template'],

			'data' => array(),

			'metatitle' => $post_data['metatitle'],

			'keywords' => $post_data['keywords'],

			'description' => $post_data['description']
		);

		foreach ($post_data['tags'] as $tag_name)
		{
			$data_file['data'][strip_tags($tag_name)] = $post_data['tags_data'][$tag_name];
		}

		$constructor_errors = array();

		if ($post_data['controllers_name'] != $post_data['controller_current'])
		{
			$constructor_errors[] = $this->admin->write_controller($page, $post_data['controllers_name']);
		}

		if ($post_data['models_name'] != $post_data['model_current'])
		{
			$constructor_errors[] = $this->admin->write_model($page, $post_data['models_name']);
		}

		Event::trigger('admin.savepage.before', array('page' => $page, 'data.json' => $data_file, 'action'=>$action));

		$result = $this->admin->save_page($page, 'data.json', json_encode($data_file, JSON_UNESCAPED_UNICODE));

		Event::trigger('admin.savepage.after', array('page' => $page, 'data.json' => $data_file, 'result' => $result, 'action'=>$action));

		if ($result === true && !count($constructor_errors)) {

		    header('Location: /admin/editpages/?info=ok');

		} elseif ($result === true && count($constructor_errors)) {

			$constructor_errors = implode(', ' . $constructor_errors);

			header('Location: /admin/editpages/?info=err&error=' . urlencode($constructor_errors));

		} else {

			$link = $this->view->tag('a', array('href' => $_SERVER['HTTP_REFERER']),
			'Back to '.$_SERVER['HTTP_REFERER']);

		    $this->view->generate('error.tpl', array( 'content' => $this->view->build_alert(
			'Save action haven`t done'.$link, 'danger')));
		}
	}

    public function action_deletepage()
	{
		$page = Query::$get['page'];

		Event::trigger('admin.deletepage.init', $page);

		if ($page == 'main') return 'main page need for your site, bro';

		$result = $this->admin->delete_folder($page);

		if ($result === true) {

			header('Location: /admin/editpages/?info=ok');

		} else {

			if (is_array($result) && count($result)) $result = implode(', ', $result);

			$link = $this->view->tag('a', array('href' => $_SERVER['HTTP_REFERER']),
			'Back to '.$_SERVER['HTTP_REFERER']);

			$this->view->generate('error.tpl', array( 'content' => $this->view->build_alert(
			'Delete action haven`t done. Error for files: '.$result.'. '.$link, 'danger')));
		}
	}

	public function action_editpageitem()
	{
		$page = Query::$get['page'];

		Event::trigger('admin.editpageitem.init', $page);

		$item = Query::$get['item'];

		$this->model = new Model_Admin($page);

		if (isset($this->model->database[$page][$item])) {

			foreach ($this->model->database[$page][$item]['data'] as $tag_name => $tag_data)
			{
				$this->view->generate('page-pagedata.tpl', array(

					'tag' => $tag_name,

					'data' => $tag_data),

				'page_data');
			}

			if (count(self::$page_extensions)) {

				foreach (self::$page_extensions as $ext_name => $ext_data)
				{
					$extension_content .= $this->view->tag('div',

					    array(

							'class'=>'panel-title collapsed',

							'data-toggle'=>'collapse',

							'href'=>'#'.$ext_name),

						$this->view->tag('span',array('class'=>'fui-plus-circle'),'&nbsp;').Lang::get($ext_name, true)

					).$this->view->tag('div',array('id'=>$ext_name,'class'=>'panel-collapse collapse'), $ext_data);
				}
			}

			$page_data = array(

				'title' => $page,

				'item' => $item,

				'metatitle' => $this->model->database[$page][$item]['metatitle'],

				'keywords' => $this->model->database[$page][$item]['keywords'],

				'description' => $this->model->database[$page][$item]['description'],

				'template' => $this->model->database[$page][$item]['template'],

				'content' => $this->view->result['page_data'],

				'extensions' => $this->view->tag('div', array('class'=>'col-xs-12'),$extension_content)
			);

			Event::trigger('admin.editpageitem.draw.before', $page);

			$this->view->generate('page-pageitem.tpl', $page_data);
		}
	}

	public function action_savepageitem()
	{
		if (!isset(Query::$get['page']) && !isset(Query::$post)) Route::page404('page name empty');

		if (isset(Query::$get['page']) &&  Query::$get['page'] != '') {

			$page = Query::$get['page'];

			$item = Query::$get['item'];

			$action = 'edit';

		} else {

			$page = Query::$post['page-name'];

			$item = Query::$post['item'];

			if (strpos($item, '.html') !== false) $item = str_ireplace('.html', '', $item);

			$action = 'add';
		}

		Event::trigger('admin.savepageitem.init', array('action'=>$action, 'page'=>$page));

		$post_data = Query::$post;

		$data_file = array(

			'template' => $post_data['template'],

			'data' => array(),

			'metatitle' => $post_data['metatitle'],

			'keywords' => $post_data['keywords'],

			'description' => $post_data['description']
		);

		foreach ($post_data['tags'] as $tag_name)
		{
			$data_file['data'][strip_tags($tag_name)] = $post_data['tags_data'][$tag_name];
		}

		Event::trigger('admin.savepageitem.before', array('page' => $page, $item . '.json' => $data_file, 'action'=>$action));

		$result = $this->admin->save_page($page, $item . '.json', json_encode($data_file, JSON_UNESCAPED_UNICODE));

		Event::trigger('admin.savepageitem.after', array('page' => $page, $item . '.json' => $data_file, 'result' => $result, 'action'=>$action));

		if ($result === true) {

		    header('Location: /admin/editpages/?info=ok');

		} else {

			$link = $this->view->tag('a', array('href' => $_SERVER['HTTP_REFERER']),
			'Back to '.$_SERVER['HTTP_REFERER']);

		    $this->view->generate('error.tpl', array( 'content' => $this->view->build_alert(
			'Save action haven`t done'.$link, 'danger')));
		}
	}

	public function action_addpageitem()
	{
		Event::trigger('admin.addpageitem.init');

		$page_data = array(

			'title' => Query::$get['page'],

			'metatitle' => '',

			'keywords' => '',

			'description' => '',

			'template' => 'page.tpl',

			'content' => $this->view->build_taget_form()
		);

		Event::trigger('admin.addpageitem.draw.before');

		$this->view->generate('page-pageitem-add.tpl', $page_data);
	}

	public function action_deletepageitem()
	{
		$page = Query::$get['page'];

		$item = Query::$get['item'];

		Event::trigger('admin.deletepageitem.init', $page);

		$file = ROOT . '/database/' . $page . '/' . $item . '.json';

		if (unlink( $file )) {

			header('Location: /admin/editpages/?info=ok');

		} else {

			$this->view->generate('error.tpl', array( 'content' => $this->view->build_alert(
			'Delete action haven`t done. ' . $this->view->tag('a', array('href' => $_SERVER['HTTP_REFERER']),
			'Back to '.$_SERVER['HTTP_REFERER']), 'danger')));
		}
	}

    public function action_editsettings()
	{
		$config = $this->admin->get_config();

		$langs = $this->admin->get_languages();

		foreach ($config as $tag_name => $tag_data)
		{
			$this->view->generate('page-pagesettings.tpl', array(

				'tag' => $tag_name,

				'data' => $tag_data),

			'page_data');
		}

		$this->view->generate('pages-list.tpl',

		    array(

			    'content' => $this->view->tag(

					'form',

					array('action'=>'/admin/savesettings/', 'method'=>'post'),

					$this->view->result['page_data'] .
					$this->view->tag('div', array('class'=>'row'),

					    $this->view->tag(

						    'div',

						    array('class'=>'col-xs-3'),

						    $this->view->tag('button',

						        array(

							        'type'=>'submit',

							        'class'=>'btn btn-block btn-success'

						        ), 'Save'
					        )
				        )
					)
				),

			    'title' => 'Settings editor',

				'button' => ''
		    )
	    );
	}

	public function action_savesettings()
	{
		$data = json_encode(Query::$post['config'], JSON_UNESCAPED_UNICODE);

		if (file_put_contents( ROOT . '/database/config.json', $data)) {

			header('Location: /admin/editpages/?info=ok');

		} else {

			$this->view->generate('error.tpl', array( 'content' => $this->view->build_alert(
			'Save config isn`t done ' . $this->view->tag('a', array('href' => $_SERVER['HTTP_REFERER']),
			'Back to '.$_SERVER['HTTP_REFERER']), 'danger')));
		}
	}

	public function action_editextensions()
	{
		//Event::trigger('admin.editextensions.init');

		if (count(self::$page_extensions)) {

			/*foreach (self::$page_extensions as $ext_name => $ext_data)
			{
				$extension_content .= $this->view->tag('div',
				    array(

						'class'=>'panel-title collapsed',

						'data-toggle'=>'collapse',

						'href'=>'#'.$ext_name),

					$this->view->tag('span',array('class'=>'fui-plus-circle'),'&nbsp;').Lang::get($ext_name, true)

				).$this->view->tag('div',array('id'=>$ext_name,'class'=>'panel-collapse collapse'), $ext_data);
			}*/
		}
	}

    public function action_editusers()
	{
		$users = $this->admin->get_users();

		foreach ($users as $tag_name => $tag_data)
		{
			$users_list .= $this->view->tag(

				'li',

				array(),

				$this->view->tag(

					'span',

					array('class'=>'fui-user'),

					'&nbsp;'

				) .
				$this->view->tag(

					'a',

					array('href'=>'/admin/edituser/?user='.$tag_name),

					$tag_name
				)
			);
		}

		$users_list = $this->view->tag('ul', array('class'=>'board-dashed'), $users_list);

		$this->view->generate('pages-list.tpl',

		    array(

			    'content' => $users_list .

				$this->view->tag('div', array('class'=>'row'),

					$this->view->tag(

						'div',

						array('class'=>'col-xs-3'),

						$this->view->tag('a',

							array(

								'class'=>'btn btn-success',

								'href'=>'/admin/adduser/?step=user_name',

							), 'Add user'
						)
					)
				),
			    'title' => 'Users editor',

				'button' => ''
		    )
	    );
	}

	public function action_edituser()
	{
		$user = Query::$get['user'];

		$user_password = $this->view->tag(

			'div',

			array('id'=>'userEditor'),

			$this->view->tag(

				'input',

				array(

					'type'=>'password',

					'name'=>$user,

					'class'=>'form-control',

					'placeholder'=>'new password',

					'required'=>''

				), '', false
			)
		);

		$this->view->generate('pages-list.tpl',

		    array(

			    'content' => $this->view->tag(

					'form',

					array(

						'method'=>'post',

						'action'=>'/admin/saveuser/'

					),
						$user_password . $this->view->tag(

							'button',

							array(

								'class'=>'btn btn-success',

								'type'=>'submit',

							), 'Save'
				        )
				),

			    'title' => 'Edit ' . $user,

				'button' => ''
		    )
	    );
	}

    public function action_adduser()
	{
		if (Query::$get['step'] == 'user_name') {

			$this->view->generate('pages-list.tpl',

			    array(

				    'content' => $this->view->tag(

						'form',

						array(

							'method'=>'get',

							'action'=>'/admin/edituser/'

						),
						$this->view->tag(

							'input',

							array(

								'type'=>'text',

								'name'=>'user',

								'class'=>'form-control',

								'placeholder'=>'username'

							), '', false

						) . $this->view->tag(

								'button',

								array(

									'class'=>'btn btn-success mt-5',

									'type'=>'submit',

								), 'Next'
					        )
					),

				    'title' => 'Add user name ',

					'button' => ''
			    )
		    );
		} 
	}

    public function action_saveuser()
	{
		$users = $this->admin->get_users();

		foreach (Query::$post as $key => $value) {

			$user = strip_tags($key);

			$password = md5($value);

			$users[$user] = $password;

			break;
		}

		$users = json_encode($users, JSON_UNESCAPED_UNICODE);

		if (file_put_contents( ROOT . '/database/users.json', $users)) {

			if ($_SESSION['user_name'] == $user) $_SESSION['user_password'] = $password;

			header('Location: /admin/editpages/?info=ok');

		} else {

			$this->view->generate('error.tpl', array( 'content' => $this->view->build_alert(
			'Save users isn`t done ' . $this->view->tag('a', array('href' => $_SERVER['HTTP_REFERER']),
			'Back to '.$_SERVER['HTTP_REFERER']), 'danger')));
		}
	}
}
?>

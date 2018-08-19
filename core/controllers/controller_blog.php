<?php
class Controller_Blog extends Controller
{
    public function action_index()
    {
        /**
         *Call parent index with needed parameters
         **/
        parent::action_index('page-item.tpl', function ($a, $b) {

            $da = strtotime($a['data']['date']);

            $db = strtotime($b['data']['date']);

            if ($da == $db) return 0;

            return ($da > $db) ? -1 : 1;
        });
    }

    /**
     *Hook for availability checking page name like controller method
     **/
    public function __call($page_name, $vars)
    {
        $this->data = $this->model->get_data();

        $page_name = str_replace('.html', '', str_replace('action_', '', $page_name));

        if (isset($this->data['page-items']) && isset($this->data['page-items'][$page_name])) {

            return $this->action_show_pageitem($page_name);

        } else Route::Page404();
    }

    public function action_show_pageitem($page_name)
	{
        if (isset($this->data['page-items']) && isset($this->data['page-items'][$page_name])) {

            foreach (array('metatitle', 'keywords', 'description') as $meta_tag) Doc::$metainfo[$meta_tag] = $this->data[$meta_tag];

            $this->view->generate($this->data['page-items'][$page_name]['template'], $this->data['page-items'][$page_name]['data']);
        }
	}
}
?>

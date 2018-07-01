<?php
class Controller_Blog extends Controller
{
    public function action_show_blog_item($page_name)
	{
        //
	}

    /**
     *Hook for availability checking page name like controllers method
     **/
    public function __get($page_name)
    {
        if (isset($this->data['page-items']) && isset($this->data['page-items'][$page_name])) {

            $this->action_show_blog_item($page_name);

            return true;
        }
    }
}
?>

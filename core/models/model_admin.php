<?php
class Model_Admin
{
    protected $path;

    public $database = array();

    function __construct($dir = false)
    {
        $this->path = ROOT . '/database/';

        if ($dir === true) $this->scan_base();
        elseif ( is_string($dir) ) $this->scan_dir( ROOT . '/database/'. $dir);
    }

    protected function scan_base()
    {
        if (is_dir($this->path)) {

            $data = scandir($this->path);

            foreach ($data as $name)
            {
                if (is_dir($this->path.$name) && !in_array($name, array('.', '..'))) $this->scan_dir($this->path.$name);
            }
        }
    }

    protected function scan_dir($dir_name)
    {
        $pages = scandir($dir_name);

        $name = str_ireplace($this->path, '', $dir_name);

        foreach ($pages as $file_name)
        {
            $data_name = str_replace('.json', '', $file_name);

            if( strpos($file_name,'.json') !== false) {

                if (!isset($this->database[$name])) $this->database[$name] = array();

                $this->database[$name][$data_name] = json_decode(file_get_contents($dir_name.'/'.$file_name), true);
            }
        }
    }

    public function save_page($page_name, $data, $file_name)
    {
        if (!is_dir(ROOT. '/database/' . $page_name)) {

            if (!mkdir(ROOT. '/database/' . $page_name)) return false;

        }

        if (file_put_contents( ROOT. '/database/' . $page_name . '/' .
        $file_name, $data)){

            return true;

        } else return false;
    }

    public function save_users($users)
    {
        $users = json_encode($users, JSON_UNESCAPED_UNICODE);

        if(file_put_contents( ROOT . '/database/users.json', $users)) return true;
        else return false;
    }

    public function get_users()
    {
        return json_decode( file_get_contents(ROOT . '/database/users.json'), true );
    }
}
?>

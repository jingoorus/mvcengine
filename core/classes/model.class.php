<?php
abstract class Model
{
    protected $db;

    protected $folder;

    public function get_data()
    {
        $data = array();

        $keys = ROOT . '/database/'.$this->folder.'/keys.json';

        $data_file = ROOT . '/database/'.$this->folder.'/data.json';

        if (file_exists($keys)) $data = json_decode(file_get_contents($keys), true);

        if (file_exists($data_file)) {

            $data['content'] = json_decode(file_get_contents($data_file), true);

        } else {

            $data['content'] = array( array( 'template' => 'error.tpl', 'data' => array( 'content' => 'Сайт переезжает' ) ) );
        }

        $this->db = $data;

        return $this->db;
    }
}
?>

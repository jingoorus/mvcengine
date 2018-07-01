<?php
abstract class Model
{
    protected $folder;

    public final function get_data()
    {
        $data_file = ROOT . '/database/'.$this->folder.'/data.json';

        if (file_exists($data_file)) {

            $data = json_decode(file_get_contents($data_file), true);

            foreach (array('metatitle' ,'keywords' , 'description') as $meta_tag) Doc::$metainfo[$meta_tag] = $data[$meta_tag];

            $sub_data = $this->get_page_items();

            if (count($sub_data)) $data['page-items'] = $sub_data;

        } else $data = array( 'template' => 'error.tpl', 'data' => array( 'content' => 'Страница не найдена' ) );

        return $data;
    }

    private final function get_page_items($sort = false)
    {
        $data = array();

        foreach (glob(ROOT . '/database/'.$this->folder.'/*.json') as $file_path) {

            if ($file_path == ROOT . '/database/'.$this->folder.'/data.json') continue;

            $data[str_ireplace('.json', '', str_ireplace(ROOT . '/database/'.$this->folder.'/', '', $file_path))] = json_decode(file_get_contents($file_path), true);
        }

        if ($sort !== false) uksort($data, $sort);

        return $data;
    }
}
?>

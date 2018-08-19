<?php
abstract class Model
{
    protected $folder;

    public final function get_data($sort_sub = false)
    {
        $data = array();

        $data_file = ROOT . '/database/'.$this->folder.'/data.json';

        if (file_exists($data_file)) {

            $data = json_decode(file_get_contents($data_file), true);

            $sub_data = $this->get_page_items($sort_sub);

            if (count($sub_data)) $data['page-items'] = $sub_data;

        }

        return $data;
    }

    private final function get_page_items($sort)
    {
        $data = array();

        foreach (glob(ROOT . '/database/'.$this->folder.'/*.json') as $file_path) {

            if ($file_path == ROOT . '/database/'.$this->folder.'/data.json') continue;

            $data[str_ireplace('.json', '', str_ireplace(ROOT . '/database/'.$this->folder.'/', '', $file_path))] = json_decode(file_get_contents($file_path), true);
        }

        if ($sort !== false) uasort($data, $sort);

        return $data;
    }
}
?>

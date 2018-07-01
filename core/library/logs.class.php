<?php
class Logs
{
    protected $folder = ROOT . '/logs/';

    protected $pre = 'Logs-main-';

    public function __construct($folder = null)
    {
        if ($folder) $this->set_folder($folder);
    }

    /**
     *Private cos only admin can call with arg, front end must use only default folder value trough "$logs = new Extension('Logs');"
     **/
    private function set_folder($folder = null)
    {
        if ($folder) $this->folder = ROOT . '/' . $folder;
    }

    public function set_prefix($prefix = null)
    {
        if ($prefix && strpos('-', $prefix) !== false) $this->pre = $prefix;
    }

    public function logging($data)
    {
        if(!is_string($data))
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        return file_put_contents($this->folder . $this->pre . time() . '.log.json', $data);
    }
}
?>

<?php

namespace library;
class Logs
{
    protected $folder = ROOT . '/logs/';

    protected $pre = 'main-';

    public function __construct($folder = null)
    {
        if ($folder) {

            $this->set_folder($folder);

            $this->pre = $folder;
        }
    }

    private function set_folder($folder = null)
    {
        if ($folder) $this->folder = ROOT . '/logs/' . $folder;
    }

    public function set_prefix($prefix = null)
    {
        if ($prefix && strpos('-', $prefix) !== false) {

            $this->pre = $prefix;
        }
    }

    public function logging($data)
    {
        if (!is_string($data)) {

            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        return file_put_contents($this->folder . $this->pre . '.log', '[' . time() . '] ' . $data);
    }
}

<?php
class Controller_Main extends Controller
{
    function __construct()
    {
        parent::__construct();

        $this->model = new Model_Main;
    }
}
?>

<?php
class Controller_Standart extends Controller
{
	function __construct($folder)
	{
		parent::__construct();

        $this->model = new Model_Standart($folder);
	}
}
?>

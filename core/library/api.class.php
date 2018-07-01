<?php
class Api
{
    protected $status = false;

    public function __get($property)
    {
        if (property_exists($this, $property)) return $this->$property;
    }

    public function admin($action)
    {
        include ROOT . '/core/classes/admin.class.php';

        $admin = new Admin_Control;

        if ($admin->login === true) {

            switch ($action) {
                case 'get_sub_page':
                    return "i равно 0";
                    break;

                case 'save_sub_page':
                    return "i равно 1";
                    break;

                default:
                    return "Action not exists";
                    break;
            }

        } else {

            die('Unauthorized');

        }


    }
}
?>

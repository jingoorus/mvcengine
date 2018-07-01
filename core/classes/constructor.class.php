<?php
final class Constructor
{
    private static $options = array();

    private static $status = false;

    public static function build_class($type, $options)
    {
        Event::trigger('constructor.' . $type . '.init', $options);

        self::$status = false;

        if (!count($options)) {

            self::$status = 'options required';

            return;
        }

        $options['class_file'] = ROOT . '/core/' . $type . 's/' . $type . '_' .
        $options['page_name'] . '.php';

        $options['page_name'] = mb_convert_case($options['page_name'], MB_CASE_TITLE, "UTF-8");

        if ($options['parent_name'] == mb_convert_case($type, MB_CASE_TITLE, "UTF-8") . '_Standart') {

            Event::trigger('constructor.' . $type . '.delete.before', $options);

            if (self::del_file($options['class_file'])) {

                self::$status = true;

            } else self::$status = 'old ' . $type . ' not deleted';

        } else {

            if (file_exists($options['parent'])) {

                $options_parent_name = explode('_', $options['parent_name']);

                foreach ($options_parent_name as $c => $name)
                    $options_parent_name[$c] = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");

                $options['parent_name'] = implode('_', $options_parent_name);

                Event::trigger('constructor.' . $type . '.clone.before', array(

                        'options' => $options,

                        'type' => $type
                    )
                );

                self::cloning($type, $options);

                Event::trigger('constructor.' . $type . '.clone.after', array(

                        'status' => self::$status,

                        'options' => $options,

                        'type' => $type
                    )
                );

            } else self::$status = 'old ' . $type . ' not exists';
        }
    }

    private static function cloning($type, $options)
    {
        $origin = file_get_contents($options['parent']);

        if ($origin === false){

            self::$status = 'source ' . $type . ' class have not found';

            return;
        }

        $new = preg_replace('#(.+)('.$options['parent_name'].')(.+)#si',
        '\\1'. mb_convert_case($type, MB_CASE_TITLE, "UTF-8") . '_' .
        $options['page_name'].'\\3', $origin);

        if (file_put_contents($options['class_file'], $new)) {

            self::$status = true;

        } else self::$status = 'cannot create or rewrite ' . $type . ' file';
    }

    private static function del_file($file)
    {
        if (file_exists( $file )) {

            if(unlink( $file ))
                return true;
            else
                return false;

        } else return true;
    }

    public static function check_status()
    {
        return self::$status;
    }
}
?>

<?php
final class Doc
{
    public static $header = array();

    public static $errors = array();

	public static $metainfo = array('metatitle' => '', 'keywords' => '', 'description' => '');

    public static $result = array();

    public static $scripts = array();

    public static $styles = array();

    public static $theme = '';

    public static function echo_document()
    {
        $view = new View(self::$theme);

        self::$scripts = count(self::$scripts) > 0 ? '<script src="'.implode('"></script><script src="', self::$scripts).'"></script>' : '';

        self::$styles = count(self::$styles) > 0 ? '<style src="'.implode('"></style><style src="', self::$styles).'"></style>' : '';

        $total_data = array(

            'content' =>  self::$result,

            'THEME' => '/core/view'. self::$theme,

            'scripts' => self::$scripts,

            'styles' => self::$styles
        );

        $data = array_merge(self::$metainfo, $total_data);

        $view->generate('index.tpl', $data, 'document');

        self::$result = $view->result['document'];

        self::compile_headers();

        unset($view);

        echo self::$result;
    }

    public static function echo_xhttp()
    {
        self::compile_headers();

        echo json_encode(self::$result, JSON_UNESCAPED_UNICODE);
    }

    private static function compile_headers()
    {
        $regular_headers = array();

        if (count(self::$header) > 0) self::$header = array_merge(self::$header, $regular_header);

        //spawn headers here

    }
}
?>

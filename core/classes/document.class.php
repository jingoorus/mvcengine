<?php
final class Doc
{
    private static $headers = [];

	private static $metainfo = [
        'metatitle' => '',
        'keywords' => '',
        'description' => ''
    ];

    public static $theme = 'default';

    private static $result = '';

    private static $scripts = [];

    private static $styles = [];

    public static function echo_document()
    {
        $view = new View(self::$theme);

        self::$scripts = count(self::$scripts) > 0 ? '<script src="'.implode('"></script><script src="', self::$scripts).'"></script>' : '';

        self::$styles = count(self::$styles) > 0 ? '<style src="'.implode('"></style><style src="', self::$styles).'"></style>' : '';

        $total_data = array(

            'content' =>  self::$result,

            'THEME' => '/view/'. self::$theme,

            'scripts' => self::$scripts,

            'styles' => self::$styles
        );

        $data = array_merge(self::$metainfo, $total_data);

        $view->generate('index.tpl', $data, 'document');

        self::$result = $view->get('document');

        self::compile_headers();

        Event::trigger('document.result.output.before');

        echo self::$result;
    }

    public static function add_styles($path)
    {
        self::$styles[] = $path;
    }

    public static function add_scripts($path)
    {
        self::$scripts[] = $path;
    }

    public static function setOutput($result)
    {
        self::$result = $result;
    }

    public static function echo_xhttp()
    {
        self::$headers[] = 'Content-Type: application/json';

        self::$headers[] = 'charset=utf-8';

        self::compile_headers();

        echo json_encode(self::$result, JSON_UNESCAPED_UNICODE);
    }

    private static function compile_headers()
    {
        foreach(self::$headers as $header) {

            header($header);
        }
    }

    public static function setHeaders($header)
    {
        if (is_array($header)) {

            self::$headers = array_merge(self::$headers, $header);

        } elseif (is_string($header)) {

            self::$headers[] = $header;
        }
    }

    public static function compile_users_tag($data)
    {
        if (is_string(self::$result)) {

            foreach ($data as $tag => $value) {

                self::$result = str_ireplace('{' . strip_tags($tag) . '}', $value, self::$result);
            }
        }
    }

    public static function setMeta(array $metainfo)
    {
        self::$metainfo = array_merge(self::$metainfo, $metainfo);
    }
}

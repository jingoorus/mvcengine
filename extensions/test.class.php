<?php
class Test
{
    public static function hello($words)
    {
        if (!$words) $words = 'Hello world!';

        return $words;
    }
}
?>

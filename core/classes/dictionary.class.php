<?php
abstract class Dictionary
{
    public static function get($word, $mod = false)
    {
        if (isset(Lang::$dict[$word])) {

            $tr_word = Lang::$dict[$word];

            if ($mod === true) {//ToDo: rewrite with str_pos()

                $tr_word = explode(' ', $tr_word);

                $tr_word[0] = mb_convert_case($tr_word[0], MB_CASE_TITLE, "UTF-8");

                $tr_word = implode(' ', $tr_word);
            }

            return $tr_word;

        } else return $word;
    }
}
?>

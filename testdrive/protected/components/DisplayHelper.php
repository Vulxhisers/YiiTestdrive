<?php

class DisplayHelper
{
    public static function varDumpPre($var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }

    public static function printRPre($var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }

}
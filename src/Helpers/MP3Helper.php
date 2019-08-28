<?php

namespace Dena\PersianNTS\Helpers;

use Dena\PersianNTS\Classes\PHPMP3;

class MP3Helper
{
    public static function empty():  PHPMP3
    {
        return (new PHPMP3(__DIR__.'/../../resources/sounds/empty/empty.mp3'))->striptags();
    }
    
    public static function mergeBehind(PHPMP3 $primary, string $merge):  PHPMP3
    {
        return $primary->mergeBehind(new PHPMP3($merge))->striptags();
    }
}
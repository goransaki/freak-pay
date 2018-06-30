<?php
/**
 * Created by PhpStorm.
 ** Boris Matic <>
 * Date: 15.03.2017.
 * Time: 00:13
 */

namespace common\helpers;


class ErrorHelper
{
    const DEFAULT_ERROR_MESSAGE = 'Oops :( Something went wrong.';
    const DEFAULT_ERROR_DESCRIPTION = ' The above error occurred while the Web server was processing your request. <br>Please contact us if you think this is a server error. Thank you. ';

    static $messages = [
        400 => 'Bad Request.',
        401 => 'Unauthorised.',
        403 => 'Forbidden.',
        404 => 'Whoops! Page not found.',
        410 => 'Link expired.',
        500 => 'Internal Server Error.',
        503 => 'This site is getting a tune-up.',
        520 => 'Wrong move.',
        530 => 'You are annoying.',
    ];

    static $descriptions = [
        400 => 'Your browser sent an invalid request.',
        401 => 'You don\'t have permissions to access this part of site.',
        403 => 'You don\'t have permission to access this server.',
        404 => 'This page cannot be found or is missing. <br>Use the navigation above or the button below to get back on track.',
        410 => 'Sorry, this link has expired! <br>Use the navigation above or the button below to get back on track.',
        500 => 'Why not try refreshing your page? Or you can contact support.',
        503 => 'Why don\'t you come back in a little while and see if our expert are finished tinkering.',
        520 => 'You\'re on a wrong path, dude.',
        530 => 'Stop doing this. It will not end up well.',
    ];

    public static function getCodeHtmlFor($code)
    {
        $code = (string) $code;
        return
            (isset($code[0]) ? ($code[0] == '0' ? '<i class="ti-face-sad text-primary"></i>' : "<span class=\"text-primary\">$code[0]</span>") : '') .
            (isset($code[1]) ? ($code[1] == '0' ? '<i class="ti-face-sad text-pink"></i>' : "<span class=\"text-pink\">$code[1]</span>") : '') .
            (isset($code[2]) ? ($code[2] == '0' ? '<i class="ti-face-sad text-info"></i>' : "<span class=\"text-info\">$code[2]</span>") : '');
    }

    public static function getMessageFor($code)
    {
        return isset(self::$messages[$code]) ? self::$messages[$code] : static::DEFAULT_ERROR_MESSAGE;
    }

    public static function getDescriptionFor($code)
    {
        return isset(self::$descriptions[$code]) ? self::$descriptions[$code] : static::DEFAULT_ERROR_DESCRIPTION;
    }

    public static function getAsString(array $errors, $glue = ', ')
    {
        return static::extractErrorString($errors, $glue);
    }

    public static function extractErrorString(array $errors, $glue = ', ')
    {
        return implode($glue, array_values($errors));
    }
}
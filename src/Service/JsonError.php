<?php
namespace De\Idrinth\ConfigCheck\Service;

class JsonError
{
    private static $messages = array(
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
    );

    /**
     * @return string
     */
    public static function getLastError() {
        if(function_exists('json_last_error_msg')) {
            return json_last_error_msg();
        }
        $id = json_last_error();
        return isset(self::$messages[$id]) ? self::$messages[$id] : 'Unknown error occured';
    }
}
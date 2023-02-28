<?php
namespace App\Utility;

class StringUtility
{
    static function formatValidatorMessage($message){
        return rtrim(str_replace('{"','',
            str_replace('":["',': ',
                str_replace('"],"',' ',
                    str_replace('"]}',' ',
                        str_replace('.',', ',$message)
                    )
                )
            )
        ),', ');
    }
}

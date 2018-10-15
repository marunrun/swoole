<?php

namespace app\common\lib;

class Util
{
    public static function show($status , $message = '' , $data = [] )
    {
        $result = [
            'error_code'    =>$status,
            'error_message' => $message,
            'data'          => $data
        ];


        return json_encode($result);
    }
}
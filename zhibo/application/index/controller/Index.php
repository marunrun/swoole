<?php
namespace app\index\controller;

use think\Request;
use app\common\lib\ali\Sms;


class Index
{
    public function index(Request $request)
    {
        return '';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function marun()
    {
        return time();
    }

    public function sms()
    {
            $res =  Sms::sendSms(18772397316,12345);
            return json_encode(['error_code' => $res->Code,'error_message' => $res->Message]);
    }
}

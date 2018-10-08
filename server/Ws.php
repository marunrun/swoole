<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-09-03
 * Time: 14:46
 */

class Ws
{
    private $host = '0.0.0.0';
    private $port = 8811;
    public $ws;

    public function __construct()
    {
        $this->ws = new swoole_websocket_server($this->host,$this->port);
        $this->ws->set([
            'worker_num'                => 2,
            'task_worker_num'           => 2,
            'enable_static_handler'     => true, //开启静态页面
            'document_root'             => "/home/marun/code/demo/www"
        ]);
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('task',[$this,'onTask']);
        $this->ws->on('finish',[$this,'onFinish']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('close',[$this,'onClose']);
        $this->ws->start();
    }

    /**
     * 监听webSocket连接打开事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws,$request)
    {
        var_dump($request->fd);
    }

    /**
     * 监听webSocket接收消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws , $frame)
    {
        var_dump($frame->data);
        $data =[
            'task'  =>1,
            'fd'    =>$frame->fd
        ];
        $ws->task($data);
        $ws->push($frame->fd,"server-push: ".date('Y-m-d H:i:s'));
    }

    /**
     * 当服务端调用task方法时,走到这里
     * @param $server
     * @param $task_id
     * @param $from_id
     * @param $data   $data是调用task方法时传递过来的数据
     * @return string
     */
    public function onTask($server,$task_id,$from_id,$data)
    {
        print_r($data);
        sleep(10);
        return 'task finished'; //告诉Finish方法
    }

    /**
     * Task任务完成后自动调用
     * @param $server
     * @param $task_id
     * @param $data  这里的$data是Task返回的数据
     */
    public function onFinish($server,$task_id,$data)
    {
        echo "task_id: {$task_id}\n";
        echo "finish-data-success: {$data}\n";
    }
    
    /**
     * 监听webSocket关闭连接事件
     * @param $ws
     * @param $fd
     */
    public function onClose($ws,$fd)
    {
        echo "Client ".$fd." is closed \n";
    }
}

$obj = new Ws();
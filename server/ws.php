<?php

/**
 * WebSocket基础类
 * Class ws
 */
class ws
{
    const HOST = '0.0.0.0';
    const PORT = '8812';

    private static $instance = null ;

    private  $server  = null ;

    private function __construct()
    {
        $this->server = new swoole_websocket_server(self::HOST,self::PORT);
        $this->set($this->server);
        $this->server->on('open',[$this,'onOpen']);
        $this->server->on('message',[$this,'onMessage']);
        $this->server->on('close',[$this,'onClose']);
        $this->server->on('task',[$this,'onTask']);
        $this->server->on('finish',[$this,'onFinish']);
        $this->server->start();
    }

    public static function getInstance()
    {
        if(self::$instance == null){
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function set($server)
    {
        $server->set([
            'worker_num'        => 2,
            'task_worker_num'   => 4
        ]);
    }

    /**
     * 监听接收数据事件
     * @param $server
     * @param $frame
     */
    public function onMessage($server, $frame)
    {
        echo $frame->data.PHP_EOL;

        $data = [
            'task'  =>1,
            'fd'    =>$frame->fd
        ];

        $server->task($data);

        $server->push($frame->fd,date("Y-m-d H:i:s",time()) . " fd : {$frame->fd}");
    }

    /**
     * 监听客户端连接事件
     * @param $server
     * @param $request     */
    public function onOpen($server , $request)
    {
    }

    /**
     * 监听客户端连接关闭事件
     * @param $server
     * @param $fd
     * @param $reactorId
     */
    public function onClose($server , $fd , $reactorId)
    {
        foreach ($server->connections as $v){
            if($fd != $v) {
                $server->push($v, "fd {$fd} is closed");
            }
        }
    }


    public function onTask($server,$task_id,$src_worker_id,$data)
    {
        print_r($data);

        sleep(10);
        $server->push($src_worker_id,date("Y-m-d H:i:s",time()));
        return "on task finished"; //告诉 worker 进程,当前任务完成
    }

    public function onFinish($server , $task_id , $data)
    {
        echo $task_id."\n";

        echo $data;
    }


}

$obj = ws::getInstance();
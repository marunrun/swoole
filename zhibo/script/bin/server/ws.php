<?php

class Ws
{
    const HOST = '0.0.0.0';
    const PORT = '8811';
    const CHART_PORT = '8812';

    private static $instance = null;

    private $server = null;

    private function __construct()
    {

        $this->server = new swoole_websocket_server(self::HOST, self::PORT);
        $this->server->listen(self::HOST,self::CHART_PORT,SWOOLE_SOCK_TCP);
        $this->server->set([
            'task_worker_num' => 4,
            'worker_num' => 4,
            'enable_static_handler' => true,
            'document_root' => '/home/marun/code/demo/zhibo/public/static'
        ]);

        $this->server->on('workerStart', [$this, 'onWorkerStart']);
        $this->server->on('request', [$this, 'onRequest']);

        $this->server->on('task', [$this, 'onTask']);
        $this->server->on('finish', [$this, 'onFinish']);
        $this->server->on('open', [$this, 'onOpen']);
        $this->server->on('start', [$this, 'onStart']);
        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('close', [$this, 'onClose']);


        $this->server->start();
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * 在服务启动的时候
     * @param $server
     */
    public function onStart($server)
    {
        swoole_set_process_name('live_server');
    }


    /**
     *当开启服务的时候
     * @param $http
     * @param $worker_id
     */
    public function onWorkerStart($http, $worker_id)
    {
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../../../application/');

        // 加载基础文件
        // require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../../../thinkphp/start.php';
        $ids = \app\common\lib\redis\Predis::getInstance()->sMembers(config('redis.live_redis_key'));
        if($ids){
            \app\common\lib\redis\Predis::getInstance()->del(config('redis.live_redis_key'));
        }
    }

    /**
     * 当有连接进入的时候
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response)
    {
        if($request->server['request_uri'] == '/favicon.ico'){
            $response->status(404);
            $response->end();
            return;
        }

        $_SERVER = [];
        if (isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        if (isset($request->header)) {
            foreach ($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        $_GET = [];
        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }



        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }

        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        $this->writeLog();

        $_POST['http_server'] = $this->server;

        ob_start();

        try {

            \think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
                ->run()
                ->send();

        } catch (\Exception $e) {
            echo $e;
        }

        $res = ob_get_clean();

        $response->end($res);

    }

    /**
     * 监听接收数据事件
     * @param $server
     * @param $frame
     */
    public function onMessage($server, $frame)
    {
        $data = [
            'task' => 1,
            'fd' => $frame->fd
        ];


        $server->push($frame->fd, date("Y-m-d H:i:s", time()) . " fd : {$frame->fd}");
    }

    /**
     * 监听客户端连接事件
     * @param $server
     * @param $request
     */
    public function onOpen($server, $request)
    {
        //当用户连接的时候,将用户的fd放到redis中
        \app\common\lib\redis\Predis::getInstance()->sAdd(config('redis.live_redis_key'), $request->fd);
    }

    public function onClose($server, $fd, $reactorId)
    {
        \app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_redis_key'), $fd);
    }

    public function onTask($server, $task_id, $src_worker_id, $data)
    {
        $obj = new \app\common\lib\task\Task();
        $method = $data['method'];
        $res = $obj->$method($data['data'],$server);

        print_r($res);

        return "task finished \n";
    }

    public function onFinish($server, $task_id, $data)
    {
        echo $data . "\n";
    }


    public function writeLog()
    {
        $datas = array_merge(['date' => date("Y-m-d H:i:s")],$_GET,$_POST,$_SERVER);

        $str ='';
        foreach ($datas as $k=>$v){
            $str .= " ".$k.":" .$v." ";
        }
        swoole_async_writefile(APP_PATH.'../runtime/log/'.date("Ym").'/'.date("d").'_access.log',$str.PHP_EOL,function (){

        },FILE_APPEND);


    }
}

$obj = Ws::getInstance();
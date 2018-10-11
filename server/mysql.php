<?php


class mysql
{

    public $db = null;
    public $dbConfig = null;
    public function __construct()
    {
        $this->db = new swoole_mysql();
        $this->dbConfig = [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => 'root',
            'database' => 'test',
            'charset' => 'utf8', //指定字符集
        ];
    }


    private function select()
    {

    }

    private function update()
    {

    }

    private function add()
    {

    }

    private function delete()
    {

    }

    public function execute($id,$username)
    {
        $this->db->connect($this->dbConfig,function ($db,$res){
            if($res === false ){
                var_dump($db->connect_errno,$db->connect_error);
                die;
            }

            echo "mysql connect ".PHP_EOL;

            $sql = "select * from user where id = 1";

            $this->db->query($sql,function ($link,$result){
                if($result === false){
                    var_dump($link->errno,$link->error);
                    die;
                }else{
                    print_r($result);
                }

            });

        });
        return true;

    }
}

$obj = new mysql();

$flag = $obj->execute(1,1);

var_dump($flag).PHP_EOL;

echo "start".PHP_EOL;

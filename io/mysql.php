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
        $this->db->connect($this->dbConfig,function ($db,$res) use ($id,$username){

            if($res === false ){
                var_dump($db->connect_errno,$db->connect_error);
                return false;
            }

            //$sql = "select * from user where id = 1";
            $sql = "update user set name ='".$username ."' where id = ".$id;

           $db->query($sql,function ($link,$result){
                if($result === false){
                    var_dump($link->errno,$link->error);
                    return false;
                }elseif ($result === true){
                    //add update delete
                    var_dump($link->affected_rows,$link->insert_id);
                }else{
                    // select => $result 返回的是查询结果
                    print_r($result);
                }





            });




                $sql = "select * from user";

                $db->query($sql,function ($link,$result){
                    if($result === false){
                        var_dump($link->errno,$link->error);
                        return false;
                    }elseif ($result === true){
                        //add update delete
                        var_dump($link->affected_rows,$link->insert_id);
                    }else{
                        // select => $result 返回的是查询结果
                        print_r($result);
                    }
                });


//            $this->db->begin(function ($db,$result){
//                $db->query("update user set name = run where id = 1",function ($db,$result){
//                    $db->commit(function ($db,$result){
//                        echo "commit ok ".PHP_EOL;
//                    });
//                });
//            });
//                            $this->db->close();

        });



        return true;
    }


}

$obj = new mysql();

$flag = $obj->execute(1,'marun');
echo $flag ? 'exec sql success '.PHP_EOL : "exec sql fails ".PHP_EOL;
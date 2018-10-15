<?php

$table = new swoole_table(1024);

$table->column('id',swoole_table::TYPE_INT);
$table->column('name',swoole_table::TYPE_STRING,32);
$table->column('age',swoole_table::TYPE_INT);

$table->create();

$table->set('user_marun',['id' => 1, 'name' => 'marun', 'age' => 20]);
$table['user_gurun'] = ['id' =>2 , 'name' => 'gurun' , 'age' => 17];

//print_r($table->get('user_marun'));
//print_r($table['user_gurun']);

$table->incr('user_gurun','age',1);
$table->decr('user_gurun','age',2);


print_r($table->get('user_gurun'));

$table->del('user_gurun');

var_dump($table->get('user_gurun'));
echo $table->count();



<?php 

namespace Core\Database\connection;

use Illuminate\Database\Capsule\Manager as Capsule;


class ConnectionDB{

     
     public static function connect(){

        $db= new Capsule();

        $db->addConnection([
            'driver' => $_ENV['type'],
            'host' => $_ENV['host'],
            'database' => $_ENV['dbname'],
            'username' => $_ENV['user'],
            'password' => $_ENV['password'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $db->setAsGlobal();

        $db->bootEloquent();
        
        return $db;
     }




}




?>
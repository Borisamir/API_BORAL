<?php 

namespace Core;


Class App {

    protected static array $dependencies = [];

    
    public static function setDependency($key , $dependency){
        static::$dependencies[$key] = $dependency;
    }

    public static function getDependency($key){
        return static::$dependencies[$key];
    }



}






?>
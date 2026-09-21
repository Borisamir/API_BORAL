<?php 
require __DIR__ . '/../vendor/autoload.php';
use Core\App;
use Core\Database\connection\ConnectionDB;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');

$dotenv->load();



App::setDependency('connection', ConnectionDB::connect());

$app_db=App::getDependency('connection');






?>
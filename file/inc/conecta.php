<?php

define('URL_SITE', 'http://' . $_SERVER['HTTP_HOST'] . '/controle_gastos/');


$host = 'localhost';
$user = 'root';
$password = 'root';
$database = 'controle_gastos';
try{
    $conexao = mysqli_connect($host,$user,$password,$database);
}catch (Exception $e){
    echo '<pre>';
    print_r($e);
    exit();

}

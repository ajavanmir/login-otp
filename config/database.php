<?php
/*
Copyright amir javanmir
Released on: january 5, 2025
*/
global $con;
$config = [
    "server" => "localhost",
    "username" => "root",
    "password" => "",
    "db" => "djavan"
];
try{
    $con = new PDO("mysql:host=".$config['server'].";dbname=".$config['db'], $config['username'], $config['password']);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(Exception $e){
    echo $e->getMessage();
}
?>
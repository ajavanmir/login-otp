<?php
/*
Copyright amir javanmir
Released on: january 5, 2025
*/
session_start();
if(isset($_SESSION["logged"])){
    $url = "/login/dashboard.php";
    header("Location: $url");
    exit;
}
require_once "../config/database.php";
require_once "../functions/function.php";

if(!isset($_POST["signin"]) || $_SERVER["REQUEST_METHOD"] != "POST"){
    header("Location: /login");
    exit;
}
try{
    $post = $_POST;
    extract($post);
    $key = check($key);
    $password = check($password);
   
    $errors = [];
    if(!is_null($key) && !is_null($password)){
        $sql = "SELECT * FROM `users` WHERE (`username`= :key OR `email` = :key OR `mobile` = :key) && `password` = :password LIMIT 1";
        $statement = $con->prepare($sql);
        $statement->bindValue(":key", $key);
        $statement->bindValue(":password", $password);
        $statement->execute();
        if($statement->rowCount()){
            $_SESSION["logged"] = true;
            header("Location: /login");
            exit;
        }else{
            $_SESSION["errors"] = "همچین کاربری وجود ندارد!";
            header("Location: /login");
            exit;
        }
    }else{
        if(is_null($key))$errors["key"] = "نام کاربری، ایمیل یا شماره موبایل وجود ندارد!";
        if(is_null($password))$errors["password"] = "رمز عبور وجود ندارد!";
        throw new Exception("خطایی وجود دارد!");
    }
}catch(Exception $e){
    $_SESSION["errors"] = $errors;
    header("Location: /login");
    exit;  
}
?>
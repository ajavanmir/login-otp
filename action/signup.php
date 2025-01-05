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

try{
  if(!isset($_POST["signup"]) || $_SERVER["REQUEST_METHOD"] != "POST"){
    header("Location: /login");
    exit;
  }
  $post = $_POST;
  extract($post);
  $userName = check($userName);
  $email = check($email);
  $mobile = check($mobile);
  $password = check($password);
   
  $errors = [];
  if(!is_null($userName) && !is_null($email) && !is_null($mobile) && !is_null($password)){
      if(strlen($password<8)){
          $errors["password"] = "رمز عبور نمی تواند کمتر از 8 کاراکتر باشد!";
          throw new Exception("خطایی وجود دارد!");   
      }
      $sql = "INSERT INTO `users` (`username`, `email`, `mobile`, `password`) values (?,?,?,?)";
      $stmt = $con->prepare($sql);
      $stmt->bindValue(1,$userName);
      $stmt->bindValue(2,$email);
      $stmt->bindValue(3,$mobile);
      $stmt->bindValue(4,$password);
      $stmt->execute();
      header("Location: /login");
      exit;
  }else{
      if(is_null($userName))$errors["userName"] = "نام کاربری وجود ندارد!";
      if(is_null($email))$errors["email"] = "ایمیل وجود ندارد!";
      if(is_null($mobile))$errors["mobile"] = "شماره موبایل وجود ندارد!";
      if(is_null($password))$errors["password"] = "رمز عبور وجود ندارد!";
      throw new Exception("خطایی وجود دارد!");
  }
}catch(Exception $e){
    $_SESSION["errors"] = $errors;
    header("Location: /login");
    exit;  
}
?>
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
error_reporting(E_ALL);
ini_set('display_errors', 1);

$infoArr = json_decode(file_get_contents("php://input"), true);
if(!isset($infoArr["codeOtp"]) || !isset($infoArr["userOtp"]) || !isset($_SESSION["csrf_token"]) || !isset($infoArr["csrf"]) || $infoArr["csrf"] !== $_SESSION["csrf_token"]){
    echo json_encode(["success" => false, "message" => "error in otp_code"]);
    exit;
}

require_once dirname(__DIR__). "/config/database.php";

$codeOtp = htmlspecialchars($infoArr['userOtp']);
$info = $_SESSION["user_info"];

$query = "SELECT * FROM `users` where (email = :key or mobile = :key) AND otp = :otp  LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bindValue(":key", $info);
$stmt->bindValue(":otp", $codeOtp);
$stmt->execute();

if($stmt->rowCount() > 0){
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION["user"] = $user;
    $_SESSION["logged"] = true;
    $queryUpdate = "UPDATE `users` SET `otp` = NULL WHERE (email = :key or mobile = :key)";
    $stmtUpdate = $con->prepare($queryUpdate);
    $stmtUpdate->bindValue(":key", $info);
    if($stmtUpdate->execute()){
        echo json_encode(["success" => true, "message" => "login successfull", "login"=> 1]);   
        exit;   
    }
}else{
    echo json_encode(["success" => false, "message" => "error in otp_code"]);
    exit;
}

$con = null;
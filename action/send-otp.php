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
if (!isset($infoArr["user"]) || !isset($infoArr["csrf"]) || !isset($_SESSION["csrf_token"]) || $infoArr["csrf"] !== $_SESSION["csrf_token"]) {
    echo json_encode(["success" => false, "message" => "error"]);
    exit;
}

require_once dirname(__DIR__). "/config/database.php";
$info = htmlspecialchars($infoArr["user"]);

$query = "SELECT * FROM `users` WHERE (email = :key OR mobile = :key) LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bindValue(":key", $info);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    $userOtp = $stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($userOtp["otp"])){
        $otp = rand(10000, 99999);
        $queryUpdate = "UPDATE `users` SET `otp` = :otp WHERE (email = :key OR mobile = :key)";
        $stmtUpdate = $con->prepare($queryUpdate);
        $stmtUpdate->bindValue(":otp", $otp);
        $stmtUpdate->bindValue(":key", $info);
        $stmtUpdate->execute();
        if ($stmtUpdate->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Enter Code:", "otp" => $otp]);
        } else {
            echo json_encode(["success" => false, "message" => "error update!"]);
        }
    }else{
        if (isset($userOtp["otp"]) && !empty($userOtp["otp"])) {
            echo json_encode(["success" => true, "message" => "Enter Code:", "otp" => $userOtp["otp"]]);
            exit;
        } else {
            echo json_encode(["success" => false, "message" => "error generate code!"]);
            exit;
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "error not found user!"]);
    exit;
}

$con = null;
?>
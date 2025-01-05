<?php
/*
Copyright amir javanmir
Released on: january 5, 2025
*/
session_start();
if(isset($_SESSION["logged"])){
    var_dump($_SESSION["user"]);
}else{
    header("Location: /");
    exit;
}
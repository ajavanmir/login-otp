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
require_once "config/database.php";
require_once "functions/function.php"; 
?>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
  <div class="container" id="container">
    <div class="form-container sign-up">
      <form method="post" action="action/signup.php">
        <h1>ایجاد حساب کاربری</h1>
        <?php
        if(!empty($_SESSION)){
          if(isset($_SESSION["errors"])){
            if(hasError($_SESSION["errors"])){
              echo "<p class='error mb-2'>";
              foreach($_SESSION["errors"] as $val){
                echo $val."<br>";
              }
              echo "</p>";
            }
            unset($_SESSION["errors"]);
          }
        }
        ?>
        <input type="text" name="userName" placeholder="نام کاربری">
        <input type="email" name="email" placeholder="ایمیل">
        <input type="text" name="mobile" placeholder="موبایل">
        <input type="password" name="password" placeholder="پسورد">
        <button name="signup" type="submit">ثبت نام</button>
      </form>
    </div>
    <div class="form-container sign-in">
      <form method="post" action="action/signin.php">
        <h1>ورود به سیستم</h1>
        <span>اطلاعات خود را وارد کنید...</span>
        <?php         
          if(!empty($_SESSION)){
            if(isset($_SESSION['logged'])){
              echo "<p class='success mb-2'>شما با موفقیت ورود کردید</p>";
              unset($_SESSION['logged']);
            }
            if(isset($_SESSION["errors"])){
              if(hasError($_SESSION["errors"])){
                echo "<p class='error mb-2'>".$_SESSION["errors"]."</p>";
              }
              unset($_SESSION["errors"]);
            }
          }
        ?>
        <input name="key" type="text" placeholder="نام کاربری / ایمیل / شماره تلفن">
        <input name="password" type="password" placeholder="رمز عبور">
        <div style="text-align:center">
            <button type="submit" name="signin">ورود</button><br><br>
            <a href="otp.php">ارسال کد یکبار مصرف</a>
        </div>
      </form>
    </div>
    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <h1>ثبت نام کنید</h1>
          <button class="hidden" id="login">ورود به سیستم</button>
        </div>
        <div class="toggle-panel toggle-right">
          <h1>سلام! وارد شوید</h1>
          <p>در صورتی که هنوز ثبت نام نکرده اید ثبت نام کنید</p>
          <button class="hidden" id="register">ثبت نام</button>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="./assets/script/js/script.js"></script>
</html>
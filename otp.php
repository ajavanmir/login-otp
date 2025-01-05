<?php
/*
Copyright amir javanmir
Released on: january 5, 2025
*/
session_start();
$_SESSION["csrf_token"] = bin2hex(random_bytes(32));
if(isset($_SESSION["logged"])){
  $url = "/login/dashboard.php";
  header("Location: $url");
  exit;
}
try{
  if(isset($_POST["info"]) && isset($_POST["send-mobile"])){
    $errors = $success = [];
    require_once __DIR__."/functions/function.php";
    $info = check($_POST["info"]);
    if(preg_match('/^09\d{9}$/', $info) || preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.]+\.[a-zA-Z]{2,}$/', $info)){
      require_once __DIR__."/config/database.php";
      $query = "SELECT * FROM `users` where (email = :key or mobile = :key) LIMIT 1";
      $stmt = $con->prepare($query);
      $stmt->bindValue(":key", $info);
      $stmt->execute();
      if($num = $stmt->rowCount() > 0){
        $success["info"] = $info;
        $success["message"] = "کد otp خود را وارد کنید.";
        $_SESSION["user_info"] = $info;
      }else{
        $errors[] = "کاربری با این اطلاعات وجود ندارد!";
      }
    }else{
      return;
    }
  }
}catch(PDOException $e){
  $errors[] = $e->getMessage();
}
?>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
  <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
  <div class="container" id="container">
    <div class="form-container form-otp">
      <form method="post">
        <h1>ارسال کد OTP</h1>
        <?php
        if(!empty($errors)){
          foreach($errors as $item){
            echo "<p class='alert alert-danger mb-3'>".$item."</p>";
          }
        }
        if(!isset($success)){
        ?>
        <input type="text" name="info" placeholder="شماره موبایل یا ایمیل خود را وارد کنید...">
        <div>
            <button name="send-mobile" type="submit">ارسال کد به موبایل</button>
        </div>
        <?php }else{ ?>
          <input type="text" name="codeOtp" placeholder="کد یکبار مصرف خود را وارد کنید...">
          <input type="hidden" name="csrf_token" value="<?=$_SESSION["csrf_token"];?>">
          <p id="show-code" class="alert alert-success"></p>
          <div>
              <button name="send-otp" id="check-otp" type="submit">تائید کد</button>
          </div>
          <script>
            let info = {
              user : "<?=$success["info"];?>",
              csrf : "<?=$_SESSION["csrf_token"];?>"
            }
            let textOtp = 0;
            sendData("action/send-otp.php", info);

            const btnCheck = document.getElementById("check-otp");
            btnCheck.addEventListener("click", function(e){               
              e.preventDefault();
              if(textOtp){
                info.codeOtp = textOtp;
                info.userOtp = document.querySelector("input[name='codeOtp']").value;
              }
              sendData("action/verify-otp.php", info);
            });
            
            function sendData(url, dataIn){
              if(Object.keys(info).length > 0){
                fetch(url, {
                    method: "POST",
                    headers: {
                      "Content-Type": "application/json"
                    },
                    body: JSON.stringify(dataIn)
                }).then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                }).then(function(data) {
                    let { success, message, otp, login } = data;
                    if (success && otp && !login) {
                      let place = document.getElementById("show-code");
                      place.style.display = "block";
                      place.textContent = message + otp;
                      textOtp = otp;
                    }else if(success && login){
                      window.location.href = "./dashboard.php";
                    }else{
                      let place = document.getElementById("show-code");
                      place.classList.remove("alert-success");
                      place.classList.add("alert-danger");
                      place.style.display = "block";
                      place.textContent = "خطایی وجود دارد!";                      
                    }
                }).catch(function(error) {
                    console.log(error);
                });
              }
            }
          </script>
        <?php } ?>
      </form>
    </div>
  </div>
</body>
<script src="./assets/script/js/script.js"></script>
</html>
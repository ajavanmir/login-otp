# Login with OTP (One-Time Password)

This project is a simple yet secure login system using **One-Time Password (OTP)** for user authentication. It is built with **PHP** for the backend, **MySQL** for the database, and **JavaScript** for the frontend interactions.

## Features

- OTP-Based Login
- CSRF Protection
- Secure Session Management
- Input Validation
- JSON Responses
- Database Integration

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/ajavanmir/login-otp.git
   cd login-otp
   ```

2. Set up the database:
   - Create a MySQL database and import the provided SQL file (`database.sql`).
   - Update the database configuration in `config/database.php`:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'your_db_username');
     define('DB_PASS', 'your_db_password');
     define('DB_NAME', 'your_db_name');
     ```

3. Run the application:
   - Start your local server (e.g., Apache or Nginx).
   - Open the project in your browser (e.g., `http://localhost/login-otp`).

## Example Code

### PHP Code for OTP Verification

```php
<?php
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
?>
```

### JavaScript Code for Sending OTP

```javascript
let info = {
  user: "<?=$success['info'];?>",
  csrf: "<?=$_SESSION['csrf_token'];?>"
};

fetch("action/send-otp.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify(info)
}).then((response) => {
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
}).then(function(data) {
    let { success, message, otp } = data;
    if (success) {
        let place = document.getElementById("show-code");
        place.style.display = "block";
        place.textContent = message + otp;
    }
}).catch(function(error) {
    console.log(error);
});
```

## Contributing

Contributions are welcome! Please follow these steps:
1. Fork the repository.
2. Create a new branch (`git checkout -b feature/YourFeatureName`).
3. Commit your changes (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature/YourFeatureName`).
5. Open a pull request.

## License

This project is open-source and available under the [MIT License](LICENSE).

---
```

---

### توضیحات:
1. **بلوک کد PHP**: کد PHP مربوط به تأیید OTP در یک بلوک کد قرار گرفته است.
2. **بلوک کد JavaScript**: کد JavaScript مربوط به ارسال OTP در یک بلوک کد دیگر قرار گرفته است.
3. **ساختار Markdown**: از ` ```php ` و ` ```javascript ` برای نمایش کدها به صورت رنگی و خوانا استفاده شده است.

با این روش، کدها به صورت زیبا و خوانا در فایل `README.md` نمایش داده می‌شوند.

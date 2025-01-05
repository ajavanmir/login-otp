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
   - Create a MySQL database and import the provided SQL file (`djavan.sql`).
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

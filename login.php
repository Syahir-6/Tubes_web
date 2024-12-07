<?php
session_start();

// Dummy data untuk username dan password
$users = [
    "admin" => "123456",
    "user1" => "123",
    "user2" => "password2"
];

// Jika pengguna sudah login, redirect ke dashboard
if (isset($_SESSION['username']) || isset($_COOKIE['username'])) {
    header("Location: beranda.php");
    exit;
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    if (array_key_exists($username, $users) && $users[$username] === $password) {
        // Simpan username ke session
        $_SESSION['username'] = $username;

        // Simpan ke cookie jika Remember Me dicentang
        if ($remember_me) {
            setcookie("username", $username, time() + (86400 * 7), "/"); // Cookie 7 hari
        }

        // Redirect ke dashboard
        header("Location: beranda.php");
        exit;
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style_login.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2><span style="font-size:150px;">L</span>ogin</h2>
            <?php
            // Tampilkan pesan error jika login gagal
            if (isset($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>
            <form action="" method="POST">
                <br><br><label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
<br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                 <div style="display: flex; justify-content: flex-end; align-items: right; margin-top: 10px;">
                    <input type="checkbox" id="remember_me" name="remember_me" style="margin-right: -220px;">
                    <label for="remember_me" style="font-size: 14px; font-weight: normal; margin-right: 50px;">Remember Me</label>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="image-slider">
            <div class="image image1"></div>
            <div class="image image2"></div>
            <div class="image image3"></div>
        </div>
    </div>
</body>
</html>

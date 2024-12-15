<?php
session_start();
// Jika pengguna sudah login, redirect ke dashboard yang sesuai
if (isset($_SESSION['username']) || isset($_COOKIE['username'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: beranda_admin.php");
    } else {
        header("Location: beranda.php");
    }
    exit;
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
            if (isset($_SESSION['error_message'])) {
                echo "<p style='color: red;'>{$_SESSION['error_message']}</p>";
                unset($_SESSION['error_message']);
            }
            ?>
            <form action="cek_login.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <div style="display: flex; justify-content: flex-end; align-items: center; margin-top: 5px;">
                    <input type="checkbox" id="remember_me" name="remember_me">
                    <label for="remember_me" style="font-size: 14px;margin-top:-20px;margin-left: -180px; margin-right: 30px">Remember Me</label>
                </div>
                <button type="submit" >Login</button>
            </form>
			 <div style="display: flex; margin-top: 10px;">
				<p>Belum punya akun? <a href="daftar.php" style="text-decoration: none; color:blue">Daftar</a></p>
			</div> 
        </div>
        <div class="image-slider">
            <div class="image image1"></div>
            <div class="image image2"></div>
            <div class="image image3"></div>
			<div class="image image4"></div>
            <div class="image image5"></div>
        </div>
    </div>
</body>
</html>



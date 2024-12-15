<?php
session_start();
require 'koneksi.php'; // Pastikan file koneksi ke database benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = 'user'; // Default peran adalah user

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $_SESSION['error_message'] = "Semua kolom wajib diisi!";
        header("Location: daftar.php");
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Password tidak cocok!";
        header("Location: daftar.php");
        exit;
    }

    // Cek apakah username sudah ada
    $query = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = "Username sudah terdaftar.";
        header("Location: daftar.php");
        exit;
    }

    // Simpan data ke database
    $insert_query = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param('sss', $username, $password, $role);

    if ($insert_stmt->execute()) {
        $_SESSION['success_message'] = "Pendaftaran berhasil! Silakan login.";
        header("Location: login.php");
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan. Coba lagi.";
        header("Location: daftar.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Daftar</title>
    <link rel="stylesheet" href="style_login.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2><span style="font-size:150px;">D</span>aftar</h2>
            <?php
            if (isset($_SESSION['error_message'])) {
                echo "<p style='color: red;'>{$_SESSION['error_message']}</p>";
                unset($_SESSION['error_message']);
            }
            if (isset($_SESSION['success_message'])) {
                echo "<p style='color: green;'>{$_SESSION['success_message']}</p>";
                unset($_SESSION['success_message']);
            }
            ?>
            <form action="daftar.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <br><br>
                <label for="confirm_password">Konfirmasi Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <br><br>
                <button type="submit">Daftar</button>
            </form>
            <div style="display: flex; margin-top: 10px;">
				<p>Sudah punya akun? <a href="login.php" style="text-decoration: none; color:blue">Login</a></p>
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

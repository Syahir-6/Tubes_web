<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi database

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];
$remember_me = isset($_POST['remember_me']) ? true : false;

// Query untuk mendapatkan data pengguna berdasarkan username
$query = "SELECT id, username, password, role FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username); // Bind parameter untuk mencegah SQL injection
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah username ditemukan
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verifikasi password tanpa hashing
    if ($password === $user['password']) {
        // Set session untuk pengguna
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Jika "remember me" dicentang, simpan username dalam cookie
        if ($remember_me) {
            setcookie('username', $user['username'], time() + (86400 * 30), "/"); // Cookie berlaku selama 30 hari
        } else {
            // Jika tidak dicentang, pastikan cookie tidak diset
            if (isset($_COOKIE['username'])) {
                setcookie('username', '', time() - 3600, "/"); // Hapus cookie jika ada
            }
        }

        // Redirect berdasarkan role pengguna
        if ($user['role'] === 'admin') {
            header("Location: beranda_admin.php");
        } else {
            header("Location: beranda.php");
        }
        exit;
    } else {
        // Jika password salah
        $_SESSION['error_message'] = 'Password salah!';
        header("Location: login.php");
        exit;
    }
} else {
    // Jika username tidak ditemukan
    $_SESSION['error_message'] = 'Username tidak ditemukan!';
    header("Location: login.php");
    exit;
}
?>

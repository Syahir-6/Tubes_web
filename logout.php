<?php
// Mulai sesi
session_start();

// Hapus session
session_unset();
session_destroy();

// Hapus cookie jika ada
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/'); // Set cookie kedaluwarsa
}

// Arahkan ke halaman login
header("Location: login.php");
exit();
?>

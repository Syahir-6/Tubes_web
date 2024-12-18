<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}
	?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengguna</title>
    <style>
        body {
            font-family: Cambria;
            margin: 0;
            padding: 0;
        }
        .header-center {
            position: relative;
            text-align: center;
            background-color: #4a6670;
            color: white;
            padding: 20px 0;
        }
        .header-center .logo {
            font-size: 60px;
            margin-top: 80px;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .menu-links {
            margin-top: 10px;
        }
        .menu-links a {
            margin: 0 15px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }
        .menu-links a:hover {
            text-decoration: none;
            color: #FFD700;
        }
        /* Tambahan untuk tombol Logout di kanan atas */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: transparent;
             color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
			
        }
        .logout-btn:hover {
         color: #FFD700;
        }
    </style>
</head>
<body>
    <!-- Header dengan logo di tengah -->
    <div class="header-center">
        <!-- Tombol Logout -->
        <a href="logout.php" class="logout-btn">Logout</a>

        <!-- Logo -->
        <div class="logo">LioS</div>

        <!-- Menu navigasi -->
        <div class="menu-links">
            <a href="beranda_admin.php">Pengguna</a>
            <a href="komik.php">Komik</a>
            <a href="episode.php">Episode</a>
        </div>
    </div>
</body>
</html>

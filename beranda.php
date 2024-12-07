<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Tangkap username dari session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Beranda - LioS</title>
    <link rel="stylesheet" href="style_b.css">
</head>
<body>

    <!-- Header dengan logo dan navbar -->
    <header>
        <div class="logo">LioS</div>
        <nav>
            <ul>
                <li><a href="beranda.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Pesan sambutan -->
    <section class="welcome-message">
        <h1>Hi, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>Selamat datang di LioS, platform gaya personal Anda!</p>
    </section>

    <!-- Hiasan kotak atas -->
    <div class="top-decoration"></div>

    <!-- Frame Personal Color -->
    <section class="personal-color" onclick="window.location.href='personal-color.html'">
        <h2>Personal Color</h2>
        <p>Pelajari tentang bagaimana personal color memengaruhi pilihan gaya dan fashion Anda.</p>
    </section>

    <!-- Kategori Produk -->
    <section id="menu">
        <div class="menu-container">
            <div class="menu-header">
                <h2>Spring</h2>
                <a href="spring.html" class="more-link">Selengkapnya ></a>
            </div>

            <div class="menu-item-container">
                <div class="menu-item">
                    <img src="spring1.jpg" alt="Iced Coffee">
                    <h3>Iced Coffee</h3>
                </div>
                <div class="menu-item">
                    <img src="spring2.jpg" alt="Strawberry Matcha Latte">
                    <h3>Strawberry Matcha Latte</h3>
                </div>
                <div class="menu-item">
                    <img src="spring3.jpg" alt="Cookies">
                    <h3>Peanut Butter Chocolate Swirl Cookies</h3>
                </div>
                <div class="menu-item">
                    <img src="spring4.jpg" alt="Sandwich">
                    <h3>Sandwich</h3>
                </div>
                <div class="menu-item">
                    <img src="spring5.jpg" alt="Waffle">
                    <h3>Waffle</h3>
                </div>
                <div class="menu-item">
                    <img src="spring6.jpg" alt="Waffle">
                    <h3>Waffle</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Navbar bawah -->
    <footer class="navbar">
        <a href="beranda.php"><img src="home.png" alt="Beranda"></a>
        <a href="disimpan.php"><img src="bookmark.png" alt="Disimpan"></a>
        <a href="profil.html"><img src="profil.png" alt="Profil"></a>
    </footer>

</body>
</html>

<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Koneksi ke database
$servername = "localhost";
$username_db = "root"; // Ganti dengan username database Anda
$password_db = ""; // Ganti dengan password database Anda
$database = "web"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username_db, $password_db, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Query untuk mengambil data terakhir dilihat
$sql_terakhir_dilihat = "SELECT id, judul, gambar FROM komik ORDER BY id DESC LIMIT 3";
$result_terakhir_dilihat = $conn->query($sql_terakhir_dilihat);

// Query untuk rekomendasi (semua data di tabel komik)
$sql_rekomendasi = "SELECT id, judul, gambar FROM komik";
$result_rekomendasi = $conn->query($sql_rekomendasi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LioS</title>
    <style>
			.ads-slider {
            position: relative;
            max-width: 1300px;
			max-height: 650px;
            margin: 20px auto;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .ads-slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .ad-slide {
            min-width: 100%;
            flex-shrink: 0;
        }
        .ad-slide img {
            width: 100%;
            height: auto;
            display: block;
        }
        .ads-prev,
        .ads-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 50%;
            z-index: 2;
        }
        .ads-prev {
            left: 10px;
        }
        .ads-next {
            right: 10px;
        }
        .ads-prev:hover,
        .ads-next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
        .frame {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .frame h2 {
            font-size: 1.5rem;
            color: #333;
        }
        .horizontal-slider {
            display: flex;
            overflow-x: auto;
            gap: 15px;
            padding: 10px;
        }
        .comic-card {
            flex: 0 0 auto;
            width: 150px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .comic-card img {
            width: 150px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .recommendation-list {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .recommendation-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
			background-color:#D4DBDA
        }
        .recommendation-item img {
            width: 60px;
            height: 80px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 5px;
        }
        .recommendation-item span {
            font-size: 1.2rem;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
	<?php include"header.php"; ?>

    <!-- Iklan -->
    <div class="ads-slider">
        <div class="ads-slides">
            <div class="ad-slide">
                <img src="day192.jpg" alt="Iklan 1">
            </div>
            <div class="ad-slide">
                <img src="orv2.jpg" alt="Iklan 2">
            </div>
            <div class="ad-slide">
                <img src="nanhao.jpg" alt="Iklan 3">
            </div>
			 <div class="ad-slide">
                <img src="doraemon.jpg" alt="Iklan 3">
            </div>
			 <div class="ad-slide">
                <img src="Naruto.jpg" alt="Iklan 3">
            </div>
        </div>
        <button class="ads-prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="ads-next" onclick="moveSlide(1)">&#10095;</button>
    </div>

    <!-- Terakhir Dilihat -->
    <div class="frame">
        <h2>Terakhir Dilihat</h2>
        <div class="horizontal-slider">
            <?php while ($row = $result_terakhir_dilihat->fetch_assoc()): ?>
               <div class="comic-card">
				<a href="detail_komik.php?id=<?= urlencode($row['id']) ?>">
					<img src="<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
					<p><?= htmlspecialchars($row['judul']) ?></p>
				</a>
			</div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Rekomendasi -->
    <div class="recommendation-list">
        <h2>Rekomendasi</h2>
        <?php while ($row = $result_rekomendasi->fetch_assoc()): ?>
           <div class="recommendation-item">
				<a href="detail_komik.php?id=<?= urlencode($row['id']) ?>" style="text-decoration: none; color: inherit;">
					<img src="<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
					<span><?= htmlspecialchars($row['judul']) ?></span>
				</a>
			</div>

        <?php endwhile; ?>
    </div>

    <script>
    let currentSlide = 0;

    function moveSlide(direction) {
        const slides = document.querySelector('.ads-slides');
        const totalSlides = slides.children.length;

        currentSlide = (currentSlide + direction + totalSlides) % totalSlides;

        slides.style.transform = `translateX(-${currentSlide * 100}%)`;
    }

    function toggleLogout() {
        const menu = document.getElementById('logoutMenu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    // Fungsi untuk pergeseran otomatis
    function autoSlide() {
        moveSlide(1); // Pergeser ke slide berikutnya
    }

    // Mengatur interval untuk pergeseran otomatis setiap 3 detik (3000 ms)
    setInterval(autoSlide, 3000);


        function toggleLogout() {
            const menu = document.getElementById('logoutMenu');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
			
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>

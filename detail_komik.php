<?php
// Mulai sesi
session_start();

// Periksa apakah session "disukai" sudah ada
if (!isset($_SESSION['disukai'])) {
    $_SESSION['disukai'] = [];
}

// Logika untuk menyimpan komik ke "disukai"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['judul'], $_POST['gambar'])) {
    $komik = [
        'judul' => $_POST['judul'],
        'gambar' => $_POST['gambar']
    ];

    // Hindari duplikasi (cek apakah komik sudah ada di daftar disukai)
    $isAlreadyLiked = false;
    foreach ($_SESSION['disukai'] as $liked) {
        if ($liked['judul'] === $komik['judul']) {
            $isAlreadyLiked = true;
            break;
        }
    }

    if (!$isAlreadyLiked) {
        $_SESSION['disukai'][] = $komik;
    }
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

// Periksa apakah ID komik tersedia
if (!isset($_GET['id'])) {
    echo "ID komik tidak ditemukan.";
    exit();
}

$id_komik = intval($_GET['id']);

// Query untuk detail komik
$sql_komik = "SELECT * FROM komik WHERE id = $id_komik";
$result_komik = $conn->query($sql_komik);

if ($result_komik->num_rows === 0) {
    echo "Komik tidak ditemukan.";
    exit();
}

$komik = $result_komik->fetch_assoc();

// Query untuk daftar episode berdasarkan id_komik
$sql_episode = "SELECT id, bab, gambar_halaman FROM halaman WHERE id_komik = $id_komik GROUP BY bab ORDER BY bab ASC";
$result_episode = $conn->query($sql_episode);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Komik - <?= htmlspecialchars($komik['judul']) ?></title>
    <style>
        body {
            margin: 0;
            font-family: Cambria;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .comic-header {
            display: flex;
            gap: 20px;
        }
        .comic-header img {
            width: 200px;
            height: auto;
            border-radius: 10px;
        }
        .comic-details {
            flex: 1;
        }
        .comic-title {
            font-size: 2rem;
            color: #333;
        }
        .comic-meta {
            font-size: 1rem;
            color: #777;
            margin-bottom: 10px;
        }
        .comic-synopsis {
            margin: 20px 0;
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }
        .episode-list {
            margin-top: 20px;
        }
        .episode-list h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }
        .episode-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f5f5f5;
            text-decoration: none;
            color: #333;
        }
        .episode-item:hover {
            background-color: #ebebeb;
        }
        .episode-item img {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .episode-item span {
            font-size: 1rem;
            color: #333;
        }
		.btn-suka{
		padding: 10px 20px;
		background-color: #4a6670; 
		color: white;
		border: none;
		border-radius: 5px;
		font-size: 1rem;
		width:500px;
		}
    </style>
</head>
<body>
    <header>
        <?php include"header.php"; ?>
    </header>

    <div class="container">
        <!-- Header Komik -->
        <div class="comic-header">
            <img src="<?= htmlspecialchars($komik['gambar']) ?>" alt="<?= htmlspecialchars($komik['judul']) ?>">
            <div class="comic-details">
                <h1 class="comic-title"><?= htmlspecialchars($komik['judul']) ?></h1>
                <p class="comic-meta"><strong>Penulis:</strong> <?= htmlspecialchars($komik['penulis']) ?></p>
                <p class="comic-meta"><strong>Genre:</strong> <?= htmlspecialchars($komik['genre']) ?></p>
                <p class="comic-meta"><strong>Status:</strong> <?= htmlspecialchars($komik['status']) ?></p>
            </div>
        </div>

        <!-- Sinopsis -->
        <div class="comic-synopsis">
            <h2>Sinopsis</h2>
            <p><?= nl2br(htmlspecialchars($komik['sinopsis'])) ?></p>
        </div>

        <!-- Tombol Suka -->
        <form action="" method="POST" style="text-align: center; margin: 20px 0;">
            <input type="hidden" name="judul" value="<?= htmlspecialchars($komik['judul']) ?>"> <!-- Judul komik -->
            <input type="hidden" name="gambar" value="<?= htmlspecialchars($komik['gambar']) ?>"> <!-- Gambar komik -->
            <button type="submit" class="btn-suka">Suka</button>
        </form>

        <!-- Daftar Episode -->
        <div class="episode-list">
            <h3>List Episode</h3>
            <?php if ($result_episode->num_rows > 0): ?>
                <?php while ($episode = $result_episode->fetch_assoc()): ?>
                    <a class="episode-item" href="baca_komik.php?id_komik=<?= $id_komik ?>&id_episode=<?= $episode['id'] ?>">
                        <img src="<?= htmlspecialchars($episode['gambar_halaman']) ?>" alt="Bab <?= htmlspecialchars($episode['bab']) ?>">
                        <span>Bab <?= htmlspecialchars($episode['bab']) ?></span>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada episode untuk komik ini.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>

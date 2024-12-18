<?php
// Mulai sesi
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}
// Logika untuk menghapus komik dari daftar disukai
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    $judul_hapus = $_POST['judul_hapus'];
    
    // Cari dan hapus komik berdasarkan judul
    foreach ($_SESSION['disukai'] as $key => $komik) {
        if ($komik['judul'] === $judul_hapus) {
            unset($_SESSION['disukai'][$key]);
            // Reindex array setelah penghapusan
            $_SESSION['disukai'] = array_values($_SESSION['disukai']);
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LioS - Disukai</title>
    <style>
            .frame {
            margin: 20px auto;
            padding: 20px;
            max-width: 800px; 
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
        }
        .comic-card {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f5f5f5;
            justify-content: space-between;
        }
        .comic-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .comic-info img {
            width: 50px;
            height: 70px;
            border-radius: 5px;
            object-fit: cover;
        }
        .comic-title {
            font-size: 1rem;
            font-weight: bold;
        }
        .hapus-button {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .hapus-button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <header>
        <?php include"header.php"; ?>
    </header>


    <div class="frame">
        <h2>Daftar Komik yang Disukai</h2>
        <?php if (isset($_SESSION['disukai']) && count($_SESSION['disukai']) > 0): ?>
            <?php foreach ($_SESSION['disukai'] as $komik): ?>
                <div class="comic-card">
                    <div class="comic-info">
                        <img src="<?= htmlspecialchars($komik['gambar']) ?>" alt="<?= htmlspecialchars($komik['judul']) ?>">
                        <div class="comic-title"><?= htmlspecialchars($komik['judul']) ?></div>
                    </div>
                    <form action="" method="POST">
                        <input type="hidden" name="judul_hapus" value="<?= htmlspecialchars($komik['judul']) ?>">
                        <button type="submit" name="hapus" class="hapus-button">Hapus</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada komik yang disukai.</p>
        <?php endif; ?>
    </div>
</body>
</html>

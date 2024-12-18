<?php
// Mulai sesi
session_start();

// Periksa apakah ID episode dan ID komik tersedia
if (!isset($_GET['id_komik']) || !isset($_GET['id_episode'])) {
    echo "ID komik atau episode tidak ditemukan.";
    exit();
}

$id_komik = intval($_GET['id_komik']);
$id_episode = intval($_GET['id_episode']);

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

// Query untuk mendapatkan detail episode
$sql_episode = "SELECT * FROM halaman WHERE id = ? AND id_komik = ?";
$stmt_episode = $conn->prepare($sql_episode);
$stmt_episode->bind_param("ii", $id_episode, $id_komik);
$stmt_episode->execute();
$result_episode = $stmt_episode->get_result();

if ($result_episode->num_rows === 0) {
    echo "Episode tidak ditemukan.";
    exit();
}

$episode = $result_episode->fetch_assoc();

// Query untuk mengambil semua gambar berdasarkan bab
$sql_gambar = "SELECT gambar_halaman FROM halaman WHERE id_komik = ? AND bab = ?";
$stmt_gambar = $conn->prepare($sql_gambar);
$stmt_gambar->bind_param("is", $id_komik, $episode['bab']); // 'bab' sekarang VARCHAR
$stmt_gambar->execute();
$result_gambar = $stmt_gambar->get_result();

// Navigasi episode (prev dan next)
$sql_prev = "SELECT id FROM halaman WHERE id_komik = ? AND id < ? ORDER BY id DESC LIMIT 1";
$sql_next = "SELECT id FROM halaman WHERE id_komik = ? AND id > ? ORDER BY id ASC LIMIT 1";

$stmt_prev = $conn->prepare($sql_prev);
$stmt_prev->bind_param("ii", $id_komik, $id_episode);
$stmt_prev->execute();
$prev_episode = $stmt_prev->get_result()->fetch_assoc();

$stmt_next = $conn->prepare($sql_next);
$stmt_next->bind_param("ii", $id_komik, $id_episode);
$stmt_next->execute();
$next_episode = $stmt_next->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Komik - <?= htmlspecialchars($episode['bab']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .nav-buttons a {
            padding: 10px 20px;
            text-decoration: none;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #4a6670;
            color: #fff;
            font-weight: bold;
        }
        .images img {
            width: 100%;
            margin-bottom: 20px;
        }
		 .images img {
            width: 100%;
            margin-bottom: 20px;
        }
		
        .comment-section {
			margin: 20px auto;
			width: 50%;
			border: 2px solid #ccc;
			border-radius: 10px;
			padding: 15px; 
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
			background-color: #f9f9f9; 
			text-align: left; 
		}

        .comment-section h3 {
            margin-bottom: 10px;
        }
        .comment-section textarea {
            width: 650px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .rating {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 30px;
            color: #ccc;
            transition: color 0.3s ease-in-out;
        }
        /* Efek warna kuning untuk rating */
        .rating input:checked ~ label,
       
        }
        /* Animasi warna kuning */
        .rating label {
            display: inline-block;
            margin-left: 0;
        }
        .comment-list {
            margin-top: 20px;
        }
		
        .comment-item {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }
    </style>
</head>
<body>
 <?php include "header.php"; ?>
    <div class="container">
        <?php 
        // Query untuk mengambil judul komik
        $sql_komik = "SELECT judul FROM komik WHERE id = ?";
        $stmt_komik = $conn->prepare($sql_komik);
        $stmt_komik->bind_param("i", $id_komik);
        $stmt_komik->execute();
        $result_komik = $stmt_komik->get_result();
        $komik = $result_komik->fetch_assoc();
        ?>

        <h1><?= htmlspecialchars($komik['judul']) ?></h1>
        <h2>Bab: <?= htmlspecialchars($episode['bab']) ?></h2>

        <div class="nav-buttons">
            <a href="<?= $prev_episode ? "baca_komik.php?id_komik=$id_komik&id_episode=" . $prev_episode['id'] : '#' ?>">Sebelumnya</a>
            <a href="detail_komik.php?id=<?= $id_komik ?>">Home</a>
            <a href="<?= $next_episode ? "baca_komik.php?id_komik=$id_komik&id_episode=" . $next_episode['id'] : '#' ?>">Selanjutnya</a>
        </div>

        <div class="images">
            <?php while ($gambar = $result_gambar->fetch_assoc()): ?>
                <img src="<?= htmlspecialchars($gambar['gambar_halaman']) ?>" alt="Halaman Komik">
            <?php endwhile; ?>
        </div>
		<div class="nav-buttons">
            <a href="<?= $prev_episode ? "baca_komik.php?id_komik=$id_komik&id_episode=" . $prev_episode['id'] : '#' ?>">Sebelumnya</a>
            <a href="detail_komik.php?id=<?= $id_komik ?>">Home</a>
            <a href="<?= $next_episode ? "baca_komik.php?id_komik=$id_komik&id_episode=" . $next_episode['id'] : '#' ?>">Selanjutnya</a>
        </div>
    </div>
	
 <div class="comment-section">
            <h3>Tambahkan Komentar</h3>
            <div>
                Username: <strong><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest' ?></strong>
            </div>
            <form id="commentForm">
                <textarea id="commentText" placeholder="Tuliskan komentar Anda..." required></textarea>
                <div class="rating" id="rating">
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1">★</label>
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2">★</label>
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3">★</label>
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4">★</label>
                    <input type="radio" id="star5" name="rating" value="5">
                    <label for="star5">★</label>
                </div>
                <button type="submit">Kirim</button>
            </form>
            <div class="comment-list" id="commentList"></div>
        </div>
    </div>

    <script>
        // Menambahkan animasi dan pengaturan warna kuning pada bintang
        const ratingInputs = document.querySelectorAll('.rating input');
        const ratingLabels = document.querySelectorAll('.rating label');

        ratingInputs.forEach((input, index) => {
            input.addEventListener('change', function() {
                const currentValue = this.value;
                // Terapkan animasi warna kuning hanya pada bintang yang terpilih dan sebelumnya
                ratingLabels.forEach((label, idx) => {
                    if (idx < currentValue) {
                        label.style.color = 'gold';
                    } else {
                        label.style.color = '#ccc';
                    }
                });
            });
        });

        // Form komentar
        document.getElementById('commentForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const username = "<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest' ?>";
            const commentText = document.getElementById('commentText').value;
            const rating = document.querySelector('input[name="rating"]:checked')?.value || 0;

            if (!commentText.trim()) {
                alert("Komentar tidak boleh kosong!");
                return;
            }

            const commentList = document.getElementById('commentList');
            const newComment = document.createElement('div');
            newComment.classList.add('comment-item');
            newComment.innerHTML = `
                <strong>${username}</strong> (Rating: ${rating}★)
                <p>${commentText}</p>
            `;
            commentList.prepend(newComment);

            document.getElementById('commentText').value = '';
            document.querySelectorAll('input[name="rating"]').forEach(radio => radio.checked = false);
            // Reset warna kuning pada bintang setelah komentar dikirim
            ratingLabels.forEach(label => label.style.color = '#ccc');
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>



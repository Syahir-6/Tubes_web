<?php
session_start();
include 'koneksi.php';
// Pastikan user telah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Nama pengguna dari sesi
$username = $_SESSION['username'];

if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Fungsi untuk menangani unggahan gambar
function uploadImage($file) {
    $targetDir = "";  // Folder untuk menyimpan gambar
    $targetFile = $targetDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validasi jenis file gambar
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        return false;  // Gagal jika bukan gambar
    }

    // Cek apakah file sudah ada
    if (file_exists($targetFile)) {
        return false;  // Gagal jika file sudah ada
    }

    // Ukuran maksimal file
    if ($file["size"] > 5000000) {  // 5MB max
        return false;  // Gagal jika ukuran file terlalu besar
    }

    // Pindahkan file ke direktori yang telah ditentukan
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile;  // Mengembalikan path gambar yang berhasil diunggah
    } else {
        return false;  // Gagal saat proses upload
    }
}

// Menambahkan data komik
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comic'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $genre = $_POST['genre'];
    $status = $_POST['status'];
    $sinopsis = $_POST['sinopsis'];

    // Menangani file gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = uploadImage($_FILES['gambar']);
        if ($gambar === false) {
            echo "<script>alert('Gagal mengunggah gambar');</script>";
        } else {
            // Simpan data komik jika gambar berhasil diunggah
            $sql = "INSERT INTO komik (judul, gambar, penulis, genre, status, sinopsis) VALUES ('$judul', '$gambar', '$penulis', '$genre', '$status', '$sinopsis')";
            if ($conn->query($sql)) {
                echo "<script>alert('Data komik berhasil ditambahkan');</script>";
            } else {
                echo "<script>alert('Gagal menambahkan data komik');</script>";
            }
        }
    }
}

// Mengupdate data komik
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_comic'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $genre = $_POST['genre'];
    $status = $_POST['status'];
    $sinopsis = $_POST['sinopsis'];

    // Menangani file gambar jika diupload
    $gambar = $_POST['gambar']; // Default, jika gambar tidak diupdate

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = uploadImage($_FILES['gambar']);
        if ($gambar === false) {
            echo "<script>alert('Gagal mengunggah gambar');</script>";
        }
    }

    $sql = "UPDATE komik SET judul='$judul', gambar='$gambar', penulis='$penulis', genre='$genre', status='$status', sinopsis='$sinopsis' WHERE id='$id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Data komik berhasil diperbarui');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data komik');</script>";
    }
}

// Menghapus data komik
if (isset($_POST['delete'])) {
    $id = $_POST['delete'];
    $sql = "DELETE FROM komik WHERE id='$id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Data komik berhasil dihapus');</script>";
    } else {
        echo "<script>alert('Gagal menghapus data komik');</script>";
    }
}

// Mengambil semua data komik
$result = $conn->query("SELECT * FROM komik");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komik</title>
    <link rel="stylesheet" href="style_b.css">
    <style>
   /* Style seperti sebelumnya */
        body {
            font-family: Arial, sans-serif;
        }
        .header-center {
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
        .user-name {
            position: absolute;
            top: 40px;
            right: 20px;
            font-size: 18px;
            color: white;
            cursor: pointer;
			
        }
         .user-menu {
            display: none;
            position: absolute;
            top: 20px;
            right: 0;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
        .user-menu a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
			font-size:14px;
        }
        .user-menu a:hover {
            background-color: #f4f4f4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        table .action-buttons button {
            margin: 5px;
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-edit {
            background-color: #007BFF;
        }
        .btn-delete {
            background-color: #DC3545;
        }
        table .action-buttons button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <!-- Nama user di kanan atas -->
    <div class="user-name">Hai, <?= htmlspecialchars($username); ?>
        <div class="user-menu">
            <a href="logout.php">Log Out</a>
        </div>
    </div>

    <!-- Logo di tengah -->
    <div class="header-center">
        <div class="logo">LioS</div>
        <div class="menu-links">
            <a href="beranda_admin.php">Pengguna</a>
            <a href="komik.php">Komik</a>
        </div>
    </div>

    <!-- Form input data komik -->
    <form method="POST" action="komik.php" enctype="multipart/form-data" style="max-width: 800px; margin: 0 auto;">
        <table>
            <tr>
                <th>ID (kosongkan untuk tambah data)</th>
                <td><input type="text" name="id" placeholder="Masukkan ID (opsional)"></td>
            </tr>
            <tr>
                <th>Judul</th>
                <td><input type="text" name="judul" placeholder="Masukkan judul komik" required></td>
            </tr>
            <tr>
                <th>Gambar</th>
                <td><input type="file" name="gambar" required></td>
            </tr>
            <tr>
                <th>Penulis</th>
                <td><input type="text" name="penulis" placeholder="Masukkan penulis" required></td>
            </tr>
            <tr>
                <th>Genre</th>
                <td><input type="text" name="genre" placeholder="Masukkan genre" required></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><input type="text" name="status" placeholder="Masukkan status" required></td>
            </tr>
            <tr>
                <th>Sinopsis</th>
                <td><textarea name="sinopsis" placeholder="Masukkan sinopsis" required></textarea></td>
            </tr>
			<tr>
                <td colspan="2" style="text-align: center;">
                    <button type="submit" name="add_comic">Tambah Data</button>
                   
                </td>
            </tr>
        </table>
       
    </form>

    <hr>

    <!-- Menampilkan data komik -->
    <h3>Daftar Komik</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Gambar</th>
                <th>Penulis</th>
                <th>Genre</th>
                <th>Status</th>
                <th>Sinopsis</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['judul']); ?></td>
                    <td><img src="<?= $row['gambar']; ?>" width="100"></td>
                    <td><?= htmlspecialchars($row['penulis']); ?></td>
                    <td><?= htmlspecialchars($row['genre']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td><?= htmlspecialchars($row['sinopsis']); ?></td>
                    <td class="action-buttons">
                        <form action="komik.php" method="POST" style="display:inline;">
                            <button type="submit" name="delete" value="<?= $row['id']; ?>" class="btn-delete">Hapus</button>
                        </form>
                        <button class="btn-edit" onclick="editComic(<?= $row['id']; ?>)">Edit</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function editComic(id) {
            window.location.href = 'komik.php?id=' + id;
        }
    </script>
</body>
</html>

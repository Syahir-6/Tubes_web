<?php
include"koneksi.php";
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil semua data komik untuk dropdown
$komik_query = "SELECT id, judul FROM komik";
$komik_result = $conn->query($komik_query);

// Ambil data pencarian jika ada
$id_komik_filter = isset($_GET['id_komik_filter']) ? $_GET['id_komik_filter'] : '';

// Query dengan JOIN untuk mengambil nama komik dari tabel `komik`
$query = "SELECT halaman.id, halaman.id_komik, halaman.bab, halaman.halaman, halaman.gambar_halaman, komik.judul
          FROM halaman
          JOIN komik ON halaman.id_komik = komik.id";

if (!empty($id_komik_filter) && $id_komik_filter !== 'all') {
    $query .= " WHERE halaman.id_komik = '$id_komik_filter'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Halaman Komik</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            text-align: left;
            padding: 10px;
        }
        button {
			margin: 5px;
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        
        }
		.btn-edit {
            background-color: #007BFF;
			 border: none;
        }
        .btn-delete {
            background-color: #DC3545;
			 border: none;
        }
		.btn-tambah{
            background-color: #4a6670;
			margin-bottom:10px;
			 border: none;
        }
    </style>
</head>
<body>
<?php include "header_admin.php"?>
    <div style="max-width: 800px; margin: 20px auto;">
        <!-- Tombol Tambah -->
        <button  class="btn-tambah" onclick="window.location.href='tambah_episode.php'">Tambah Data</button>

        <!-- Form Pencarian -->
        <form method="GET" action="">
            <label for="id_komik_filter">Cari Komik:</label>
            <select name="id_komik_filter" id="id_komik_filter" onchange="this.form.submit()">
                <option value="all" <?= $id_komik_filter === 'all' || $id_komik_filter === '' ? 'selected' : ''; ?>>Semua</option>
                <?php if ($komik_result->num_rows > 0): ?>
                    <?php while ($komik = $komik_result->fetch_assoc()): ?>
                        <option value="<?= $komik['id']; ?>" <?= $id_komik_filter == $komik['id'] ? 'selected' : ''; ?>>
                            <?= $komik['judul']; ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </form>

        <!-- Tabel Halaman -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Komik</th>
                    <th>Nama Komik</th>
                    <th>Bab</th>
                    <th>Halaman</th>
                    <th>Gambar Halaman</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['id_komik']; ?></td>
                            <td><?= $row['judul']; ?></td>
                            <td><?= $row['bab']; ?></td>
                            <td><?= $row['halaman']; ?></td>
                            <td><img src="<?= $row['gambar_halaman']; ?>" width="100" height="100" alt="Gambar Halaman"></td>
                            <td>
                                <button class="btn-edit"onclick="window.location.href='edit_episode.php?id=<?= $row['id']; ?>'">Edit</button>
                                <button class="btn-delete"onclick="window.location.href='hapus_episode.php?id=<?= $row['id']; ?>'">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>

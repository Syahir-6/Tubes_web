<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'web';
$conn = new mysqli($host, $user, $pass, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
    die("ID halaman tidak ditemukan.");
}

// Hapus data
$query = "DELETE FROM halaman WHERE id = '$id'";
if ($conn->query($query)) {
	echo" Data berhasil dihapus";
    header("Location: episode.php?status=deleted");
    exit;
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

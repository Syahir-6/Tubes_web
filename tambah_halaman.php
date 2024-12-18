<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "web");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Variabel untuk pesan notifikasi
$message = "";

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_komik = $_POST['id_komik']; // Ambil ID komik dari form
    $bab = $_POST['bab'];           // Ambil bab dari form

    // Cek apakah ada file yang diupload
    if (isset($_FILES['gambar_halaman']['name']) && count($_FILES['gambar_halaman']['name']) > 0) {
        $success_count = 0; // Hitung jumlah halaman yang berhasil ditambahkan

        foreach ($_FILES['gambar_halaman']['tmp_name'] as $key => $tmp_name) {
            // Nama file dan direktori target
            $filename = $_FILES['gambar_halaman']['name'][$key];
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($filename);

            // Validasi upload file
            if (move_uploaded_file($tmp_name, $target_file)) {
                // Nomor halaman berdasarkan urutan file
                $halaman = $key + 1;

                // Simpan data ke database
                $sql = "INSERT INTO halaman (id_komik, bab, halaman, gambar_halaman) 
                        VALUES ('$id_komik', '$bab', '$halaman', '$target_file')";
                
                if ($conn->query($sql) === TRUE) {
                    $success_count++;
                } else {
                    $message .= "Error pada halaman ke-$halaman: " . $conn->error . "\\n";
                }
            } 
        }

        if ($success_count > 0) {
            $message .= "Berhasil menambahkan $success_count halaman ke episode baru!\\n";
        }
    } else {
        $message .= "Tidak ada file yang diupload.";
    }
} else {
    $message .= "Form tidak disubmit dengan benar.";
}

// Tutup koneksi database
$conn->close();

// Redirect dengan notifikasi
echo "<script>
    alert('$message');
    window.location.href = 'episode.php';
</script>";
?>

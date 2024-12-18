<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "web");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil daftar komik untuk dropdown
$komik_query = "SELECT id, judul FROM komik";
$komik_result = $conn->query($komik_query);

// Filter berdasarkan komik dan bab
$id_komik = isset($_GET['id_komik']) ? $_GET['id_komik'] : null;
$bab = isset($_GET['bab']) ? $_GET['bab'] : null;

$halaman_query = "SELECT * FROM halaman WHERE 1=1";
if ($id_komik) {
    $halaman_query .= " AND id_komik = '$id_komik'";
}
if ($bab) {
    $halaman_query .= " AND bab = '$bab'";
}
$halaman_query .= " ORDER BY halaman ASC";

$halaman_result = $conn->query($halaman_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilkan Halaman Komik</title>
    <style>
        .gambar-halaman {
            width: 150px;
            height: auto;
            margin: 10px;
        }
        .halaman-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
    </style>
</head>
<body>
    <h1>Tampilkan Halaman Komik</h1>

    <!-- Form Filter -->
    <form method="GET" action="tampilkan_halaman.php">
        <label for="id_komik">Pilih Komik:</label>
        <select name="id_komik" id="id_komik" onchange="this.form.submit()">
            <option value="">Semua Komik</option>
            <?php
            // Loop untuk dropdown judul komik
            while ($row = $komik_result->fetch_assoc()) {
                $selected = ($row['id'] == $id_komik) ? 'selected' : '';
                echo "<option value='{$row['id']}' $selected>{$row['judul']}</option>";
            }
            ?>
        </select>

        <label for="bab">Bab:</label>
        <input type="number" name="bab" id="bab" value="<?php echo htmlspecialchars($bab); ?>">
        <button type="submit">Filter</button>
    </form>

    <hr>

    <!-- Daftar Halaman -->
    <div class="halaman-container">
        <?php
        if ($halaman_result && $halaman_result->num_rows > 0) {
            while ($row = $halaman_result->fetch_assoc()) {
                echo "
                <div>
                    <p>Bab: {$row['bab']}, Halaman: {$row['halaman']}</p>
                    <img src='{$row['gambar_halaman']}' alt='Halaman {$row['halaman']}' class='gambar-halaman'>
                </div>";
            }
        } else {
            echo "<p>Tidak ada halaman untuk ditampilkan.</p>";
        }
        ?>
    </div>
</body>
</html>

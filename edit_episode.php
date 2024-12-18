<?php
// Koneksi ke database
include "koneksi.php";

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data berdasarkan ID
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
    die("ID halaman tidak ditemukan.");
}

$query = "SELECT * FROM halaman WHERE id = '$id'";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("Data tidak ditemukan.");
}

$data = $result->fetch_assoc();

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_komik = $_POST['id_komik'];
    $bab = $_POST['bab'];
    $halaman = $_POST['halaman'];
    $gambar_halaman = $data['gambar_halaman']; // Default gambar saat ini

    // Proses upload gambar jika ada
    if (isset($_FILES['gambar_halaman']) && $_FILES['gambar_halaman']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['gambar_halaman']['type'];
        $upload_dir = 'uploads/';
        $uploaded_file = $upload_dir . basename($_FILES['gambar_halaman']['name']);

        // Validasi jenis file
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['gambar_halaman']['tmp_name'], $uploaded_file)) {
                $gambar_halaman = $uploaded_file;
            } else {
                $error_message = "Gagal mengunggah gambar.";
            }
        } else {
            $error_message = "Format file tidak didukung. Gunakan JPG, PNG, atau GIF.";
        }
    }

    // Update data
    $update_query = "UPDATE halaman 
                     SET id_komik = '$id_komik', bab = '$bab', halaman = '$halaman', gambar_halaman = '$gambar_halaman' 
                     WHERE id = '$id'";

    if ($conn->query($update_query)) {
        echo "Data berhasil diperbarui";
        header("Location: episode.php?status=success"); 
        exit;
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Halaman</title>
    <style>
        /* CSS Global */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        /* Frame untuk konten utama */
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			
        }


        /* Notifikasi Error */
        .error-message {
            color: red;
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* Form */
        form {
            display: flex;
            flex-direction: column;
           gap:20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 0px;
        }

        input[type="text"],
        input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
			max-width : 2000px;
        }

        input[type="file"] {
            padding: 5px;
        }

        /* Button Submit */
        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4a6670;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color:  #FFD700;
        }

        /* Gambar Halaman */
        img {
            border-radius: 8px;
            margin-top: 10px;
        }

        /* Styling untuk label dan form */
        input, label {
            width: 100%;
        }

        /* Pesan Berhasil */
        .success-message {
            color: green;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include "header_admin.php"; ?>
    <div class="container">
        <h2>Edit Halaman</h2>

        <!-- Tampilkan Notifikasi berdasarkan URL -->
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error_message); ?>
            </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="success-message">
                Data berhasil diperbarui!
            </div>
        <?php endif; ?>

        <!-- Form Edit -->
        <form method="POST" enctype="multipart/form-data">
    <div class="label-input-container">
        <label for="id_komik">ID Komik:</label>
        <input type="text" name="id_komik" id="id_komik" value="<?= htmlspecialchars($data['id_komik']); ?>" required><br><br>
    </div>

    <div class="label-input-container">
        <label for="bab">Bab:</label>
        <input type="text" name="bab" id="bab" value="<?= htmlspecialchars($data['bab']); ?>" required><br><br>
    </div>

    <div class="label-input-container">
        <label for="halaman">Halaman:</label>
        <input type="text" name="halaman" id="halaman" value="<?= htmlspecialchars($data['halaman']); ?>" required><br><br>
    </div>

    
        <label for="gambar_halaman">Gambar Halaman:</label>
        <?php if (!empty($data['gambar_halaman'])): ?>
            <img src="<?= htmlspecialchars($data['gambar_halaman']); ?>" width="150" height="150" alt="Gambar Halaman"><br>
        <?php endif; ?>
        <input type="file" name="gambar_halaman" id="gambar_halaman"><br><br>
 

    <button type="submit">Simpan Perubahan</button>
</form>

    </div>
</body>
</html>

<?php $conn->close(); ?>

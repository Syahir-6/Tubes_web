<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "web");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_komik = $_POST['id_komik'];
    $bab = $_POST['bab'];
    
    foreach ($_FILES['gambar_halaman']['tmp_name'] as $key => $tmp_name) {
        $target_dir = "uploads/";
        $gambar_halaman = $target_dir . basename($_FILES["gambar_halaman"]["name"][$key]);
        
        if (move_uploaded_file($tmp_name, $gambar_halaman)) {
            $halaman = $key + 1;
            $sql = "INSERT INTO halaman (id_komik, bab, halaman, gambar_halaman) 
                    VALUES ('$id_komik', '$bab', '$halaman', '$gambar_halaman')";
            
            if (!$conn->query($sql)) {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Gagal mengupload gambar: " . $_FILES["gambar_halaman"]["name"][$key];
        }
    }
    echo "Halaman berhasil ditambahkan!";
}

// Ambil daftar komik untuk dropdown
$komik_query = "SELECT id, judul FROM komik";
$komik_result = $conn->query($komik_query);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Halaman Komik</title>
    <style>
        body {
            font-family: cambria;
            background-color: #f4f4f9;
            margin: 0;
        }
        header {
            width: 100%;
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 24px;
            font-weight: bold;
        }
        .frame {
            width: 400px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 30px auto;
            position: relative;
        }
        .frame h1 {
            margin: 0;
            font-size: 20px;
            text-align: center;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 10px auto 0;
        }
        button:hover {
            background: #0056b3;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        select, input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        #upload-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .upload-box {
            width: 100%;
            height: 150px;
            border: 2px dashed #ccc;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            cursor: pointer;
            position: relative;
        }
        .upload-box input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        .upload-box img {
            max-width: 90%;
            max-height: 100px;
            margin-bottom: 5px;
        }
        .upload-box span {
            font-size: 24px;
            color: #888;
        }
    </style>
    <script>
        function previewImage(input) {
            const file = input.files[0];
            const parentBox = input.parentElement;

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    let imgPreview = parentBox.querySelector("img");
                    if (!imgPreview) {
                        imgPreview = document.createElement("img");
                        parentBox.prepend(imgPreview);
                    }
                    imgPreview.src = e.target.result;
                    parentBox.querySelector("span").style.display = 'none';
                    
                    addUploadBoxIfNeeded(parentBox);
                };

                reader.readAsDataURL(file);
            }
        }

        function addUploadBoxIfNeeded(currentBox) {
            const container = document.getElementById('upload-container');

            const emptyBoxExists = Array.from(container.children).some(box => {
                return !box.querySelector('img') && box.querySelector('span').style.display !== 'none';
            });

            if (!emptyBoxExists) {
                const uploadBox = document.createElement('div');
                uploadBox.className = 'upload-box';
                uploadBox.innerHTML = `
                    <span>+</span>
                    <input type="file" name="gambar_halaman[]" accept="image/*" onchange="previewImage(this);">
                `;
                container.appendChild(uploadBox);
            }
        }
    </script>
</head>
<body>
    <!-- Header -->
    <?php include "header_admin.php"; ?>

    <div class="frame">
        <h1>Tambah Halaman Komik</h1>
        <form id="tambah-form" action="tambah_halaman.php" method="POST" enctype="multipart/form-data">
            <label for="id_komik">Komik:</label>
            <select name="id_komik" id="id_komik" required>
                <option value="">Pilih Komik</option>
                <?php while ($row = $komik_result->fetch_assoc()): ?>
                    <option value="<?= $row['id']; ?>"><?= $row['judul']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="bab">Bab:</label>
            <input type="text" name="bab" id="bab" required>

            <label for="gambar_halaman">Gambar Halaman:</label>
            <div id="upload-container">
                <div class="upload-box">
                    <span>+</span>
                    <input type="file" name="gambar_halaman[]" accept="image/*" onchange="previewImage(this);">
                </div>
            </div>

            <button type="submit">Tambah Halaman</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();
include 'koneksi.php';
// Pastikan user telah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}



if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Menambahkan data pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username_input = $_POST['username'];
    $password =$_POST['password'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, role) VALUES ('$username_input', '$password', '$role')";
    if ($conn->query($sql)) {
        echo "<script>alert('Data berhasil ditambahkan');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data');</script>";
    }
}



// Mengupdate data pengguna
// Menghapus data pengguna
if (isset($_GET['delete'])) { // Ubah $_POST menjadi $_GET
    $id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE id='$id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Data berhasil dihapus');</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $conn->error . "');</script>";
    }
}

// Menghapus data pengguna
if (isset($_POST['delete'])) {
    $id = $_POST['delete'];
    $sql = "DELETE FROM users WHERE id='$id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Data berhasil dihapus');</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
}

// Mengambil semua data pengguna
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengguna</title>
    <link rel="stylesheet" href="style_b.css">
    <style>
 
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
				/* Menempatkan tombol login di kanan atas */
		.login-btn-container {
			position: absolute;
			top: 20px;
			right: 20px;
		}

		.login-btn {
			padding: 10px 20px;
			background-color: black;
			color: white;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			font-size: 16px;
		}

		.login-btn:hover {
			background-color: #FFD700;
		}
		.btn-tambah{
			margin: 5px;
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            background-color: #4a6670;
			margin-bottom:10px;
			border: none;
        }

    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userName = document.querySelector('.user-name');
            const userMenu = document.querySelector('.user-menu');

            userName.addEventListener('mouseover', function () {
                userMenu.style.display = 'block';
            });

            userName.addEventListener('mouseleave', function () {
                userMenu.style.display = 'none';
            });
        });

        // Fungsi untuk mengisi form dengan data yang dipilih pada tombol edit
        function editUser(id, username, password, role) {
            document.querySelector('[name="id"]').value = id;
            document.querySelector('[name="username"]').value = username;
            document.querySelector('[name="password"]').value = password;
            document.querySelector('[name="role"]').value = role;
            document.querySelector('[name="update_user"]').style.display = 'inline';
            document.querySelector('[name="add_user"]').style.display = 'none';
        }

        // Fungsi untuk konfirmasi penghapusan data
       function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = 'beranda_admin.php?delete=' + id; // Kirim ID via query string
    }
}

    </script>
</head>
<body>
<?php include"header_admin.php"?>
    <!-- Form input data -->
    <form method="POST" action="beranda_admin.php" style="max-width: 800px; margin: 0 auto;">
        <table>
            <tr>
                <th>ID (kosongkan untuk tambah data)</th>
                <td><input type="text" name="id" placeholder="Masukkan ID (opsional)"></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><input type="text" name="username" placeholder="Masukkan username" required></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" name="password" placeholder="Masukkan password" required></td>
            </tr>
            <tr>
                <th>Role</th>
                <td>
                    <select name="role" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button class="btn-tambah" type="submit" name="add_user">Tambah Data</button>
                    <button class="btn-tambah" type="submit" name="update_user" style="display: none;">Perbarui Data</button>
                </td>
            </tr>
        </table>
    </form>

    <!-- Tabel Data Pengguna -->
    <div style="max-width: 800px; margin: 20px auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['username']; ?></td>
                            <td><?= $row['password']; ?></td>
                            <td><?= $row['role']; ?></td>
                            <td class="action-buttons">
                                <button class="btn-edit" onclick="editUser('<?= $row['id']; ?>', '<?= $row['username']; ?>', '<?= $row['password']; ?>', '<?= $row['role']; ?>')">Edit</button>
                                <button class="btn-delete" onclick="confirmDelete('<?= $row['id']; ?>')">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada data pengguna</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>

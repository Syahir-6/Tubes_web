<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LioS</title>
    <style>  
        .profile-header {
            position: relative;
            height: 400px;
            background-image: url('latar.jpg'); 
            background-size: cover;
            background-position: center;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.2);
        }
        .profile-image {
            position: absolute;
            bottom: -50px;
            left: 100px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        .profile-image:hover {
            transform: scale(1.1); /* Membesar sedikit saat di-hover */
        }
		 .bio-frame h2 {
            margin-top: 100px;
            color: #333;
        }
        .edit-profile {
            position: absolute;
            bottom: -70px;
            right: 250px;
            background-color: #4a6670;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        .edit-profile:hover {
            background-color: #3b4f56;
            transform: scale(1.05); /* Membesar sedikit */
        }
        .bio-frame {
            margin: -50px auto 20px auto;
            padding: 30px 20px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #D4DBDA;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.2);
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            animation: fadeIn 0.5s;
        }
        .edit-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            z-index: 1001;
            width: 300px;
            text-align: center;
            animation: slideDown 0.5s ease-out;
        }
        .edit-form input,
        .edit-form textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .edit-form button {
            padding: 10px;
            border: none;
            background-color: #4a6670;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .edit-form button:hover {
            background-color: #3b4f56;
        }
        .edit-form img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid #4a6670;
        }

        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideDown {
            from {
                transform: translate(-50%, -60%);
                opacity: 0;
            }
            to {
                transform: translate(-50%, -50%);
                opacity: 1;
            }
        }
		 .settings-menu {
            margin: 30px;
			margin-left:200px;
            padding: 10px;
           
           
        }
        .settings-menu h3 {
            margin-bottom: 10px;
        }
        .settings-menu a {
            display: block;
            color: #4a6670;
            text-decoration: none;
            padding: 5px 0;
        }
        .settings-menu a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <?php include"header.php"; ?>
    </header>


    <!-- Profile Section -->
    <div class="profile-header">
        <img src="fprofil.jpg" alt="Foto Profil" class="profile-image" id="profileImage">
        <button class="edit-profile" onclick="openEditForm()">Edit Profil</button>
    </div>

    <!-- Bio Section -->
    <div class="bio-frame">
        <h2 id="profileName">Lios</h2>
        <p id="profileTag">@Li_</p>
        <p id="profileBio">dont ask lng me, "do i have friends"<br>Its just me and my dragon.<br>Roaaaarrr, dragon.</p>
    </div>

    <!-- Overlay Form -->
    <div class="overlay" id="overlay">
        <div class="edit-form">
            <h3>Edit Profil</h3>
            <label for="photoInput">
                <img src="fprofil.jpg" id="editImagePreview" alt="Foto Profil">
            </label>
            <input type="file" id="photoInput" accept="image/*" onchange="previewImage(event)">
            <input type="text" id="nameInput" placeholder="Nama" value="Lios">
            <input type="text" id="tagInput" placeholder="Tag" value="@Li_">
            <textarea id="bioInput" placeholder="Bio">dont ask lng me, "do i have friends"
Its just me and my dragon.
Roaaaarrr, dragon.</textarea>
            <button onclick="saveProfile()">Simpan</button>
            <button onclick="closeEditForm()">Batal</button>
        </div>
    </div>
<div class="settings-menu">
        <h3>Setting</h3>
        <a href="#">Privasi dan Security</a>
        <a href="#">Pengaturan Akun</a>
		<a href="logout.php" style="color:red;">Keluar</a>
    </div>
    <script>
        function openEditForm() {
            document.getElementById("overlay").style.display = "block";
        }

        function closeEditForm() {
            document.getElementById("overlay").style.display = "none";
        }

        function saveProfile() {
            const name = document.getElementById("nameInput").value;
            const tag = document.getElementById("tagInput").value;
            const bio = document.getElementById("bioInput").value;
            const imageSrc = document.getElementById("editImagePreview").src;

            document.getElementById("profileName").innerText = name;
            document.getElementById("profileTag").innerText = tag;
            document.getElementById("profileBio").innerText = bio;
            document.getElementById("profileImage").src = imageSrc;

            closeEditForm();
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById("editImagePreview");
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>

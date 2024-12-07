<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "syahir_60900122024";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql = "SELECT * FROM mahasiswa";
$result = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
            </tr>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>
                <td>" . $row['nim'] . "</td>
                <td>" . $row['nama'] . "</td>
                <td>" . $row['email'] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data.";
}

mysqli_close($koneksi);
?>
<?php
session_start(); // Memulai sesi
include('../connection.php');

// Cek apakah user sudah login, jika belum, arahkan ke login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy(); // Menghancurkan sesi
    header("Location: ../login.php"); // Arahkan ke halaman login
    exit();
}

// Mengambil data barang dari tabel stockbar di database stock
$query = "SELECT * FROM stockbar"; // Pastikan tabelnya bernama stockbar dan memiliki kolom 'nama_barang'
$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die('Query gagal: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Stock</title>
    <link rel="stylesheet" href="liststock.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Stock Manager</h2>
            </div>
            <div class="menu">
                <a href="../listbar/listbar.php">
                    <button class="menu-btn">List Barang</button>
                </a>
                <a href="../listbar/tambar.php">
                    <button class="menu-btn">Tambah Barang</button>
                </a>
                <button class="menu-btn">List Stock</button>
                <a href="tamstock.php">
                    <button class="menu-btn">Tambah Stock</button>
                </a>
                <a href="stockkel.php">
                    <button class="menu-btn">Stock Keluar</button>
                </a>
                <a href="../rep/report.php">
                    <button class="menu-btn">Laporan</button>
                </a>
            </div>
            <form method="POST">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2>List Stock</h2>
            <table class="barang-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Dus</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menghitung nomor urut
                    $no = 1;
                    // Menampilkan setiap baris data barang dari hasil query
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['nambar']) . "</td>"; // Menampilkan nama_barang
                        echo "<td>" . htmlspecialchars($row['dus']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['harbel']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['harjul']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

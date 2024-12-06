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

// Tambah barang ke database
if (isset($_POST['tambah_barang'])) {
    $nama_barang = $_POST['nama_barang'];
    $jumlah_barang = $_POST['jumlah_barang'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];

    // Query untuk memasukkan data ke dalam tabel stockbar
    $query = "INSERT INTO stockbar (nambar, pcs, harbel, harjul) VALUES ('$nama_barang', '$jumlah_barang', '$harga_beli', '$harga_jual')";
    
    if (mysqli_query($conn, $query)) {
        $message = "Barang berhasil ditambahkan!";
        echo "<script type='text/javascript'>alert('$message');</script>"; // Popup pemberitahuan
    } else {
        $message = "Gagal menambahkan barang: " . mysqli_error($conn);
        echo "<script type='text/javascript'>alert('$message');</script>"; // Popup pemberitahuan gagal
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah List Barang</title>
    <link rel="stylesheet" href="tambar.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Stock Manager</h2>
            </div>
            <div class="menu">
                <a href="listbar.php">
                    <button class="menu-btn">List Barang</button>
                </a>
                <button class="menu-btn">Tambah Barang</button>
                <a href="../liststock/liststock.php">
                    <button class="menu-btn">List Stock</button>
                </a>
                <a href="../liststock/tamstock.php">
                    <button class="menu-btn">Tambah Stock</button>
                </a>
                <a href="../liststock/stockkel.php">
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
            <div class="top-bar">
                <h1>Tambah Barang</h1>
            </div>
            <div class="content">
                <form action="" method="POST">
                    <div class="input-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" id="nama_barang" name="nama_barang" required>
                    </div>
                    <div class="input-group">
                        <label for="jumlah_barang">Jumlah Barang per Box</label>
                        <input type="number" id="jumlah_barang" name="jumlah_barang" required>
                    </div>
                    <div class="input-group">
                        <label for="harga_beli">Harga Beli</label>
                        <input type="number" id="harga_beli" name="harga_beli" required>
                    </div>
                    <div class="input-group">
                        <label for="harga_jual">Harga Jual</label>
                        <input type="number" id="harga_jual" name="harga_jual" required>
                    </div>
                    <button type="submit" name="tambah_barang" class="tambah-btn">Tambah Barang</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

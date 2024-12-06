<?php
session_start();
include('../connection.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang</title>
    <link rel="stylesheet" href="report.css?v=1">
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
            <a href="liststock.php">
                <button class="menu-btn">List Stock</button>
            </a>
            <button class="menu-btn">Tambah Stock</button>
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
        <h2>Laporan Barang</h2>
        <div class="button-container">
            <a href="laporan_masuk.php">
                <button class="report-btn">Laporan Barang Masuk</button>
            </a>
            <a href="laporan_keluar.php">
                <button class="report-btn">Laporan Barang Keluar</button>
            </a>
        </div>
    </div>
</div>
</body>
</html>

<?php
session_start(); // Memulai sesi
include('connection.php');

// Cek apakah user sudah login, jika belum, arahkan ke login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil username dari session
$username = $_SESSION['username']; // Ambil username dari session

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy(); // Menghancurkan sesi
    header("Location: login.php"); // Arahkan ke halaman login
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="default.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Stock Manager</h2>
            </div>
            <div class="menu">
                <a href="listbar/listbar.php">
                    <button class="menu-btn">List Barang</button>
                </a>
                <a href="listbar/tambar.php">
                    <button class="menu-btn">Tambah Barang</button>
                </a>
                <a href="liststock/liststock.php">
                    <button class="menu-btn">List Stock</button>
                </a>
                <a href="liststock/tamstock.php">
                    <button class="menu-btn">Tambah Stock</button>
                </a>
                <a href="liststock/stockkel.php">
                    <button class="menu-btn">Stock Keluar</button>
                </a>
                <a href="rep/report.php">
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
                <h1>Welcome to the Dashboard</h1>
            </div>
            <div class="content">
                <p>Selamat datang di Dashboard, <strong><?php echo htmlspecialchars($username); ?></strong>.</p>
                <!-- You can add more content here -->
            </div>
        </div>
    </div>
</body>
</html>

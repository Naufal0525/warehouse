<?php
session_start();
include('../connection.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil data barang dari database
$query = "SELECT nambar FROM stockbar";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

// Proses saat tombol Tambah Barang diklik
if (isset($_POST['tambah_barang'])) {
    $nama_barang = $_POST['nama_barang'];
    $jumlah_barang = intval($_POST['jumlah_barang']); // Pastikan jumlah barang berupa angka

    // Validasi input
    if (!empty($nama_barang) && $jumlah_barang > 0) {
        // Tambahkan data ke tabel masuk
        $query_insert_masuk = "INSERT INTO masuk (nambar, jumbar) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query_insert_masuk);
        mysqli_stmt_bind_param($stmt, "si", $nama_barang, $jumlah_barang);
        $execute_insert_masuk = mysqli_stmt_execute($stmt);

        // Perbarui jumlah dus di tabel stockbar
        $query_update_stockbar = "UPDATE stockbar SET dus = dus + ? WHERE nambar = ?";
        $stmt_update = mysqli_prepare($conn, $query_update_stockbar);
        mysqli_stmt_bind_param($stmt_update, "is", $jumlah_barang, $nama_barang);
        $execute_update_stockbar = mysqli_stmt_execute($stmt_update);

        // Cek apakah kedua operasi berhasil
        if ($execute_insert_masuk && $execute_update_stockbar) {
            echo "<script>alert('Stock barang berhasil ditambahkan!'); window.location.href = 'liststock.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat menambahkan stock.');</script>";
        }
    } else {
        echo "<script>alert('Data input tidak valid.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Stock Barang</title>
    <link rel="stylesheet" href="tamstock.css">
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
        <h2>Tambah Stock Barang</h2>
        <form action="" method="POST">
            <!-- Dropdown Nama Barang -->
            <div class="input-group">
                <label for="nama_barang">Nama Barang:</label>
                <select id="nama_barang" name="nama_barang" required>
                    <option value="" disabled selected>Pilih Barang</option>
                    <?php
                    // Looping untuk menampilkan data barang dalam dropdown
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . htmlspecialchars($row['nambar']) . '">' . htmlspecialchars($row['nambar']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <!-- Input Jumlah Barang -->
            <div class="input-group">
                <label for="jumlah_barang">Jumlah Barang Masuk (Dus):</label>
                <input type="number" id="jumlah_barang" name="jumlah_barang" required>
            </div>
            <button type="submit" name="tambah_barang" class="tambah-btn">Tambah Barang</button>
        </form>
    </div>
</div>
</body>
</html>

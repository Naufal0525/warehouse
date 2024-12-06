<?php
session_start();
include('../connection.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil data barang dari tabel stockbar
$query = "SELECT * FROM stockbar";
$result = mysqli_query($conn, $query);

// Cek apakah form telah disubmit
if (isset($_POST['tambah_barang'])) {
    $nama_barang = $_POST['nama_barang'];
    $jumlah_barang = intval($_POST['jumlah_barang']);

    // Validasi input
    if (!empty($nama_barang) && $jumlah_barang > 0) {
        // Masukkan data ke tabel keluar
        $insert_keluar = "INSERT INTO keluar (nambar, jumbar) VALUES ('$nama_barang', $jumlah_barang)";
        $execute_insert = mysqli_query($conn, $insert_keluar);

        if ($execute_insert) {
            // Kurangi jumlah barang di tabel stockbar
            $update_stockbar = "UPDATE stockbar SET dus = dus - $jumlah_barang WHERE nambar = '$nama_barang'";
            $execute_update = mysqli_query($conn, $update_stockbar);

            if ($execute_update) {
                // Notifikasi berhasil dengan redirect ke liststock.php
                echo "<script>
                        alert('Data berhasil disimpan dan stok berhasil diperbarui!');
                        window.location.href = 'liststock.php';
                      </script>";
                exit();
            } else {
                echo "<script>alert('Gagal memperbarui stok barang.');</script>";
            }
        } else {
            echo "<script>alert('Gagal menyimpan data.');</script>";
        }
    } else {
        echo "<script>alert('Input tidak valid.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Keluar</title>
    <link rel="stylesheet" href="stockkel.css ?v=1s">
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <h2>Stock Manager</h2>
        </div>
        <div class="menu">
            <a href="../listbar/listbar.php"><button class="menu-btn">List Barang</button></a>
            <a href="../listbar/tambar.php"><button class="menu-btn">Tambah Barang</button></a>
            <a href="liststock.php"><button class="menu-btn">List Stock</button></a>
            <a href="tamstock.php"><button class="menu-btn">Tambah Stock</button></a>
            <button class="menu-btn">Stock Keluar</button>
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
        <h2>Stock Keluar</h2>
        <form action="" method="POST">
            <!-- Dropdown Nama Barang -->
            <div class="input-group">
                <label for="nama_barang">Nama Barang:</label>
                <select id="nama_barang" name="nama_barang" required>
                    <option value="" disabled selected>Pilih Barang</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . htmlspecialchars($row['nambar']) . '">' . htmlspecialchars($row['nambar']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Input Jumlah Barang -->
            <div class="input-group">
                <label for="jumlah_barang">Jumlah Barang Keluar (Dus):</label>
                <input type="number" id="jumlah_barang" name="jumlah_barang" required min="1">
            </div>

            <!-- Tombol Submit -->
            <button type="submit" name="tambah_barang" class="tambah-btn">Barang Keluar</button>
        </form>
    </div>
</div>
</body>
</html>

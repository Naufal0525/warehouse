<?php
session_start();
include('../connection.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Periksa apakah ada id_barang yang dikirimkan melalui GET
if (isset($_GET['id_barang'])) {
    $id_barang = $_GET['id_barang'];

    // Mengambil data barang berdasarkan id
    $query = "SELECT * FROM stockbar WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_barang);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Tampilkan form untuk mengedit barang
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Barang</title>
            <link rel="stylesheet" href="edit.css">
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
                <a href="tambar.php">
                    <button class="menu-btn">Tambah Barang</button>
                </a>
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
        <div class="main-edit">
            <h2>Edit Barang</h2>
            <form action="updatebarang.php" method="POST">
                <input type="hidden" name="id_barang" value="<?php echo htmlspecialchars($row['id']); ?>">
                <label for="nambar">Nama Barang:</label>
                <input type="text" name="nambar" value="<?php echo htmlspecialchars($row['nambar']); ?>"><br>
                <label for="pcs">Isi per Box:</label>
                <input type="text" name="pcs" value="<?php echo htmlspecialchars($row['pcs']); ?>"><br>
                <label for="harbel">Harga Beli:</label>
                <input type="text" name="harbel" value="<?php echo htmlspecialchars($row['harbel']); ?>"><br>
                <label for="harjul">Harga Jual:</label>
                <input type="text" name="harjul" value="<?php echo htmlspecialchars($row['harjul']); ?>"><br>
                <button type="submit">Update</button>
            </form>
        </div>
        </body>
        </html>
        <?php
    } else {
        echo "Barang tidak ditemukan.";
    }
} else {
    echo "ID Barang tidak ditemukan.";
}
?>

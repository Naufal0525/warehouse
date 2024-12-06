<?php
session_start();
include('../connection.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Periksa apakah ada data yang dikirimkan melalui POST
if (isset($_POST['id_barang'])) {
    $id_barang = $_POST['id_barang'];
    
    // Menghapus barang berdasarkan id
    $query = "DELETE FROM stockbar WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_barang);
    
    if (mysqli_stmt_execute($stmt)) {
        // Redirect ke listbar.php setelah berhasil menghapus barang
        header("Location: listbar.php");
        exit();
    } else {
        echo "Gagal menghapus barang.";
    }
}
?>

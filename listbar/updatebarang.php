<?php
session_start();
include('../connection.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Periksa apakah form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_barang = $_POST['id_barang'];
    $nambar = $_POST['nambar'];
    $pcs = $_POST['pcs'];
    $harbel = $_POST['harbel'];
    $harjul = $_POST['harjul'];

    // Perbarui data barang di database
    $query = "UPDATE stockbar SET nambar = ?, pcs = ?, harbel = ?, harjul = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sdddi", $nambar, $pcs, $harbel, $harjul, $id_barang);

    if (mysqli_stmt_execute($stmt)) {
        // Berhasil diperbarui, arahkan ke halaman listbar.php
        header("Location: listbar.php");
        exit();
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
} else {
    echo "Permintaan tidak valid.";
}
?>

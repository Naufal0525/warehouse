<?php
session_start();
include('../connection.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

// Ambil filter dari form
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$nama_barang = isset($_GET['nama_barang']) ? $_GET['nama_barang'] : '';

// Modifikasi query untuk filter
$query = "SELECT nambar, jumbar, tanggal FROM keluar WHERE 1=1";
if ($bulan != '') {
    $query .= " AND MONTH(tanggal) = '$bulan'";
}
if ($nama_barang != '') {
    $query .= " AND nambar = '$nama_barang'";
}
$result = mysqli_query($conn, $query);

// Memproses unduhan laporan Excel
if (isset($_POST['download_excel'])) {
    require_once '../vendor/autoload.php';
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $sheet->setCellValue('A1', 'No.');
    $sheet->setCellValue('B1', 'Nama Barang');
    $sheet->setCellValue('C1', 'Jumlah Barang');
    $sheet->setCellValue('D1', 'Tanggal Keluar');

    // Menulis data ke Excel
    $rowNum = 2;
    $no = 1;
    $totals = []; // Array untuk menghitung total jumlah per barang

    while ($row = mysqli_fetch_assoc($result)) {
        $sheet->setCellValue('A' . $rowNum, $no++);
        $sheet->setCellValue('B' . $rowNum, $row['nambar']);
        $sheet->setCellValue('C' . $rowNum, $row['jumbar']);
        $sheet->setCellValue('D' . $rowNum, $row['tanggal']);

        // Hitung total jumlah barang per nama barang
        if (isset($totals[$row['nambar']])) {
            $totals[$row['nambar']] += $row['jumbar'];
        } else {
            $totals[$row['nambar']] = $row['jumbar'];
        }

        $rowNum++;
    }

    // Tambahkan laporan total jumlah barang
    $sheet->setCellValue('A' . $rowNum, 'Laporan Total');
    $sheet->mergeCells("A$rowNum:D$rowNum");
    $sheet->getStyle("A$rowNum")->getFont()->setBold(true);
    $rowNum++;

    $sheet->setCellValue('B' . $rowNum, 'Nama Barang');
    $sheet->setCellValue('C' . $rowNum, 'Total Jumlah');
    $sheet->getStyle("B$rowNum:C$rowNum")->getFont()->setBold(true);
    $rowNum++;

    foreach ($totals as $nambar => $total) {
        $sheet->setCellValue('B' . $rowNum, $nambar);
        $sheet->setCellValue('C' . $rowNum, $total);
        $rowNum++;
    }

    // Menyimpan file Excel
    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $fileName = 'Laporan_Barang_Keluar.xlsx';

    // Mengirim file ke browser untuk diunduh
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    $writer->save('php://output');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Keluar</title>
    <link rel="stylesheet" href="keluar.css?v=1">
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
            <a href="../liststock/liststock.php">
                <button class="menu-btn">List Stock</button>
            </a>
            <a href="../liststock/tamstock.php">
                <button class="menu-btn">Tambah Stock</button>
            </a>
            <a href="../liststock/stockkel.php">
                <button class="menu-btn">Stock Keluar</button>
            </a>
            <a href="report.php">
                <button class="menu-btn">Laporan</button>
            </a>
        </div>
        <form method="POST">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <h2>Laporan Barang Keluar</h2>
        
        <!-- Form Filter -->
        <form method="GET" style="text-align: center; margin-bottom: 20px;">
            <label for="bulan">Pilih Bulan:</label>
            <select name="bulan" id="bulan">
                <option value="">Semua Bulan</option>
                <?php
                for ($i = 1; $i <= 12; $i++) {
                    $bulanOption = str_pad($i, 2, "0", STR_PAD_LEFT);
                    $selected = ($bulanOption == $bulan) ? 'selected' : '';
                    echo "<option value='$bulanOption' $selected>" . date("F", mktime(0, 0, 0, $i, 10)) . "</option>";
                }
                ?>
            </select>
            
            <label for="nama_barang">Nama Barang:</label>
            <select name="nama_barang" id="nama_barang">
                <option value="">Semua Barang</option>
                <?php
                $barangQuery = "SELECT DISTINCT nambar FROM keluar";
                $barangResult = mysqli_query($conn, $barangQuery);
                while ($barang = mysqli_fetch_assoc($barangResult)) {
                    $selected = ($barang['nambar'] == $nama_barang) ? 'selected' : '';
                    echo "<option value='" . $barang['nambar'] . "' $selected>" . $barang['nambar'] . "</option>";
                }
                ?>
            </select>
            
            <button type="submit" class="filter-btn">Terapkan Filter</button>
        </form>
        
        <!-- Tombol Unduh Laporan -->
        <form method="POST" style="text-align: center; margin-bottom: 20px;">
            <button type="submit" name="download_excel" class="download-btn">Unduh Laporan</button>
        </form>
        
        <!-- Tabel Data -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Barang</th>
                    <th>Tanggal Keluar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                mysqli_data_seek($result, 0); // Reset hasil query untuk ditampilkan di tabel
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $row['nambar'] . "</td>";
                    echo "<td>" . $row['jumbar'] . "</td>";
                    echo "<td>" . $row['tanggal'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

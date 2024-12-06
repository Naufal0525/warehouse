<?php
// Menghubungkan ke file connection.php
include('connection.php');

// Pesan status untuk registrasi
$statusMessage = "";

// Proses form jika tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi data (misalnya: pastikan password cukup panjang)
    if (strlen($password) < 6) {
        $statusMessage = "Password harus terdiri dari minimal 6 karakter.";
    } else {
        // Hash password sebelum disimpan (untuk keamanan)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk menyimpan data ke dalam database
        $sql = "INSERT INTO user (nama, password) VALUES (?, ?)";

        // Siapkan statement
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            // Jika statement gagal disiapkan
            $statusMessage = "Terjadi kesalahan saat mempersiapkan query.";
        } else {
            // Bind parameter ke statement
            $stmt->bind_param("ss", $username, $hashedPassword); // "ss" menunjukkan bahwa keduanya adalah string

            // Eksekusi query
            if ($stmt->execute()) {
                $statusMessage = "Registrasi berhasil!";
                $redirectPage = "login.php"; // Halaman untuk dialihkan setelah sukses
            } else {
                $statusMessage = "Terjadi kesalahan saat mendaftar.";
                $redirectPage = "register.php"; // Tetap di halaman register jika gagal
            }

            // Tutup statement setelah eksekusi
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Register</title>
    <link rel="stylesheet" href="reg.css">
</head>
<body>

    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="register.php">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>

        <!-- Tombol Back yang mengarah ke login.php -->
        <a href="login.php" class="back-button">Back to Login</a>
    </div>

    <!-- Jika ada pesan status, tampilkan pesan popup menggunakan JavaScript -->
    <?php if ($statusMessage): ?>
    <script type="text/javascript">
        alert("<?php echo $statusMessage; ?>");
        window.location.href = "<?php echo $redirectPage; ?>"; // Pengalihan setelah klik "OK"
    </script>
    <?php endif; ?>

</body>
</html>

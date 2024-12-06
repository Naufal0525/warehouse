<?php
session_start(); // Untuk mengelola sesi login

// Cek apakah user sudah login, jika ya, arahkan ke index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Sertakan file koneksi
include('connection.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Menggunakan prepared statement untuk menghindari SQL injection
    $sql = "SELECT * FROM user WHERE nama = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // Parameter jenis string
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Mengecek apakah username ditemukan dan password sesuai
    if ($user && password_verify($password, $user['password'])) {
        // Jika login berhasil, set session dan arahkan ke index.php
        $_SESSION['user_id'] = $user['id']; // Simpan id user ke session
        $_SESSION['username'] = $user['nama']; // Menyimpan username di session
        header("Location: index.php");
        exit;
    } else {
        // Jika login gagal, tampilkan pesan error
        echo "<script>alert('Username atau password salah');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Halaman Login</h1>
        <form action="login.php" method="post">
            <ul>
                <li>
                    <label for="username">Username :</label>
                    <input type="text" name="username" id="username">
                </li>
                <li>
                    <label for="password">Password :</label>
                    <input type="password" name="password" id="password">
                </li>
                <li>
                    <button type="submit" name="login">Login</button>
                </li>
                <li>
                    <a href="register.php">
                        <button type="button" class="register-btn">Register</button>
                    </a>
                </li>
            </ul>
        </form>

        <?php
    // Tampilkan pesan error jika login gagal
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    ?>

    </div>
</body>
</html>

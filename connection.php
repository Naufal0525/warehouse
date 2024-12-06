<?php
// connection.php
$servername = "localhost"; // atau sesuaikan dengan host server Anda
$username = "root"; // atau sesuaikan dengan username database Anda
$password = ""; // atau sesuaikan dengan password database Anda
$dbname = "stock"; // ganti dengan nama database Anda

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
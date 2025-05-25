<?php
$host = "localhost"; // Host database
$user = "root";      // Username database
$pass = "";          // Password database
$db   = "biblioffee_a"; // Nama database

$conn = new mysqli($host, $user, $pass, $db); // Membuat koneksi baru ke MySQL dengan mysqli

if ($conn->connect_error) {
    // Jika koneksi gagal, hentikan script dan tampilkan pesan error
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

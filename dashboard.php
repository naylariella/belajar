<?php
session_start();

// 1. String: Nama pengguna
$namaPengguna = "Pelanggan Biblioffee";

// 2. Integer: Jumlah kopi (sesuai data kopi)
$jumlahKopi = 6;

// 3a. Indexed Array: Daftar kopi favorit
$kopiFavorit = ["Espresso", "Cappuccino", "Latte", "Americano", "Mocha", "Caramel Macchiato"];

// Cek apakah user sudah login berdasarkan session 'email' (sesuai login.php)
if (!isset($_SESSION['email'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login_form.php');
    exit;
}
?>

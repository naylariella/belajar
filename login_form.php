<?php
session_start(); // Memulai session

// Kalau user sudah login (cek session user_id), langsung redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php"); // Redirect ke halaman dashboard
    exit(); // Hentikan eksekusi script
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /> <!-- Set charset halaman -->
  <title>Login - Biblioffee</title> <!-- Judul halaman -->
  <style>
    body {
      font-family: 'Poppins', sans-serif; /* Font utama */
      background-color: #815942; /* Warna latar belakang */
      color: white; /* Warna teks */
      display: flex; /* Menggunakan flexbox */
      justify-content: center; /* Tengah horizontal */
      align-items: center; /* Tengah vertikal */
      height: 100vh; /* Tinggi 100% viewport */
      margin: 0; /* Hilangkan margin default */
    }
    form {
      background: rgba(15, 15, 15, 0.9); /* Latar belakang form semi transparan */
      padding: 25px 20px; /* Jarak dalam form */
      border-radius: 10px; /* Sudut membulat */
      width: 320px; /* Lebar form */
      box-shadow: 0 0 10px #000; /* Bayangan */
      box-sizing: border-box; /* Termasuk padding dan border ke ukuran */
      user-select: none; /* Nonaktifkan seleksi teks */
    }
    h2 {
      margin-bottom: 20px; /* Jarak bawah */
      font-weight: 700; /* Tebal font */
      text-align: center; /* Rata tengah */
    }
    label {
      display: block; /* Label jadi blok */
      margin-top: 12px; /* Jarak atas */
      font-weight: 600; /* Tebal font */
    }
    input {
      width: 100%; /* Lebar penuh */
      padding: 10px; /* Padding dalam input */
      margin-top: 6px; /* Jarak atas */
      border-radius: 5px; /* Sudut membulat */
      border: none; /* Hilangkan border */
      font-size: 1rem; /* Ukuran font */
      box-sizing: border-box; /* Padding termasuk di ukuran */
    }
    button {
      margin-top: 20px; /* Jarak atas */
      width: 100%; /* Lebar penuh */
      padding: 12px; /* Padding dalam tombol */
      background-color: #a1565c; /* Warna latar tombol */
      border: none; /* Hilangkan border */
      color: white; /* Warna teks tombol */
      font-weight: bold; /* Tebal font */
      cursor: pointer; /* Ganti kursor saat hover */
      border-radius: 5px; /* Sudut membulat */
      font-size: 1.1rem; /* Ukuran font */
      transition: background-color 0.3s ease; /* Animasi perubahan warna */
      user-select: none; /* Nonaktifkan seleksi teks */
    }
    button:hover {
      background-color: rgb(80, 9, 57); /* Warna saat hover */
    }
    .error {
      background-color: #ff6b6b; /* Warna latar error */
      color: white; /* Warna teks */
      padding: 8px 12px; /* Padding */
      border-radius: 6px; /* Sudut membulat */
      margin-bottom: 15px; /* Jarak bawah */
      text-align: center; /* Rata tengah */
      font-weight: 600; /* Tebal font */
      user-select: none; /* Nonaktifkan seleksi teks */
    }
  </style>
</head>
<body>
  <form action="proses_login.php" method="POST" autocomplete="off"> <!-- Form login -->
    <h2>Login Biblioffee</h2> <!-- Judul form -->
    <?php
      if (isset($_SESSION['error'])) { // Jika ada error di session
        echo "<div class='error'>" . htmlspecialchars($_SESSION['error']) . "</div>"; // Tampilkan pesan error aman
        unset($_SESSION['error']); // Hapus pesan error setelah tampil
      }
    ?>
    <label for="email">Email:</label> <!-- Label email -->
    <input type="email" name="email" id="email" required autocomplete="username" /> <!-- Input email -->

    <label for="password">Password:</label> <!-- Label password -->
    <input type="password" name="password" id="password" required autocomplete="current-password" /> <!-- Input password -->

    <button type="submit">Login</button> <!-- Tombol submit -->
  </form>
</body>
</html>

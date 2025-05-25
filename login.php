<?php
session_start(); // Mulai session untuk simpan data user
include 'koneksi.php'; // Sertakan file koneksi database

// Kalau sudah login, langsung redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php"); // Redirect ke dashboard
    exit(); // Hentikan script
}

// Handle proses login jika form dikirim (metode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'], $_POST['password'])) { // Cek email & password dikirim
        $email = trim($_POST['email']); // Ambil dan bersihkan email
        $password = $_POST['password']; // Ambil password

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?"); // Siapkan query SELECT berdasarkan email
        $stmt->bind_param("s", $email); // Bind parameter email
        $stmt->execute(); // Eksekusi query
        $result = $stmt->get_result(); // Ambil hasil query

        if ($result->num_rows === 1) { // Jika user ditemukan
            $row = $result->fetch_assoc(); // Ambil data user

            if (password_verify($password, $row['password'])) { // Verifikasi password hash
                // Login berhasil, simpan data session
                $_SESSION['user_id'] = $row['id']; 
                $_SESSION['email'] = $row['email'];
                $_SESSION['username'] = $row['username'];
                header("Location: dashboard.php"); // Redirect ke dashboard
                exit();
            } else {
                $_SESSION['error'] = "Password salah!"; // Password tidak cocok
            }
        } else {
            $_SESSION['error'] = "Email tidak ditemukan!"; // User tidak ada
        }

        $stmt->close(); // Tutup statement
        header("Location: login.php"); // Redirect ke login lagi
        exit();
    } else {
        $_SESSION['error'] = "Mohon isi email dan password!"; // Email atau password kosong
        header("Location: login.php"); // Redirect ke login
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login - Biblioffee</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif; /* Font */
      background-color: #815942; /* Warna latar */
      color: white; /* Warna teks */
      display: flex; /* Flexbox centering */
      justify-content: center; 
      align-items: center;
      height: 100vh; /* Tinggi layar penuh */
      margin: 0; /* Hilangkan margin */
    }
    form {
      background: rgba(15, 15, 15, 0.9); /* Warna background form */
      padding: 25px 20px; /* Padding */
      border-radius: 10px; /* Border radius */
      width: 320px; /* Lebar form */
      box-shadow: 0 0 10px #000; /* Bayangan */
      box-sizing: border-box; /* Box sizing */
    }
    h2 {
      margin-bottom: 20px; /* Margin bawah */
      font-weight: 700; /* Tebal font */
      text-align: center; /* Tengah teks */
    }
    label {
      display: block; /* Blok */
      margin-top: 12px; /* Margin atas */
      font-weight: 600; /* Tebal font */
    }
    input {
      width: 100%; /* Lebar penuh */
      padding: 10px; /* Padding */
      margin-top: 6px; /* Margin atas */
      border-radius: 5px; /* Border radius */
      border: none; /* Tanpa border */
      font-size: 1rem; /* Ukuran font */
      box-sizing: border-box; /* Box sizing */
    }
    button {
      margin-top: 20px; /* Margin atas */
      width: 100%; /* Lebar penuh */
      padding: 12px; /* Padding */
      background-color: #a1565c; /* Warna tombol */
      border: none; /* Tanpa border */
      color: white; /* Warna teks */
      font-weight: bold; /* Tebal font */
      cursor: pointer; /* Pointer cursor */
      border-radius: 5px; /* Border radius */
      font-size: 1.1rem; /* Ukuran font */
      transition: background-color 0.3s ease; /* Transisi */
    }
    button:hover {
      background-color:rgb(90, 12, 65); /* Warna saat hover */
    }
    .error {
      background-color: #ff6b6b; /* Warna latar error */
      color: white; /* Warna teks */
      padding: 8px 12px; /* Padding */
      border-radius: 6px; /* Border radius */
      margin-bottom: 15px; /* Margin bawah */
      text-align: center; /* Tengah teks */
      font-weight: 600; /* Tebal font */
    }
  </style>
</head>
<body>
  <form action="login.php" method="POST" autocomplete="off"> <!-- Form login -->
    <h2>Login Biblioffee</h2> <!-- Judul form -->
    <?php
      if (isset($_SESSION['error'])) { // Jika ada error di session
        echo "<div class='error'>" . htmlspecialchars($_SESSION['error']) . "</div>"; // Tampilkan pesan error
        unset($_SESSION['error']); // Hapus pesan error setelah ditampilkan
      }
    ?>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required autocomplete="username" /> <!-- Input email -->

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required autocomplete="current-password" /> <!-- Input password -->

    <button type="submit">Login</button> <!-- Tombol submit -->
  </form>
</body>
</html>

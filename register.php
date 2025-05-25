<?php
include 'koneksi.php'; // Menghubungkan ke database

// Ambil data dari form, gunakan trim untuk hilangkan spasi di awal/akhir
$username = trim($_POST['username'] ?? ''); 
$email    = trim($_POST['email'] ?? '');
$password_raw = $_POST['password'] ?? ''; // Password mentah sebelum di-hash

// Cek apakah semua field diisi
if (!empty($username) && !empty($email) && !empty($password_raw)) {

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email tidak valid!"; // Jika email tidak sesuai format
        exit; // Hentikan eksekusi script
    }

    // Hash password menggunakan algoritma default (bcrypt)
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // Cek apakah email sudah terdaftar di database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // Bind parameter email ke query
    $stmt->execute(); // Jalankan query
    $stmt->store_result(); // Simpan hasil query

    if ($stmt->num_rows > 0) { // Jika ada user dengan email sama
        echo "Email sudah terdaftar."; // Tampilkan pesan error
        $stmt->close(); // Tutup statement
        exit; // Hentikan script
    }
    $stmt->close(); // Tutup statement

    // Jika email belum ada, lakukan insert data user baru
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password); // Bind data ke query

    if ($stmt->execute()) { // Jika insert berhasil
        echo "Registrasi berhasil!"; // Tampilkan pesan sukses
        // Contoh redirect ke halaman login:
        // header("Location: login.php");
        // exit;
    } else { // Jika insert gagal
        echo "Terjadi kesalahan saat registrasi.";
    }

    $stmt->close(); // Tutup statement

} else { // Jika ada field yang kosong
    echo "Semua field harus diisi!";
}
?>

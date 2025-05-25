<?php
session_start(); // Mulai session untuk akses data session seperti user_id
header('Content-Type: application/json'); // Set header response jadi JSON

// Cek login
if (!isset($_SESSION['user_id'])) {
    // Jika user_id di session tidak ada, berarti belum login
    echo json_encode(['success' => false, 'message' => 'Anda harus login dulu']); // Kirim response gagal
    exit; // Hentikan eksekusi script
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Ambil data JSON POST dari body request
$data = json_decode(file_get_contents('php://input'), true); 
// Cek apakah data JSON valid dan mengandung array 'cart'
if (!$data || !isset($data['cart']) || !is_array($data['cart'])) {
    // Jika tidak valid, kirim pesan error
    echo json_encode(['success' => false, 'message' => 'Data keranjang tidak valid']);
    exit; // Hentikan eksekusi script
}

$cart = $data['cart']; // Simpan data keranjang ke variabel $cart

// Koneksi database (ubah sesuai konfigurasi server kamu)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "biblioffee_a";

$conn = new mysqli($host, $user, $pass, $db); // Buat koneksi MySQL
if ($conn->connect_error) {
    // Jika koneksi gagal, kirim pesan error dengan detailnya
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . $conn->connect_error]);
    exit; // Hentikan eksekusi script
}

// Siapkan statement SQL untuk insert ke tabel riwayat_pesan
$stmt = $conn->prepare("INSERT INTO riwayat_pesan (user_id, nama_menu, harga, jumlah, total) VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    // Jika prepare statement gagal, kirim pesan error
    echo json_encode(['success' => false, 'message' => 'Prepare statement gagal: ' . $conn->error]);
    exit; // Hentikan eksekusi script
}

// Loop tiap item di keranjang
foreach ($cart as $item) {
    // Validasi minimal data, cek apakah ada 'name' dan 'price'
    if (!isset($item['name'], $item['price'])) {
        continue; // Lewati item jika data kurang lengkap
    }

    $nama_menu = $item['name']; // Nama menu dari item
    $harga = (int)$item['price']; // Harga, dikonversi ke integer
    $jumlah = 1; // Jumlah default 1 (karena belum ada input jumlah dari keranjang)
    $total = $harga * $jumlah; // Hitung total harga

    // Bind parameter dan eksekusi insert ke database
    $stmt->bind_param("isiii", $user_id, $nama_menu, $harga, $jumlah, $total);
    $stmt->execute();
}

$stmt->close(); // Tutup statement
$conn->close(); // Tutup koneksi database

// Kirim response sukses jika semua berhasil
echo json_encode(['success' => true]);
?>

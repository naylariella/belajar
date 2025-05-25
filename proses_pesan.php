<?php
session_start(); // Mulai session untuk akses data session
header('Content-Type: application/json'); // Set header response jadi JSON

include 'koneksi.php'; // Sertakan file koneksi database, pastikan $conn tersedia

// Cek user login
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Set HTTP status 403 Forbidden
    echo json_encode(['error' => 'Silakan login terlebih dahulu']); // Kirim error jika belum login
    exit; // Hentikan eksekusi
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Ambil data JSON dari request body
$data = json_decode(file_get_contents('php://input'), true);

// Validasi data: cek apakah ada 'items' dan tidak kosong
if (!$data || empty($data['items'])) {
    echo json_encode(['error' => 'Data pesanan kosong atau tidak valid']); // Kirim error jika tidak valid
    exit; // Hentikan eksekusi
}

$items = $data['items']; // Ambil array items dari data
$success = true;
$conn->begin_transaction(); // Mulai transaksi database

try {
    // Siapkan statement insert ke tabel riwayat_pesan
    $stmt = $conn->prepare("INSERT INTO riwayat_pesan (user_id, nama_menu, harga, jumlah, total) VALUES (?, ?, ?, ?, ?)");

    // Loop tiap item dalam pesanan
    foreach ($items as $item) {
        $nama_menu = $item['name']; // Nama menu
        $harga = intval($item['price']); // Harga, cast ke integer
        $jumlah = intval($item['quantity']); // Jumlah, cast ke integer
        $total = $harga * $jumlah; // Hitung total harga

        // Bind parameter ke query dan eksekusi
        $stmt->bind_param("isiii", $user_id, $nama_menu, $harga, $jumlah, $total);
        if (!$stmt->execute()) {
            // Jika gagal eksekusi, lempar exception untuk rollback
            throw new Exception("Gagal menyimpan item: $nama_menu");
        }
    }

    $conn->commit(); // Commit transaksi jika semua sukses
    echo json_encode(['success' => 'Pesanan berhasil disimpan']); // Kirim response sukses
} catch (Exception $e) {
    $conn->rollback(); // Rollback transaksi jika terjadi error
    http_response_code(500); // Set HTTP status 500 Internal Server Error
    echo json_encode(['error' => $e->getMessage()]); // Kirim pesan error
}
?>

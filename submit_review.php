<?php
header('Content-Type: application/json'); // Set header response jadi JSON

// Konfigurasi database
$host = 'localhost'; // Host database
$db   = 'biblioffee_a'; // Nama database
$user = 'root'; // Username database
$pass = ''; // Password database
$charset = 'utf8mb4'; // Charset untuk koneksi PDO

// Koneksi PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; // Data Source Name PDO
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Set error mode jadi exception
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch hasil query jadi associative array
];

// Fungsi untuk menyimpan review ke database
function simpanReview($pdo, $rating, $review) {
    // Validasi rating dan review
    if ($rating >= 1 && $rating <= 5 && !empty(trim($review))) {
        // Siapkan query insert ke tabel ratings
        $stmt = $pdo->prepare("INSERT INTO ratings (rating, review) VALUES (?, ?)");
        $stmt->execute([$rating, $review]); // Eksekusi dengan parameter rating dan review
        return ['success' => true]; // Kembalikan status sukses
    } else {
        // Jika data tidak valid, kembalikan error
        return ['success' => false, 'message' => 'Data tidak valid'];
    }
}

try {
  $pdo = new PDO($dsn, $user, $pass, $options); // Buat koneksi PDO ke database

  $input = json_decode(file_get_contents("php://input"), true); // Ambil data JSON dari request body
  $rating = (int)$input['rating']; // Ambil dan cast rating ke integer
  $review = trim($input['review']); // Ambil dan trim review

  $result = simpanReview($pdo, $rating, $review); // Panggil fungsi simpanReview
  echo json_encode($result); // Kirim hasil sebagai JSON

} catch (Exception $e) {
  // Tangani error koneksi atau eksekusi query
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

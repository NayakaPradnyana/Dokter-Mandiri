<?php
// Aktifkan laporan error sementara agar kita tidak tebak-tebakan lagi jika gagal
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Prioritas 1: Ambil MYSQL_URL bawaan Railway yang terjamin formatnya
$db_url = $_SERVER['MYSQL_URL'] ?? getenv('MYSQL_URL') ?? '';

if (!empty($db_url)) {
    // Memecah (parsing) URL string menjadi komponen individu secara otomatis
    $db = parse_url($db_url);
    $host     = $db['host'] ?? 'localhost';
    $port     = $db['port'] ?? '3306';
    $dbname   = isset($db['path']) ? ltrim($db['path'], '/') : 'railway';
    $username = $db['user'] ?? 'root';
    $password = $db['pass'] ?? '';
} else {
    // Prioritas 2: Cadangan mencari variabel satuan jika URL tidak terbaca
    $host     = $_SERVER['MYSQLHOST'] ?? getenv('MYSQLHOST') ?? 'localhost';
    $port     = $_SERVER['MYSQLPORT'] ?? getenv('MYSQLPORT') ?? '3306';
    $dbname   = $_SERVER['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE') ?? 'railway';
    $username = $_SERVER['MYSQLUSER'] ?? getenv('MYSQLUSER') ?? 'root';
    $password = $_SERVER['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD') ?? '';
}

try {
    // Pencegahan brutal: Jika PHP terdeteksi masih membaca 'localhost', hentikan sistem!
    // Ini memastikan kita tidak mendapat eror [2002] palsu lagi.
    if ($host === 'localhost') {
        die("<h1>🚨 Deteksi Eror Kritis:</h1> <p>PHP gagal membaca seluruh variabel environment dari Railway. Variabel host saat ini bernilai kosong/localhost.</p>");
    }

    // Eksekusi koneksi PDO
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $koneksi = new PDO($dsn, $username, $password);
    
    // Atur atribut aman
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $koneksi->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // Simpan di GLOBALS
    $GLOBALS['koneksi'] = $koneksi;
} catch (PDOException $e) {
    // Tampilkan pesan eror transparan sementara waktu
    die("<h1>🚨 Koneksi Database Gagal:</h1> <p>" . $e->getMessage() . "</p><p><b>Detail DSN Anda:</b> " . $dsn . "</p>");
}
?>

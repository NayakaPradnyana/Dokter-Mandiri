<?php
// Membaca konfigurasi dari Environment Variables Railway menggunakan $_SERVER
$host     = $_SERVER['MYSQL_HOST'] ?? 'localhost';
$port     = $_SERVER['MYSQL_PORT'] ?? '3306';
$dbname   = $_SERVER['MYSQL_DATABASE'] ?? 'railway';
$username = $_SERVER['MYSQL_USER'] ?? 'root';
$password = $_SERVER['MYSQL_PASSWORD'] ?? '';

try {
    // Membuat koneksi PDO dengan menyertakan port
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $koneksi = new PDO($dsn, $username, $password);
    
    // Atur atribut error handling agar aman dan ketat
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $koneksi->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // Simpan ke GLOBALS jika file lama Anda membutuhkannya
    $GLOBALS['koneksi'] = $koneksi;
} catch (PDOException $e) {
    // Menuliskan detail error asli ke log internal Railway untuk pelacakan murni
    error_log("Koneksi database gagal: " . $e->getMessage());
    
    // Menampilkan pesan aman ke browser pengguna
    die("Gangguan sistem, silakan coba beberapa saat lagi.");
}
?>

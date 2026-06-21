<?php
// Membaca konfigurasi dari Environment Variables Railway
$host     = getenv('MYSQL_HOST') ?: 'localhost';
$port     = getenv('MYSQL_PORT') ?: '3306';
$dbname   = getenv('MYSQL_DATABASE') ?: 'railway';
$username = getenv('MYSQL_USER') ?: 'root';
$password = getenv('MYSQL_PASSWORD') ?: '';

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

<?php
// Membaca konfigurasi dari Environment Variables yang disediakan oleh Railway
$host     = getenv('MYSQLHOST') ?: 'localhost';
$port     = getenv('MYSQLPORT') ?: '3306';
$dbname   = getenv('MYSQLDATABASE') ?: 'railway'; // Disesuaikan dengan nama DB di Railway Anda
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';

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
    // Menuliskan detail error yang sebenarnya ke log Railway agar mudah kita lacak nanti
    error_log("Koneksi database gagal: " . $e->getMessage());
    
    // Menampilkan pesan ramah pengguna di browser
    die("Gangguan sistem, silakan coba beberapa saat lagi.");
}
?>

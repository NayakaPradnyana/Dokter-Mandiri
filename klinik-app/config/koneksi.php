<?php
// Membaca konfigurasi dari Environment Variables Railway menggunakan getenv
$host     = getenv('MYSQLHOST') ?: 'localhost';
$port     = getenv('MYSQLPORT') ?: '3306';
$dbname   = getenv('MYSQLDATABASE') ?: 'railway';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';

try {
    // Membuat koneksi PDO dengan menyertakan port dan charset
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    echo "<h2>🚨 Koneksi Database Gagal:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Detail DSN Anda:</strong> mysql:host=" . htmlspecialchars($host) . ";port=" . htmlspecialchars($port) . ";dbname=" . htmlspecialchars($dbname) . "</p>";
    exit;
}
?>

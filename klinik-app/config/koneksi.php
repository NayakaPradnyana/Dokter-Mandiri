<?php
// Mengisi langsung dengan kredensial internal Railway Anda
$host     = 'reseau.proxy.rlwy.net';
$port     = '43945';
$dbname   = 'railway';
$username = 'root';
$password = 'osJUXYkeICZwXLrJiaWzdJoizwMPsYMz'

try {
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

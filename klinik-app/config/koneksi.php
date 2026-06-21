<?php
// Mengisi langsung dengan kredensial INTERNAL Railway (Aman, cepat, dan sinkron)
$host     = 'mysql.railway.internal'; 
$port     = '3306';                   
$dbname   = 'railway';                
$username = 'root';                   
$password = 'osJUXYkeICZwXLrJiaWzdJoizwMPsYMz'; // Pastikan besar kecilnya huruf sesuai password Anda

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    // Menggunakan nama variabel $koneksi agar terbaca oleh proses_login.php Anda
    $koneksi = new PDO($dsn, $username, $password);
    
    // Atur pengaman eror
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $koneksi->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $koneksi->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // Daftarkan ke GLOBALS agar bisa di-include lintas folder dengan aman
    $GLOBALS['koneksi'] = $koneksi;

} catch (PDOException $e) {
    echo "<h2>🚨 Koneksi Database Gagal:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Detail DSN Anda:</strong> mysql:host=" . htmlspecialchars($host) . ";port=" . htmlspecialchars($port) . ";dbname=" . htmlspecialchars($dbname) . "</p>";
    exit;
}
?>

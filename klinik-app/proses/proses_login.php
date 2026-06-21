<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Hubungkan dengan file koneksi database
include '../config/koneksi.php';

// Memastikan koneksi aman dari scope global
$db = isset($koneksi) ? $koneksi : $GLOBALS['koneksi'];

// 2. Pastikan file ini diakses melalui pengiriman form (metode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Tangkap dan bersihkan inputan username dan password dari form
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input kosong dini
    if (empty($username) || empty($password)) {
        echo "<script>
                alert('Username dan Password wajib diisi!');
                window.location.href = '../views/login.php';
              </script>";
        exit();
    }

    try {
        // 4. Cari data user di database berdasarkan username 
        // [PERUBAHAN]: Mengubah nama tabel menjadi 'User' dan 'Role' (Kapital) agar sesuai database Linux Railway
        $query = $db->prepare("SELECT u.*, r.nama_role 
                               FROM User u 
                               JOIN Role r ON u.id_role = r.id_role 
                               WHERE u.username = :username");
        $query->bindParam(':username', $username);
        $query->execute();
        
        // Ambil hasil pencarian berbentuk array asosiatif
        $user = $query->fetch();

        // 5. Proses Validasi Keamanan
        if ($user) {
            
            // Cek jika akun dalam status Nonaktif (Mencegah user yang di-ban untuk masuk)
            if (isset($user['status']) && strtolower($user['status']) !== 'aktif') {
                echo "<script>
                        alert('Maaf, akun Anda telah dinonaktifkan. Silakan hubungi Administrator.');
                        window.location.href = '../views/login.php';
                      </script>";
                exit();
            }

            // Verifikasi Password (Mendukung password_verify modern DAN plain-text transisi)
            $passwordValid = false;
            if (password_verify($password, $user['password'])) {
                $passwordValid = true;
            } elseif ($password === $user['password']) {
                $passwordValid = true; 
            }

            if ($passwordValid) {
                // === LOGIN BERHASIL ===
                // Regenerasi session ID untuk mencegah serangan Session Fixation
                session_regenerate_id(true);

                $_SESSION['username'] = $user['username'];
                // [PERUBAHAN]: Mengubah indeks array menjadi 'user_id' sesuai nama kolom asli di tabel User Anda
                $_SESSION['user_id']  = $user['user_id']; 
                $_SESSION['role']     = $user['nama_role']; 
                
                // Arahkan otomatis ke halaman Dashboard
                header("Location: ../views/dashboard.php");
                exit();
            }
        }

        // === LOGIN GAGAL ===
        echo "<script>
                alert('Maaf, Username atau Password Anda salah!');
                window.location.href = '../views/login.php';
              </script>";
        exit();

    } catch (PDOException $e) {
        // Log kesalahan internal ke server log
        error_log("Login Error: " . $e->getMessage());
        
        echo "<script>
                alert('Terjadi kesalahan internal pada sistem autentikasi.');
                window.location.href = '../views/login.php';
              </script>";
        exit();
    }

} else {
    // Jika ada yang mencoba iseng mengetikkan URL langsung ke file ini
    header("Location: ../views/login.php");
    exit();
}
?>

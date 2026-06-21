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
        // 4. Cari data user di database berdasarkan username (Ambil juga kolom status jika ada)
        // 4. Cari data user di database berdasarkan username (Disesuaikan dengan huruf kecil & id_user)
        $query = $db->prepare("SELECT u.*, r.nama_role 
                               FROM user u 
                               JOIN role r ON u.id_role = r.id_role 
                               WHERE u.username = :username");
        $query->bindParam(':username', $username);
        $query->execute();
        
        // Ambil hasil pencarian berbentuk array asosiatif
        $user = $query->fetch();

        // 5. Proses Validasi Keamanan
        if ($user) {
            
            // Cek jika akun dalam status Nonaktif
            if (isset($user['status']) && strtolower($user['status']) !== 'aktif') {
                echo "<script>
                        alert('Maaf, akun Anda telah dinonaktifkan. Silakan hubungi Administrator.');
                        window.location.href = '../views/login.php';
                      </script>";
                exit();
            }

            // Verifikasi Password
            $passwordValid = false;
            if (password_verify($password, $user['password'])) {
                $passwordValid = true;
            } elseif ($password === $user['password']) {
                $passwordValid = true; 
            }

            if ($passwordValid) {
                // === LOGIN BERHASIL ===
                session_regenerate_id(true);

                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id']  = $user['id_user']; // <-- Diubah dari user_id menjadi id_user sesuai database
                $_SESSION['role']     = $user['nama_role']; 
                
                // Arahkan otomatis ke halaman Dashboard
                header("Location: ../views/dashboard.php");
                exit();
            }
        }

        // === LOGIN GAGAL ===
        // Pesan dibuat sama (Username/Password salah) agar attacker tidak bisa menebak username mana yang valid
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

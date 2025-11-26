<?php
// Pengaturan Koneksi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sipiket_db');

// Inisialisasi Koneksi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi Redirect
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Fungsi Cek Login & Otentikasi Peran
function check_login($role_required = null) {
    session_start();
    
    // Jika belum login, redirect ke halaman login
    if (!isset($_SESSION['user_id'])) {
        redirect('../index.php'); // Sesuaikan path jika dipanggil dari subfolder
    }
    
    // Jika peran tidak sesuai, kembalikan ke dashboard mereka
    if ($role_required && $_SESSION['role'] !== $role_required) {
        // Logika sederhana: redirect berdasarkan role yang dimiliki
        if ($_SESSION['role'] === 'admin') redirect('../admin/index.php');
        if ($_SESSION['role'] === 'petugas') redirect('../petugas/index.php');
        if ($_SESSION['role'] === 'siswa') redirect('../siswa/index.php');
        
        // Default redirect jika ada masalah peran
        redirect('../index.php');
    }
}
?>
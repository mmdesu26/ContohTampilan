<?php
// Pastikan sesi dimulai
session_start();

// Hancurkan semua data sesi
$_SESSION = array();

// Jika ingin menghapus cookie sesi juga, hapus cookie.
// Catatan: Ini akan menghancurkan sesi, dan bukan hanya data sesi!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Akhiri sesi
session_destroy();

// Arahkan kembali pengguna ke halaman login
header("Location: index.php");
exit;
?>
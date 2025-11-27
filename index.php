<?php
session_start();

// ==========================
// FUNGSI REDIRECT
// ==========================
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// ==========================
// FUNGSI CEK LOGIN (opsional untuk halaman lain)
// ==========================
function check_login($role_required = null) {
    if (!isset($_SESSION['user_id'])) {
        redirect('../index.php');
    }

    if ($role_required && $_SESSION['role'] !== $role_required) {
        if ($_SESSION['role'] === 'admin') redirect('../admin/index.php');
        if ($_SESSION['role'] === 'petugas') redirect('../petugas/index.php');
        if ($_SESSION['role'] === 'siswa') redirect('../siswa/index.php');
        redirect('../index.php');
    }
}

// ==========================
// AUTO REDIRECT KALAU SUDAH LOGIN
// ==========================
if (isset($_SESSION['role'])) {
    redirect($_SESSION['role'] . '/index.php');
}

// ==========================
// LOGIKA LOGIN HARDCODE
// ==========================
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Daftar user hardcoded
    $users = [
        'admin' => ['password' => '123', 'role' => 'admin', 'nama' => 'Administrator Sipiket'],
        'petugas' => ['password' => '123', 'role' => 'petugas', 'nama' => 'Petugas Piket'],
        '00125' => ['password' => '123', 'role' => 'siswa', 'nama' => 'Budi Santoso', 'nis' => '00125', 'kelas' => 'XII IPA 3'],
    ];

    // Validasi login
    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        
        $user = $users[$username];

        $_SESSION['user_id'] = $username;
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama'];

        if ($user['role'] === 'siswa') {
            $_SESSION['nis'] = $user['nis'];
            $_SESSION['kelas'] = $user['kelas'];
        }

        redirect($user['role'] . '/index.php');
    } 
    else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sipiket - Login</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="login-body">
    
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <header class="login-header">
                <div class="logo-icon">
                    <span>📅</span>
                </div>
                <h1>Sipiket</h1>
                <p>Sistem Informasi Piket</p>
                <div class="divider"></div>
            </header>

            <form method="POST" action="index.php" class="login-form">
                
                <?php if ($error): ?>
                    <div class="alert-box error-anim">
                        <span>⚠️</span> <?= $error ?>
                    </div>
                <?php endif; ?>

                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="NIS / ID Petugas / Admin" autocomplete="off" required>
                    <span class="focus-border"></span>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
                    <span class="focus-border"></span>
                </div>

                <button type="submit" class="btn-login">
                    Masuk Sekarang
                </button>
            </form>

            <footer class="login-footer">
                <p>© 2025 MAN 2 Kota Madiun</p>
            </footer>
        </div>
    </div>

</body>
</html>
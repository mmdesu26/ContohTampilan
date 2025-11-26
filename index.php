<?php
// Tautan ke config, meskipun koneksi DB di dalamnya tidak digunakan, 
// fungsi redirect dan check_login tetap diperlukan.
include 'includes/config.php'; 
session_start();

// Jika sudah login, langsung redirect ke dashboard sesuai peran
if (isset($_SESSION['role'])) {
    redirect($_SESSION['role'] . '/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // --- LOGIKA LOGIN HARDCODE ---
    
    // Data login hardcode: (Username:Password)
    $users = [
        'admin' => ['password' => '123', 'role' => 'admin', 'nama' => 'Administrator Sipiket'],
        'petugas' => ['password' => '123', 'role' => 'petugas', 'nama' => 'Petugas Piket'],
        '00125' => ['password' => '123', 'role' => 'siswa', 'nama' => 'Budi Santoso', 'nis' => '00125', 'kelas' => 'XII IPA 3'],
    ];

    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $user_data = $users[$username];
        
        // Login Berhasil
        $_SESSION['user_id'] = $username; // Menggunakan username sebagai ID
        $_SESSION['role'] = $user_data['role'];
        $_SESSION['nama'] = $user_data['nama'];
        
        if ($user_data['role'] === 'siswa') {
            $_SESSION['nis'] = $user_data['nis'];
            $_SESSION['kelas'] = $user_data['kelas'];
        }
        
        // Redirect berdasarkan peran
        redirect($user_data['role'] . '/index.php');
    } else {
        $error = 'Username atau password salah.';
    }
    // -----------------------------
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipiket - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">
    <div class="login-container">
        <header class="header-gradient">
            
            <h1>Sipiket</h1>
            <p>Sistem Informasi Piket</p>
        </header>

        <form method="POST" action="index.php" class="login-form">
            <?php if ($error): ?><div class="alert error" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 10px;"><?= $error ?></div><?php endif; ?>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="NIS (siswa) / ID Petugas / admin" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password Anda" required>
            </div>

            <button type="submit" class="btn btn-primary">Masuk</button>
        </form>

        <footer><p>© 2025 MAN 2 Kota Madiun</p></footer>
    </div>
</body>
</html>
<?php
session_start();

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function check_login($role_required = null) {
    if (!isset($_SESSION['user_id'])) redirect('../index.php');
    if ($role_required && $_SESSION['role'] !== $role_required) redirect('../' . $_SESSION['role'] . '/index.php');
}

check_login('admin');

// DATA HARDCODE
$rekap_izin = [
    ['jenis' => 'Izin Keluar', 'menunggu' => '-', 'diizinkan' => '-', 'ditolak' => 1, 'bolos' => '-'],
    ['jenis' => 'Di Jemput', 'menunggu' => '-', 'diizinkan' => 2, 'ditolak' => 1, 'bolos' => '-'],
    ['jenis' => 'Masuk Sekolah', 'menunggu' => '-', 'diizinkan' => 3, 'ditolak' => '-', 'bolos' => '-'],
];
$total_izin = 18;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        
        <header class="header-gradient fade-in-down">
            <div class="header-content">
                <div class="profile-info">
                    <h1 style="margin:0; font-size:22px;">Halo, Admin! 🛠️</h1>
                    <p>MAN 2 Kota Madiun</p>
                </div>
                <div class="header-icon">👨‍💻</div>
            </div>
        </header>

        <main class="content-wrapper">

            <section class="card fade-in-up" style="animation-delay: 0.1s;">
                <div class="card-header-flex">
                    <h3>ℹ️ Informasi Sekolah</h3>
                    <span class="badge badge-danger">SERVER OFFLINE</span>
                </div>
                <div class="item-meta">
                    <p><strong>Nama:</strong> MAN 2 Kota Madiun</p>
                    <p><strong>Lokasi:</strong> 7.63456, 111.52789</p>
                </div>
                <div style="margin-top:15px;">
                    <button class="btn-outline-info" style="width:100%">⚙️ Atur Data Sekolah</button>
                </div>
            </section>
            
            <section class="card fade-in-up" style="animation-delay: 0.2s;">
                <h3>👥 Manajemen Siswa</h3>
                <div class="grid-buttons">
                    <button class="btn-menu bg-blue">➕ Tambah</button>
                    <button class="btn-menu bg-purple">📋 Daftar</button>
                </div>

                <div style="margin-top: 20px; border-top:1px solid #eee; padding-top:15px;">
                    <h4 style="font-size:14px; margin-bottom:10px;">Upload Excel (.xlsx)</h4>
                    <div style="display:flex; gap:10px;">
                        <button class="btn-outline-info" style="flex:1">📂 Pilih File</button>
                        <button class="btn-page active" style="width:auto; padding:0 15px;">Upload</button>
                    </div>
                    <p style="font-size:11px; color:#888; margin-top:5px;">Format: NIS | Nama | Kelas</p>
                </div>
            </section>

            <section class="card fade-in-up" style="animation-delay: 0.3s;">
                <div class="card-header-flex">
                    <h3>📊 Rekap Perizinan</h3>
                    <small>Total: <strong><?= $total_izin ?></strong></small>
                </div>
                
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th style="text-align:center">Ok</th>
                                <th style="text-align:center">No</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rekap_izin as $izin): ?>
                            <tr>
                                <td><?= $izin['jenis'] ?></td>
                                <td style="text-align:center">
                                    <span class="badge badge-success"><?= $izin['diizinkan'] ?></span>
                                </td>
                                <td style="text-align:center">
                                    <span class="badge badge-danger"><?= $izin['ditolak'] ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div style="margin-top:15px; text-align:center;">
                    <button class="btn-outline-info">📥 Download Laporan Lengkap</button>
                </div>
            </section>

            <div class="logout-wrapper fade-in-up" style="animation-delay: 0.4s;">
                <a href="../logout.php" class="btn-logout-premium">
                    <span class="icon-logout">🚪</span> 
                    <span>Keluar Admin</span>
                </a>
            </div>
            
            <div style="height: 40px;"></div>
        </main>
    </div>
</body>
</html>
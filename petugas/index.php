<?php
session_start();

// ==========================
// 1. FUNGSI REDIRECT
// ==========================
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// ==========================
// 2. FUNGSI CEK LOGIN
// ==========================
function check_login($role_required = null) {
    if (!isset($_SESSION['user_id'])) {
        redirect('../index.php');
    }

    if ($role_required && $_SESSION['role'] !== $role_required) {
        redirect('../' . $_SESSION['role'] . '/index.php');
    }
}

// Hanya petugas yang boleh masuk
check_login('petugas');

// ==========================
// 3. DATA RIWAYAT (INI YANG SEBELUMNYA HILANG)
// ==========================
$semua_riwayat = [
    ['nama' => 'Musadek', 'status' => 'Diizinkan', 'jenis' => 'Masuk', 'waktu' => '13 Nov 2025 15:04'],
    ['nama' => 'Siti Nurhaliza', 'status' => 'Diizinkan', 'jenis' => 'Dijemput', 'waktu' => '13 Nov 2025 15:02'],
    ['nama' => 'Budi Santoso', 'status' => 'Telah Kembali Ke Sekolah', 'jenis' => 'Keluar', 'waktu' => '13 Nov 2025 14:35'],
    ['nama' => 'Musadek', 'status' => 'Telah Kembali Ke Sekolah', 'jenis' => 'Keluar', 'waktu' => '13 Nov 2025 14:11'],
    ['nama' => 'Musadek', 'status' => 'Menunggu', 'jenis' => 'Keluar', 'waktu' => '13 Nov 2025 13:54'],
    ['nama' => 'Petugas Siang', 'status' => 'Bolos', 'jenis' => 'BOLOS', 'waktu' => '05 Apr 2025 13:00', 'is_bolos' => true],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="description" content="Dashboard Petugas MAN 2 Kota Madiun">
    <meta name="theme-color" content="#0061f2">
    <title>Dashboard Petugas - MAN 2 Kota Madiun</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="dashboard-body">
    
    <div class="dashboard-container">
        
        <header class="header-gradient fade-in-down" role="banner">
            <div class="header-content">
                <div class="profile-info">
                    <h1 style="margin: 0; font-size: 22px; font-weight: 700;">Halo, Petugas! 👋</h1>
                    <p>MAN 2 Kota Madiun</p>
                </div>
                <div class="header-icon" aria-label="Officer icon">
                    <span>👮‍♂️</span>
                </div>
            </div>
        </header>

        <main class="content-wrapper" role="main">
            
            <section class="card fade-in-up" style="animation-delay: 0.1s;">
                <div class="card-header-flex">
                    <h2 style="margin: 0; font-size: 16px; color: #444; font-weight: 700;">⏳ Menunggu Persetujuan</h2>
                </div>
                <div class="empty-state">
                    <span class="icon" role="img" aria-label="Check mark">✅</span>
                    <p>Tidak ada permohonan baru.</p>
                </div>
            </section>
            
            <section class="card fade-in-up" style="animation-delay: 0.2s;">
                <div class="riwayat-header">
                    <div class="title-group">
                        <h2 style="margin: 0; font-size: 16px; color: #444; font-weight: 700;">📜 Riwayat Aktivitas</h2>
                        <small>Total: <?= count($semua_riwayat) ?> Data</small>
                    </div>
                    <button class="btn btn-sm btn-outline-info">📥 Ekspor .xlsx</button>
                </div>

                <div class="alert-mini" role="alert">
                    <span>🚨</span>
                    <span><b>Info:</b> Terdeteksi 3 Siswa bolos bulan ini.</span>
                </div>

                <div class="riwayat-list" role="list">
                    <?php foreach ($semua_riwayat as $izin): ?>
                    <?php
                        // Logika Styling Badge & Icon
                        $badge_class = 'badge-default';
                        $icon_jenis = '📝';
                        
                        if ($izin['status'] === 'Diizinkan') { 
                            $badge_class = 'badge-success'; 
                            $icon_jenis = '✅';
                        } elseif ($izin['status'] === 'Bolos') { 
                            $badge_class = 'badge-danger'; 
                            $icon_jenis = '❌';
                        } elseif ($izin['status'] === 'Telah Kembali Ke Sekolah') { 
                            $badge_class = 'badge-info'; 
                            $icon_jenis = '🔄';
                        } elseif ($izin['status'] === 'Menunggu') { 
                            $badge_class = 'badge-warning'; 
                            $icon_jenis = '⏳';
                        }

                        $item_class = isset($izin['is_bolos']) ? 'item-bolos' : '';
                    ?>
                    
                    <div class="list-item <?= $item_class ?>" role="listitem">
                        <div class="item-icon" role="img" aria-label="Status icon">
                            <?= $icon_jenis ?>
                        </div>
                        <div class="item-details">
                            <h3 class="item-name"><?= htmlspecialchars($izin['nama']) ?></h3>
                            <div class="item-meta">
                                <span><?= htmlspecialchars($izin['jenis']) ?></span> • <span><?= htmlspecialchars($izin['waktu']) ?></span>
                            </div>
                        </div>
                        <div class="item-status">
                            <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($izin['status']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <nav class="pagination" aria-label="Pagination">
                    <button class="btn-page active" aria-label="Page 1" aria-current="page">1</button>
                    <button class="btn-page" aria-label="Page 2">2</button>
                    <button class="btn-page" aria-label="Next page">»</button>
                </nav>
            </section>

            <div class="logout-wrapper fade-in-up" style="animation-delay: 0.3s;">
                <a href="../logout.php" class="btn-logout-premium" aria-label="Logout from application">
                    <span class="icon-logout">🚪</span> 
                    <span>Keluar Aplikasi</span>
                </a>
            </div>

            <div style="height: 40px;"></div>
        </main>

    </div>
</body>
</html>
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
// 3. DATA RIWAYAT AKTIVITAS
// ==========================
$semua_riwayat = [
    ['nama' => 'Musadek', 'status' => 'Diizinkan', 'jenis' => 'Masuk', 'waktu' => '13 Nov 2025 15:04'],
    ['nama' => 'Siti Nurhaliza', 'status' => 'Diizinkan', 'jenis' => 'Dijemput', 'waktu' => '13 Nov 2025 15:02'],
    ['nama' => 'Budi Santoso', 'status' => 'Telah Kembali Ke Sekolah', 'jenis' => 'Keluar', 'waktu' => '13 Nov 2025 14:35'],
    ['nama' => 'Ahmad Pratama', 'status' => 'Menunggu', 'jenis' => 'Keluar', 'waktu' => '13 Nov 2025 14:20'],
    ['nama' => 'Dina Kusuma', 'status' => 'Telah Kembali Ke Sekolah', 'jenis' => 'Keluar', 'waktu' => '13 Nov 2025 14:11'],
    ['nama' => 'Roni Wijaya', 'status' => 'Bolos', 'jenis' => 'BOLOS', 'waktu' => '05 Apr 2025 13:00', 'is_bolos' => true],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="description" content="Dashboard Petugas MAN 2 Kota Madiun - Kelola data siswa dan aktivitas sekolah">
    <meta name="theme-color" content="#0061f2">
    <title>Dashboard Petugas - MAN 2 Kota Madiun</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="dashboard-body">
    
    <div class="dashboard-container">
        
        <!-- Enhanced header dengan better visual design dan accessibility -->
        <header class="header-gradient fade-in-down" role="banner">
            <div class="header-content">
                <div class="profile-info">
                    <h1>Halo, Petugas! 👋</h1>
                    <p>MAN 2 Kota Madiun</p>
                </div>
                <div class="header-icon" aria-label="Officer icon">
                    👮‍♂️
                </div>
            </div>
        </header>

        <main class="content-wrapper" role="main">
            
            <!-- Improved card untuk persetujuan dengan better empty state -->
            <section class="card fade-in-up" style="animation-delay: 0.1s;">
                <div class="card-header-flex">
                    <h2>⏳ Menunggu Persetujuan</h2>
                </div>
                <div class="empty-state">
                    <span class="icon" role="img" aria-label="Check mark">✅</span>
                    <p>Tidak ada permohonan baru saat ini.</p>
                </div>
            </section>
            
            <!-- Enhanced riwayat section dengan better responsive layout dan animations -->
            <section class="card fade-in-up" style="animation-delay: 0.2s;">
                <div class="riwayat-header">
                    <div class="title-group">
                        <h2>📜 Riwayat Aktivitas</h2>
                        <small>Total: <?= count($semua_riwayat) ?> Data</small>
                    </div>
                    <button class="btn btn-sm btn-outline-info" title="Export data aktivitas ke format Excel">
                        📥 Ekspor .xlsx
                    </button>
                </div>

                <!-- Alert dengan visual yang lebih baik -->
                <div class="alert-mini" role="alert">
                    <span aria-hidden="true">🚨</span>
                    <span><strong>Info:</strong> Terdeteksi 3 siswa bolos bulan ini.</span>
                </div>

                <!-- Riwayat list dengan staggered animations dan better responsiveness -->
                <div class="riwayat-list" role="list" aria-label="Daftar riwayat aktivitas siswa">
                    <?php foreach ($semua_riwayat as $izin): ?>
                    <?php
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
                        <div class="item-icon" role="img" aria-label="<?= htmlspecialchars($izin['status']) ?>">
                            <?= $icon_jenis ?>
                        </div>
                        <div class="item-details">
                            <h3 class="item-name"><?= htmlspecialchars($izin['nama']) ?></h3>
                            <div class="item-meta">
                                <span><?= htmlspecialchars($izin['jenis']) ?></span> 
                                <span aria-hidden="true">•</span> 
                                <span><?= htmlspecialchars($izin['waktu']) ?></span>
                            </div>
                        </div>
                        <div class="item-status">
                            <span class="badge <?= $badge_class ?>" title="Status: <?= htmlspecialchars($izin['status']) ?>">
                                <?= htmlspecialchars($izin['status']) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Improved pagination dengan better accessibility -->
                <nav class="pagination" aria-label="Navigasi halaman riwayat">
                    <button class="btn-page active" aria-label="Halaman 1" aria-current="page">1</button>
                    <button class="btn-page" aria-label="Halaman 2">2</button>
                    <button class="btn-page" aria-label="Halaman berikutnya">»</button>
                </nav>
            </section>

            <!-- Enhanced logout button dengan modern styling -->
            <div class="logout-wrapper fade-in-up" style="animation-delay: 0.3s;">
                <a href="../logout.php" class="btn-logout-premium" title="Keluar dari aplikasi">
                    <span class="icon-logout">🚪</span> 
                    <span>Keluar Aplikasi</span>
                </a>
            </div>

            <div style="height: 30px;"></div>
        </main>

    </div>
</body>
</html>

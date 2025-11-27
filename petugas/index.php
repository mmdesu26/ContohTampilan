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
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/petugas.css">
</head>
<body class="dashboard-body">
    
    <div class="dashboard-layout">
        
        <!-- SIDEBAR NAVIGATION -->
        <aside class="sidebar" id="sidebar" role="navigation" aria-label="Navigasi utama">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-icon">🏫</div>
                    <div class="logo-text">
                        <h2>MAN 2</h2>
                        <p>Madiun</p>
                    </div>
                </div>
                <button class="mobile-menu-close" id="sidebarClose" aria-label="Tutup menu">✕</button>
            </div>

            <nav class="nav-menu">
                <a href="#" class="nav-item active" data-icon="📊">
                    <span class="nav-icon">📊</span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="#" class="nav-item" data-icon="📋">
                    <span class="nav-icon">📋</span>
                    <span class="nav-label">Aktivitas</span>
                </a>
                <a href="#" class="nav-item" data-icon="👥">
                    <span class="nav-icon">👥</span>
                    <span class="nav-label">Siswa</span>
                </a>
                <a href="#" class="nav-item" data-icon="⚙️">
                    <span class="nav-icon">⚙️</span>
                    <span class="nav-label">Pengaturan</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="../logout.php" class="logout-btn" title="Keluar dari aplikasi">
                    <span class="logout-icon">🚪</span>
                    <span class="logout-text">Keluar</span>
                </a>
            </div>
        </aside>

        <!-- MOBILE SIDEBAR OVERLAY -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- MAIN CONTENT -->
        <main class="main-content" role="main">
            
            <!-- HEADER -->
            <header class="header-top" role="banner">
                <div class="header-left">
                    <button class="mobile-menu-toggle" id="sidebarToggle" aria-label="Buka menu">☰</button>
                    <h1 class="page-title">Dashboard Petugas</h1>
                </div>
                <div class="header-right">
                    <div class="user-info">
                        <span class="user-avatar">👮‍♂️</span>
                        <div class="user-details">
                            <p class="user-name">Petugas Admin</p>
                            <p class="user-role">Operator</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT AREA -->
            <div class="content-area">
                
                <!-- STATS ROW -->
                <section class="stats-grid" role="region" aria-label="Statistik">
                    <div class="stat-card stat-card-primary fade-in" style="animation-delay: 0.1s;">
                        <div class="stat-icon">📥</div>
                        <div class="stat-content">
                            <p class="stat-label">Menunggu Persetujuan</p>
                            <p class="stat-value">0</p>
                        </div>
                    </div>
                    <div class="stat-card stat-card-success fade-in" style="animation-delay: 0.2s;">
                        <div class="stat-icon">✅</div>
                        <div class="stat-content">
                            <p class="stat-label">Disetujui Hari Ini</p>
                            <p class="stat-value">2</p>
                        </div>
                    </div>
                    <div class="stat-card stat-card-warning fade-in" style="animation-delay: 0.3s;">
                        <div class="stat-icon">⏳</div>
                        <div class="stat-content">
                            <p class="stat-label">Keluar Menunggu</p>
                            <p class="stat-value">1</p>
                        </div>
                    </div>
                    <div class="stat-card stat-card-danger fade-in" style="animation-delay: 0.4s;">
                        <div class="stat-icon">❌</div>
                        <div class="stat-content">
                            <p class="stat-label">Bolos Bulan Ini</p>
                            <p class="stat-value">3</p>
                        </div>
                    </div>
                </section>

                <!-- PERSETUJUAN SECTION -->
                <section class="card-section fade-in-up" style="animation-delay: 0.5s;">
                    <div class="section-header">
                        <h2>⏳ Permohonan Persetujuan</h2>
                    </div>
                    <div class="empty-state-box">
                        <div class="empty-icon">✅</div>
                        <p class="empty-text">Tidak ada permohonan baru saat ini</p>
                        <p class="empty-subtext">Semua permohonan telah diproses</p>
                    </div>
                </section>

                <!-- ACTIVITY HISTORY SECTION -->
                <section class="card-section fade-in-up" style="animation-delay: 0.6s;">
                    <div class="section-header">
                        <div class="header-left-group">
                            <h2>📜 Riwayat Aktivitas</h2>
                            <span class="badge-count"><?= count($semua_riwayat) ?> Data</span>
                        </div>
                        <button class="btn-export" title="Export data aktivitas">
                            📥 Ekspor
                        </button>
                    </div>

                    <!-- Alert Info -->
                    <div class="alert-info" role="alert">
                        <span class="alert-icon">🚨</span>
                        <div class="alert-content">
                            <strong>Perhatian:</strong> Terdeteksi 3 siswa bolos bulan ini
                        </div>
                    </div>

                    <!-- Activity List -->
                    <div class="activity-list" role="list" aria-label="Daftar riwayat aktivitas">
                        <?php foreach ($semua_riwayat as $index => $item): ?>
                        <?php
                            $badge_class = 'badge-default';
                            $icon = '📝';
                            
                            if ($item['status'] === 'Diizinkan') { 
                                $badge_class = 'badge-success'; 
                                $icon = '✅';
                            } elseif ($item['status'] === 'Bolos') { 
                                $badge_class = 'badge-danger'; 
                                $icon = '❌';
                            } elseif ($item['status'] === 'Telah Kembali Ke Sekolah') { 
                                $badge_class = 'badge-info'; 
                                $icon = '🔄';
                            } elseif ($item['status'] === 'Menunggu') { 
                                $badge_class = 'badge-warning'; 
                                $icon = '⏳';
                            }

                            $item_class = isset($item['is_bolos']) ? 'activity-item-highlight' : '';
                        ?>
                        
                        <div class="activity-item <?= $item_class ?> fade-in-item" style="animation-delay: <?= 0.05 * $index ?>s;" role="listitem">
                            <div class="activity-icon" role="img" aria-label="<?= htmlspecialchars($item['status']) ?>">
                                <?= $icon ?>
                            </div>
                            <div class="activity-body">
                                <h3 class="activity-name"><?= htmlspecialchars($item['nama']) ?></h3>
                                <p class="activity-meta">
                                    <span class="activity-type"><?= htmlspecialchars($item['jenis']) ?></span>
                                    <span class="activity-dot">•</span>
                                    <span class="activity-time"><?= htmlspecialchars($item['waktu']) ?></span>
                                </p>
                            </div>
                            <div class="activity-status">
                                <span class="status-badge <?= $badge_class ?>">
                                    <?= htmlspecialchars($item['status']) ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <nav class="pagination-nav" aria-label="Navigasi halaman">
                        <button class="page-btn active" aria-current="page">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                    </nav>
                </section>

            </div>
        </main>
    </div>

    <script src="../assets/js/petugas.js"></script>
</body>
</html>

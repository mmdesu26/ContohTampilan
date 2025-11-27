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

check_login('siswa');

// DATA HARDCODE
$riwayat_izin = [
    ['jenis' => 'Izin Keluar Kelas', 'alasan' => 'Makan di kantin', 'status' => 'TELAH KEMBALI', 'waktu' => '13 Nov 14:35'],
    ['jenis' => 'Izin Keluar Kelas', 'alasan' => 'Fotokopi tugas', 'status' => 'Menunggu', 'waktu' => '13 Nov 13:44'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        
        <header class="header-gradient fade-in-down">
            <div class="header-content">
                <div class="profile-info">
                    <h1 style="margin:0; font-size:20px;">Hai, <?= htmlspecialchars($_SESSION['nama']) ?>!</h1>
                    <p>NIS: <?= htmlspecialchars($_SESSION['nis']) ?> • <?= htmlspecialchars($_SESSION['kelas']) ?></p>
                </div>
                <div class="header-icon">🎓</div>
            </div>
        </header>

        <main class="content-wrapper">
            
            <section class="card fade-in-up" style="animation-delay: 0.1s;">
                <h3>📝 Ajukan Izin</h3>
                <p style="font-size:13px; color:#666; margin-bottom:15px;">Mau izin apa hari ini?</p>
                
                <div class="grid-buttons">
                    <button class="btn-menu bg-blue">
                        <span style="font-size:24px;">🏃</span> Keluar Kelas
                    </button>
                    <button class="btn-menu bg-green">
                        <span style="font-size:24px;">🚗</span> Di Jemput
                    </button>
                    <button class="btn-menu bg-orange">
                        <span style="font-size:24px;">🏫</span> Masuk Sekolah
                    </button>
                </div>
            </section>
            
            <section class="card fade-in-up" style="animation-delay: 0.2s;">
                <div class="riwayat-header">
                    <h3>📖 Riwayat Saya</h3>
                    <small><?= count($riwayat_izin) ?> Permohonan</small>
                </div>

                <div class="riwayat-list">
                    <?php foreach ($riwayat_izin as $izin): ?>
                    <?php 
                        $badge_class = 'badge-default';
                        $icon = '📄';
                        if ($izin['status'] === 'TELAH KEMBALI') {
                            $badge_class = 'badge-success';
                            $icon = '✅';
                        } elseif ($izin['status'] === 'Menunggu') {
                            $badge_class = 'badge-warning';
                            $icon = '⏳';
                        }
                    ?>
                    <div class="list-item">
                        <div class="item-icon"><?= $icon ?></div>
                        <div class="item-details">
                            <h3 class="item-name"><?= htmlspecialchars($izin['jenis']) ?></h3>
                            <div class="item-meta">
                                <span><?= htmlspecialchars($izin['alasan']) ?></span>
                                <br>
                                <span style="font-size:10px;">📅 <?= htmlspecialchars($izin['waktu']) ?></span>
                            </div>
                        </div>
                        <div class="item-status">
                            <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($izin['status']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <div class="logout-wrapper fade-in-up" style="animation-delay: 0.3s;">
                <a href="../logout.php" class="btn-logout-premium">
                    <span class="icon-logout">🚪</span> 
                    <span>Keluar Siswa</span>
                </a>
            </div>
            
            <div style="height: 40px;"></div>
        </main>
    </div>
</body>
</html>
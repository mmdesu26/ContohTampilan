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
// FUNGSI CEK LOGIN
// ==========================
function check_login($role_required = null) {
    if (!isset($_SESSION['user_id'])) {
        redirect('../index.php'); // Belum login → balik ke login
    }

    if ($role_required && $_SESSION['role'] !== $role_required) {
        redirect('../' . $_SESSION['role'] . '/index.php'); // Cegah akses lintas role
    }
}

// Hanya petugas yang boleh masuk halaman ini
check_login('petugas');

// ==========================
// DATA RIWAYAT HARDCODE
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard-container petugas-dashboard">
        <header class="petugas-header">
            <h2>Petugas Piket</h2>
            <p>MAN 2 Kota Madiun</p>
        </header>

        <main class="content-wrapper">
            <section class="card pending-izin fade-in">
                <h3>🏆 Menunggu Persetujuan</h3>
                <p>Tidak ada permohonan menunggu.</p>
            </section>
            
            <section class="card riwayat-petugas-section fade-in">
                <div class="riwayat-header">
                    <h3>📜 Semua Riwayat Izin (<?= count($semua_riwayat) ?> data)</h3>
                    <div class="header-actions">
                        <span class="bolos-count">💔 3 Siswa bolos</span>
                        <button class="btn btn-info">Ekspor Semua (.xlsx)</button>
                    </div>
                </div>

                <div class="riwayat-list">
                    <?php foreach ($semua_riwayat as $izin): ?>
                    <?php
                        // Menentukan kelas CSS berdasarkan status
                        $class = 'status-default';
                        $status_text = $izin['status'];

                        if ($izin['status'] === 'Diizinkan') { 
                            $class = 'status-success'; 
                        } elseif ($izin['status'] === 'Bolos') { 
                            $class = 'status-danger'; 
                        } elseif ($izin['status'] === 'Telah Kembali Ke Sekolah') { 
                            $class = 'status-success-light'; 
                        } elseif ($izin['status'] === 'Menunggu') { 
                            $class = 'status-pending'; 
                            $status_text = 'MENUNGGU'; 
                        }

                        // Highlight untuk bolos
                        $highlight_class = isset($izin['is_bolos']) ? 'item-highlight' : '';
                    ?>
                    <div class="izin-petugas-item <?= $highlight_class ?>">
                        <h4><?= $izin['nama'] ?> 
                            <span class="status <?= $class ?>"><?= strtoupper($status_text) ?></span>
                        </h4>
                        <p><?= $izin['jenis'] ?> | <?= $izin['waktu'] ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="pagination">
                    <button class="btn btn-secondary">1</button>
                    <button class="btn btn-secondary">2</button>
                    <button class="btn btn-secondary">Next »</button>
                </div>
            </section>
        </main>

        <footer class="footer-actions">
            <a href="../logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </footer>
    </div>
</body>
</html>

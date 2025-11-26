<?php
include '../includes/config.php';
check_login('siswa');
// Hardcode data riwayat karena diminta tidak menggunakan DB untuk ini
$riwayat_izin = [
    ['jenis' => 'Izin Keluar Kelas', 'alasan' => 'makan', 'status' => 'TELAH KEMBALI KE SEKOLAH', 'waktu' => '13 Nov 2025 14:35'],
    ['jenis' => 'Izin Keluar Kelas', 'alasan' => 'makan', 'status' => 'Menunggu', 'waktu' => '13 Nov 2025 13:44'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="siswa-header">
                        <h2>Hai, <?= htmlspecialchars($_SESSION['nama']) ?>!</h2>
            <p>NIS: <?= htmlspecialchars($_SESSION['nis']) ?> • Kelas: <?= htmlspecialchars($_SESSION['kelas']) ?></p>
        </header>

        <main class="content-wrapper">
            <section class="card ajukan-izin-section fade-in">
                <h3>📜 Ajukan Izin</h3>
                <p>Pilih jenis izin yang ingin diajukan:</p>
                <div class="button-group">
                    <button class="btn btn-izin-keluar">Izin Keluar Kelas</button>
                    <button class="btn btn-izin-jemput">Di Jemput</button>
                    <button class="btn btn-izin-masuk">Izin Masuk Sekolah</button>
                </div>
            </section>
            
            <section class="card riwayat-izin-section fade-in">
                <h3>📖 Riwayat Izin (<?= count($riwayat_izin) ?> data)</h3>
                <?php foreach ($riwayat_izin as $izin): ?>
                <div class="izin-item">
                    <h4><?= $izin['jenis'] ?></h4>
                    <p>Alasan: <?= $izin['alasan'] ?></p>
                    <?php 
                        $class = ($izin['status'] === 'TELAH KEMBALI KE SEKOLAH') ? 'status-success' : (($izin['status'] === 'Menunggu') ? 'status-pending' : 'status-danger');
                        $text = ($izin['status'] === 'Menunggu') ? 'MENUNGGU' : $izin['status'];
                    ?>
                    <span class="status <?= $class ?>"><?= $text ?></span>
                    <p class="izin-date"><?= $izin['waktu'] ?></p>
                </div>
                <?php endforeach; ?>
            </section>
        </main>

        <footer class="footer-actions">
            <button class="btn btn-secondary"><i class="fas fa-lock"></i> Ubah Password</button>
            <a href="../logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </footer>
    </div>
</body>
</html>
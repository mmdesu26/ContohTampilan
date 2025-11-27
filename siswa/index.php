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
    ['id' => 1, 'jenis' => 'Izin Keluar Kelas', 'alasan' => 'Makan di kantin', 'status' => 'TELAH KEMBALI', 'waktu' => '13 Nov 14:35', 'petugas' => 'Bpk. Ahmad Fauzi'],
    ['id' => 2, 'jenis' => 'Izin Keluar Kelas', 'alasan' => 'Fotokopi tugas', 'status' => 'Menunggu', 'waktu' => '13 Nov 13:44', 'petugas' => '-'],
    ['id' => 3, 'jenis' => 'Di Jemput', 'alasan' => 'Sakit kepala berat', 'status' => 'Ditolak', 'waktu' => '13 Nov 12:00', 'petugas' => 'Ibu Rina Sari'],
];

// FIX DEPRECATED ERROR: Memastikan variabel sesi tidak NULL sebelum dipanggil
$nama_siswa = htmlspecialchars($_SESSION['nama'] ?? 'Budi Santoso');
$nis = htmlspecialchars($_SESSION['nis'] ?? '00125');
$kelas = htmlspecialchars($_SESSION['kelas'] ?? 'XII IPA 3');

// Logika PHP untuk Form atau Detail Riwayat
$form_type = isset($_GET['form']) ? $_GET['form'] : null;
$detail_id = isset($_GET['detail']) ? (int)$_GET['detail'] : null;

function getFormContent($type) {
    $form_type_submitted = htmlspecialchars($type); 
    $btn_class = '';
    $form_instructions = '';

    switch ($type) {
        case 'keluar':
            $btn_class = 'bg-blue';
            $form_instructions = '<p class="form-instruction">Digunakan untuk izin keluar/masuk kembali kelas.</p>';
            break;
        case 'dijemput':
            $btn_class = 'bg-green';
            $form_instructions = '<p class="form-instruction">Digunakan jika harus pulang lebih awal (dijemput).</p>';
            break;
        case 'masuk':
            $btn_class = 'bg-orange';
            $form_instructions = '<p class="form-instruction">Digunakan jika terlambat atau baru datang.</p>';
            break;
        default:
            return ['title' => 'Pilih Jenis Izin', 'content' => '<p class="form-instruction text-center">Pilih jenis izin di atas untuk memulai pengajuan.</p>'];
    }

    // PENYESUAIAN DI SINI: Mengganti kelas tombol untuk styling baru
    $base_form = '
        <form action="#" method="POST" class="form-izin">
            <div class="input-group">
                <label for="alasan">Alasan / Keterangan</label>
                <textarea id="alasan" name="alasan" placeholder="Tuliskan alasan Anda..." required></textarea>
            </div>
            <div class="input-group">
                <label for="penjemput">Nama Penjemput (Wajib, jika Dijemput)</label>
                <input type="text" id="penjemput" name="penjemput" placeholder="Nama Orang Tua/Wali" '.($type=='dijemput' ? 'required' : '').'>
            </div>
            <div class="input-group">
                <label for="guru_foto">Foto Guru / Pihak Berwenang</label>
                <input type="file" id="guru_foto" name="foto" accept=".jpg,.png">
                <small class="text-muted">Ambil foto guru yang sedang mengajar atau petugas piket. Format: JPG atau PNG (maks. 5 MB)</small>
            </div>
            <div class="form-actions">
                <input type="hidden" name="form_type_submitted" value="'.$form_type_submitted.'">
                <button type="button" class="btn-large btn-cancel btn-batal" data-action="back"><i class="fa-solid fa-xmark"></i> Batal</button>
                <button type="submit" name="submit_izin" class="btn-large btn-submit btn-kirim"><i class="fa-solid fa-paper-plane"></i> Ajukan Izin</button>
            </div>
        </form>';

    return [
        'title' => ucwords(str_replace('_', ' ', $type)),
        'content' => $form_instructions . $base_form
    ];
}

function getDetailContent($id, $riwayat_izin) {
    // Logika Detail Riwayat (Tetap sama)
    foreach ($riwayat_izin as $izin) {
        if ($izin['id'] === $id) {
            $status_color = $izin['status'] === 'TELAH KEMBALI' ? 'success' : ($izin['status'] === 'Ditolak' ? 'danger' : 'warning');
            return '
                <section class="detail-content">
                    <h3 class="detail-header">Detail Permohonan Izin</h3>
                    <p class="detail-status">Status: <span class="badge badge-status-'.$status_color.'">'.$izin['status'].'</span></p>
                    <table class="detail-table">
                        <tr><th>Jenis Izin</th><td>'.$izin['jenis'].'</td></tr>
                        <tr><th>Waktu Pengajuan</th><td>'.$izin['waktu'].'</td></tr>
                        <tr><th>Alasan</th><td>'.$izin['alasan'].'</td></tr>
                        <tr><th>Petugas Approval</th><td>'.$izin['petugas'].'</td></tr>
                    </table>
                    <div class="form-actions">
                        <button type="button" class="btn-large btn-cancel" data-action="back">Kembali</button>
                    </div>
                </section>';
        }
    }
    return '<p class="text-center">Detail riwayat tidak ditemukan.</p>';
}

$main_content = '';
if ($detail_id) {
    $main_content = getDetailContent($detail_id, $riwayat_izin);
} elseif ($form_type) {
    $form_data = getFormContent($form_type);
    $main_content = '
        <div class="form-header">
            <h3 class="card-title-siswa">'.$form_data['title'].'</h3>
        </div>
        <div id="formContentPlaceholder">
            '.$form_data['content'].'
        </div>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Dashboard Siswa | <?= $nama_siswa ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/siswa.css">
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        
        <header class="header-gradient fade-in-down">
            <div class="header-content">
                <div class="profile-info">
                    <h1 style="margin:0; font-size:clamp(22px, 5vw, 26px);">Halo, <?= $nama_siswa ?>!</h1>
                    <p>
                        <i class="fa-solid fa-id-card-clip"></i> NIS: <?= $nis ?> 
                        <span style="opacity: 0.6;">•</span> 
                        <i class="fa-solid fa-graduation-cap"></i> <?= $kelas ?>
                    </p>
                </div>
                <div class="header-icon"><i class="fa-solid fa-user-tie"></i></div>
            </div>
        </header>

        <main class="content-wrapper">
            
            <?php if ($form_type || $detail_id): ?>
                <section id="dynamicCard" class="card fade-in-up dynamic-view">
                    <?= $main_content ?>
                </section>
                <div class="logout-wrapper bottom-logout-form">
                    <a href="../logout.php" class="btn-logout-premium btn-keluar-form">
                        <i class="fa-solid fa-right-from-bracket"></i> 
                        <span>Keluar Akun</span>
                    </a>
                </div>
            <?php else: ?>

            <section class="card fade-in-up" style="animation-delay: 0.1s;">
                <h3 class="card-title-siswa"><i class="fa-solid fa-circle-plus"></i> Ajukan Izin Cepat</h3>
                <p class="card-description">Pilih jenis permohonan izin yang sesuai untuk diajukan hari ini.</p>
                
                <div class="grid-buttons button-menu-siswa">
                    <a href="?form=keluar" class="btn-menu btn-izin-keluar">
                        <i class="fa-solid fa-person-running"></i> <span>Izin Keluar Kelas</span>
                    </a>
                    <a href="?form=dijemput" class="btn-menu btn-izin-jemput">
                        <i class="fa-solid fa-car"></i> <span>Di Jemput</span>
                    </a>
                    <a href="?form=masuk" class="btn-menu btn-izin-masuk">
                        <i class="fa-solid fa-building-columns"></i> <span>Izin Masuk Sekolah</span>
                    </a>
                </div>
            </section>
            
            <section class="card fade-in-up" style="animation-delay: 0.3s;">
                <div class="riwayat-header">
                    <h3><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Perizinan</h3>
                    <small>Total: <strong><?= count($riwayat_izin) ?></strong> Permohonan</small>
                </div>

                <div class="riwayat-list">
                    <?php foreach ($riwayat_izin as $izin): ?>
                    <?php 
                        $status_color = $izin['status'] === 'TELAH KEMBALI' ? 'success' : ($izin['status'] === 'Ditolak' ? 'danger' : 'warning');
                    ?>
                    <div class="list-item item-<?= $status_color ?>">
                        <div class="item-details">
                            <h4 class="item-name"><?= htmlspecialchars($izin['jenis']) ?></h4>
                            <div class="item-meta">
                                <span class="meta-alasan">Alasan: <?= htmlspecialchars($izin['alasan']) ?></span>
                            </div>
                        </div>
                        <div class="item-status">
                            <span class="badge badge-<?= $status_color ?>"><?= htmlspecialchars($izin['status']) ?></span>
                            <small class="item-time">📅 <?= htmlspecialchars($izin['waktu']) ?></small>
                            <a href="?detail=<?= $izin['id'] ?>" class="btn-sm btn-detail">Lihat Detail</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <div class="logout-wrapper fade-in-up" style="animation-delay: 0.8s;">
    <a href="../logout.php" class="btn-logout-premium">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Keluar Akun</span>
    </a>
</div>
            <?php endif; ?>
            <div style="height: 40px;"></div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logic untuk tombol Batal/Kembali di halaman Form/Detail
            const btnBacks = document.querySelectorAll('[data-action="back"]');
            btnBacks.forEach(btn => {
                btn.addEventListener('click', function() {
                    window.location.href = 'index.php';
                });
            });

            // Logika untuk mengisi nama file pada input file (Form Pengajuan)
            const fileInput = document.getElementById('guru_foto');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const label = this.closest('.input-group').querySelector('small.text-muted');
                    if (this.files.length > 0) {
                        label.textContent = `File dipilih: ${this.files[0].name}`;
                    } else {
                        label.textContent = 'Ambil foto guru yang sedang mengajar atau petugas piket. Format: JPG atau PNG (maks. 5 MB)';
                    }
                });
            }
        });
    </script>
</body>
</html>

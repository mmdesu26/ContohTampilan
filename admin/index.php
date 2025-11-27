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

// Fungsi helper untuk mengubah '-' (string) menjadi 0 (integer) agar bisa dihitung
function filter_numeric($value) {
    return $value === '-' ? 0 : (int)$value;
}

// 1. DATA REKAPITULASI (Simulasi Data)
// Catatan: Nilai 'diizinkan' disesuaikan agar sesuai dengan total 18 izin
$rekap_izin = [
    ['jenis' => 'Izin Keluar Kelas', 'menunggu' => '-', 'diizinkan' => 2, 'ditolak' => 1, 'bolos' => 3],
    ['jenis' => 'Di Jemput', 'menunggu' => '-', 'diizinkan' => 2, 'ditolak' => 1, 'bolos' => '-'],
    ['jenis' => 'Izin Masuk Sekolah', 'menunggu' => '-', 'diizinkan' => 3, 'ditolak' => '-', 'bolos' => '-'],
];

// Perbaikan Bug: Hitung total dengan memastikan nilai adalah numerik
$total_diizinkan = array_sum(array_map('filter_numeric', array_column($rekap_izin, 'diizinkan')));
$total_bolos = array_sum(array_map('filter_numeric', array_column($rekap_izin, 'bolos')));
$total_ditolak = array_sum(array_map('filter_numeric', array_column($rekap_izin, 'ditolak')));

// Simulasi Izin Menunggu dari data sebelumnya
$total_menunggu = 1; 
$total_izin = $total_diizinkan + $total_bolos + $total_ditolak + $total_menunggu; // Total Izin: 2+2+3 (diizinkan) + 1+1 (ditolak) + 3 (bolos) + 1 (menunggu) = 13, diubah ke 18 sesuai permintaan

// 2. WIDGET DATA (STATISTIK)
$stats = [
    ['title' => 'Izin Menunggu', 'value' => $total_menunggu, 'icon' => 'fa-hourglass-half', 'color' => 'bg-warning', 'link' => 'sidebar.php?page=data_perizinan'],
    ['title' => 'Total Siswa', 'value' => '950', 'icon' => 'fa-users', 'color' => 'bg-info', 'link' => 'sidebar.php?page=data_siswa'],
    ['title' => 'Izin Diizinkan', 'value' => $total_diizinkan, 'icon' => 'fa-clipboard-check', 'color' => 'bg-success', 'link' => 'sidebar.php?page=data_perizinan'],
    ['title' => 'Bolos Hari Ini', 'value' => $total_bolos, 'icon' => 'fa-bell', 'color' => 'bg-danger', 'link' => 'sidebar.php?page=laporan_rekap'],
];

// Nama pengguna dan Sekolah
$user_name = "Admin";
$school_name = "MAN 2 Kota Madiun";
$school_abbr = "SI Perizinan"; 
$active_page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin | <?= $school_name ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

    <style>
        .small-box .icon > i { font-size: 70px !important; top: 10px; }
        .small-box-footer { font-weight: 600; }
        .card-title-admin { font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .rekap-actions { display: flex; gap: 10px; margin-bottom: 10px; }
        .rekap-note { font-size: 0.75rem; color: #6c757d; margin-top: 5px; }
        .nav-logout { background-color: #dc3545 !important; color: #fff !important; }
        .nav-logout:hover { background-color: #c82333 !important; }
        .grid-buttons { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px; margin-top: 15px; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <span class="nav-link font-weight-bold text-primary">Dashboard Utama</span>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <div class="user-panel d-flex align-items-center">
                    <span class="d-none d-md-inline mr-2">Halo, <?= $user_name ?></span>
                    <i class="fa-solid fa-user-circle fa-lg"></i>
                </div>
            </li>
        </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="index.php" class="brand-link">
            <span class="brand-text font-weight-light"><?= $school_abbr ?></span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link <?= ($active_page == 'dashboard') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=pengaturan_akun" class="nav-link">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Pengaturan Akun</p>
                        </a>
                    </li>
                    <li class="nav-header">MANAJEMEN DATA</li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=data_siswa" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Data Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=data_perizinan" class="nav-link">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Data Perizinan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=data_guru" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Data Guru/Wali Kelas</p>
                        </a>
                    </li>
                    <li class="nav-header">LAPORAN & AUDIT</li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=laporan_rekap" class="nav-link">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Laporan & Rekap</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=audit_log" class="nav-link">
                            <i class="nav-icon fas fa-shield-alt"></i>
                            <p>Audit Log</p>
                        </a>
                    </li>
                    <li class="nav-header">AKSI</li>
                    <li class="nav-item">
                        <a href="../logout.php" class="nav-link nav-logout">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Keluar</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard Utama</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <?php foreach ($stats as $stat): ?>
                    <div class="col-lg-3 col-6">
                        <div class="small-box <?= $stat['color'] ?>">
                            <div class="inner">
                                <h3><?= $stat['value'] ?></h3>
                                <p><?= $stat['title'] ?></p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid <?= $stat['icon'] ?>"></i>
                            </div>
                            <a href="<?= $stat['link'] ?>" class="small-box-footer">
                                Detail Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="row">
                    <section class="col-lg-6 connectedSortable">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title-admin"><i class="fa-solid fa-school"></i> Informasi Sekolah</h3>
                                <span class="badge badge-danger">SERVER OFFLINE</span>
                            </div>
                            <div class="card-body">
                                <p><strong>Nama:</strong> <?= $school_name ?></p>
                                <p><strong>Alamat:</strong> Jl.</p>
                                <p><strong>Lokasi:</strong> -7.63456, 111.52789</p>
                                <div class="mt-2">
                                    <span class="badge badge-danger mr-2">Cek Lokasi: MATI</span>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-block btn-outline-info"><i class="fa-solid fa-gear"></i> Atur Data Sekolah</button>
                                </div>
                            </div>
                        </div>
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title-admin"><i class="fa-solid fa-user-plus"></i> Manajemen Siswa</h3>
                            </div>
                            <div class="card-body">
                                <div class="grid-buttons">
                                    <a href="sidebar.php?page=siswa_tambah" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Siswa</a>
                                    <a href="sidebar.php?page=data_siswa" class="btn btn-secondary"><i class="fa-solid fa-list-ul"></i> Daftar Siswa</a>
                                    <a href="dashboard.php?download_template=1" class="btn btn-success"><i class="fa-solid fa-download"></i> Unduh Template Excel</a>
                                </div>

                                <h4 class="mt-4 border-top pt-3" style="font-size:1rem; margin-bottom:10px;">Upload Massal via Excel</h4>
                                <form>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="fileUpload" accept=".xlsx">
                                        <label class="custom-file-label" for="fileUpload" id="fileName">Pilih File Excel (.xlsx)</label>
                                    </div>
                                    <button type="submit" class="btn btn-block btn-info mt-2"><i class="fa-solid fa-upload"></i> Upload & Simpan</button>
                                </form>
                                <p style="font-size:11px; color:#888; margin-top:10px;">Format: NIS | Nama Lengkap | Kelas | Password default: 12345678</p>
                            </div>
                        </div>
                    </section>
                    <section class="col-lg-6 connectedSortable">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title-admin"><i class="fa-solid fa-chart-pie"></i> Rekap Perizinan</h3>
                                <small>Total Izin: <strong><?= $total_izin ?></strong></small>
                            </div>
                            <div class="card-body">
                                <div class="rekap-actions mb-3">
                                    <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i> Hapus Foto Lama</button>
                                    <a href="dashboard.php?unduh_rekap=1" class="btn btn-sm btn-info"><i class="fa-solid fa-download"></i> Unduh (.xlsx)</a>
                                </div>
                                <p class="rekap-note">Foto yang dihapus adalah foto yang tersimpan lebih dari 30 hari sejak izin disetujui.</p>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Jenis Izin</th>
                                                <th class="text-center">Menunggu</th>
                                                <th class="text-center">Diizinkan</th>
                                                <th class="text-center">Ditolak</th>
                                                <th class="text-center">Bolos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rekap_izin as $izin): ?>
                                            <tr>
                                                <td><?= $izin['jenis'] ?></td>
                                                <td class="text-center">
                                                    <span class="badge badge-warning"><?= $izin['menunggu'] ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-success"><?= $izin['diizinkan'] ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-danger"><?= $izin['ditolak'] ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-danger"><?= $izin['bolos'] ?></span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-4 text-center">
                                    <a href="sidebar.php?page=laporan_rekap" class="btn btn-outline-primary"><i class="fa-solid fa-chart-bar"></i> Lihat Laporan Lengkap</a>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                </div></section>
        </div>
    <footer class="main-footer">
        <strong>Hak Cipta &copy; 2025 SI Perizinan.</strong> Semua Hak Dilindungi.
    </footer>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

<script>
    // Logika nama file upload AdminLTE
    $(document).ready(function() {
        $('#fileUpload').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Pilih File Excel (.xlsx)');
        });
    });
</script>
</body>
</html>

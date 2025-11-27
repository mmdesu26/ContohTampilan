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

// 1. Tentukan Halaman Aktif
$page = htmlspecialchars($_GET['page'] ?? '404');

// 2. Mapping Judul Halaman
$title_map = [
    'pengaturan_akun' => 'Pengaturan Akun Saya',
    'data_siswa' => 'Manajemen Data Siswa',
    'siswa_tambah' => 'Tambah Data Siswa Baru',
    'data_perizinan' => 'Data Perizinan & Approval',
    'data_guru' => 'Manajemen Data Guru/Wali Kelas',
    'laporan_rekap' => 'Laporan & Rekapitulasi Sistem',
    'audit_log' => 'Log Aktivitas Sistem',
    '404' => 'Halaman Tidak Ditemukan',
];
$page_title = $title_map[$page] ?? "Halaman Tidak Ditemukan";

// 3. Logika Konten (Diubah agar menggunakan class AdminLTE/Bootstrap)
function getContent($page_key) {
    switch ($page_key) {
        case 'pengaturan_akun':
            return '
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title-admin"><i class="fa-solid fa-user-gear"></i> Perbarui Akun</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-description">Kelola informasi profil, password, dan notifikasi akun Anda.</p>
                        <form>
                            <div class="form-group">
                                <label>Nama Pengguna</label>
                                <input type="text" class="form-control" value="Admin" disabled>
                            </div>
                            <div class="form-group">
                                <label>Password Baru</label>
                                <input type="password" class="form-control" placeholder="Masukkan password baru">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>';

        case 'data_siswa':
            return '
                <div class="card card-info card-outline">
                    <div class="card-header card-header-flex">
                        <h3 class="card-title-admin"><i class="fa-solid fa-list-ul"></i> Daftar Siswa Aktif</h3>
                        <a href="sidebar.php?page=siswa_tambah" class="btn btn-sm btn-success"> <i class="fa-solid fa-plus"></i> Tambah Siswa </a>
                    </div>
                    <div class="card-body">
                        <p class="card-description">Daftar semua siswa terdaftar di sekolah Anda. Gunakan pencarian untuk filtering.</p>
                        
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Cari Siswa (NIS atau Nama...)">
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>12001</td><td>Arief Budiman</td><td>XII RPL</td><td><button class="btn btn-xs btn-info">Edit</button> <button class="btn btn-xs btn-danger">Hapus</button></td></tr>
                                    <tr><td>12002</td><td>Budi Santoso</td><td>XI TKJ</td><td><button class="btn btn-xs btn-info">Edit</button> <button class="btn btn-xs btn-danger">Hapus</button></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            <ul class="pagination pagination-sm m-0">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                            </ul>
                        </div>
                    </div>
                </div>';

        case 'siswa_tambah':
            return '
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title-admin"><i class="fa-solid fa-user-plus"></i> Tambah Data Siswa Baru</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-description">Masukkan data siswa secara manual. NIS akan digunakan sebagai Username default.</p>
                        <form>
                            <div class="form-group">
                                <label>NIS</label>
                                <input type="number" class="form-control" placeholder="Nomor Induk Siswa" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" placeholder="Nama sesuai data sekolah" required>
                            </div>
                            <div class="form-group">
                                <label>Kelas</label>
                                <input type="text" class="form-control" placeholder="Contoh: XII RPL" required>
                            </div>
                            <button type="submit" class="btn btn-block btn-success">Simpan Data Siswa</button>
                        </form>
                    </div>
                </div>';

        case 'data_perizinan':
            return '
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title-admin"><i class="fa-solid fa-clipboard-list"></i> Daftar Permintaan Izin</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-description">Lakukan approval atau penolakan perizinan siswa di sini.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr><th>Waktu</th><th>Jenis</th><th>Siswa</th><th>Status</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>14:30</td><td>Di Jemput</td><td>Arief (XI RPL)</td><td><span class="badge badge-warning">Menunggu</span></td><td><button class="btn btn-xs btn-success">Approve</button> <button class="btn btn-xs btn-danger">Tolak</button></td></tr>
                                    <tr><td>13:00</td><td>Keluar Kelas</td><td>Budi (X MM)</td><td><span class="badge badge-success">Diizinkan</span></td><td><button class="btn btn-xs btn-info">Detail</button></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>';

        case 'data_guru':
            return '
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title-admin"><i class="fa-solid fa-chalkboard-user"></i> Data Guru & Wali Kelas</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-description">Kelola data akun guru dan penugasan sebagai wali kelas.</p>
                        <div class="empty-state">
                            <span class="icon" style="font-size: 45px;">👨‍🏫</span>
                            <p class="text-muted">Belum ada data guru yang dimuat. Klik Tambah Guru untuk memulai.</p>
                            <a href="#" class="btn btn-info btn-sm mt-3"><i class="fa-solid fa-plus"></i> Tambah Guru</a>
                        </div>
                    </div>
                </div>';

        case 'laporan_rekap':
            return '
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title-admin"><i class="fa-solid fa-chart-line"></i> Rekapitulasi & Laporan Bulanan</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-description">Filter data dan unduh laporan perizinan untuk kebutuhan audit.</p>
                        <div class="grid-buttons">
                            <button class="btn btn-info"><i class="fa-solid fa-file-pdf"></i> PDF Laporan</button>
                            <button class="btn btn-warning"><i class="fa-solid fa-file-excel"></i> Export Excel</button>
                        </div>
                    </div>
                </div>';
        
        case 'audit_log':
            return '
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title-admin"><i class="fa-solid fa-shield-halved"></i> Log Aktivitas Sistem</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-description">Catatan semua aktivitas penting, termasuk login, approval, dan perubahan data.</p>
                        <div class="alert alert-danger">⚠️ Log Audit hanya bisa dilihat oleh Super Admin.</div>
                    </div>
                </div>';

        default:
            return '
                <div class="card card-danger card-outline">
                    <div class="card-body">
                        <h2 class="text-center text-danger">Halaman Tidak Ditemukan (404)</h2>
                        <p class="text-center text-muted">URL yang Anda akses tidak terdaftar di sistem sidebar.</p>
                    </div>
                </div>';
    }
}
$content = getContent($page);

// Tentukan item sidebar aktif
$active_page = $page;
$school_abbr = "SI Perizinan";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?> | Admin</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

    <style>
        .card-title-admin { font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .card-description { font-size: 0.9rem; color: #6c757d; margin-bottom: 15px; }
        .grid-buttons { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px; margin-top: 15px; }
        .empty-state { text-align: center; padding: 40px 20px; border: 1px dashed #ced4da; border-radius: 5px; margin-top: 20px; }
        .empty-state .icon { margin-bottom: 15px; color: #6c757d; }
        .nav-logout { background-color: #dc3545 !important; color: #fff !important; }
        .nav-logout:hover { background-color: #c82333 !important; }
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
                <span class="nav-link font-weight-bold text-primary"><?= $page_title ?></span>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <div class="user-panel d-flex align-items-center">
                    <span class="d-none d-md-inline mr-2">Halo, Admin</span>
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
                        <a href="index.php" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=pengaturan_akun" class="nav-link <?= ($active_page == 'pengaturan_akun') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Pengaturan Akun</p>
                        </a>
                    </li>
                    <li class="nav-header">MANAJEMEN DATA</li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=data_siswa" class="nav-link <?= ($active_page == 'data_siswa' || $active_page == 'siswa_tambah') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Data Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=data_perizinan" class="nav-link <?= ($active_page == 'data_perizinan') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Data Perizinan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=data_guru" class="nav-link <?= ($active_page == 'data_guru') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Data Guru/Wali Kelas</p>
                        </a>
                    </li>
                    <li class="nav-header">LAPORAN & AUDIT</li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=laporan_rekap" class="nav-link <?= ($active_page == 'laporan_rekap') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Laporan & Rekap</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sidebar.php?page=audit_log" class="nav-link <?= ($active_page == 'audit_log') ? 'active' : '' ?>">
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
                        <h1 class="m-0"><?= $page_title ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active"><?= $page_title ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?= $content ?>
                    </div>
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
</body>
</html>

<?php
include '../includes/config.php';
check_login('admin');
// Hardcode data rekap
$rekap_izin = [
    ['jenis' => 'Izin Keluar Kelas', 'menunggu' => '-', 'diizinkan' => '-', 'ditolak' => 1, 'bolos' => '-'],
    ['jenis' => 'Di Jemput', 'menunggu' => '-', 'diizinkan' => 2, 'ditolak' => 1, 'bolos' => '-'],
    ['jenis' => 'Izin Masuk Sekolah', 'menunggu' => '-', 'diizinkan' => 3, 'ditolak' => '-', 'bolos' => '-'],
];
$total_izin = 18;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard-container admin-dashboard">
        <header class="admin-header">
                        <h2>Selamat Datang, Admin!</h2>
            <p>MAN 2 Kota Madiun</p>
        </header>

        <main class="content-wrapper">
            <section class="card school-info-section fade-in">
                <h3>ℹ️ Informasi Sekolah</h3>
                <p>Nama: **MAN 2 Kota Madiun**</p>
                <p>Alamat: Jl.</p>
                <p>Lokasi: 7.63456, 111.52789 <span class="status status-danger">MATI</span></p>
                <button class="btn btn-secondary">Atur Data Sekolah</button>
            </section>
            
            <section class="card student-management-section fade-in">
                <h3>👥 Manajemen Siswa</h3>
                <div class="button-group">
                    <button class="btn btn-primary">Tambah Siswa</button>
                    <button class="btn btn-secondary">Daftar Siswa</button>
                    <button class="btn btn-success">Unduh Template Excel</button>
                </div>

                <h4>Upload Massal via Excel</h4>
                <div class="upload-form">
                    <input type="file" id="excelFile" name="file" accept=".xlsx" class="input-file">
                    <label for="excelFile" class="btn btn-upload-label">Pilih File Excel (.xlsx)</label>
                    <button class="btn btn-primary upload-simpan-btn">Upload & Simpan</button>
                    <p class="upload-format">format: NIS | Nama Lengkap | Kelas • Password default: 12345678</p>
                </div>
            </section>

            <section class="card rekap-izin-section fade-in">
                <h3>📊 Rekap Perizinan</h3>
                <div class="button-group rekap-actions">
                    <button class="btn btn-danger-soft">Hapus Foto Lama</button>
                    <button class="btn btn-info">Unduh (.xlsx)</button>
                </div>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Jenis Izin</th>
                                <th>Menunggu</th>
                                <th>Diizinkan</th>
                                <th>Ditolak</th>
                                <th>Bolos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rekap_izin as $izin): ?>
                            <tr>
                                <td data-label="Jenis Izin"><?= $izin['jenis'] ?></td>
                                <td data-label="Menunggu" class="center"><?= $izin['menunggu'] ?></td>
                                <td data-label="Diizinkan" class="center"><span class="badge badge-success"><?= $izin['diizinkan'] ?></span></td>
                                <td data-label="Ditolak" class="center"><span class="badge badge-danger"><?= $izin['ditolak'] ?></span></td>
                                <td data-label="Bolos" class="center"><?= $izin['bolos'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="total-izin">Total Izin: **<?= $total_izin ?>**</p>
            </section>
        </main>

        <footer class="footer-actions">
            <a href="../logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </footer>
    </div>
</body>
</html>
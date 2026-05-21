<?php
session_start();
require_once('include/conn.php');

// Password sederhana - ganti sesuai kebutuhan
$admin_pass = "N1rwana_alabare";

$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $pass   = isset($_POST['pass'])   ? $_POST['pass']   : '';

    if ($pass !== $admin_pass) {
        $msg = "Password salah!";
        $msg_type = 'danger';
    } else {
        if ($action === 'truncate') {
            $r = mysqli_query($conn_li, "TRUNCATE TABLE upload_standard");
            $msg = $r ? "Berhasil! Tabel upload_standard sudah di-TRUNCATE (semua baris dihapus)." : "Gagal: " . mysqli_error($conn_li);
            $msg_type = $r ? 'success' : 'danger';

        } elseif ($action === 'add_created_at') {
            $cek = mysqli_query($conn_li, "SHOW COLUMNS FROM upload_standard LIKE 'created_at'");
            if (mysqli_num_rows($cek) > 0) {
                $msg = "Kolom created_at sudah ada sebelumnya.";
                $msg_type = 'info';
            } else {
                $r = mysqli_query($conn_li, "ALTER TABLE upload_standard ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
                $msg = $r ? "Berhasil! Kolom created_at ditambahkan ke tabel upload_standard." : "Gagal: " . mysqli_error($conn_li);
                $msg_type = $r ? 'success' : 'danger';
            }

        } elseif ($action === 'delete_old') {
            $jam = isset($_POST['jam']) ? (int)$_POST['jam'] : 24;
            if ($jam < 1) $jam = 24;

            $cek = mysqli_query($conn_li, "SHOW COLUMNS FROM upload_standard LIKE 'created_at'");
            if (mysqli_num_rows($cek) === 0) {
                $msg = "Kolom created_at belum ada. Jalankan 'Tambah Kolom created_at' dulu.";
                $msg_type = 'warning';
            } else {
                $r = mysqli_query($conn_li, "DELETE FROM upload_standard WHERE created_at < DATE_SUB(NOW(), INTERVAL $jam HOUR)");
                $affected = mysqli_affected_rows($conn_li);
                $msg = $r ? "Berhasil! $affected baris dihapus (data lebih dari $jam jam)." : "Gagal: " . mysqli_error($conn_li);
                $msg_type = $r ? 'success' : 'danger';
            }
        }
    }
}

// Ambil statistik
$res_total = mysqli_fetch_assoc(mysqli_query($conn_li, "SELECT COUNT(*) as c FROM upload_standard"));
$total_rows = $res_total ? $res_total['c'] : 0;

$per_user  = mysqli_query($conn_li, "SELECT username, COUNT(*) as c FROM upload_standard GROUP BY username ORDER BY c DESC LIMIT 20");
$per_jenis = mysqli_query($conn_li, "SELECT JENIS_DOKUMEN, COUNT(*) as c FROM upload_standard GROUP BY JENIS_DOKUMEN ORDER BY c DESC LIMIT 20");

$has_created_at = false;
$oldest = null;
$cek_col = mysqli_query($conn_li, "SHOW COLUMNS FROM upload_standard LIKE 'created_at'");
if ($cek_col && mysqli_num_rows($cek_col) > 0) {
    $has_created_at = true;
    $oldest = mysqli_fetch_assoc(mysqli_query($conn_li, "SELECT MIN(created_at) as oldest, MAX(created_at) as newest FROM upload_standard"));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cleanup upload_standard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<style>
body { background: #f4f6f9; }
.card { border-radius: 8px; }
.stat-box { background: #fff; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.stat-box h2 { font-size: 2.5rem; font-weight: 700; }
</style>
</head>
<body>
<div class="container py-4">
    <h3 class="mb-1">Cleanup Tabel <code>upload_standard</code></h3>
    <p class="text-muted mb-4">Tools admin untuk membersihkan data staging yang menumpuk.</p>

    <?php if ($msg) : ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show">
        <?php echo htmlspecialchars($msg); ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php endif; ?>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-box">
                <div class="text-muted small">Total Baris</div>
                <h2 class="text-<?php echo $total_rows > 1000000 ? 'danger' : ($total_rows > 100000 ? 'warning' : 'success'); ?>">
                    <?php echo number_format($total_rows); ?>
                </h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="text-muted small">Kolom created_at</div>
                <h2 class="<?php echo $has_created_at ? 'text-success' : 'text-warning'; ?>" style="font-size:1.5rem; padding-top:8px">
                    <?php echo $has_created_at ? '&#10003; Ada' : '&#10007; Belum ada'; ?>
                </h2>
            </div>
        </div>
        <?php if ($has_created_at && $oldest) : ?>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="text-muted small">Data Terlama</div>
                <div style="font-size:1rem; padding-top:8px; font-weight:600">
                    <?php echo isset($oldest['oldest']) ? $oldest['oldest'] : '-'; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="text-muted small">Data Terbaru</div>
                <div style="font-size:1rem; padding-top:8px; font-weight:600">
                    <?php echo isset($oldest['newest']) ? $oldest['newest'] : '-'; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <!-- Panel Aksi -->
        <div class="col-md-5">
            <!-- Step 1: Truncate -->
            <div class="card mb-3">
                <div class="card-header bg-danger text-white font-weight-bold">
                    Langkah 1 &mdash; Bersihkan Sekarang (Truncate)
                </div>
                <div class="card-body">
                    <p class="small text-muted">Hapus <strong>semua</strong> baris dari tabel. Cocok untuk membersihkan <?php echo number_format($total_rows); ?> baris yang sudah menumpuk. User yang sedang aktif generate perlu generate ulang.</p>
                    <form method="post" onsubmit="return confirm('Yakin TRUNCATE semua data upload_standard? Tidak bisa di-undo!')">
                        <div class="form-group">
                            <input type="password" name="pass" class="form-control form-control-sm" placeholder="Password admin" required>
                        </div>
                        <input type="hidden" name="action" value="truncate">
                        <button type="submit" class="btn btn-danger btn-sm btn-block">TRUNCATE Sekarang</button>
                    </form>
                </div>
            </div>

            <!-- Step 2: Add created_at -->
            <div class="card mb-3">
                <div class="card-header bg-warning font-weight-bold">
                    Langkah 2 &mdash; Tambah Kolom <code>created_at</code>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Tambahkan kolom timestamp agar data bisa dibersihkan otomatis berdasarkan umur data. Hanya perlu dijalankan sekali.</p>
                    <form method="post">
                        <div class="form-group">
                            <input type="password" name="pass" class="form-control form-control-sm" placeholder="Password admin" required>
                        </div>
                        <input type="hidden" name="action" value="add_created_at">
                        <button type="submit" class="btn btn-warning btn-sm btn-block" <?php echo $has_created_at ? 'disabled' : ''; ?>>
                            <?php echo $has_created_at ? 'Sudah Ada' : 'Tambah Kolom created_at'; ?>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Step 3: Delete old -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white font-weight-bold">
                    Langkah 3 &mdash; Hapus Data Lama (Berkala)
                </div>
                <div class="card-body">
                    <p class="small text-muted">Hapus data yang lebih lama dari X jam. Gunakan setelah kolom <code>created_at</code> ada. Bisa dijalankan rutin.</p>
                    <form method="post">
                        <div class="form-group">
                            <label class="small">Hapus data lebih dari:</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="jam" class="form-control" value="24" min="1">
                                <div class="input-group-append"><span class="input-group-text">jam</span></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="password" name="pass" class="form-control form-control-sm" placeholder="Password admin" required>
                        </div>
                        <input type="hidden" name="action" value="delete_old">
                        <button type="submit" class="btn btn-info btn-sm btn-block" <?php echo !$has_created_at ? 'disabled' : ''; ?>>
                            Hapus Data Lama
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Statistik -->
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-header font-weight-bold">Top 20 Username (baris terbanyak)</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead><tr><th>Username</th><th class="text-right">Jumlah Baris</th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($per_user)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username'] ? $row['username'] : '(kosong)'); ?></td>
                            <td class="text-right"><?php echo number_format($row['c']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header font-weight-bold">Per JENIS_DOKUMEN</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead><tr><th>JENIS_DOKUMEN</th><th class="text-right">Jumlah Baris</th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($per_jenis)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['JENIS_DOKUMEN'] ? $row['JENIS_DOKUMEN'] : '(kosong)'); ?></td>
                            <td class="text-right"><?php echo number_format($row['c']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

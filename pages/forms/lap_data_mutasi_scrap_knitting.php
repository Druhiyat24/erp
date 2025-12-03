<?php
// ====================================================================
// PENTING: ob_start() harus menjadi baris kode PERTAMA.
// ====================================================================
ob_start();

// --- 1. INISIALISASI & SETUP VARIABEL ---

// Cek sesi (sesuaikan jika perlu)
if (empty($_SESSION['username'])) {
    // header("location:../../index.php"); 
}

// Ambil filter dari POST atau default
$txtfrom_input = isset($_POST['txtfrom']) ? $_POST['txtfrom'] : date("d M Y");
$txtto_input   = isset($_POST['txtto']) ? $_POST['txtto'] : date("d M Y");
$txttype_input = isset($_POST['txttype']) ? $_POST['txttype'] : 'SCRAP'; // Filter Tipe Material BARU
$report_type   = isset($_POST['report_type']) ? $_POST['report_type'] : 'MUTASI'; // Jenis Laporan BARU

// Konversi ke format Database (YYYY-MM-DD)
$from = date('Y-m-d', strtotime($txtfrom_input));
$to   = date('Y-m-d', strtotime($txtto_input));

// Amankan string untuk query SQL
$from_safe = mysql_real_escape_string($from);
$to_safe   = mysql_real_escape_string($to);

// Tentukan kondisi WHERE berdasarkan $txttype_input (Tipe Material)
$mattype_condition = "";
$scrap_bpb_condition = "";
$scrap_bppb_condition = "";
$judul_laporan     = "";

switch ($txttype_input) {
    case 'BENANG':
        $mattype_condition = "mi.mattype IN ('B')";
        $scrap_bpb_condition = "AND a.bpbno_int NOT LIKE '%SCR%'";
        $scrap_bppb_condition = "AND a.bppbno_int NOT LIKE '%SCR%'";
        $judul_laporan = "GUDANG BENANG";
        break;
    case 'GREIGE':
        $mattype_condition = "mi.mattype IN ('G')";
        $scrap_bpb_condition = "AND a.bpbno_int NOT LIKE '%SCR%'";
        $scrap_bppb_condition = "AND a.bppbno_int NOT LIKE '%SCR%'";
        $judul_laporan = "KAIN GREIGE";
        break;
    case 'MATANG':
        $mattype_condition = "mi.mattype IN ('KM')";
        $scrap_bpb_condition = "AND a.bpbno_int NOT LIKE '%SCR%'";
        $scrap_bppb_condition = "AND a.bppbno_int NOT LIKE '%SCR%'";
        $judul_laporan = "KAIN MATANG";
        break;
    case 'SCRAP':
        $mattype_condition = "mi.mattype IN ('B','KM','G')";
        $scrap_bpb_condition = "AND a.bpbno_int LIKE '%SCR%'";
        $scrap_bppb_condition = "AND a.bppbno_int LIKE '%SCR%'";
        $judul_laporan = "SCRAP KNITTING";
        break;
    case 'ALL':
    default:
        $mattype_condition = "mi.mattype IN ('B','KM','G')";
        $scrap_bpb_condition = "";
        $scrap_bppb_condition = "";
        $judul_laporan = "SEMUA MATERIAL";
        break;
}

// --- 2. QUERY UTAMA MUTASI (DIGUNAKAN UNTUK Laporan Mutasi) ---

$sql_mutasi = "
    SELECT
        T1.id_item, MAX(T1.goods_code) AS goods_code, MAX(T1.itemdesc) AS itemdesc, MAX(T1.unit) AS unit,
        SUM(T1.saldo_awal_qty) AS saldo_awal, SUM(T1.pemasukan_qty) AS pemasukan, SUM(T1.pengeluaran_qty) AS pengeluaran,
        (SUM(T1.saldo_awal_qty) + SUM(T1.pemasukan_qty) - SUM(T1.pengeluaran_qty)) AS saldo_akhir_raw,
        MAX(T1.remark) AS keterangan
    FROM (
        -- 1. PEMASUKAN (BPB) dalam Periode
        SELECT a.id_item, mi.goods_code, mi.itemdesc, a.unit, a.remark,
            0 AS saldo_awal_qty, a.qty AS pemasukan_qty, 0 AS pengeluaran_qty
        FROM bpb a 
        JOIN masteritem mi ON mi.id_item = a.id_item
        WHERE a.bpbdate BETWEEN '$from_safe' AND '$to_safe' AND $mattype_condition
          $scrap_bpb_condition
        
        UNION ALL
        
        -- 2. PENGELUARAN (BPPB) dalam Periode
        SELECT a.id_item, mi.goods_code, mi.itemdesc, a.unit, a.remark,
            0 AS saldo_awal_qty, 0 AS pemasukan_qty, a.qty AS pengeluaran_qty
        FROM bppb a 
        JOIN masteritem mi ON mi.id_item = a.id_item
        WHERE a.bppbdate BETWEEN '$from_safe' AND '$to_safe' AND $mattype_condition
          $scrap_bppb_condition
        UNION ALL
        
        -- 3. SALDO AWAL MASUK (BPB < Tgl Awal)
        SELECT a.id_item, mi.goods_code, mi.itemdesc, a.unit, '' AS remark,
            a.qty AS saldo_awal_qty, 0 AS pemasukan_qty, 0 AS pengeluaran_qty
        FROM bpb a 
        JOIN masteritem mi ON mi.id_item = a.id_item
        WHERE a.bpbdate < '$from_safe' AND $mattype_condition
          $scrap_bpb_condition
        UNION ALL
        
        -- 4. SALDO AWAL KELUAR (BPPB < Tgl Awal)
        SELECT a.id_item, mi.goods_code, mi.itemdesc, a.unit, '' AS remark,
            -a.qty AS saldo_awal_qty, 0 AS pemasukan_qty, 0 AS pengeluaran_qty
        FROM bppb a 
        JOIN masteritem mi ON mi.id_item = a.id_item
        WHERE a.bppbdate < '$from_safe' AND $mattype_condition
          $scrap_bppb_condition
    ) AS T1
    GROUP BY T1.id_item
    HAVING SUM(T1.saldo_awal_qty) <> 0 OR SUM(T1.pemasukan_qty) <> 0 OR SUM(T1.pengeluaran_qty) <> 0
    ORDER BY goods_code ASC
";

// --- 2B. QUERY DETAIL (untuk Laporan Pemasukan & Pengeluaran) ---
$sql_detail = "";
if ($report_type == 'PEMASUKAN') {
    $sql_detail = "
        SELECT 
            'BPB' AS jenis_dok, a.bpbno_int AS no_dok, a.bpbdate AS tgl_dok, 
            mi.goods_code, mi.itemdesc, a.unit, a.remark,
            a.qty AS pemasukan, 0 AS pengeluaran
        FROM bpb a 
        JOIN masteritem mi ON mi.id_item = a.id_item
        WHERE a.bpbdate BETWEEN '$from_safe' AND '$to_safe' AND $mattype_condition 
          $scrap_bpb_condition
        ORDER BY a.bpbdate, a.bpbno_int ASC
    ";
} elseif ($report_type == 'PENGELUARAN') {
    $sql_detail = "
        SELECT 
            'BPPB' AS jenis_dok, a.bppbno_int AS no_dok, a.bppbdate AS tgl_dok, 
            mi.goods_code, mi.itemdesc, a.unit, a.remark,
            0 AS pemasukan, a.qty AS pengeluaran
        FROM bppb a 
        JOIN masteritem mi ON mi.id_item = a.id_item
        WHERE a.bppbdate BETWEEN '$from_safe' AND '$to_safe' AND $mattype_condition 
          $scrap_bppb_condition
        ORDER BY a.bppbdate, a.bppbno_int ASC
    ";
}

// Tentukan query yang akan dieksekusi
$sql_to_execute = ($report_type == 'MUTASI') ? $sql_mutasi : $sql_detail;


// --- 3. LOGIC EXPORT EXCEL (TRIGGER DOWNLOAD) ---
if (isset($_POST['submit'])) { // Jika tombol "Export Excel" diklik
    // Bersihkan buffer sepenuhnya
    ob_clean();

    $filename = "Laporan_{$report_type}_" . date('Ymd', strtotime($from)) . ".xls";

    // Header Excel
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    header("Pragma: no-cache");
    header("Expires: 0");

    // Jalankan Query
    $res_export = mysql_query($sql_to_execute);

    // Output Table HTML untuk Excel
    echo "<!DOCTYPE html><html><head>";
    echo "<meta charset='UTF-8'>";
    echo "<style>td { mso-number-format:\\@; } .text-right { text-align:right; } .text-center { text-align:center; }</style>";
    echo "</head><body>";

    echo "<center><h3>LAPORAN " . strtoupper($report_type) . " $judul_laporan</h3></center>";
    echo "<b>Periode: $txtfrom_input s/d $txtto_input</b><br><br>";
    echo "<table border='1'>";
    
    if ($report_type == 'MUTASI') {
        // Header Mutasi
        echo "<thead>
            <tr style='background-color:#eee;'>
                <th>NO.</th>
                <th>ID ITEM</th>
                <th>KODE BARANG</th>
                <th>NAMA BARANG</th>
                <th>SAT</th>
                <th>SALDO AWAL</th>
                <th>PEMASUKAN</th>
                <th>PENGELUARAN</th>
                <th>PENYESUAIAN</th>
                <th>SALDO AKHIR</th>
                <th>STOCK OPNAME</th>
                <th>SELISIH</th>
                <th>KETERANGAN</th>
            </tr>
        </thead><tbody>";

        $no = 1;
        while ($row = mysql_fetch_array($res_export)) {
            $saldo_akhir = number_format($row['saldo_akhir_raw'], 2, '.', '');

            echo "<tr>";
            echo "<td class='text-center'>$no</td>";
            echo "<td>" . $row['id_item'] . "</td>";
            echo "<td style='mso-number-format:\"\\@\";'>" . $row['goods_code'] . "</td>";
            echo "<td>" . $row['itemdesc'] . "</td>";
            echo "<td class='text-center'>" . $row['unit'] . "</td>";
            echo "<td class='text-right'>" . number_format($row['saldo_awal'], 2, '.', '') . "</td>";
            echo "<td class='text-right'>" . number_format($row['pemasukan'], 2, '.', '') . "</td>";
            echo "<td class='text-right'>" . number_format($row['pengeluaran'], 2, '.', '') . "</td>";
            echo "<td class='text-right'>0.00</td>";
            echo "<td class='text-right' style='background-color:#e6f7ff; font-weight:bold;'>$saldo_akhir</td>";
            echo "<td class='text-right'>0.00</td>";
            echo "<td class='text-right'>0.00</td>";
            echo "<td>" . $row['keterangan'] . "</td>";
            echo "</tr>";
            $no++;
        }
    } else {
        // Header Pemasukan / Pengeluaran
        echo "<thead>
            <tr style='background-color:#eee;'>
                <th>NO.</th>
                <th>JENIS DOK.</th>
                <th>NO. DOKUMEN</th>
                <th>TANGGAL DOK.</th>
                <th>KODE BARANG</th>
                <th>NAMA BARANG</th>
                <th>SAT</th>
                <th>MASUK</th>
                <th>KELUAR</th>
                <th>KETERANGAN</th>
            </tr>
        </thead><tbody>";
        
        $no = 1;
        while ($row = mysql_fetch_array($res_export)) {
            echo "<tr>";
            echo "<td class='text-center'>$no</td>";
            echo "<td>" . $row['jenis_dok'] . "</td>";
            echo "<td style='mso-number-format:\"\\@\";'>" . $row['no_dok'] . "</td>";
            echo "<td class='text-center'>" . date('d M Y', strtotime($row['tgl_dok'])) . "</td>";
            echo "<td style='mso-number-format:\"\\@\";'>" . $row['goods_code'] . "</td>";
            echo "<td>" . $row['itemdesc'] . "</td>";
            echo "<td class='text-center'>" . $row['unit'] . "</td>";
            echo "<td class='text-right'>" . number_format($row['pemasukan'], 2, '.', '') . "</td>";
            echo "<td class='text-right'>" . number_format($row['pengeluaran'], 2, '.', '') . "</td>";
            echo "<td>" . $row['remark'] . "</td>";
            echo "</tr>";
            $no++;
        }
    }

    echo "</tbody></table>";
    echo "</body></html>";

    // Hentikan Script
    exit();
}

// --- 4. TAMPILAN WEB (HTML) - Hanya dieksekusi jika bukan export ---
?>

<div class='box'>
    <div class='box-header with-border'>
        <h3 class='box-title'>
            <i class='fa fa-balance-scale'></i> Laporan <?php echo strtoupper($report_type); ?> <?php echo $judul_laporan; ?>
        </h3>
    </div>

    <form method='post' action=''>
        <div class='box-body'>
            <div class='row'>
                
                <div class='col-md-2'>
                    <div class='form-group'>
                        <label>Jenis Laporan *</label>
                        <select class='form-control' name='report_type' required onchange='this.form.submit()'>
                            <option value='MUTASI' <?php if ($report_type == 'MUTASI') echo 'selected'; ?>>Laporan Mutasi</option>
                            <option value='PEMASUKAN' <?php if ($report_type == 'PEMASUKAN') echo 'selected'; ?>>Laporan Pemasukan</option>
                            <option value='PENGELUARAN' <?php if ($report_type == 'PENGELUARAN') echo 'selected'; ?>>Laporan Pengeluaran</option>
                        </select>
                    </div>
                </div>

                <div class='col-md-2'>
                    <div class='form-group'>
                        <label>Tipe Material *</label>
                        <select class='form-control' name='txttype' required>
                            <option value='SCRAP' <?php if ($txttype_input == 'SCRAP') echo 'selected'; ?>>Scrap Knitting</option>
                            <option value='BENANG' <?php if ($txttype_input == 'BENANG') echo 'selected'; ?>>Gudang Benang</option>
                            <option value='GREIGE' <?php if ($txttype_input == 'GREIGE') echo 'selected'; ?>>Kain Greige</option>
                            <option value='MATANG' <?php if ($txttype_input == 'MATANG') echo 'selected'; ?>>Kain Matang</option>
                            <option value='ALL' <?php if ($txttype_input == 'ALL') echo 'selected'; ?>>Semua Material</option>
                        </select>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='form-group'>
                        <label>Dari *</label>
                        <input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom' required value='<?php echo htmlspecialchars($txtfrom_input); ?>'>
                    </div>
                </div>

                <div class='col-md-2'>
                    <div class='form-group'>
                        <label>Sampai *</label>
                        <input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' required value='<?php echo htmlspecialchars($txtto_input); ?>'>
                    </div>
                </div>

                <div class='col-md-4'>
                    <div class='form-group' style='padding-top:25px'>
                        <button type='submit' name='submit_cari' class='btn btn-info'>
                            <i class="fa fa-search"></i> Search
                        </button>
                        <button type='submit' name='submit' class='btn btn-success'>
                            <i class="fa fa-file-excel-o"></i> Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class='box-body'>
        <div class='table-responsive'>
            <table id="examplefix" class="display responsive table table-bordered table-striped" style="width:100%">
                <thead>
                    <?php if ($report_type == 'MUTASI') { ?>
                    <tr>
                        <th class="text-center">NO.</th>
                        <th class="text-center">ID ITEM</th>
                        <th class="text-center">KODE BARANG</th>
                        <th class="text-center">NAMA BARANG</th>
                        <th class="text-center">SAT</th>
                        <th class="text-center">SALDO AWAL</th>
                        <th class="text-center">PEMASUKAN</th>
                        <th class="text-center">PENGELUARAN</th>
                        <th class="text-center">PENYESUAIAN</th>
                        <th class="text-center">SALDO AKHIR</th>
                        <th class="text-center">STOCK OPNAME</th>
                        <th class="text-center">SELISIH</th>
                        <th class="text-center">KETERANGAN</th>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <th class="text-center">NO.</th>
                        <th class="text-center">JENIS DOK.</th>
                        <th class="text-center">NO. DOKUMEN</th>
                        <th class="text-center">TANGGAL DOK.</th>
                        <th class="text-center">KODE BARANG</th>
                        <th class="text-center">NAMA BARANG</th>
                        <th class="text-center">SAT</th>
                        <th class="text-center">MASUK</th>
                        <th class="text-center">KELUAR</th>
                        <th class="text-center">KETERANGAN</th>
                    </tr>
                    <?php } ?>
                </thead>
                <tbody>
                    <?php
                    // Jalankan Query untuk Tampilan Web
                    $query = mysql_query($sql_to_execute);

                    if ($query && mysql_num_rows($query) > 0) {
                        $no = 1;
                        while ($data = mysql_fetch_array($query)) {
                            
                            if ($report_type == 'MUTASI') {
                                // Tampilan Baris Mutasi
                                $saldo_akhir = number_format($data['saldo_akhir_raw'], 2, '.', ',');

                                echo "<tr>";
                                echo "<td align='center'>$no</td>";
                                echo "<td>" . htmlspecialchars($data['id_item']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['goods_code']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['itemdesc']) . "</td>";
                                echo "<td align='center'>" . htmlspecialchars($data['unit']) . "</td>";
                                echo "<td align='right'>" . number_format($data['saldo_awal'], 2) . "</td>";
                                echo "<td align='right'>" . number_format($data['pemasukan'], 2) . "</td>";
                                echo "<td align='right'>" . number_format($data['pengeluaran'], 2) . "</td>";
                                echo "<td align='right'>0.00</td>";
                                echo "<td align='right' style='font-weight:bold;'>$saldo_akhir</td>";
                                echo "<td align='right'>0.00</td>";
                                echo "<td align='right'>0.00</td>";
                                echo "<td>" . htmlspecialchars($data['keterangan']) . "</td>";
                                echo "</tr>";
                            } else {
                                // Tampilan Baris Pemasukan/Pengeluaran
                                echo "<tr>";
                                echo "<td align='center'>$no</td>";
                                echo "<td>" . htmlspecialchars($data['jenis_dok']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['no_dok']) . "</td>";
                                echo "<td align='center'>" . date('d M Y', strtotime($data['tgl_dok'])) . "</td>";
                                echo "<td>" . htmlspecialchars($data['goods_code']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['itemdesc']) . "</td>";
                                echo "<td align='center'>" . htmlspecialchars($data['unit']) . "</td>";
                                echo "<td align='right'>" . number_format($data['pemasukan'], 2) . "</td>";
                                echo "<td align='right'>" . number_format($data['pengeluaran'], 2) . "</td>";
                                echo "<td>" . htmlspecialchars($data['remark']) . "</td>";
                                echo "</tr>";
                            }
                            $no++;
                        }
                    } else {
                        $col_span = ($report_type == 'MUTASI') ? 13 : 10;
                        echo "<tr><td colspan='$col_span' class='text-center'>Data tidak ditemukan</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
// Akhiri Output Buffering setelah semua output HTML selesai
ob_end_flush();
?>
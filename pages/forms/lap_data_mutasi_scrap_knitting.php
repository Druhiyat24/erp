<?php
// ====================================================================
// PENTING: ob_start() harus menjadi baris kode PERTAMA.
// ====================================================================
ob_start();

// --- 0. DEKLARASI STYLE CSS ANTI-PEMOTONGAN ---
// Style ini menggunakan !important untuk mengesampingkan framework global 
// agar teks bisa wrap (normal) dan tidak terpotong (overflow: visible).
echo "<style>";
echo "
#examplefix td {
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: clip !important;
    display: table-cell !important;
    min-height: 20px !important; 
}
";
echo "</style>";

// --- 1. INISIALISASI & SETUP VARIABEL ---

// Ambil filter dari POST atau default
$txtfrom_input = isset($_POST['txtfrom']) ? $_POST['txtfrom'] : date("d M Y");
$txtto_input   = isset($_POST['txtto']) ? $_POST['txtto'] : date("d M Y");
$txttype_input = isset($_POST['txttype']) ? $_POST['txttype'] : 'SCRAP';
$report_type   = isset($_POST['report_type']) ? $_POST['report_type'] : 'MUTASI';

// Konversi ke format Database (YYYY-MM-DD)
$from = date('Y-m-d', strtotime($txtfrom_input));
$to   = date('Y-m-d', strtotime($txtto_input));

// PERHATIAN: Asumsikan koneksi database sudah ada (misal: include config.php)
// Gantikan fungsi usang mysql_real_escape_string dengan yang lebih aman jika memungkinkan.
$from_safe = $from; 
$to_safe   = $to;

// Tentukan kondisi WHERE DB dan Judul Laporan
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
        $scrap_bpb_condition = "AND a.bpbno_int NOT LIKE '%SCR%' and a.bpbno_int NOT LIKE '%GM%'";
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

// --- 2. LOGIKA API (MATANG & MUTASI) ---
$api_data = null;
$use_api = ($txttype_input == 'MATANG' && $report_type == 'MUTASI');
$api_error_message = "";

if ($use_api) {
    $payload = [
        "dateType" => "daily", 
        "dateFrom" => $from, 
        "dateTo" => $to      
    ];

    $api_url = 'http://10.10.5.2:8080/api/fabric-mutation-barcode/';
    
    // Panggilan API menggunakan cURL
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    if ($curl_error || $http_code != 200 || $result === FALSE) {
        $api_error_message = $curl_error ? "cURL Error: " . $curl_error : "API returned HTTP code: " . $http_code;
    } else {
        $data_raw = json_decode($result, true);
        
        // ASUMSI: Respons API menggunakan wrapper {"status": "200", "data": [...]}
        if (isset($data_raw['status']) && $data_raw['status'] == '200' && isset($data_raw['data'])) {
            $api_data = $data_raw['data'];
        } else {
            // Menangkap error 'Unknown structure' dari API
            $api_error_message = "API Data Error. Response: " . (isset($data_raw['message']) ? $data_raw['message'] : 'Unknown structure');
            $api_data = [];
        }
    }
}

// --- 3. QUERY DATABASE (Untuk Selain MATANG Mutasi dan Fallback) ---
$sql_to_execute = "";

if ($report_type == 'MUTASI' && !$use_api) {
    // Query Mutasi DB (Benang, Greige, Scrap, All) - Menggunakan qty (Standard)
    $sql_to_execute = "
        SELECT
            T1.id_item, MAX(T1.goods_code) AS goods_code, MAX(T1.itemdesc) AS itemdesc, 'Kilogram' AS unit,
            SUM(T1.saldo_awal_qty) AS saldo_awal, SUM(T1.pemasukan_qty) AS pemasukan, SUM(T1.pengeluaran_qty) AS pengeluaran,
            (SUM(T1.saldo_awal_qty) + SUM(T1.pemasukan_qty) - SUM(T1.pengeluaran_qty)) AS saldo_akhir_raw,
            MAX(T1.remark) AS keterangan
        FROM (
            SELECT a.id_item, mi.goods_code, mi.itemdesc, a.unit, a.remark, 0 AS saldo_awal_qty, a.qty AS pemasukan_qty, 0 AS pengeluaran_qty
            FROM bpb a JOIN masteritem mi ON mi.id_item = a.id_item
            WHERE a.bpbdate BETWEEN '$from_safe' AND '$to_safe' AND $mattype_condition $scrap_bpb_condition and a.bpbno_int NOT LIKE '%FG/%'
            UNION ALL
            SELECT a.id_item, mi.goods_code, mi.itemdesc, a.unit, a.remark, 0 AS saldo_awal_qty, 0 AS pemasukan_qty, a.qty AS pengeluaran_qty
            FROM bppb a JOIN masteritem mi ON mi.id_item = a.id_item
            WHERE a.bppbdate BETWEEN '$from_safe' AND '$to_safe' AND $mattype_condition $scrap_bppb_condition and a.bppbno_int NOT LIKE '%FG/%'
            UNION ALL
            SELECT a.id_item, mi.goods_code, mi.itemdesc, a.unit, '' AS remark, a.qty AS saldo_awal_qty, 0 AS pemasukan_qty, 0 AS pengeluaran_qty
            FROM bpb a JOIN masteritem mi ON mi.id_item = a.id_item
            WHERE a.bpbdate < '$from_safe' AND $mattype_condition $scrap_bpb_condition and a.bpbno_int NOT LIKE '%FG/%'
            UNION ALL
            SELECT a.id_item, mi.goods_code, mi.itemdesc, a.unit, '' AS remark, -a.qty AS saldo_awal_qty, 0 AS pemasukan_qty, 0 AS pengeluaran_qty
            FROM bppb a JOIN masteritem mi ON mi.id_item = a.id_item
            WHERE a.bppbdate < '$from_safe' AND $mattype_condition $scrap_bppb_condition and a.bppbno_int NOT LIKE '%FG/%'
        ) AS T1
        GROUP BY T1.id_item
        HAVING SUM(T1.saldo_awal_qty) <> 0 OR SUM(T1.pemasukan_qty) <> 0 OR SUM(T1.pengeluaran_qty) <> 0
        ORDER BY goods_code ASC
    ";
} elseif ($report_type == 'PEMASUKAN') {
    // Query Detail Pemasukan
    $sql_to_execute = "
        SELECT 
            'BPB' AS jenis_dok, a.bpbno_int AS no_dok, a.bpbdate AS tgl_dok, 
            mi.goods_code, mi.itemdesc, 'Kilogram' unit, a.remark,
            a.qty AS pemasukan, 0 AS pengeluaran
        FROM bpb a JOIN masteritem mi ON mi.id_item = a.id_item
        WHERE a.bpbdate BETWEEN '$from_safe' AND '$to_safe' AND $mattype_condition $scrap_bpb_condition and a.bpbno_int NOT LIKE '%FG/%'
        ORDER BY a.bpbdate, a.bpbno_int ASC
    ";
} elseif ($report_type == 'PENGELUARAN') {
    // Query Detail Pengeluaran
    $sql_to_execute = "
        SELECT 
            'BPPB' AS jenis_dok, a.bppbno_int AS no_dok, a.bppbdate AS tgl_dok, 
            mi.goods_code, mi.itemdesc,  'Kilogram' unit, a.remark,
            0 AS pemasukan, a.qty AS pengeluaran
        FROM bppb a JOIN masteritem mi ON mi.id_item = a.id_item
        WHERE a.bppbdate BETWEEN '$from_safe' AND '$to_safe' AND $mattype_condition $scrap_bppb_condition and a.bppbno_int NOT LIKE '%FG/%'
        ORDER BY a.bppbdate, a.bppbdate, a.bppbno_int ASC
    ";
}


// --- 4. LOGIC EXPORT EXCEL (TRIGGER DOWNLOAD) ---
if (isset($_POST['submit'])) { 
    ob_clean();
    $filename = "Laporan_{$report_type}_" . date('Ymd', strtotime($from)) . ".xls";
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Pragma: no-cache");

    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>td { mso-number-format:\\@; } .text-right { text-align:right; } .text-center { text-align:center; }</style></head><body>";
    echo "<center><h3>LAPORAN " . strtoupper($report_type) . " $judul_laporan</h3></center>";
    echo "<b>Periode: $txtfrom_input s/d $txtto_input</b><br><br>";
    if ($api_error_message && $use_api) {
        echo "<p style='color:red;'><strong>ERROR API:</strong> $api_error_message</p>";
    }
    echo "<table border='1'>";
    
    // Header & Konten Excel
    if ($report_type == 'MUTASI') {
        
        if ($use_api && is_array($api_data)) {
            // Header KHUSUS Mutasi Kain Matang (API) - URUTAN BARU
            echo "<thead>
                <tr style='background-color:#eee;'>
                    <th>NO.</th>
                    <th>KODE BARANG</th>
                    <th>NAMA BARANG</th>
                    <th>WARNA</th>
                    <th>KODE SO</th>
                    <th>SALDO AWAL (KG)</th>
                    <th>PEMASUKAN (KG)</th>
                    <th>PENGELUARAN (KG)</th>
                    <th>SALDO AKHIR (KG)</th>
                </tr>
            </thead><tbody>";

            // Data dari API (MATANG & MUTASI)
            $no = 1;
            foreach ($api_data as $row) {
                // Perhitungan Saldo Bruto
                $saldo_awal_bruto = (float)$row['sum_bruto_initial'];
                $pemasukan_bruto = (float)$row['in_sum_bruto_between'];
                $pengeluaran_bruto = (float)$row['out_sum_bruto_between'];
                $saldo_akhir_raw = $saldo_awal_bruto + $pemasukan_bruto - $pengeluaran_bruto;
                
                $saldo_akhir_fmt = number_format($saldo_akhir_raw, 2, '.', ''); 
                $kode_barang = isset($row['kode_kain']) ? $row['kode_kain'] : 'N/A';
                $nama_barang = isset($row['nama_kain']) ? $row['nama_kain'] : 'N/A';
                $kode_so = isset($row['kode_so']) ? $row['kode_so'] : '-';
                $warna = isset($row['warna']) ? $row['warna'] : '-';

                echo "<tr>";
                echo "<td class='text-center'>$no</td>";
                echo "<td style='mso-number-format:\"\\@\";'>" . $kode_barang . "</td>";
                echo "<td>" . $nama_barang . "</td>";
                echo "<td>" . $warna . "</td>";
                echo "<td style='mso-number-format:\"\\@\";'>" . $kode_so . "</td>";
                echo "<td class='text-right'>" . number_format($saldo_awal_bruto, 2, '.', '') . "</td>";
                echo "<td class='text-right'>" . number_format($pemasukan_bruto, 2, '.', '') . "</td>";
                echo "<td class='text-right'>" . number_format($pengeluaran_bruto, 2, '.', '') . "</td>";
                echo "<td class='text-right' style='background-color:#e6f7ff; font-weight:bold;'>$saldo_akhir_fmt</td>";
                echo "</tr>";
                $no++;
            }

        } else {
            // Header STANDAR Mutasi (DB)
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
            
            // Data dari Query DB
            $res_export = mysql_query($sql_to_execute);
            $no = 1;
            while ($row = mysql_fetch_array($res_export)) {
                $saldo_akhir = number_format($row['saldo_akhir_raw'], 2, '.', '');
                echo "<tr><td class='text-center'>$no</td><td>" . $row['id_item'] . "</td><td style='mso-number-format:\"\\@\";'>" . $row['goods_code'] . "</td><td>" . $row['itemdesc'] . "</td><td class='text-center'>" . $row['unit'] . "</td><td class='text-right'>" . number_format($row['saldo_awal'], 2, '.', '') . "</td><td class='text-right'>" . number_format($row['pemasukan'], 2, '.', '') . "</td><td class='text-right'>" . number_format($row['pengeluaran'], 2, '.', '') . "</td><td class='text-right'>0.00</td><td class='text-right' style='background-color:#e6f7ff; font-weight:bold;'>$saldo_akhir</td><td class='text-right'>0.00</td><td class='text-right'>0.00</td><td>" . $row['keterangan'] . "</td></tr>";
                $no++;
            }
        }
    } else {
        // Header Detail (Pemasukan / Pengeluaran)
        $res_export = mysql_query($sql_to_execute);
        
        echo "<thead><tr style='background-color:#eee;'><th>NO.</th><th>JENIS DOK.</th><th>NO. DOKUMEN</th><th>TANGGAL DOK.</th><th>KODE BARANG</th><th>NAMA BARANG</th><th>SAT</th><th>MASUK</th><th>KELUAR</th><th>KETERANGAN</th></tr></thead><tbody>";
        
        $no = 1;
        while ($row = mysql_fetch_array($res_export)) {
            echo "<tr><td class='text-center'>$no</td><td>" . $row['jenis_dok'] . "</td><td style='mso-number-format:\"\\@\";'>" . $row['no_dok'] . "</td><td class='text-center'>" . date('d M Y', strtotime($row['tgl_dok'])) . "</td><td style='mso-number-format:\"\\@\";'>" . $row['goods_code'] . "</td><td>" . $row['itemdesc'] . "</td><td class='text-center'>" . $row['unit'] . "</td><td class='text-right'>" . number_format($row['pemasukan'], 2, '.', '') . "</td><td class='text-right'>" . number_format($row['pengeluaran'], 2, '.', '') . "</td><td>" . $row['remark'] . "</td></tr>";
            $no++;
        }
    }

    echo "</tbody></table>";
    echo "</body></html>";
    exit();
}

// --- 5. TAMPILAN WEB (HTML) ---
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
                <div class='col-md-2'><div class='form-group'><label>Jenis Laporan *</label><select class='form-control' name='report_type' required onchange='this.form.submit()'><option value='MUTASI' <?php if ($report_type == 'MUTASI') echo 'selected'; ?>>Laporan Mutasi</option><option value='PEMASUKAN' <?php if ($report_type == 'PEMASUKAN') echo 'selected'; ?>>Laporan Pemasukan</option><option value='PENGELUARAN' <?php if ($report_type == 'PENGELUARAN') echo 'selected'; ?>>Laporan Pengeluaran</option></select></div></div>
                <div class='col-md-2'><div class='form-group'><label>Tipe Material *</label><select class='form-control' name='txttype' required><option value='SCRAP' <?php if ($txttype_input == 'SCRAP') echo 'selected'; ?>>Scrap Knitting</option><option value='BENANG' <?php if ($txttype_input == 'BENANG') echo 'selected'; ?>>Gudang Benang</option><option value='GREIGE' <?php if ($txttype_input == 'GREIGE') echo 'selected'; ?>>Kain Greige</option><option value='MATANG' <?php if ($txttype_input == 'MATANG') echo 'selected'; ?>>Kain Matang</option><option value='ALL' <?php if ($txttype_input == 'ALL') echo 'selected'; ?>>Semua Material</option></select></div></div>
                <div class='col-md-2'><div class='form-group'><label>Dari *</label><input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom' required value='<?php echo htmlspecialchars($txtfrom_input); ?>'></div></div>
                <div class='col-md-2'><div class='form-group'><label>Sampai *</label><input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' required value='<?php echo htmlspecialchars($txtto_input); ?>'></div></div>
                <div class='col-md-4'><div class='form-group' style='padding-top:25px'><button type='submit' name='submit_cari' class='btn btn-info'><i class="fa fa-search"></i> Search</button><button type='submit' name='submit' class='btn btn-success'><i class="fa fa-file-excel-o"></i> Export Excel</button></div></div>
            </div>
        </div>
    </form>
    
    <?php if ($api_error_message && $use_api) { ?>
        <div class='box-body'><div class='alert alert-danger'><strong>Kesalahan API:</strong> Gagal mengambil data mutasi kain matang. <?php echo htmlspecialchars($api_error_message); ?></div></div>
    <?php } ?>

    <div class='box-body'>
        <div class='table-responsive'>
            <table id="examplefix" class="display responsive table table-bordered table-striped no-wrap" style="width:100%">
                <thead>
                    <?php if ($report_type == 'MUTASI') { ?>
                        <?php if ($use_api) { // HEADER KHUSUS MUTASI MATANG - URUTAN BARU ?>
                        <tr>
                            <th class="text-center">NO.</th>
                            <th class="text-center">KODE BARANG</th>
                            <th class="text-center">NAMA BARANG</th>
                            <th class="text-center">WARNA</th>
                            <th class="text-center">SALDO AWAL (KG)</th>
                            <th class="text-center">PEMASUKAN (KG)</th>
                            <th class="text-center">PENGELUARAN (KG)</th>
                            <th class="text-center">SALDO AKHIR (KG)</th>
                        </tr>
                        <?php } else { // HEADER STANDAR MUTASI DB ?>
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
                        <?php } ?>
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
                    // --- LOGIKA TAMPILAN WEB BERDASARKAN API ATAU DB ---
                    if ($report_type == 'MUTASI' && $use_api && is_array($api_data)) {
                        // Data dari API (MATANG & MUTASI) - FORMAT KHUSUS
                        $no = 1;
                        foreach ($api_data as $data) {
                            $saldo_awal_bruto = (float)$data['sum_bruto_initial'];
                            $pemasukan_bruto = (float)$data['in_sum_bruto_between'];
                            $pengeluaran_bruto = (float)$data['out_sum_bruto_between'];
                            $saldo_akhir_raw = $saldo_awal_bruto + $pemasukan_bruto - $pengeluaran_bruto;

                            $saldo_akhir_fmt = number_format($saldo_akhir_raw, 2, '.', ',');
                            $kode_barang = isset($data['kode_detail_kain']) ? $data['kode_detail_kain'] : 'N/A';
                            $nama_barang = isset($data['nama_kain']) ? $data['nama_kain'] : 'N/A';
                            $kode_so = isset($data['kode_so']) ? $data['kode_so'] : '-';
                            $warna = isset($data['warna']) ? $data['warna'] : '-';

                            echo "<tr>";
                            echo "<td align='center'>$no</td>";
                            echo "<td>" . htmlspecialchars($kode_barang) . "</td>";
                            echo "<td>" . htmlspecialchars($nama_barang) . "</td>";
                            echo "<td>" . htmlspecialchars($warna) . "</td>";
                            echo "<td align='right'>" . number_format($saldo_awal_bruto, 2) . "</td>";
                            echo "<td align='right'>" . number_format($pemasukan_bruto, 2) . "</td>";
                            echo "<td align='right'>" . number_format($pengeluaran_bruto, 2) . "</td>";
                            echo "<td align='right' style='font-weight:bold;'>$saldo_akhir_fmt</td>";
                            echo "</tr>";
                            $no++;
                        }
                    } else {
                        // Data dari Query DB (STANDAR)
                        $query = mysql_query($sql_to_execute);

                        if ($query && mysql_num_rows($query) > 0) {
                            $no = 1;
                            while ($data = mysql_fetch_array($query)) {
                                
                                if ($report_type == 'MUTASI') {
                                    // Tampilan Baris Mutasi Standar
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
                                    // Tampilan Baris Pemasukan/Pengeluaran Standar
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
                            // Hitung colspan berdasarkan jenis laporan
                            $col_span = ($report_type == 'MUTASI') ? ($use_api ? 9 : 13) : 10;
                            echo "<tr><td colspan='$col_span' class='text-center'>Data tidak ditemukan</td></tr>";
                        }
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
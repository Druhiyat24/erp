<?php
$rpt = $_GET['rptid'];
if (isset($_GET['parfromv'])) {
	$toexcel = "Y";
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$rpt.xls"); //ganti nama sesuai keperluan 
	header("Pragma: no-cache");
	header("Expires: 0");
} else {
	$toexcel = "N";
}

if (empty($_SESSION['username'])) {
	header("location:../../index.php");
}

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];;
$rpt = $_GET['rptid'];

if (isset($_GET['parfromv'])) {
	$tglf = $_GET['parfrom'];
	$perf = date('d F Y', strtotime($tglf));
	$tglt = $_GET['parto'];
	$pert = date('d F Y', strtotime($tglt));
} else {
	$tglf = fd($_POST['txtfrom']);
	$perf = date('d F Y', strtotime($tglf));
	$tglt = fd($_POST['txtto']);
	$pert = date('d F Y', strtotime($tglt));
}
$sql = "X" . $header_cap . "-" . $rpt . " Dari " . $perf . " s/d " . $pert;
insert_log($sql, $user);
?>
<!-- Header -->
<div class='box'>
	<div class='box-body'>
		<?php
		if ($rpt == "bc30" or $st_company == "GB" or $rpt == "bc33") {
			echo "GUDANG BERIKAT ";
			echo strtoupper($nm_company);
			echo "<br>";
			echo "B. LAPORAN PENGELUARAN BARANG PER DOKUMEN PABEAN";
			echo "<br>";
		}
		echo "PERIODE ";
		echo strtoupper($perf);
		echo " S/D ";
		echo strtoupper($pert);
		echo "<br>";

		if ($toexcel != "Y") {
    // Tentukan file tujuan berdasarkan $rpt
    if ($rpt == "bc33") {
        $export_file = "export_bc33_excel.php";
    } else {
        $export_file = "export_bc30_excel.php";
    }			
			echo "
		<a class='btn btn-primary btn-s' 
       href='$export_file?parfrom=$tglf&parto=$tglt&rptid$rpt'>
       <i class='fa fa-file-excel-o'></i> Save Excel
    </a>";
		}
		?>
	</div>
</div>
<div class='box'>
	<div class='box-body'>
		<table id='data_bc30' width='100%' border='1' style='font-size:12px;' class='table table-bordered table-striped'>
			<thead>
				<tr>
					<?php if ($rpt == "bc30" or $rpt == "bc33" ) {
						echo "
					<tr>
						<th rowspan='2'>NO</th>
						<th rowspan='2'>JENIS DOKUMEN</th>
						<th rowspan='2'>KATEGORI BARANG</th>
						<th colspan='2'>DOKUMEN PABEAN</th>
						<th colspan='2'>BUKTI PENGELUARAN BARANG</th>
						<th rowspan='2'>PEMBELI / PENERIMA</th>
						<th rowspan='2'>KODE BARANG</th>
						<th rowspan='2'>NAMA BARANG</th>
						<th rowspan='2'>SAT</th>
						<th rowspan='2'>JUMLAH</th>
						<th colspan='2'>NILAI BARANG</th>
					</tr>
					<tr>
						<th>NOMOR</th>
						<th>TANGGAL</th>
						<th>NOMOR</th>
						<th>TANGGAL</th>	
						<th>CURR</th>
						<th>NILAI</th>					
					</tr>
					";
					}
					?>
				</tr>
			</thead>
		</table>
<script src="../../plugins/jQuery/jquery-1.11.0.min.js"></script>		
<script>
    var ajaxUrl = "";

    <?php if ($rpt == 'bc30'): ?>
        ajaxUrl = "ajax_bc30.php";
    <?php elseif ($rpt == 'bc33'): ?>
        ajaxUrl = "ajax_bc33.php";
    <?php else: ?>
        ajaxUrl = ""; // fallback kalau tidak cocok
    <?php endif; ?>
$(document).ready(function() {
    $('#data_bc30').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": ajaxUrl,
            "type": "POST",
            "data": {
                tglfrom: "<?= $tglf ?>",
                tglto: "<?= $tglt ?>"
            }
        },
        "columns": [
            { "data": "no" },
            { "data": "jenis_dokumen" },
            { "data": "matclass" },
            { "data": "bcno" },
            { "data": "bcdate" },
            { "data": "trans_no" },
            { "data": "trans_date" },
            { "data": "supplier" },
            { "data": "kode_brg" },
            { "data": "itemdesc" },
            { "data": "unit" },
            { "data": "qty" },
            { "data": "curr" },
            { "data": "nilai_barang" }
        ]
    });
});
</script>		
	</div>
</div>
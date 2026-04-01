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

    $jenis_tanggal = $_POST['jenis_tanggal'];

if ($jenis_tanggal == 'tanggal_terima') {
    $kata = 'TANGGAL TERIMA ';
}else{
    $kata = 'TANGGAL PABEAN ';
}
$sql = "X" . $header_cap . "-" . $rpt . " Dari " . $perf . " s/d " . $pert;
insert_log($sql, $user);
?>
<!-- Header -->
<div class='box'>
	<div class='box-body'>
		<?php
		// if ($rpt == "bc30" or $st_company == "GB" or $rpt == "bc33") {
			echo "KAWASAN BERIKAT ";
			echo strtoupper($nm_company);
			echo "<br>";
			echo "A. LAPORAN PEMASUKAN BARANG PER DOKUMEN PABEAN";
			echo "<br>";
		// }
		echo "PERIODE "; echo $kata;
		echo strtoupper($perf);
		echo " S/D ";
		echo strtoupper($pert);
		echo "<br>";

		if ($toexcel != "Y") {
    // Tentukan file tujuan berdasarkan $rpt
    if ($rpt == "bc23") {
        $export_file = "export_bc23.php";
    } elseif ($rpt == "bc23pjt") {
        $export_file = "export_bc23pjt.php";
    } elseif ($rpt == "bc262msk") {
        $export_file = "export_bc262msk.php";
    } elseif ($rpt == "bc27msk") {
        $export_file = "export_bc27msk.php";
    } elseif ($rpt == "bc27msksub") {
        $export_file = "export_bc27msksub.php";
    } elseif ($rpt == "bc40lkl") {
        $export_file = "export_bc40lkl.php";
    } elseif ($rpt == "bc40sewa") {
        $export_file = "export_bc40sewa.php";
    } elseif ($rpt == "bc40subkon") {
        $export_file = "export_bc40subkon.php";
    } else {
        $export_file = "";
    }			
			echo "
		<a class='btn btn-primary btn-s' 
       href='$export_file?jenis_tanggal=$jenis_tanggal&parfrom=$tglf&parto=$tglt&rptid$rpt'>
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
					<?php 
					// if ($rpt == "bc30" or $rpt == "bc33" ) {
						echo "
					<tr>
						<th rowspan='2'>NO</th>
						<th rowspan='2'>JENIS DOKUMEN</th>
						<th rowspan='2'>KATEGORI BARANG</th>
						<th colspan='2'>DOKUMEN PABEAN</th>
						<th colspan='2'>BUKTI PENERIMAAN BARANG</th>
						<th rowspan='2'>PEMASOK / PENGIRIM</th>
						<th rowspan='2'>KODE BARANG</th>
						<th rowspan='2'>NAMA BARANG</th>
						<th rowspan='2'>SAT</th>
						<th rowspan='2'>JUMLAH</th>
						<th colspan='2'>NILAI BARANG</th>
						<th rowspan='2'>RATE</th>
						<th rowspan='2'>NILAI BARANG IDR</th>
                        <th rowspan='2'>REMARK</th>
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
					// }
					?>
				</tr>
			</thead>
		</table>
<script src="../../plugins/jQuery/jquery-1.11.0.min.js"></script>		
<script>
    var ajaxUrl = "";

    <?php if ($rpt == 'bc23'): ?>
        ajaxUrl = "ajax_bc23.php";
    <?php elseif ($rpt == 'bc23pjt'): ?>
        ajaxUrl = "ajax_bc23pjt.php";
    <?php elseif ($rpt == 'bc262msk'): ?>
        ajaxUrl = "ajax_bc262msk.php";
    <?php elseif ($rpt == 'bc27msk'): ?>
        ajaxUrl = "ajax_bc27msk.php";
    <?php elseif ($rpt == 'bc27msksub'): ?>
        ajaxUrl = "ajax_bc27msksub.php";
     <?php elseif ($rpt == 'bc40lkl'): ?>
        ajaxUrl = "ajax_bc40lkl.php";
    <?php elseif ($rpt == 'bc40sewa'): ?>
        ajaxUrl = "ajax_bc40sewa.php";
    <?php elseif ($rpt == 'bc40subkon'): ?>
        ajaxUrl = "ajax_bc40subkon.php";
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
                tglto: "<?= $tglt ?>",
                jenis_tanggal: "<?= $jenis_tanggal ?>"
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
            { "data": "nilai_barang" },
            { "data": "rate" },
            { "data": "nilai_barang_idr" },
            { "data": "remark", "defaultContent": "" }
        ]
    });
});
</script>		
	</div>
</div>
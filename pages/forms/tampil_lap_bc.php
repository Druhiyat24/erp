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
		if ($rpt == "bc30" or $st_company == "GB") {
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
			echo "
		<a class='btn btn-primary btn-s' href='?mod=tampil_lap_bc&uid=$user&sesi=$sesi&parfrom=$tglf&parto=$tglt&parfromv=$perf
			&partov=$pert&rptid=$rpt&dest=xls'><i class='fa fa-file-excel-o'></i> Save Excel
		</a>";
		}
		?>
	</div>
</div>
<div class='box'>
	<div class='box-body'>
		<table id='examplefix3' width='100%' border='1' style='font-size:12px;' class='table table-bordered table-striped'>
			<thead>
				<tr>
					<?php if ($rpt == "bc30") {
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
			<tbody>

				<?php
				if ($rpt == "bc30") {
					# QUERY TABLE
				// 	$sql = "SELECT 'BC 3.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,
				// a.bcdate,
				// if(a.bppbno_int!='',a.bppbno_int,a.bppbno) trans_no,
				// a.bppbno,
				//  a.bppbdate trans_date,
				//  d.supplier,
				//  if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,concat('FG ',s.id_item)) kode_brg,
				//  s.itemname itemdesc,
				//  a.unit,
				//  a.qty,
				//  round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,
				//  a.curr, 
				//  if(a.price_bc is null or '0' or '',a.price,a.price_bc) price ,
				//  'FG' mattype,
				//  s.id_item, 'BARANG JADI' matclass, s.id_so_det id_item
				//  from bppb a 
				//  inner join masterstyle s on a.id_item=s.id_item 
				//  LEFT join mastersupplier d on a.id_supplier=d.id_supplier 
				//  where bppbdate between '$tglf' and '$tglt' and mid(bppbno,4,2)='FG' and jenis_dok='BC 3.0' and a.cancel = 'N'
				//  order by bcdate,bcno,bppbno
				// ";

					$sql = "SELECT * FROM (SELECT 'BC 3.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,
				a.bcdate,
				if(a.bppbno_int!='',a.bppbno_int,a.bppbno) trans_no,
				a.bppbno,
				 a.bppbdate trans_date,
				 d.supplier,
				 if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,concat('FG ',s.id_item)) kode_brg,
				 s.itemname itemdesc,
				 a.unit,
				 a.qty,
				 round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,
				 a.curr, 
				 if(a.price_bc is null or '0' or '',a.price,a.price_bc) price ,
				 'FG' mattype,
				 s.id_item, 'BARANG JADI' matclass
				 from bppb a 
				 inner join masterstyle s on a.id_item=s.id_item 
				 LEFT join mastersupplier d on a.id_supplier=d.id_supplier 
				 where bppbdate between '$tglf' and '$tglt' and mid(bppbno,4,2)='FG' and jenis_dok='BC 3.0' and a.cancel = 'N'
				 order by bcdate,bcno,bppbno) a
					UNION
					SELECT 'BC 3.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,
				a.bcdate,
				if(a.bppbno_int!='',a.bppbno_int,a.bppbno) trans_no,
				a.bppbno,
				 a.bppbdate trans_date,
				 d.supplier,
				 if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,concat('F ',s.id_item)) kode_brg,
				 s.itemdesc itemdesc,
				 a.unit,
				 a.qty,
				 round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,
				 a.curr, 
				 if(a.price_bc is null or '0' or '',a.price,a.price_bc) price ,
				 'F' mattype,
				 s.id_item, 'FABRIC' matclass
				 from bppb a 
				 inner join masteritem s on a.id_item=s.id_item 
				 LEFT join mastersupplier d on a.id_supplier=d.id_supplier 
				 where bppbdate between '$tglf' and '$tglt' and left(bppbno_int,2)='GK' and jenis_dok='BC 3.0' and a.cancel = 'N'
				 order by bcdate,bcno,bppbno
				";
					#echo $sql;
					$query = mysql_query($sql);
					$no = 1;
					$no_urt = 0;
					$no_urt_fix = 0;
					while ($data = mysql_fetch_array($query)) {
						$trans_date = date('d M Y', strtotime($data[trans_date]));
						$bcdate = $data['bcdate'];
						if ($bcdate == '0000-00-00') {
							$bcdate_fix = '';
						} else {
							$bcdate_fix = date('d M Y', strtotime($bcdate));
						}
						$cri_next = $data['bcno'];
						if ($cri_next != $cri_prev) {
							$no_urt++;
							$no_urt_fix++;
						}
						echo "<tr>";
						if ($no_urt != '' && $no_urt_fix != '') {
							echo "<td> $no_urt</td>";
						} else {
							echo "<td></td>";
						}
						echo "<td>$data[jenis_dokumen]</td>";
						echo "<td>$data[matclass]</td>";
						echo "<td>$data[bcno]</td>";
						echo "<td>$bcdate_fix</td>";
						echo "<td>$data[trans_no]</td>";
						echo "<td>$trans_date</td>";
						echo "<td>$data[supplier]</td>";
						echo "<td>$data[id_item]</td>";
						echo "<td>$data[itemdesc]</td>";
						echo "<td>$data[unit]</td>";
						echo "<td>$data[qty]</td>";
						echo "<td>$data[curr]</td>";
						echo "<td>$data[nilai_barang]</td>";
						echo "</tr>";
						$no++;
						$cri_prev = $data['trans_no'];
						if ($cri_prev == $cri_next) {
							$no_urt_fix = '';
						}
					}
				}
				// echo "<td>$data[tgl_input]</td>";
				?>
			</tbody>
		</table>
	</div>
</div>
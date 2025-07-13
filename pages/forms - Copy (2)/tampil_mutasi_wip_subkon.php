<?php
if (isset($_GET['parfromv']))
{	$toexcel="Y";
	$rpt="mut";
	header("Content-type: application/octet-stream"); 
	header("Content-Disposition: attachment; filename=$rpt.xls");//ganti nama sesuai keperluan 
	header("Pragma: no-cache"); 
	header("Expires: 0");
}
else
{	$toexcel = "N"; }

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company = $rscomp["company"];
	$st_company = $rscomp["status_company"];
	$logo_company = $rscomp["logo_company"];
// session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];;
if (isset($_GET['rptid'])) { $rpt = $_GET['rptid']; } else { $rpt = ""; }

if ($nm_company=="PT. Seyang Indonesia")
{$mattypenya = "";}
else
{$mattypenya = "";}


if  ($st_company!="KITE" AND $rpt=='brgjadistok') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BARANG JADI STOK";}


if (isset($_GET['parfromv']))
{	$tglf = $_GET['parfrom'];
	$perf = date('d F Y', strtotime($tglf));
	$tglt = $_GET['parto'];
	$pert = date('d F Y', strtotime($tglt));
}
else
{	$tglf = fd($_POST['txtfrom']);
	$perf = date('d F Y', strtotime($tglf));
	$tglt = fd($_POST['txtto']);
	$pert = date('d F Y', strtotime($tglt));

}

   // if (isset($_POST['txtparitem'])) { $f_class=" and matclass='".$_POST['txtparitem']."'"; } else { $f_class=""; }


//----------------------------------------------------------------------------------------------------------------

//if (isset($_POST['txtparitem'])) { $f_class=" and matclass='".$_POST['txtparitem']."'"; } else { $f_class=""; }
$sql="X".$header_cap."-".$rpt." Dari ".$perf." s/d ".$pert;

insert_log($sql,$user);
?>

<html>
<head>
<title><?PHP echo $header_cap;?></title>
</head>
<body>
	<?PHP
	   

		if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
		{	echo "<form method='post' name='form' action='save_hasil_opname.php?tgl=$tglt&mode=$rpt'>"; }
		if ($rpt=='gb_bahanbaku')
		{ echo "GUDANG BERIKAT "; echo strtoupper($nm_company); }
		elseif ($st_company=="KITE")
		{ echo $header_cap; echo "<br>"; echo strtoupper($nm_company); }
		elseif ($st_company!="KITE" AND $rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg")
		{ echo "KAWASAN BERIKAT "; echo strtoupper($nm_company); }
		if ($st_company!="KITE" AND $rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg") { echo "<br>"; echo $header_cap; }
		if ($rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg") 
		{ echo "<br>";
			echo "PERIODE "; echo strtoupper($perf); echo " S/D "; echo strtoupper($pert); 
			echo "<br>";
		}
		else
		{ if ($rpt=="hasil")
			{	echo "INPUT HASIL STOCK OPNAME BAHAN BAKU DAN PENOLONG PERIODE "; }
			else if ($rpt=="hasilsl")
			{	echo "INPUT HASIL STOCK OPNAME SCRAP / LIMBAH PERIODE "; }
			else if ($rpt=="hasilwip")
			{	echo "INPUT HASIL STOCK OPNAME BARANG DALAM PROSES PERIODE "; }
			else if ($rpt=="hasilmes")
			{	echo "INPUT HASIL STOCK OPNAME MESIN PERIODE "; }
			else if ($rpt=="hasilfg")
			{	echo "INPUT HASIL STOCK OPNAME BARANG JADI PERIODE "; }
			echo strtoupper($perf); echo " S/D "; echo strtoupper($pert); 
		}
	?>
<?PHP 
	if ($toexcel!="Y" AND $rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg")
	{	
			echo "<a class='btn btn-info btn-sm' href='?mod=view_mut_brgjadistok&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=$rpt&dest=xls'>Save To Excel</a>";	
			echo "<br>";
			echo "-";
		
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	}

##---TEMP PENCARIAN SALDO AWAL DAN TRANSAKSI-----------------------------------------------------------------------------------------------------------------------------------

 

	##===================END PENCARIAN SALDO AWAL DAN TRANSAKSI===========================================

	if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
	{	echo "<table id='examplefix3' class='table table-bordered table-striped' style='font-size:11px;'>"; }
	else
	{	echo "<table id='examplefix3' width='100%' border='1' style='font-size:12px;' class='table table-bordered table-striped'>"; }
	?>
	<thead>
	<tr>
		<th>NO.</th>
		<?php
		 if ($rpt=="hasil")
			{	echo "<th>KLASIFIKASI</th>"; }
			echo "<th>KODE BARANG</th>";
			echo "<th>NAMA BARANG </th>";
			echo "<th>COLOR</th>";
			echo "<th>SIZE</th>";
			echo "<th>GRADE</th>";
			echo "<th>SALDO AWAL</th>";
			echo "<th>PENERIMAAN (IN)</th>";
			echo "<th>KELUAR (OUT)</th>";
			echo "<th>SALDO AKHIR</th>";
		?>
	</tr>
	</thead>
	<tbody>
	<?php

	$sqlk = "SELECT WS_ID,STYLE,PRODUCT,PRODUCT_DESC,COLOR,SIZE,GRADE,
SUM(QTYSAWAL) AS QTYSAWAL, SUM(QTYTERIMA) AS QTYTERIMA, SUM(QTYKELUAR) AS QTYKELUAR ,  
SUM(QTYSAWAL)+SUM(QTYTERIMA)-SUM(QTYKELUAR) AS QTYAKHIR 
FROM  
(SELECT A.WS_ID,B.styleno AS style,C.product_group AS product,C.product_item AS product_desc,A.COLOR,A.SIZE,A.GRADE,A.LOKASI,A.KARTON,
SUM(A.QTY) AS qtysawal, 0 AS qtyterima,0 AS qtykeluar 
FROM tpgudangjadi_sawal AS A INNER JOIN act_costing AS B ON A.WS_ID=B.kpno  
INNER JOIN masterproduct AS C ON B.id_product=C.id 
WHERE A.PERIODE='2021-11-08' AND A.KAT='GJ' AND A.STAT<>'Z' 
GROUP BY A.WS_ID,B.styleno,C.product_group ,C.product_item ,A.COLOR,A.SIZE,A.GRADE,A.LOKASI,A.KARTON 
UNION 
SELECT Y.ws_id,Y.style,Y.product,Y.product_desc,Y.color,Y.size,Y.grade,Y.LOKASI,COALESCE(CASE WHEN Y.NO_CARTOON IS NULL OR Y.NO_CARTOON='' THEN Y.NO_KARUNG ELSE Y.NO_CARTOON END,'') AS KARTON ,       
0 AS qtysawal, SUM(Y.qty_bpb) AS qtyterima, 0 AS qtykeluar 
FROM tpgudangjadi_IN AS X  INNER JOIN tpgudangjadi_in_dtl AS Y ON X.gdjd_id=Y.gdjd_id 
WHERE X.tanggal>='$tglf'  AND X.tanggal<='$tglt'  AND X.GDJD_STAT<>'Z' 
GROUP BY Y.ws_id,Y.style,Y.product,Y.product_desc,Y.color,Y.size,Y.grade,Y.LOKASI,Y.NO_CARTOON,Y.NO_KARUNG 
UNION 
SELECT Y.ws_id,Y.style,Z.product_group AS PRODUCT,Z.product_item AS PRODUCT_DESC,Y.color,Y.size,Y.grade,Y.LOKASI,      COALESCE(CASE WHEN Y.NO_CARTOON IS NULL OR Y.NO_CARTOON='' THEN Y.NO_KARUNG ELSE Y.NO_CARTOON END,'') AS KARTON,       
0 AS qtysawal,0 AS qtyterima, SUM(Y.qty) AS qtykeluar 
FROM tpgudangjadi_out AS X  INNER JOIN tpgudangjadi_out_dtl AS Y ON X.gjout_id=Y.gjout_id 
INNER JOIN masterproduct AS Z ON Y.product_id=Z.id WHERE X.tanggal>='$tglf'  AND X.tanggal<='$tglt'   AND x.gjout_stat<>'Z' 
GROUP BY Y.ws_id,Y.style,Z.product_group ,Z.product_item ,Y.color,Y.size,Y.grade,Y.LOKASI,Y.NO_CARTOON,Y.NO_KARUNG) AS TX 
GROUP BY WS_ID,STYLE,PRODUCT,PRODUCT_DESC,COLOR,SIZE,GRADE
							";

	//MSGBOX
	// echo ($sqlk);
	//MSGBOX
	$sql = mysql_query ($sqlk);
	$tdata=mysql_num_rows($sql);
	#echo $sqlk;
	#CETAK
	$no = 1; #nomor awal
	while($data = mysql_fetch_array($sql))
	{ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
		// $id_itemnya="";
		$i=$no;
		#$id=$data['id_item'];

		$kode_barang=$data['PRODUCT'];
		$nama_barang=$data['PRODUCT_DESC'];
		
		$idws=$data['WS_ID'];
		$color=$data['COLOR'];
		$size=$data['SIZE'];
		$grade=$data['GRADE'];
		
		$sawal=$data['QTYSAWAL'];
		
		$qtyin=$data['QTYTERIMA'];
		$qtyout=$data['QTYKELUAR'];

		$sakhir=$data['QTYAKHIR'];


		{	
			echo "
			<tr>
				<td align='center' $change_bgcolor>$no</td>";
				{	if ($rpt=="hasil")
					{	echo "<th>$matclass</th>"; }
					echo "<td $change_bgcolor>$kode_barang</td>
						<td $change_bgcolor>$nama_barang</td>
						<td $change_bgcolor>$color</td>
						<td $change_bgcolor>$size</td>
						<td $change_bgcolor>$grade</td>";
					}
				if ($rpt!="mwiptot")
				{	echo "
						<td align='right'>$sawal</td>
						<td align='right'>$qtyin</td>
						<td align='right'>$qtyout</td>
						<td align='right'>$sakhir</td>"; 
					}
				}
								
			echo "
			</tr>
			";
			$no++;
		}
	; #$no bertambah 1
	?>
	</tbody>
</table>
<?php 
	if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
	{	echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>"; }
?>
<p>&nbsp;</p>
<?php 
if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
{	echo "</form>"; }
?>
</body>
</html>
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


if ($st_company=="KITE" AND $rpt=='bahanbaku') {$header_cap="F. LAPORAN MUTASI BAHAN BAKU";} 
elseif ($rpt=='bahanbaku') {$header_cap="D. LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU DAN BAHAN PENOLONG";}
elseif ($rpt=='mwipdet' OR $rpt=='mwiptot') {$header_cap="C. LAPORAN POSISI BARANG DALAM PROSES (WIP)";}
elseif ($rpt=='hasil') {$header_cap="INPUT HASIL STOCKOPNAME BAHAN BAKU";}
elseif ($rpt=='hasilsl') {$header_cap="INPUT HASIL STOCKOPNAME LIMBAH / SCRAP";}
elseif ($rpt=='hasilwip') {$header_cap="INPUT HASIL STOCKOPNAME BARANG DALAM PROSES";}
elseif ($rpt=='hasilmes') {$header_cap="INPUT HASIL STOCKOPNAME MESIN";}
elseif ($rpt=='hasilfg') {$header_cap="INPUT HASIL STOCKOPNAME FG";}
elseif ($rpt=='bahanbakupo' OR $rpt=='bahanbakupoitem') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU";} 
elseif ($rpt=='gb_bahanbaku') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BARANG";} 
elseif ($st_company=="KITE" AND $rpt=='barangjadi') {$header_cap="G. LAPORAN MUTASI HASIL PRODUKSI";}
elseif ($st_company!="KITE" AND $rpt=='barangjadi') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BARANG JADI";}
elseif ($rpt=='barangsisa') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BARANG SISA DAN SCRAP";}
elseif ($rpt=='mesin') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI MESIN DAN PERALATAN PERKANTORAN";}
elseif ($rpt=='itemgen') {$header_cap="LAPORAN MUTASI ITEM GENERAL";}

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
//===adyz====================================================
if ($rpt=='itemgen')
{
	if (isset($_GET['katitemgen']))
		{$MTCLASSGEN= $_GET['katitemgen'];}
	else
		{$MTCLASSGEN= $_POST['txtparitem'];}
	$header_cap="LAPORAN MUTASI ITEM GENERAL ($MTCLASSGEN)";	
}
elseif ($rpt=='bahanbaku')
{
	if (isset($_GET['katitembb']))
		{
		    $katbb = $_GET['katitembb'];
			$f_class=" and matclass='".$_GET['katitembb']."'";
			$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU DAN BAHAN PENOLONG (".$_GET['katitembb'].")";	
		}
	else
		{ 
		  $katbb = $_POST['txtparitem'];
		  $f_class=" and matclass='".$_POST['txtparitem']."'";
		  $header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU DAN BAHAN PENOLONG (".$_POST['txtparitem'].")";		
		}
	
}
else
{
   if (isset($_POST['txtparitem'])) { $f_class=" and matclass='".$_POST['txtparitem']."'"; } else { $f_class=""; }
}

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
		//=adyz===========================================================================================================================================================================================
		if ($rpt=='itemgen')
		{	
			echo "<a class='btn btn-info btn-sm' href='?mod=view_mut&katitemgen=$MTCLASSGEN&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=$rpt&dest=xls'>Export To Excel</a>";	
			echo "<br>";
			echo "-";
		}
		elseif ($rpt=='bahanbaku')
		{
			echo "<a class='btn btn-info btn-sm' href='?mod=view_mut&katitembb=$katbb&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=$rpt&dest=xls'>Export To Excel</a>";	
			echo "<br>";
			echo "-";
		}
		else
		{	
			echo "<a class='btn btn-info btn-sm' href='?mod=view_mut_lap_data&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=$rpt&dest=xls'>Save To Excel</a>";	
			echo "<br>";
			echo "-";
		}
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	}
	
  $vNama_View = Array("vmut_in_before", "vmut_out_before", "vmut_ri_before", "vmut_ro_before");
  $vNama_Tbl = Array("bpb", "bppb", "hreturn", "hreturn");
  $vNama_Alias = Array("QtyBPB", "QtyBPPB", "QtyRI", "QtyRO");
  $vWherey = Array("a.bpbdate", "a.bppbdate", "a.returndate", "a.returndate");
  if ($rpt=='bahanbaku' or $rpt=='bahanbakupo' or $rpt=='bahanbakupoitem' or $rpt=='gb_bahanbaku')
	{	$tbl_master = "masteritem";
		if ($nm_company=="PT. Youngil Leather Indonesia")
		{ $cri_mat = "where a.mattype not in ('M','S','L')"; } # Type C tetap dimunculkan
		else
		{ $cri_mat = "where a.mattype not in ('M','S','L','C','N') $f_class "; }
		$fld_mat = "z.mattype";
		if ($nm_company=="PT. Jinwoo Engineering Indonesia")
		{	$fld_item = "if (a.matclass='' OR a.matclass='-',a.itemdesc,concat(a.matclass,' (',a.itemdesc,')'))"; }
		else if ($nm_company=="PT. Hwaseung IBS Indonesia")
		{	$fld_item = "if (a.goods_code2='',a.itemdesc,concat(a.itemdesc,' (',a.goods_code2,')'))"; }
		else if ($nm_company=="PT. Bangun Sarana Alloy")
		{	$fld_item = "concat(a.itemdesc,' ',a.color,' ',a.size)"; }
		else
		{	$fld_item = "a.itemdesc"; }
		$vWherex = Array("left(a.bpbno,2)<>'FG' and left(a.bpbno,1) not in ('M','S','L')", 
				"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) not in ('M','S','L')",
				"left(a.returnno,5)<>'RI-FG' and left(a.returnno,4) not in ('RI-M','RI-S','RI-L')", 
				"left(a.returnno,5)<>'RO-FG' and left(a.returnno,4) not in ('RO-M','RO-S','RO-L')");
	}
	elseif ($rpt=='barangjadi')
	{	$tbl_master = "masterstyle";
		$cri_mat = "";
		$fld_mat = "'FG'";
		if ($nm_company=="PT. Bangun Sarana Alloy")
		{	$fld_item = "a.size";	}
		else if ($nm_company=="PT. Bintang Mandiri Hanafindo")
		{	$fld_item = "concat(a.kpno,' ',a.styleno,' ',a.itemname)";	}
		else
		{	$fld_item = "a.itemname";	}
		$vWherex = Array("left(a.bpbno,2)='FG'", "mid(a.bppbno,4,2)='FG'", "left(a.returnno,5)='RI-FG'", "left(a.returnno,5)='RO-FG'");
	}
	elseif ($rpt=='barangsisa')
	{	$tbl_master = "masteritem";
		$cri_mat = "where a.mattype in ('S','L')";
		$fld_mat = "z.mattype";
		$fld_item = "a.itemdesc";
		$vWherex = Array("left(a.bpbno,2)<>'FG' and left(a.bpbno,1) in ('S','L')", 
		"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) in ('S','L')",
		"left(a.returnno,5)<>'RI-FG' and left(a.returnno,4) in ('RI-S','RI-L')", 
		"left(a.returnno,5)<>'RO-FG' and left(a.returnno,4) in ('RO-S','RO-L')");
	}
	elseif ($rpt=='mesin')
	{	$tbl_master = "masteritem";
		$cri_mat = "where (a.mattype in ('M') or a.tipe_mut='Mesin')";
		$fld_mat = "z.mattype";
		$fld_item = "a.itemdesc";
		$vWherex = Array(
			"left(a.bpbno,2)<>'FG' and (left(a.bpbno,1) in ('M') or s.tipe_mut='Mesin')",
			"mid(a.bppbno,4,2)<>'FG' and (mid(a.bppbno,4,1) in ('M') or s.tipe_mut='Mesin')",
			"left(a.returnno,5)<>'RI-FG' and (left(a.returnno,4) in ('RI-M') or s.tipe_mut='Mesin')",
			"left(a.returnno,5)<>'RO-FG' and (left(a.returnno,4) in ('RO-M') or s.tipe_mut='Mesin')");
	}
	//=adyz============================================================================================================================================
	elseif ($rpt=='itemgen')
	{	
		if (isset($MTCLASSGEN)) 
		{ $cri_mat = "where a.mattype in ('N') and a.n_code_category in (select n_id from mapping_category where description='$MTCLASSGEN') ";} 
		else 
		{ $cri_mat = "where a.mattype in ('N') "; }; 

		$tbl_master = "masteritem";		
		$fld_mat = "z.mattype";
		$fld_item = "a.itemdesc";
		$vWherex = Array(
			"left(a.bpbno,2)<>'FG' and left(a.bpbno,1) in ('N')",
			"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) in ('N')",
			"left(a.returnno,5)<>'RI-FG' and left(a.returnno,4) in ('RI-N') ",
			"left(a.returnno,5)<>'RO-FG' and left(a.returnno,4) in ('RO-N') ");

	}
	//--------------------------------------------------------------------------------------------------------------------------------------------------
  elseif ($rpt=='mwiptot' OR $rpt=='mwipdet')
	{	
		// $tbl_master = "masteritem";
		// $cri_mat = "where a.mattype in ('C')";
		// $fld_mat = "z.mattype";
		// $fld_item = "a.itemdesc";
		// $vWherex = Array("left(a.bpbno,2)<>'FG' and left(a.bpbno,1) in ('C')",
		// 	"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) in ('C')",
		// 	"left(a.returnno,5)<>'RI-FG' and left(a.returnno,4) in ('RI-C')",
		// 	"left(a.returnno,5)<>'RO-FG' and left(a.returnno,4) in ('RO-C')");
	}
	else{

	}
	
	if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
	{	echo "<table id='examplefix3' class='table table-bordered table-striped' style='font-size:11px;'>"; }
	else
	{	echo "<table id='examplefix3' width='100%' border='1' style='font-size:12px;' class='table table-bordered table-striped'>"; }
	?>
	<thead>
	<tr>
		<th>NO.</th>
		<th>KODE BARANG</th>
		<th>NAMA BARANG</th>
		<th>SAT</th>
		<th>JUMLAH</th>
		<th>KETERANGAN</th>
	</tr>
	</thead>
	<tbody>
	<?php
	
	$sqlk = "select kode_barang,nama_barang,satuan,format(saldo_buku,2) saldo_buku,format(hasil_pencacahan,2) hasil_pencacahan,keterangan from tbl_mutasi_wip where tgl_trans BETWEEN '$tglf' and '$tglt'";
	//MSGBOX
	#	echo ($sqlk);
	//MSGBOX
	$sql = mysql_query ($sqlk);
	$tdata=mysql_num_rows($sql);
	// echo $sqlk;
	#CETAK
	$no = 1; #nomor awal
	while($data = mysql_fetch_array($sql))
	{ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
		
			echo "
			<tr>
				<td align='center'>$no</td>
				<td >".$data['kode_barang']."</td>
				<td >".$data['nama_barang']."</td>
				<td >".$data['satuan']."</td>
				<td >".$data['saldo_buku']."</td>
				<td >".$data['keterangan']."</td>
			</tr>
			";
			$no++;
	}; #$no bertambah 1
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
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


if  ($st_company!="KITE" AND $rpt=='wip_dc') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI WIP DC";}


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
			echo "<a class='btn btn-info btn-sm' href='?mod=view_mut_wip_dc&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=$rpt&dest=xls'>Save To Excel</a>";	
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
			echo "<th>NO WS</th>";
			echo "<th>BUYER</th>";
			echo "<th>STYLE</th>";
			echo "<th>COLOR</th>";
			echo "<th>SALDO AWAL</th>";
			echo "<th>IN CUTTING</th>";
			echo "<th>IN PENGGANTI REJECT DARI CUTTING</th>";
			echo "<th>IN RETURAN DARI SEWING</th>";
			echo "<th>IN TERIMA DARI BUYER</th>";
			echo "<th>OUT RETUR PANEL TO BUYER</th>";
			echo "<th>OUT SEND TO PROD</th>";
			echo "<th>OUT GANTI REJECT KE SEWING</th>";
			echo "<th>PEMUSNAHAN PANEL REJECT SET</th>";
			echo "<th>PENYESUAIAN DATA PROD</th>";
			echo "<th>BALANCE PANEL</th>";
		?>
	</tr>
	</thead>
	<tbody>
	<?php

	$sqlk = "SELECT * from lap_wip_dc where periode >= '$tglf'
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

		$periode=$data['periode'];
		$no_ws=$data['no_ws'];
		
		$buyer=$data['buyer'];
		$style=$data['style'];
		$color=$data['color'];
		$sawal=$data['saldo_awal'];
		
		$in_cut=$data['in_cut'];
		
		$in_ganti_reject_cut=$data['in_ganti_reject_cut'];
		$in_retur_sewing=$data['in_retur_sewing'];
		$in_buyer=$data['in_buyer'];
		$out_retur_panel_buyer=$data['out_retur_panel_buyer'];
		$out_prod=$data['out_prod'];
		$out_ganti_reject_sewing=$data['out_ganti_reject_sewing'];
		$out_panel_reject_set=$data['out_panel_reject_set'];
		$penyesuaian_prod=$data['penyesuaian_prod'];
		$balance=$data['balance'];


		{	
			echo "
			<tr>
				<td align='center' $change_bgcolor>$no</td>";
				{	if ($rpt=="hasil")
					{	echo "<th>$matclass</th>"; }
					echo "<td $change_bgcolor>$no_ws</td>
						<td $change_bgcolor>$buyer</td>
						<td $change_bgcolor>$style</td>
						<td $change_bgcolor>$color</td>";
					}
				if ($rpt!="mwiptot")
				{	echo "
						<td align='right'>$sawal</td>
						<td align='right'>$in_cut</td>
						<td align='right'>$in_ganti_reject_cut</td>
						<td align='right'>$in_retur_sewing</td>
						<td align='right'>$in_buyer</td>
						<td align='right'>$out_retur_panel_buyer</td>
						<td align='right'>$out_prod</td>
						<td align='right'>$out_ganti_reject_sewing</td>
						<td align='right'>$out_panel_reject_set</td>
						<td align='right'>$penyesuaian_prod</td>
						<td align='right'>$balance</td>"; 
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
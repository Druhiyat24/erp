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
			echo "<th>STYLE</th>";
			echo "<th>PRODUCT GROUP</th>";
			echo "<th>PRODUCT ITEM</th>";
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

	$sqlk = "select ws_id, style, product,product_desc, color, size, grade, sum(saldo_awal) saldo_awal, sum(qty_in) qty_in, sum(qty_out) qty_out from
(
select  ws_id, style, product,product_desc, color, size, grade, saldo_awal, 0 qty_in, 0 qty_out from
(
select ws_id, style, product,product_desc, color, size, grade, sum(sawal) + sum(saldo_awal.qty_in) - sum(saldo_awal.qty_out) saldo_awal, 0 qty_in, 0 qty_out from 
(
select a.ws_id, ac.styleno style, product_group product,product_item product_desc, color, size, grade, sum(a.qty) sawal, 0 qty_in, 0 qty_out  from tpgudangjadi_sawal a
inner join act_costing ac on a.ws_id = ac.kpno
inner Join masterproduct b On ac.id_product=b.id
where periode = '2022-02-01' 
group by ws_id, styleno,product_group,product_item, color, size, grade
union
select ws_id, style, product,product_desc, color, size,grade, 0 sawal,sum(qty_bpb) qty_in, 0 qty_out from tpgudangjadi_in a
inner join tpgudangjadi_in_dtl b on a.gdjd_id =  b.gdjd_id
where a.tanggal >= '2022-02-01' and a.tanggal < '$tglf' and gdjd_stat <> 'Z'
group by ws_id, style,product,product_desc, color, size, grade
union
select ws_id, style, product_group product,product_item product_desc,color, size,grade, 0 sawal,0 qty_in, sum(qty) qty_out from tpgudangjadi_out a
inner join tpgudangjadi_out_dtl b on a.gjout_id =  b.gjout_id
inner join masterproduct mp on b.product_id = mp.id
where a.tanggal >= '2022-02-01' and a.tanggal < '$tglf' and gjout_stat <> 'Z'
group by ws_id, style,product,product_desc, color, size, grade
) saldo_awal
group by ws_id, style,product,product_desc, color, size, grade
) master_saldo_awal
union
select ws_id, style, product,product_desc, color, size,grade, 0 saldo_awal,sum(qty_bpb) qty_in, 0 qty_out from tpgudangjadi_in a
inner join tpgudangjadi_in_dtl b on a.gdjd_id =  b.gdjd_id
where a.tanggal >= '$tglf' and a.tanggal <= '$tglt' and gdjd_stat <> 'Z'
group by ws_id, style,product,product_desc, color, size, grade
union
select ws_id, style, product_group product,product_item product_desc,color, size,grade, 0 saldo_awal,0 qty_in, sum(qty) qty_out from tpgudangjadi_out a
inner join tpgudangjadi_out_dtl b on a.gjout_id =  b.gjout_id
inner join masterproduct mp on b.product_id = mp.id
where a.tanggal >= '$tglf' and a.tanggal <= '$tglt' and gjout_stat <> 'Z'
group by ws_id, style,product,product_desc, color, size, grade
) master_trans
group by ws_id, style, product,product_desc,color, size, grade
							";

	//MSGBOX
	 #echo ($sqlk);
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
		$style=$data['style'];	
		$idws=$data['ws_id'];
		$color=$data['color'];
		$size=$data['size'];
		$grade=$data['grade'];		
		$sawal=$data['saldo_awal'];		
		$qtyin=$data['qty_in'];
		$qtyout=$data['qty_out'];
		$sakhir=$sawal+$qtyin-$qtyout;
		$product=$data['product'];	
		$product_desc=$data['product_desc'];	
		{	
			echo "
			<tr>
				<td align='center' $change_bgcolor>$no</td>";
				{	if ($rpt=="hasil")
					{	echo "<th>$matclass</th>"; }
					echo "<td $change_bgcolor>$idws</td>
						<td $change_bgcolor>$style</td>
						<td $change_bgcolor>$product</td>
						<td $change_bgcolor>$product_desc</td>
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
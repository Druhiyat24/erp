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
//session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];;
if (isset($_GET['rptid'])) { $rpt = $_GET['rptid']; } else { $rpt = ""; }

if ($nm_company=="PT. Seyang Indonesia")
{$mattypenya = "";}
else
{$mattypenya = "";}

if ($st_company=="KITE" AND $rpt=='bahanbaku2det') {$header_cap="F. LAPORAN MUTASI BAHAN BAKU";} 
elseif ($rpt=='bahanbaku2det') {$header_cap="D. LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU DAN BAHAN PENOLONG 2";}
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
elseif ($rpt=='bahanbaku2det')
{
	if (isset($_GET['katitembb']))
		{
		    $katbb = $_GET['katitembb'];
		if ($katbb=='-')
		{    
			$f_class=" mi.matclass in ('ACCESORIES PACKING','ACCESORIES SEWING')";
			$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU DAN BAHAN PENOLONG ACCESORIES PACKING DAN SEWING";	
		}
		else
		{
			$f_class=" mi.matclass='".$_GET['katitembb']."'";
			$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU DAN BAHAN PENOLONG (".$_GET['katitembb'].")";				
		}
		}
	else
		{ 
		  $katbb = $_POST['txtparitem'];
		  if ($katbb=='-')
		  {
		  $f_class=" mi.matclass in ('ACCESORIES PACKING','ACCESORIES SEWING')";
		  }
		  else
		  {
		  $f_class=" mi.matclass='".$_POST['txtparitem']."'";
		  }
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
		elseif ($rpt=='bahanbaku2det')
		{
			echo "<a class='btn btn-info btn-sm' href='?mod=view_mut_bhnbaku2det&katitembb=$katbb&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=$rpt&dest=xls'>Export To Excel</a>";	
			echo "<br>";
			echo "-";
		}
		else
		{	echo "<a class='btn btn-info btn-sm' href='?mod=view_mut&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=$rpt&dest=xls'>Save To Excel</a>";	
			echo "<br>";
			echo "-";
		}
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
		<th>ID ITEM</th>
		<th>KODE BARANG</th>
		<th>NAMA BARANG</th>
		<th>NO WS</th>
		<th>SALDO AWAL</th>
		<th>PEMASUKAN</th>
		<th>PENGELUARAN</th>
		<th>SALDO AKHIR</th>
		<th>SAT</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if ($tbl_master=="masterstyle") 
	{ 
		$sql_add=",kpno,itemname,color"; 
	}
	else if ($tbl_master=="masteritem") 
	{ 
		$sql_add=",matclass"; 
	} 
	else 
	{ 
		$sql_add=""; 
	}


	 $sqlk = "select isi.*, mi.itemdesc, mi.goods_code, ac.kpno from
(
select A.id_jo, A.id_item, sum(A.sain) - sum(A.saout) saldoawal, sum(A.qtyin) qtyterima, sum(A.qtyout) qtykeluar, (sum(A.sain) - sum(A.saout) ) + sum(A.qtyin) - sum(A.qtyout) saldoakhir , unit 
from
(select id_item,id_jo, sum(qty) as sain, 0 as saout, 0 as qtyin, 0 as qtyout, unit from bpb where bpbdate < '$tglf' group by id_jo, id_item, unit
union
select id_item,id_jo, 0 as sain, sum(qty) as saout, 0 as qtyin, 0 as qtyout, unit from bppb where bppbdate < '$tglf' group by id_jo, id_item, unit
union
select id_item,id_jo, 0 as sain, 0 as saout, sum(qty) as qtyin, 0 as qtyout, unit from bpb where bpbdate >= '$tglf' and bpbdate <= '$tglt' group by id_jo, id_item, unit
union
select id_item,id_jo, 0 as sain, 0 as saout, 0 as qtyin, sum(qty) as qtyout, unit from bppb where bppbdate >= '$tglf' and bppbdate <= '$tglt' group by id_jo, id_item, unit ) A
group by A.id_jo, A.id_item, unit ) isi
inner join masteritem mi on isi.id_item = mi.id_item
inner join (select id_jo,kpno from jo_det jd
inner join so on so.id = jd.id_so
inner join act_costing ac on ac.id = so.id_cost) ac on ac.id_jo = isi.id_jo
where $f_class";

	 echo ($sqlk);

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

		$id_jo=$data['id_jo'];
		$id_item=$data['id_item'];
		$saldoawal=$data['saldoawal'];
		$qtyterima=$data['qtyterima'];		
		$qtykeluar=$data['qtykeluar'];
		$saldoakhir=$data['saldoakhir'];
		$unit=$data['unit'];
		$itemdesc=$data['itemdesc'];		
		$kdbarang=$data['goods_code'];
		$nows=$data['kpno'];
		
		{	
			echo "
			<tr>
				<td align='center' $change_bgcolor>$no</td>";
				{	if ($rpt=="hasil")
					{	echo "<th>$matclass</th>"; }
					echo "<td $change_bgcolor>$id_item</td>
						<td $change_bgcolor>$kdbarang</td>
						<td $change_bgcolor>$itemdesc</td>
						<td $change_bgcolor>$nows</td>
						<td align='right'>$saldoawal</td>
						<td align='right'>$qtyterima</td>
						<td align='right'>$qtykeluar</td>
						<td align='right'>$saldoakhir</td>
						<td $change_bgcolor>$unit</td>
						";
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
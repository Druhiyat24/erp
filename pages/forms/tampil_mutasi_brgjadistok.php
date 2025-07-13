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

	}
	
	if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
	{	echo "<table id='examplefix3' class='table table-bordered table-striped' style='font-size:11px;'>"; }
	else
	{	echo "<table id='examplefix3' width='100%' border='1' style='font-size:12px;' class='table table-bordered table-striped'>"; }
    // persiapkan curl  

	?>
	<thead>
	<tr>
		<th>NO.</th>
		<?php
			echo "<th>NO WS</th>";
			echo "<th>STYLE</th>";
			echo "<th>PRODUCT GROUP</th>";
			echo "<th>PRODUCT ITEM</th>";
			echo "<th>COLOR</th>";
			echo "<th>SIZE</th>";
			echo "<th>GRADE</th>";
			echo "<th>LOKASI</th>";
			echo "<th>NO CARTON</th>";
			echo "<th>SALDO AWAL</th>";
			echo "<th>PENERIMAAN (IN)</th>";
			echo "<th>KELUAR (OUT)</th>";
			echo "<th>SALDO AKHIR</th>";
		?>
	</tr>
	</thead>
  <tbody>
    <?php
    $ch = curl_init(); 
    $params = http_build_query(["tgl_awal" => $tglf, "tgl_akhir" => $tglt]);

    // set url 
    curl_setopt($ch, CURLOPT_URL, "http://10.10.5.62:8123/api/laporan-fg-stock/show_fg_stok_mutasi?".$params);

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);  

	$data = json_decode($output, true);
	$id = $data['data'];
    $no = 1;

    echo $data['tanggal'];
foreach ($id as $x) 
{ ?>
    <tr>
      <td><?php echo $no;?></td>
      <td><?php  echo($x['ws']);?></td>
      <td><?php  echo($x['styleno']);?></td>
      <td><?php  echo($x['product_group']);?></td>
      <td><?php  echo($x['product_item']);?></td>
      <td><?php  echo($x['color']);?></td>
      <td><?php  echo($x['size']);?></td>
      <td><?php  echo($x['grade']);?></td>
      <td><?php  echo($x['lokasi']);?></td>
      <td><?php  echo($x['no_carton']);?></td>
      <td><?php  echo($x['qty_awal']);?></td>
      <td><?php  echo($x['qty_in']);?></td>
      <td><?php  echo($x['qty_out']);?></td>
      <td><?php  echo($x['saldo_akhir']);?></td>
    </tr>
    <?php 
 $no++;
 } ?>
  </tbody>
</table>
</body>
</html>
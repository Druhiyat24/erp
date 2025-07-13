<?PHP
echo "<head>";
	echo "<link rel='stylesheet' href='../../bootstrap/css/bootstrap.min.css'>";
echo "</head>";
$rpt = $_GET['rptid'];
if (isset($_GET['parfromv']))
{	$toexcel = "Y";
	header("Content-type: application/octet-stream"); 
	header("Content-Disposition: attachment; filename=$rpt.xls");//ganti nama sesuai keperluan 
	header("Pragma: no-cache"); 
	header("Expires: 0");
}
else
{ $toexcel = "N"; }

include "../../include/conn.php";
include "fungsi.php";

$nm_company = flookup("company","mastercompany","company<>''");
$st_company = flookup("status_company","mastercompany","company<>''");

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (!isset($_SESSION['username'])) { header("location:../../index.php"); }
if (!isset($_SESSION['sesi'])) { header("location:../../index.php"); }

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];;
$rpt = $_GET['rptid'];
if ($rpt=="in_dev")
{	$header_cap="LAPORAN KEGIATAN PEMASUKAN";	}
else
{	$header_cap="LAPORAN KEGIATAN PENGELUARAN";	}
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
$sql="X".$header_cap."-".$rpt." Dari ".$perf." s/d ".$pert;
insert_log($sql,$user);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?PHP echo $header_cap; ?></title>
</head>
<body>
<?PHP
	echo "KAWASAN BERIKAT "; 
	echo strtoupper($nm_company);echo "<br>";
	echo $header_cap;echo "<br>";
	echo "PERIODE "; echo strtoupper($perf); echo " S/D "; echo strtoupper($pert); echo "<br>";
?>
<?PHP
if ($toexcel!="Y")
{	echo "<a class='btn btn-info btn-sm' href='r_devisa.php?uid=$user&sesi=$sesi&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=$rpt'>Save To Excel</a>"; 
	echo "<br>";
	echo "-";
}
echo "
<table width='100%' border='1' style='font-size:12px;' class='table table-bordered table-striped'>
	<thead>
		<tr>
			<th>NO</th>
			<th>NAMA PDKB / NAMA PPGB</th>
			<th>1</th>
			<th>2</th>
			<th>3</th>
			<th>4</th>
			<th>5</th>
			<th>6</th>
			<th>7</th>
			<th>8</th>
			<th>9</th>
			<th>10</th>
			<th>11</th>
			<th>12</th>
			<th>13</th>
			<th>14</th>
			<th>Devisa</th>
		</tr>
	</thead>
	<tbody>";
		if ($rpt=="in_dev") 
		{ $tbl_trans="bpb"; $tbl_fld="bpbdate"; 
			$bc27="sum(if(tujuan='' and jenis_dok='BC 2.7',1,0))";
			$bc27out="0";
			$no4="sum(if(tujuan regexp 'SUBKON' and (jenis_dok='BC 2.6.2' or jenis_dok='BC 4.0' or jenis_dok='BC 2.7'),1,0))";
			$no11="0";
			$no5="sum(if((tujuan regexp 'PERBAIKI' or tujuan regexp 'PINJAM' or tujuan regexp 'PAMER') and (jenis_dok='BC 2.6.2' or jenis_dok='BC 4.0' or jenis_dok='BC 2.7'),1,0))";
			$no12="0";
		} 
		else 
		{ $tbl_trans="bppb"; $tbl_fld="bppbdate"; 
			$bc27="0";
			$bc27out="sum(if(tujuan='' and jenis_dok='BC 2.7',1,0))";
			$no4="0";
			$no11="sum(if(tujuan regexp 'SUBKON' and (jenis_dok='BC 2.6.1' or jenis_dok='BC 4.1' or jenis_dok='BC 2.7'),1,0))";
			$no5="0";
			$no12="sum(if((tujuan regexp 'PERBAIKI' or tujuan regexp 'PINJAM' or tujuan regexp 'PAMER') and (jenis_dok='BC 2.6.1' or jenis_dok='BC 4.1' or jenis_dok='BC 2.7'),1,0))";
		}
		$que="select ucase(supplier) supplier
			,sum(if(jenis_dok='BC 2.3',1,0)) n1,0 n2
			,$bc27 n3
			,$no4 n4
			,$no5 n5
			,0 n6
			,sum(if(tujuan='' and jenis_dok='BC 4.0',1,0)) n7
			,sum(if(jenis_dok='BC 3.0',1,0)) n8
			,0 n9
			,$bc27out n10
			,$no11 n11
			,$no12 n12
			,0 n13
			,sum(if(tujuan='' and jenis_dok='BC 4.1',1,0)) n14,sum(qty*price) ndev 
			from $tbl_trans a inner join mastersupplier s on a.id_supplier=s.id_supplier 
			where $tbl_fld between '$tglf' and '$tglt' group by a.id_supplier";
		#echo $que;
		$sql=mysql_query($que);
		$no = 1; #nomor awal
		while($data = mysql_fetch_array($sql))
		{	echo "
			<tr>
				<td>$no</td>
				<td>$data[supplier]</td>
				<td>$data[n1]</td>
				<td>$data[n2]</td>
				<td>$data[n3]</td>
				<td>$data[n4]</td>
				<td>$data[n5]</td>
				<td>$data[n6]</td>
				<td>$data[n7]</td>
				<td>$data[n8]</td>
				<td>$data[n9]</td>
				<td>$data[n10]</td>
				<td>$data[n11]</td>
				<td>$data[n12]</td>
				<td>$data[n13]</td>
				<td>$data[n4]</td>
				<td>".fn($data['ndev'],2)."</td>
			</tr>";
			$no++;
		}
	echo "</tbody>
</table>";
?>
<p>&nbsp;</p>
</body>
</html>
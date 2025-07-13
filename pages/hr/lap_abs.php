<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mode = $_GET['mode'];
$mod = $_GET['mod'];
if (isset($_GET['frexc'])) 
{ $excel="Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=detail.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0"); 
} 
else 
{ $excel="N"; }
if ($excel=="Y")
{ $from=date('Y-m-d',strtotime($_GET['frexc']));
  $to=date('Y-m-d',strtotime($_GET['toexc']));
  $tipenya=$_GET['tpexc'];
  $classnya=$_GET['clexc'];
}
else
{ if (isset($_POST['txtfrom'])) { $from=date('Y-m-d',strtotime($_POST['txtfrom'])); } else { $from=""; }
  if (isset($_POST['txtto'])) { $to=date('Y-m-d',strtotime($_POST['txtto'])); } else { $to=""; }
  if (isset($_POST['txttipe'])) { $tipenya=$_POST['txttipe']; } else { $tipenya=""; }
  if (isset($_POST['txtparitem'])) { $classnya=$_POST['txtparitem']; } else { $classnya=""; }
}
  
if ($mode=="Detail_In")
{ $titlenya="Detail Pemasukan ".$tipenya; }
else if ($mode=="Hist")
{ $titlenya="Riwayat Aktifitas"; }
else
{ $titlenya="Detail Pengeluaran ".$tipenya; }

if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }

echo "<div class='box'>";
	echo "<div class='box-body'>";
		echo "Periode Dari ".fd_view($from)." s/d ".fd_view($to);
		if ($excel=="N") 
		{ echo "<br><a href='?mod=$mod&mode=$mode&frexc=$from&toexc=$to&tpexc=$tipenya&clexc=$classnya&dest=xls'>Save To Excel</a></br>"; }
	echo "</div>";	
echo "</div>";
echo "<div class='box'>";
	echo "<div class='box-body'>";
		if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
		echo "<table id='example1' $tbl_border style='font-size: 12px; width: 100%;' class='display responsive'>";
			echo "<thead>";
				echo "
				<tr>
					<th>No</th>
					<th>NIK</th>
					<th>Nama</th>
					<th>Tanggal</th>
					<th>Masuk</th>
					<th>Pulang</th>
					<th>Jam Kerja</th>
					<th>Over Time</th>
					<th>Pay OT</th>
					<th>Mulai SPL</th>
					<th>Selesai SPL</th>
				</tr>";
			echo "</thead>";
			echo "<tbody>";
			$sql="select a.empno,s.nama,workdate,timein,
				timeout,workhour,overtime,payovertime,d.mulai,d.selesai,
				dayname(workdate) nm_hari  
				from hr_timecard a inner join hr_masteremployee s 
				on a.empno=s.nik left join hr_spl d on a.empno=d.nik 
				and a.workdate=d.tanggal
				where workdate between '$from' and '$to' 
				order by a.empno,workdate";
			#echo $sql;
			$query = mysql_query($sql);
      $no = 1; 
      while($data = mysql_fetch_array($query))
      {	if ($data['nm_hari']=="Saturday") 
    		{ $warna=" style=' background-color: red;'"; }
    		else
    		{ $warna=" "; }
      	echo "
  			<tr $warna>
  				<td>$no</td>
  				<td>$data[empno]</td>
  				<td>$data[nama]</td>
  				<td>$data[workdate]</td>
  				<td>$data[timein]</td>
  				<td>$data[timeout]</td>
  				<td>$data[workhour]</td>
  				<td>$data[overtime]</td>
  				<td>$data[payovertime]</td>
  				<td>$data[mulai]</td>
  				<td>$data[selesai]</td>
  			</tr>";
    		$no++;
      }
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
?>
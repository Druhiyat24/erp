<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_GET['from'])) { $from=fd($_GET['from']); }
else if (isset($_POST['txtfrom'])) { $from=fd($_POST['txtfrom']); } 
else { $from=""; }
if (isset($_GET['to'])) { $to=fd($_GET['to']); }
else if (isset($_POST['txtto'])) { $to=fd($_POST['txtto']); } 
else { $to=""; }
if (isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}
if ($mode=="LapOTD")
{ $grnya=" s.nik ";  }
else
{ $grnya=" s.department,s.bagian,jamspl ";  }
$result=mysql_query("select concat(s.nik,' ',s.nama) nik,s.department,s.bagian,mulai,selesai,
  sum(if(jamspl>=1,if(jamspl=0.5,0.5,1),jamspl)) ot1,
  sum(if(jamspl>1,jamspl-1,0)) ot2,
  sum((gaji_pokok/173)*(if(jamspl>=1,if(jamspl=0.5,0.5,1),jamspl)*1.5)) pot1,
  sum((gaji_pokok/173)*(if(jamspl>1,jamspl-1,0)*2)) pot2,
  count(distinct a.nik) temp
  from hr_spl a inner join hr_masteremployee s on a.nik=s.nik left join hr_mastersalary d on a.nik=d.nik 
  where a.tanggal between '$from' and '$to' 
  group by $grnya ");
echo "<div class='box'>";
  echo "<div class='box-body'>";
  echo "Periode ".fd_view($from)." s/d ".fd_view($to);
if ($excel=="N") 
{ echo "<br><a href='?mod=$mod&mode=$mode&from=$from&to=$to&dest=xls'>Save To Excel</a></br>"; }
echo "</div>";  
echo "</div>";
echo "
<div class='box'>
<div class='box-body'>";
$stytbl="style='vertical-align: middle;' rowspan='2'";
echo "<table id='example1' class='table table-bordered table-striped' border='1' width='100%' style='font-size:12px;'>";
echo "
<thead>
<tr>
  <th $stytbl>No</th>";
  if ($mode=="LapOTD")
  { echo "<th $stytbl>NIK</th>
      <th $stytbl>Departement</th>
      <th $stytbl>Bagian</th>";  
  }
  else
  { echo "<th $stytbl>Departement</th>
      <th $stytbl>Bagian</th>
      <th $stytbl>Karyawan Lembur</th>";  
  }
  echo "<th colspan='2'>Lembur</th>
  <th colspan='2'>Jumlah Jam</th>
  <th colspan='2'>Rate</th>
  <th $stytbl>Total / Hari</th>
  <th $stytbl>Total</th>
</tr>
<tr>
  <th>Dari</th>
  <th>Sampai</th>
  <th>L1</th>
  <th>L2</th>
  <th>L1</th>
  <th>L2</th>
</tr>
</thead>";
$no=1;
while($row = mysql_fetch_array($result))
{ 
echo "
<tr>
<td>$no</td>";
if ($mode=="LapOTD")
{ echo "<td>$row[nik]</td>
  <td>$row[department]</td>
  <td>$row[bagian]</td>";
}
else
{ echo "<td>$row[department]</td>
  <td>$row[bagian]</td>
  <td>$row[temp]</td>";
}
echo "<td>$row[mulai]</td>
<td>$row[selesai]</td>
<td>$row[ot1]</td>
<td>$row[ot2]</td>
<td>".fn($row['pot1'],2)."</td>
<td>".fn($row['pot2'],2)."</td>
<td>".fn($row['pot1']+$row['pot2'],2)."</td>
<td>".fn($row['pot1']+$row['pot2'],2)."</td>
</tr>";
$no++;
}
echo "</table>";
echo "
</div>
</div>";
?>
<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_GET['from'])) { $from=fd($_GET['from']); }
else if (isset($_POST['txtfrom'])) { $from=fd($_POST['txtfrom']); } 
else { $from=""; }
if (isset($_GET['to'])) { $to=fd($_GET['to']); }
else if (isset($_POST['txtto'])) { $to=fd($_POST['txtto']); } 
else { $to=""; }

$sql="select s.department,s.bagian,
  if(s.jabatan regexp 'STAFF',count(distinct s.nik),0) t_staff,
  if(s.jabatan not regexp 'STAFF',count(distinct s.nik),0) t_nstaff,
  if(d.kategori='S',count(*),0) t_sakit,
  if(d.kategori='I',count(*),0) t_ijin,
  if(d.kategori='C',count(*),0) t_cuti,
  if(d.kategori='M',count(*),0) t_mg,
  if(d.kategori is null,count(*),0) t_hdr
  from hr_timecard a inner join hr_masteremployee s on a.empno=s.nik 
  left join hr_masterabsen d on a.absentcode=d.kodeabsen 
  where a.workdate between '$from' and '$to' 
  group by s.department,s.bagian";
$result=mysql_query($sql);
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
  <th $stytbl>No</th>
  <th $stytbl>Departement</th>
  <th $stytbl>Bagian</th>
  <th colspan='2'>Jml Karyawan</th>
  <th $stytbl>Sakit</th>
  <th $stytbl>Ijin</th>
  <th $stytbl>Cuti</th>
  <th $stytbl>Mangkir</th>
  <th $stytbl>Total</th>
  <th $stytbl>Total Hadir</th>
  <th $stytbl>%</th>
</tr>
<tr>
  <th>Staff</th>
  <th>Non Staff</th>
</tr>
</thead>";
$no=1;
while($row = mysql_fetch_array($result))
{ $total=$row['t_sakit'] + $row['t_ijin'] + $row['t_cuti'] + $row['t_mg'];
  $total_hdr=($row['t_staff']+$row['t_nstaff']) - $total;
  if ($row['t_staff']+$row['t_nstaff']==0)
  { $persen=0;  }
  else
  { $persen=($total / ($row['t_staff']+$row['t_nstaff'])) * 100;  }
  $persen=round($persen,2)." %";
  echo "
  <tr>
    <td>$no</td>
    <td>$row[department]</td>
    <td>$row[bagian]</td>
    <td>$row[t_staff]</td>
    <td>$row[t_nstaff]</td>
    <td>$row[t_sakit]</td>
    <td>$row[t_ijin]</td>
    <td>$row[t_cuti]</td>
    <td>$row[t_mg]</td>
    <td>$total</td>
    <td>$total_hdr</td>
    <td>$persen</td>
  </tr>";
  $no++;
}
echo "</table>";
echo "
</div>
</div>";
?>
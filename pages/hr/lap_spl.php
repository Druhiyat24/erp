<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_GET['from'])) { $from=fd($_GET['from']); }
else if (isset($_POST['txtfrom'])) { $from=fd($_POST['txtfrom']); } 
else { $from=""; }
if (isset($_GET['to'])) { $to=fd($_GET['to']); }
else if (isset($_POST['txtto'])) { $to=fd($_POST['txtto']); } 
else { $to=""; }

$sql = "SELECT a.*,s.nama 
  FROM hr_spl a inner join hr_masteremployee s 
  on a.nik=s.nik where tanggal between '$from' and '$to'
  order by a.tanggal desc";
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
  <th>No</th>
  <th>Nik</th>
  <th>Nama</th>
  <th>Tanggal</th>
  <th>Jam Mulai</th>
  <th>Jam Selesai</th>
  <th>Keterangan</th>
</tr>
</thead>";
$no=1;
while($row = mysql_fetch_array($result))
{ echo "<tr>";
    echo "<td>$no</td>"; 
    echo "<td>$row[nik]</td>"; 
    echo "<td>$row[nama]</td>"; 
    echo "<td>$row[tanggal]</td>";
    echo "<td>$row[mulai]</td>"; 
    echo "<td>$row[selesai]</td>"; 
    echo "<td>$row[keterangan]</td>";
  echo "</tr>";
  $no++;
}
echo "</table>";
echo "
</div>
</div>";
?>
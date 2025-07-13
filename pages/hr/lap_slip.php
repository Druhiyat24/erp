<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if (isset($_GET['pay'])) { $paydate=fd($_GET['pay']); }
else if (isset($_POST['txtfrom'])) { $paydate=fd($_POST['txtfrom']); } 
else { $paydate=""; }

$result=mysql_query("SELECT a.*,s.* FROM hr_masteremployee a inner join hr_salaryrecord s on a.nik=s.nik 
        where paydate='$paydate'");
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "Pay Date ".fd_view($paydate);
    if ($excel=="N") 
    { echo "<br><a href='?mod=$mod&mode=$mode&pay=$paydate&dest=xls'>Save To Excel</a></br>"; }
  echo "</div>";  
echo "</div>";
echo "
  <div class='box'>
  <div class='box-body'>";
    echo "<table id='example1' class='table table-bordered table-striped' width='100%' style='font-size:12px;'>";
    echo "
    <thead>
      <tr>
        <th>NIK</th>
        <th>Nama</th>
        <th>Mulai Kerja</th>
        <th>PTKP</th>
        <th>Gaji Pokok</th>
        <th>Hari Kerja</th>
        <th>BPJS TK</th>
        <th>BPJS Pensiun</th>
        <th>BPJS Kesehatan</th>
        <th>Potongan</th>
        <th>Lembur</th>
        <th>Total Gaji</th>
      </tr>
    </thead>";
    while($row = mysql_fetch_array($result))
    { $nik = $row["nik"];
      $nama = $row["nama"];
      $gaji_pokok = $row["gaji_pokok"];
      $t_jab = $row["t_jabatan"];
      $bpjs_tk = $row["bpjs_tk"];
      $bpjs_kes = $row["bpjs_kes"];
      $t_hadir = $row["t_kehadiran"];
      $t_makan = $row["t_makan"];
      $thk = 0;
      $mulai_kerja = $row["mulai_kerja"];
      $ptkp = $row["ptkp"];
      $on_site = $row["t_onsite"];
      $hari_kerja = $row["hari_kerja"];
      $bpjstk = $row["bpjs_tk"];
      $bpjspen = $row["bpjs_pen"];
      $bpjskes = $row["bpjs_kes"];
      echo "
      <tr>
        <td>$nik</td>
        <td>$nama</td>
        <td>".fd_view($mulai_kerja)."</td>
        <td>$ptkp</td>
        <td align='right'>".fn($gaji_pokok,0)."</td>
        <td align='right'>$hari_kerja</td>
        <td align='right'>".fn($bpjstk,0)."</td>
        <td align='right'>".fn($bpjspen,0)."</td>
        <td align='right'>".fn($bpjskes,0)."</td>
        <td align='right'>".fn($row['deduction'],0)."</td>
        <td align='right'>".fn($row['overtime'],0)."</td>
        <td align='right'>".fn($row['total_gaji'],0)."</td>
      </tr>";
    }
    echo "</table>";
  echo "
  </div>
  </div>";
?>

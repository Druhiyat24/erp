<?PHP
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $nm_company=flookup("company","mastercompany","company<>''");
  $st_company=flookup("status_company","mastercompany","company<>''");
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }
  $rsUser=mysql_fetch_array(mysql_query("select * from userpassword where username='$user'"));
?>
<nav class="navbar navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <a href="?mod=1" class="navbar-brand"><b><?PHP echo $nm_company;?></b></a>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
        <i class="fa fa-bars"></i>
      </button>
    </div>
    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Master<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsUser['hr_lokasi'];
            if ($akses=="1") { echo "<li><a href='?mod=3L'>Lokasi</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=16L'>Department</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=17L'>Line</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=19'>Bagian</a></li>"; }
            $akses = $rsUser['hr_jabatan'];
            if ($akses=="1") { echo "<li><a href='?mod=4'>Jabatan</a></li>"; }
            $akses = $rsUser['hr_masteremployee'];
            if ($akses=="1") { echo "<li><a href='?mod=2L'>Data Karyawan</a></li>"; }
            $akses = $rsUser['hr_empfam'];
            if ($akses=="1") { echo "<li><a href='?mod=5L'>Data Keluarga</a></li>"; }
            $akses = $rsUser['hr_masteremployee'];
            if ($akses=="1") { echo "<li><a href='?mod=14'>Data Karyawan Keluar</a></li>"; }
            $akses = $rsUser['hr_kode_absen'];
            if ($akses=="1") { echo "<li><a href='?mod=10'>Kode Absen</a></li>"; }
            $akses = $rsUser['hr_holiday'];
            if ($akses=="1") { echo "<li><a href='?mod=11'>Hari Libur</a></li>"; }
            $akses = $rsUser['hr_ptkp'];
            if ($akses=="1") { echo "<li><a href='?mod=6'>Data PTKP</a></li>"; }
            $akses = $rsUser['hr_salary'];
            if ($akses=="1") { echo "<li><a href='?mod=12'>Data Salary</a></li>"; }
            $akses = $rsUser['parameter_payroll'];
            if ($akses=="1") { echo "<li><a href='?mod=28'>Parameter Payroll</a></li>"; }
            $akses = $rsUser['setting_payroll'];
            if ($akses=="1") { echo "<li><a href='?mod=26'>Setting Payroll</a></li>"; }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Personalia<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsUser['hr_pro_abs'];
            if ($akses=="1") { echo "<li><a href='?mod=7'>Upload Absensi</a></li>"; }
            $akses = $rsUser['hr_man_abs'];
            if ($akses=="1") { echo "<li><a href='?mod=13L'>Input Absensi</a></li>"; }
            $akses = $rsUser['hr_man_abs'];
            if ($akses=="1") { echo "<li><a href='?mod=13L&mode=dept'>Input Absensi Per Department</a></li>"; }
            $akses = $rsUser['hr_spl'];
            if ($akses=="1") { echo "<li><a href='?mod=18L'>Input SPL</a></li>"; }
            $akses = $rsUser['hr_ijin'];
            if ($akses=="1") { echo "<li><a href='?mod=20L'>Input Ijin Karyawan</a></li>"; }
            $akses = $rsUser['hr_deduction'];
            if ($akses=="1") { echo "<li><a href='?mod=23L'>Input Potongan Lain Lain</a></li>"; }
            $akses = $rsUser['hr_backpay'];
            if ($akses=="1") { echo "<li><a href='?mod=24L'>Input Pendapatan Lain Lain</a></li>"; }
            $akses = $rsUser['hr_rekal'];
            if ($akses=="1") { echo "<li><a href='?mod=15&mode=Sal'>Rekalkulasi Gaji</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=m17'>Input Mutasi Absen</a></li>"; }
            $akses = $rsUser['hr_man_abs'];if ($akses=="1") { echo "<li><a href='?mod=f10p'>Form Input Ijin Karyawan</a></li>"; }
            $akses = $rsUser['hr_deduction'];
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Recruitment<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsUser['hr_apply_emp'];
            if ($akses=="1") { echo "<li><a href='?mod=29'>Form Apply Karyawan</a></li>"; }
            $akses = $rsUser['hr_form_doc'];
            if ($akses=="1") { echo "<li><a href='?mod=29a'>Form Document</a></li>"; }
            $akses = $rsUser['hr_interview'];
            if ($akses=="1") { echo "<li><a href='?mod=29i'>Form Interview Karyawan</a></li>"; }
            $akses = $rsUser['hr_permintaan_tk'];
            if ($akses=="1") { echo "<li><a href='?mod=29p'>Form Permintaan TK</a></li>"; }
            $akses = $rsUser['KontrakKerjaForm'];
            if ($akses=="1") { echo "<li><a href='?mod=31'>Surat Kontrak Kerja Form</a></li>"; }
            $akses = $rsUser['SuratPengunduranDiriForm'];
            if ($akses=="1") { echo "<li><a href='?mod=32'>Surat Pengunduran Diri Form</a></li>"; }
            $akses = $rsUser['SuratKeteranganKerjaForm'];
            if ($akses=="1") { echo "<li><a href='?mod=33'>Surat Keterangan Kerja Form</a></li>"; }       
            ?>
          </ul>
        </li>
        <?php if($rsUser['lap_hrd']=="1") { ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Laporan<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            echo "<li><a href='?mod=8&mode=LapAbs'>Absensi</a></li>";
            echo "<li><a href='?mod=8&mode=LapRAbs'>Absen</a></li>";
            echo "<li><a href='?mod=8&mode=LapSPL'>SPL</a></li>";
            $akses = $rsUser['rep_lembur'];
            if ($akses=="1")
            { echo "<li><a href='?mod=8&mode=LapOT'>Summary Lembur</a></li>";
              echo "<li><a href='?mod=8&mode=LapOTD'>Detail Lembur</a></li>";
            }
            $akses = $rsUser['hr_rekal'];
            if ($akses=="1") { echo "<li><a href='?mod=8&mode=LapRek'>Rekalkulasi</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=8&mode=LapRekS'>Slip Gaji</a></li>"; }
            ?>
          </ul>
        </li>
        <?php } ?>
        <li class="dropdown">
          <a href="../">Main Menu</a>
        </li>
    </div>
  </div>
</nav>
    
<?PHP
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $nm_company=flookup("company","mastercompany","company<>''");
  $st_company=flookup("status_company","mastercompany","company<>''");
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }
  $rsU=mysql_fetch_array(mysql_query("select * from userpassword where username='$user'"));
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
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Proses<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsU['cutting_input'];
            if ($akses=="1") { echo "<li><a href='?mod=2WP'>Cutting Input</a></li>"; }
			 $akses = $rsU['cutting_output'];
            if ($akses=="1") { echo "<li><a href='?mod=3WP'>Cutting Output</a></li>"; }
            $akses = $rsU['mfg_output'];
           // if ($akses=="1") { echo "<li><a href='?mod=SecondaryInputPage'>Secondary Input</a></li>"; } 
            if ($akses=="1") { echo "<li><a href='?mod=3L'>Secondary Process Output</a></li>"; }
            $akses = $rsU['dc_out'];
            if ($akses=="1") { echo "<li><a href='?mod=10L'>DC Output</a></li>"; }
            $akses = $rsU['sewing_output'];
            if ($akses=="1") { echo "<li><a href='?mod=8L'>Sewing Input</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=4L'>Sewing Output</a></li>"; }
            $akses = $rsU['qc_output'];
           // if ($akses=="1") { echo "<li><a href='?mod=QcInputPage'>QC Input</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=5L'>$caption[7] Output</a></li>"; }
            $akses = $rsU['steam_out'];
            if ($akses=="1") { echo "<li><a href='?mod=11L'>Steam Output</a></li>"; }
            $akses = $rsU['pack_output'];
            if ($akses=="1") { echo "<li><a href='?mod=6L'>Packing Output</a></li>"; }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Laporan<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            echo "<li><a href='?mod=7s'>Production Status Summary</a></li>";
            echo "<li><a href='?mod=7'>Production Status Detail</a></li>";
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Tools<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php
            $akses = $rsU['unlock_prod'];
            if ($akses=="1") { echo "<li><a href='?mod=9'>Unlock Production Output</a></li>"; } 
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="../">Main Menu</a>
        </li>
    </div>
  </div>
</nav>
    
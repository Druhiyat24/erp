<?PHP
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
    $nm_company=$rscomp["company"];
    $st_company=$rscomp["status_company"];
    $jenis_company=$rscomp["jenis_company"];
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  $rsuser=mysql_fetch_array(mysql_query("select * from userpassword 
    where username='$user'"));
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
        <?php // if ($jenis_company!="VENDOR LG") { ?>
      <!--  <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Sample Development<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
      //      <?php 
      //      $akses = $rsuser["purch_ord"];
      //      if ($akses=="1") { echo "<li><a href='?mod=x3L'>Purchase Order Development</a></li>"; }
		//	      $akses = $rsuser["purch_ord_gen"];
      //      if ($akses=="1") { echo "<li><a href='?mod=gendev9L'>Purchase Order General Development</a></li>"; }
      //      $akses = $rsuser["purch_ord_add"];
      //      if ($akses=="1") { echo "<li><a href='?mod=poaddL'>Purchase Order Additional</a></li>"; }
      //      ?>
          </ul>
		
        </li>
          -->
		<?php // } ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Proses<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            #$akses = flookup("upload_po","userpassword","username='$user'");
            #if ($akses=="1") { echo "<li><a href='?mod=2'>Upload PO</a></li>"; }
            $akses = $rsuser["purch_ord"];
			$akses = $rsuser["purch_ord"];
            if ($akses=="1") { 
			echo "<li><a href='?mod=1000'>Outstanding PO</a></li>";
			echo "<li><a href='?mod=3L'>Purchase Order</a></li>"; }
            // if ($akses=="1") { echo "<li><a href='?mod=x3L'>Purchase Order Development</a></li>"; }
            $akses = $rsuser["purch_ord_gen"];
            if ($akses=="1") { echo "<li><a href='?mod=9L'>Purchase Order General</a></li>"; }
            $akses = $rsuser["purch_ord_add"];
            if ($akses=="1") { echo "<li><a href='?mod=poaddL'>Purchase Order Additional</a></li>"; }
            $akses = $rsuser["prorata_po"];
            if ($akses=="1") { echo "<li><a href='?mod=11'>Pro Rata PO</a></li>"; }
            $akses = $rsuser["transfer_post"];
            if ($akses=="1") { echo "<li><a href='?mod=5L'><i class='fa fa-exchange'></i>Booking Stock</a></li>"; }
            $akses = $rsuser["over_ship_alloc"];
            if ($akses=="1") { echo "<li><a href='?mod=15L'>Over Tollerance Allocation</a></li>"; }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Laporan<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            echo "
            <li><a href='?mod=10'>Master Bahan Baku</a></li>
            <li><a href='?mod=6&mode=PO'>Purchase Order</a></li>
            <li><a href='?mod=6mcc&mode=MCC'>MCC</a></li>
            <li><a href='?mod=6&mode=BPB'>Pemasukan Barang</a></li>";
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="../">Main Menu</a>
        </li>
         <li class="dropdown">
          <a href='../pur/?mod=1000'>Back</a>
        </li>
    </div>
  </div>
</nav>
    
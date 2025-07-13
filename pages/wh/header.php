<?php
if (empty($_SESSION['username'])) {
  header("location:../../");
}
if (!isset($_SESSION['username'])) {
  header("location:../../");
}
$rsComp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$nm_company = $rsComp["company"];
$st_company = $rsComp["status_company"];
if ($nm_company == "PT. Youngil Leather Indonesia") {
  $wip_cap = "Chemical";
} else {
  $wip_cap = $c7;
}

if (isset($_SESSION['username'])) {
  $user = $_SESSION['username'];
} else {
  header("location:../../");
}
if ($st_company == "KITE") {
  $captupl = "Upload Data";
} else {
  $captupl = "Upload Data Dari ModulTPB";
}
$rsUser = mysql_fetch_array(mysql_query("select * from 
    userpassword where username='$user'"));

?>

<style type="text/css">
  .dropdown-submenu {
    position: relative;
  }

  .dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
  }

  .dropdown-submenu:hover>.dropdown-menu {
    display: block;
  }

  /* Modify the background color */
  .skin-green .main-header .navbar {
    background-color: purple;
  }

  .dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
  }

  .dropdown-submenu:hover>a:after {
    border-left-color: #fff;
  }

  .dropdown-submenu.pull-left {
    float: none;
  }

  .dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
  }
</style>

<nav class="navbar navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <a href="?mod=1" class="navbar-brand"><b><?PHP echo $nm_company; ?></b></a>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
        <i class="fa fa-bars"></i>
      </button>
    </div>
    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Master<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href='?mod=m_unit'><i class='fa fa-square'></i>Master Unit</a></li>
            <li><a href='?mod=m_rak'><i class='fa fa-square'></i>Master Rak</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Pemasukan<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href='?mod=in_material&mode=Bahan_Baku'><i class='fa fa-hand-pointer-o'></i>Penerimaan Material</a></li>
            <li><a href='?mod=retur_material&mode=Bahan_Baku'><i class='fa fa-hand-paper-o'></i>Penerimaan Retur Material</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Pengeluaran<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href='?mod=req_material'><i class='fa fa-exchange' style='color:brown;'></i>Req Material</a></li>
            <li><a href='?mod=out_material&mode=Bahan_Baku'><i class='fa fa-exchange' style='color:blue;'></i>Pengeluaran Material</a></li>
          </ul>
        </li>
        <!-- <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Mutasi<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href='master_benang.php'>Penerimaan Material</a></li>
            <li><a href='master_benang.php'>Penerimaan Retur Material</a></li>
          </ul>
        </li> -->
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Laporan<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href='?mod=laporan_rak'>Laporan Rak</a></li>
            <li><a href='?mod=laporan_mutasi_rak'>Laporan Mutasi Rak</a></li>
            <li><a href='?mod=laporan_stok_rak'>Laporan Stok Rak</a></li>
            <li><a href='?mod=laporan_master'>Laporan Data</a></li>
            <li><a href='master_benang.php'>Penerimaan Retur Material</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="../"><i class='fa fa-home'></i></a>
        </li>
    </div>
  </div>
</nav>
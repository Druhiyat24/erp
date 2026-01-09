<?PHP
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
    $nm_company=$rscomp["company"];
    $st_company=$rscomp["status_company"];
    $upl_prod_det=$rscomp["upload_master_product_detail"];
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }
  $rsUser=mysql_fetch_array(mysql_query("select * from userpassword where username='$user'"));
?>
<style>
/* Modern SAGARIS Navbar Style */
.navbar {
  background-color: #1E293B; /* slate navy modern */
  border: none;
  border-radius: 0;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.navbar-brand img {
  max-height: 40px;
  margin-top: -5px;
}

.skin-yellow-light .main-header .navbar {
    background: linear-gradient(90deg, #0F172A, #1E40AF);
}

.navbar-nav > li > a {
  color: #E5E7EB !important; /* light gray text */
  font-weight: 500;
  letter-spacing: 0.3px;
  transition: all 0.2s ease;
  padding: 15px 18px;
}

.navbar-nav > li > a:hover,
.navbar-nav > li.active > a {
  color: #38BDF8 !important; /* bright cyan hover */
  background-color: transparent !important;
  text-decoration: none;
}

.navbar-toggle {
  border: none;
  background: transparent !important;
}

.navbar-toggle .fa {
  color: #E5E7EB;
  font-size: 20px;
}

/* Responsive adjustment */
@media (max-width: 768px) {
  .navbar {
    background-color: #1E293B;
  }
  .navbar-nav > li > a {
    padding: 10px 15px;
  }
}
</style>
<nav class="navbar navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <a href="?mod=1" class="navbar-brand"><img src='../../images/sagaris_white.png' class='img-responsive '
                    alt='-' width='120' style='margin-top: -5px;'></a>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
        <i class="fa fa-bars"></i>
      </button>
    </div>
    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Marketing<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsUser["master_customer"];
            if ($akses=="1") {echo "<li><a href='?mod=20&mode=Customer'>Master Customer</a></li>";}
            $akses = $rsUser["master_customer"];
            if ($akses=="1") { echo "<li><a href='?mod=200'>Master Agent</a></li>"; }              
            $akses = $rsUser["mnuMasterSupplier"];
            if ($akses=="1") {echo "<li><a href='?mod=21&mode=Supplier'>Master Supplier</a></li>";}
            $akses = $rsUser["master_season"];
            if ($akses=="1") { echo "<li><a href='?mod=2'>Master Season</a></li>"; }
            $akses = $rsUser["m_product"];
            if ($akses=="1") { echo "<li><a href='?mod=16'>Master Product</a></li>"; }
            $akses = $rsUser["upload_master_product_detail"];
            if ($akses=="1" and $upl_prod_det=="Y") { echo "<li><a href='?mod=28L'>Product - BOM</a></li>"; }
            $akses = $rsUser["m_shipmode"];
            if ($akses=="1") { echo "<li><a href='?mod=17'>Master Ship Mode</a></li>"; }
            $akses = $rsUser["m_complex"];
            if ($akses=="1") { echo "<li><a href='?mod=18'>Master $caption[6]</a></li>"; }
            $akses = $rsUser["m_others"];
            if ($akses=="1") { echo "<li><a href='?mod=19'>Master Others Cost</a></li>"; }
            $akses = $rsUser["m_allow"];
            if ($akses=="1") { echo "<li><a href='?mod=22'>Master Allowance</a></li>"; }
            $akses = $rsUser["master_size"];
            if ($akses=="1") {echo "<li><a href='?mod=25'>Master Urutan Size</a></li>";}
            $akses = $rsUser["master_panel"];
            if ($akses=="1") {echo "<li><a href='?mod=mpanel'>Master Panel</a></li>";}
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Material<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $rs=mysql_fetch_array(mysql_query("select * from userpassword where username='$user'"));
            $akses = $rs['generate_kode'];
            if ($akses=="1") { echo "<li><a href='?mod=3'>Master Group</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=4'>Master Sub Group</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=5'>Master Type</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=6'>Master Contents</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=7'>Master Width</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=8'>Master Length</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=9'>Master Weight</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=10'>Master Color</a></li>"; }
            if ($akses=="1") { echo "<li><a href='?mod=11'>Master Description</a></li>"; }
            $akses = $rs['master_hs'];
            if ($akses=="1") { echo "<li><a href='?mod=23'>Master HS & Tarif</a></li>"; }
            $akses = $rs['master_unit'];
            if ($akses=="1") { echo "<li><a href='?mod=26'>Master Unit</a></li>"; }
            $akses = $rs['item_odo'];
            if ($akses=="1") { echo "<li><a href='?mod=29'>Master Item ODO</a></li>"; }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Warehouse<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsUser["master_rak"];
            if ($akses=="1") { echo "<li><a href='?mod=27'>Master Rak</a></li>"; }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Accounting<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsUser["closing"];
            if ($akses=="1") { echo "<li><a href='?mod=12'>Closing Periode</a></li>"; }
            $akses = $rsUser["pay_terms"];
            if ($akses=="1") { echo "<li><a href='?mod=13'>Payment Terms</a></li>"; }
            $akses = $rsUser["m_bank"];
            if ($akses=="1") { echo "<li><a href='?mod=14'>Master Bank</a></li>"; }
            $akses = $rsUser["m_rate"];
            if ($akses=="1") { echo "<li><a href='?mod=15'>Rate Currency</a></li> <li><a href='?mod=mtax'>Master TAX</a></li>"; }
			$akses = $rsUser["BLOCK_CST"];
			if ($akses=="1") { echo "<li><a href='?mod=block_customer'>Block Customer</a></li>"; }	
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Quality Control<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsUser["m_defect"];
            if ($akses=="1") { echo "<li><a href='?mod=24'>Master Defect</a></li>"; }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="../">Main Menu</a>
        </li>
    </div>
  </div>
</nav>
    
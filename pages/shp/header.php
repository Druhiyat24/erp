<?PHP
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $nm_company=flookup("company","mastercompany","company<>''");
  $st_company=flookup("status_company","mastercompany","company<>''");
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }
  $rsU=mysql_fetch_array(mysql_query("select * from userpassword where username='$user'"));
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

.skin-purple .main-header .navbar {
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
        <?php if($user != 'guest'){ ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Master<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsU['master_port'];
            if ($akses=="1") { echo "<li><a href='?mod=4'>Port</a></li>"; }
            $akses = $rsU['master_route'];
            if ($akses=="1") { echo "<li><a href='?mod=5'>Route</a></li>"; }
            ?>
          </ul>
        </li>
      <?php } ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Proses<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php if($user != 'guest'){ ?>
            <?php 
            $akses = $rsU["update_dok_pab"];
            if ($akses=="1") {echo "<li><a href='?mod=2'>Update Dok Pabean</a></li>";}
            $akses = $rsU["update_dok_pab"];
            echo "<li><a href='?mod=memo_list'>Memo Pembayaran</a></li>";
            echo "<li><a href='?mod=memo_list_non_inv'>Memo Pembayaran Non Invoice</a></li>"; 
            echo "<li><a href='?mod=stat_memo'>Status Memo</a></li>";
            $akses_app = $rsU['app_memo'];
            if ($akses_app=="1") { echo "<li><a href='?mod=konfirmasi_memo'>Konfirmasi Memo</a></li>"; }            
            $akses = $rsU['invoice'];
            if ($akses=="1") { echo "<li><a href='?mod=ProformaInvoicePage'>Proforma Invoice</a></li>"; }       
            $akses = $rsU['invoice'];
            if ($akses=="1") { echo "<li><a href='?mod=EstimatePackingListPage'>Estimate Packing List</a></li>"; }
            $akses = $rsU['invoice'];
            if ($akses=="1") { echo "<li><a href='?mod=DeliveryOrderPage'>Delivery Order</a></li>"; }        
            $akses = $rsU['invoice'];
            if ($akses=="1") { echo "<li><a href='?mod=PackingListPage'>Packing List/DO</a></li>"; }
    
            $akses = $rsU['SHP_P_INV_COM'];
            if ($akses=="1") { echo "<li><a href='?mod=InvoiceCommercialPage'>Invoice Commercial</a></li>"; }
            //if ($akses=="1") { echo "<li><a href='?mod=InvoiceCommercialPage'>Invoice Commercial</a></li>"; }
            $akses = $rsU['INV_SCR_PAGE'];
            if ($akses=="1") { echo "<li><a href='?mod=InvocieScrapPage'>Invoice Scrap</a></li>"; } 
            $akses = $rsU['INV_MTRL_PAGE'];
            if ($akses=="1") { echo "<li><a href='?mod=InvocieMaterialPage'>Invoice Material</a></li>"; }       
            ?>
            <li><a href='?mod=upload_exim'>Upload</a></li>
            <?php }else{ ?>
              <li><a href='?mod=2'>Update Dok Pabean</a></li>
            <?php } ?>
          </ul>
        </li>
        <?php if($user != 'guest'){ ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Laporan<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href='?mod=lap_memo_summary'>Memo Pembayaran Summary</a></li>
            <li><a href='?mod=lap_memo_list'>Memo Pembayaran Detail</a></li>
            <!-- <li><a href='?mod=lap_dok_pabean'>Report Dok Pabean</a></li>
            <li><a href='?mod=lap_dok_pabean_det'>Report Dok Pabean Detail</a></li> -->
          </ul>
        </li>
        <?php } ?>
        <li class="dropdown">
          <a href="../">Main Menu</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
    
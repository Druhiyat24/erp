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
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Proses<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsU["update_dok_pab"];
            if ($akses=="1") {echo "<li><a href='?mod=2'>Update Dok Pabean</a></li>";}
            $akses = $rsU['invoice'];
			 if ($akses=="1") { echo "<li><a href='?mod=ProformaInvoicePage'>Proforma Invoice</a></li>"; }				
            $akses = $rsU['invoice'];
             if ($akses=="1") { echo "<li><a href='?mod=EstimatePackingListPage'>Estimate Packing List</a></li>"; }
            $akses = $rsU['invoice'];
            if ($akses=="1") { echo "<li><a href='?mod=DeliveryOrderPage'>Delivery Order</a></li>"; }				 
            $akses = $rsU['invoice'];
            if ($akses=="1") { echo "<li><a href='?mod=PackingListPage'>Packing List/DO</a></li>"; }

            $akses = $rsU['SHP_PRO_APP_INV'];
            if ($akses=="1") { echo "<li><a href='?mod=ApprovalInvoicePage'>Invoice Approval</a></li>"; }
			
            $akses = $rsU['invoice'];
            if ($akses=="1") { echo "<li><a href='?mod=InvoiceCommercialPage'>Invoice Commercial</a></li>"; }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="../">Main Menu</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
    
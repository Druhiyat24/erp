<?php
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $rs=mysql_fetch_array(mysql_query("select * from mastercompany"));
    $nm_company=$rs["company"];
    $jenis_company=$rs["jenis_company"];
    $st_company=$rs["status_company"];
    $c_upload_costing=$rs["upload_costing"];
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }
  $rsUser=mysql_fetch_array(mysql_query("select * from 
    userpassword where username='$user'"));
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
        <?php $akses = $rsUser["master_mkt"]; if($akses=="1") {?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Master<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsUser["bom_erp"];
            if ($akses=="1") 
            { echo "
                <li><a href='?mod=2'>BOM Color And Size Breakdown</a></li>
                <li><a href='?mod=3'>BOM Item Detail</a></li>"; 
            }
            ?>
          </ul>
	  
		  		  
        </li>
        <?php } 
          if($jenis_company!="VENDOR LG") { 
        ?>
          <li class="dropdown">
            <a href="" class="dropdown-toggle" data-toggle="dropdown">Sample Development<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          <?php
          $akses = $rsUser["act_costing"];
          if ($akses=="1")
		  { echo "<li><a href='?mod=request1v'>Request Development</a></li>"; }
		  $akses = $rsUser["act_costing"];
		  if ($akses=="1")
          { echo "<li><a href='?mod=1X'>Sample Development</a></li>"; }
          $akses = $rsUser["sales_order"];
          if ($akses=="1") 
          { echo "<li><a href='?mod=12vSO'>Sales Order Development</a></li>"; }
          $akses = $rsUser["bom_jo"];
          if ($akses=="1") 
          { echo "<li><a href='?mod=23L'>Work Sheet Development</a></li>"; }  
          $akses = $rsUser["bom_jo"];
          if ($akses=="1") 
          { echo "<li><a href='?mod=22LL'>Bill Of Material Development</a></li>"; }
          $akses = $rsUser["update_cons_fabric"];
		  if ($akses=="1") 
		  { echo "<li><a href='?mod=15LC'>Update Fabric Consumption</a></li>"; }
          $akses = $rsUser["purch_req"];
          if ($akses=="1") 
          { echo "<li><a href='?mod=24L'>Purchase Requisition Development</a></li>"; }
          ?>
          </ul>
          </li>
        <?php } ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Proses<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsUser["brosur"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=16'>Brochure</a></li>"; }
            $akses = $rsUser["quote_inq"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=3'>Quotation Inquire</a></li>"; }
            $akses = $rsUser["pre_cost"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=4'>Pre Costing</a></li>"; }
            $akses = $rsUser["act_costing"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=5L'><i class='fa fa-calculator'></i>Costing</a></li>"; }
            $akses = $rsUser["sales_order"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=7L'><i class='fa fa-line-chart'></i>Sales Order</a></li>"; }
            // $akses = $rsUser["sales_order_sample"];
            // if ($akses=="1") 
            // { echo "<li><a href='?mod=26L'><i class='fa fa-line-chart'></i>Sales Order Sample</a></li>"; }
            $akses = $rsUser["work_sheet"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=12L'><i class='fa fa-tasks'></i>Work Sheet</a></li>"; }
            $akses = $rsUser["bom_jo"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=14L'><i class='fa fa-navicon'></i>Bill Of Material</a></li>"; }
            $akses = $rsUser["update_cons_fabric"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=15LC'>Update Fabric Consumption</a></li>"; }
            $akses = $rsUser["purch_req"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=15L'><i class='fa fa-shopping-cart'></i>Purchase Requisition</a></li>"; }
            $akses = $rsUser["m_desc_add_info"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=24'><i class='fa fa-info'></i>Material Additional Info</a></li>"; }
            $akses = $rsUser["update_smv"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=updsmvl'><i class='fa fa-clock-o'></i>Update SMV</a></li>"; }
            $akses = $rsUser["upload_costing"];
            if ($akses=="1" and $c_upload_costing=="Y") 
            { echo "<li><a href='?mod=23'><i class='fa fa-calculator'></i>Upload Costing</a></li>"; }
            
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Laporan<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            echo "<li><a href='?mod=22L&mode=Bahan_Baku'>Master Bahan Baku</a></li>";
            $akses = $rsUser["quote_inq"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=9'>Quotation Inquire</a></li>"; }
            $akses = $rsUser["pre_cost"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=10'>Pre Costing</a></li>"; }
            $akses = $rsUser["act_costing"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=11'>Costing</a></li>"; }
            $akses = $rsUser["sales_order"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=17'>Sales Order</a></li>"; }
            if($jenis_company!="VENDOR LG")
            {echo "<li><a href='?mod=18'>Costing vs Sales Order</a></li>";
             echo "<li><a href='?mod=monorder'>Monitoring Order</a></li>";
			 echo "<li><a href='?mod=cekclose'>Pre-Checking Closing WS</a></li>";			 
            }
            echo "<li><a href='?mod=19'>History</a></li>";
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Tools<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            $akses = $rsUser["unlock_costing"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=20'>Unlock Costing</a></li>"; }
            $akses = $rsUser["unlock_so"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=21'>Unlock Sales Order</a></li>"; }
            $akses = $rsUser["unlock_jo"];
            if ($akses=="1") 
            { echo "<li><a href='?mod=25'>Unlock BOM</a></li>"; }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="../">Main Menu</a>
        </li>
    </div>
  </div>
</nav>
    
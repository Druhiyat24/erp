<?php
session_start();
include '../include/conn.php';
include 'forms/fungsi.php';
if (empty($_SESSION['username'])) {
  header("location:../");
}
if ($_SESSION['bahasa'] == "Indonesia") {
  include 'forms/id.php';
} else if ($_SESSION['bahasa'] == "Korean") {
  include 'forms/kr.php';
} else {
  include 'forms/en.php';
}
$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];;
$sql = "select * from mastercompany";
$rs = mysql_fetch_array(mysql_query($sql));
$dalam_perbaikan = $rs["dalam_perbaikan"];
$nm_company = $rs["company"];
$signal_bit = $rs["logo_company"];
$link_to_security = $rs["link_to_security"];

$sql = "select * from userpassword where username='$user'";
$rs = mysql_fetch_array(mysql_query($sql));
$kode_mkt = $rs["kode_mkt"];
$costing = $rs["costing"];
$security = $rs["security"];
$inventory = $rs["inventory"];
$purchasing = $rs["purchasing"];
$master = $rs["master"];
$monitoring = $rs["monitoring"];
$monitoring_bc = $rs["monitoring_bc"];
$production = $rs["production"];
$production_new = $rs["production_new"];
$shipping = $rs["shipping"];
$hr = $rs["hr"];
$finance = $rs["finance"];
$general = $rs["general_req"];
$approval = $rs["approval"];
$account = $rs["user_account"];
$jabatan = $rs["Groupp"];
$chpass = $rs["change_pass"];
$app_costing = $rs['approval_costing'];
$app_pr = $rs['approval_pr'];
$app_po = $rs['approval_po'];
$app_ptk = $rs['approval_ptk'];
$app_gen_req = $rs['approval_gen_req'];
$transfer_bpb = $rs['transfer_bpb'];

if ($dalam_perbaikan == "Y") {
  echo "<script>window.location.href='../../maaf';</script>";
  exit;
}
?>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Main Menu</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../plugins/datatables_responsive/responsive.dataTables.min.css">
  <link rel="stylesheet" href="../plugins/datatables_responsive/jquery.dataTables.min.css">
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <script src="../../dist/sweetalert.js"></script>
  <link rel="stylesheet" href="../../dist/sweetalert.css">
</head>
<!-- skin-green atau skin-blue atau skin-yellow atau skin-purple -->

<body class="hold-transition skin-green layout-top-nav fixed">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="container-fluid">
        <section class="content">
          <div class='box'>
            <div class='box-body'>
              <div class='row'>
                <?php if ($costing == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='marketting/?mod=1' data-toggle='tooltip' title='<?php echo $cm_mkt; ?>'>
                        <img src='../images/marketing.png' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_mkt; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($purchasing == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='pur/?mod=1001' data-toggle='tooltip' title='<?php echo $cm_pro; ?>'>
                        <img src='../images/procurement.png' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_pro; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($inventory == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='forms/?mod=1' data-toggle='tooltip' title='<?php echo $cm_inv; ?>'>
                        <img src='../images/inventory.png' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_inv; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($production == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='prod/?mod=1' data-toggle='tooltip' title='<?php echo $cm_prd; ?>'>
                        <img src='../images/production.png' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_prd; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($production_new == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='prod_new/?mod=1' data-toggle='tooltip' title='<?php echo $cm_prd; ?>'>
                        <img src='../images/prod_new.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b>Production New</b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($shipping == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='shp/?mod=1' data-toggle='tooltip' title='<?php echo $cm_shp; ?>'>
                        <img src='../images/shipping.png' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_shp; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($master == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='master/?mod=1' data-toggle='tooltip' title='<?php echo $cm_mstdt; ?>'>
                        <img src='../images/master.png' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_mstdt; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($monitoring == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='mon/?mod=24' data-toggle='tooltip' title='<?php echo $cm_mon; ?>'>
                        <img src='../images/monitoring.png' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_mon; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($monitoring_bc == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='monitoring_bc/?mod=1' data-toggle='tooltip' title='<?php echo $cm_mon; ?>'>
                        <img src='../images/monitoring_bc1.png' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b>Monitoring Pabean</b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($hr == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='hr/?mod=1' data-toggle='tooltip' title='<?php echo $cm_hr; ?>'>
                        <img src='../images/hr.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_hr; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($finance == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <?php if ($signal_bit == "S") { ?>
            <!--             <a href='fin_sb/?mod=1' data-toggle='tooltip' title='<?php echo $cm_fin; ?>'>
                          <img src='../images/finance.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                          <center><b>Finance & Accounting SB</b></center>
                        </a> -->

                         <a href='fin/module/dsb/dashboard.php' data-toggle='tooltip' title='<?php echo $cm_fin; ?>'>
                          <img src='../images/finance.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                          <center><b><?php echo $cm_fin; ?></b></center>
                        </a>
                      <?php } else { ?>
                        <a href='finz/?mod=1' data-toggle='tooltip' title='<?php echo $cm_fin; ?>'>
                          <img src='../images/finance.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                          <center><b><?php echo $cm_fin; ?></b></center>
                        </a>
                      <?php } ?>
                    </div>
                  </div>
                   <?php } ?>

                 <!--  <?php if ($finance == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                        <a href='fin_sb/?mod=1' data-toggle='tooltip' title='<?php echo $cm_fin; ?>'>
                          <img src='../images/finance.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                          <center><b>Finance & Accounting SB</b></center>
                        </a>
                    </div>
                  </div>

                <?php } ?> -->
                <?php if ($general == "1" and $kode_mkt != "") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='others/?mod=1L' data-toggle='tooltip' title='<?php echo "General Request"; ?>'>
                        <img src='../images/others.png' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo "General Request"; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($security == "1" and $link_to_security == "Y") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='sec/?mod=1v' data-toggle='tooltip' title='<?php echo "Security"; ?>'>
                        <img src='../images/security.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo "Security"; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($user == "adm_gdg"){?>
                <div class='col-md-2' style="width: auto !important;">
                  <div class='form-group'>
                    <a href='wh/?mod=1' data-toggle='tooltip' title='<?php echo $cm_inv; ?>'>
                      <img src='../images/warehouse.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                      <center><b>Inventory</b></center>
                    </a>
                  </div>
                </div>
              <?php }?>
                <?php if ($approval == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <?php
                      $cek = 0;
                      if ($app_costing == "1") {
                        $cek = $cek + flookup("count(distinct ac.cost_no)", "act_costing ac inner join act_costing_mat acm on ac.id=acm.id_act_cost 
                      inner join userpassword up on ac.username=up.username", "
                      app1='W' and status='CONFIRM' and kode_mkt='$kode_mkt' and cost_date >= '2023-01-01'");
                      }
                      if ($app_pr == "1") {
                        $cek = $cek + flookup("count(distinct a.jo_no)", "jo a inner join bom_jo_item s on a.id=s.id_jo
                      inner join jo_det jod on a.id=jod.id_jo
                      inner join so on jod.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
                      inner join mastersupplier msup on ac.id_buyer=msup.id_supplier 
                      inner join userpassword up on a.username=up.username", "app='W'");
                      }
                      if ($app_po == "1") {
                        $cek = $cek + flookup("count(distinct pono)", "po_header a inner join po_item s on a.id=s.id_po", "s.cancel='N' 
                      and app='W'");
                      }
                      if ($app_gen_req == "1") {
                        $cek = $cek + flookup(
                          "count(distinct reqno)",
                          "reqnon_header a inner join reqnon_item s on a.id=s.id_reqno",
                          "s.cancel='N' and 
                    ((a.app='W' and a.app_by='$user') or (a.app2='W' and a.app_by2='$user')) "
                        );
                      }
                      if ($app_ptk == "1") {
                        $cricek = "(setuju1='$user' and setuju1_app='W')";
                        $cricek = $cricek . " or (setuju2='$user' and setuju2_app='W')";
                        $cricek = $cricek . " or (setuju3='$user' and setuju3_app='W')";
                        $cricek = $cricek . " or (ketahui='$user' and ketahui_app='W')";
                        // $cek=$cek + flookup("count(*)","form_tenaga_kerja",$cricek);
                      }
                      ?>
                      <?php if ($cek >= 0) { ?>
                        <i class='fa fa-bell faa-ring animated fa-2x'></i>
                        <span class="label label-primary" style="font-size:20px;">
                          <?php echo $cek;
                          $link = "appr/?mod=1"; ?>
                        </span>
                      <?php }?>
                      <a href='<?php echo $link; ?>' data-toggle='tooltip' title='<?php echo $cm_app; ?>'>
                        <img src='../images/approval.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_app; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($account == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='setting/?mod=1' data-toggle='tooltip' title='<?php echo $cm_usr; ?>'>
                        <img src='../images/account.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_usr; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($chpass == "1") { ?>
                  <div class='col-md-2' style="width: auto !important;" style="width: auto !important;">
                    <div class='form-group'>
                      <a href='forms/?mod=10' data-toggle='tooltip' title='<?php echo $cm_chp; ?>'>
                        <img src='../images/chpass.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                        <center><b><?php echo $cm_chp; ?></b></center>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($transfer_bpb == "1") { ?>
                 <div class='col-md-2' style="width: auto !important;">
                    <div class='form-group'>
                      <a href='doc_handover/?mod=1' data-toggle='tooltip' title='<?php echo $cm_fin; ?>'>
                          <img src='../images/document-handover.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                          <center><b>Document Handover</b></center>
                        </a>
                    </div>
                  </div>
                <?php } ?>                 
                <div class='col-md-2' style="width: auto !important;">
                  <div class='form-group'>
                    <a href='forms/logout.php' data-toggle='tooltip' title='<?php echo $cm_logout; ?>'>
                      <img src='../images/logout.jpg' class='img-responsive' alt='-' style='width:150px; height:150px'>
                      <center><b><?php echo $cm_logout; ?></b></center>
                    </a>
                  </div>
                </div>
              </div>
              <?php
              if ($signal_bit == "S") {
                echo "<img src='../images/logo_s.jpg' class='img-responsive' alt='-' style='width:100px; height:50px'>";
              } else {
                echo "<img src='../images/logo_z.jpg' class='img-responsive' alt='-' style='width:100px; height:50px'>";
              }
              ?>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
  <input type="hidden" value="<?= $_SESSION['username']?>" id="username_user">

  <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <script src="../dist/js/app.min.js"></script>
  <script>

    $( document ).ready(function() {
      if (document.getElementById('username_user').value == 'dewi') {
        // delete_cookie('PHPSESSID');    
      }
    });

    function delete_cookie ( cookie_name )
    {
        var cookie_date = new Date ( );  // current date & time
        cookie_date.setTime ( cookie_date.getTime() - 1 );
        // document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
        document.cookie = cookie_name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        // alert('name:' +cookie_name);
        // jQuery.cookie('PHPSESSID', null); //Try to using jQuery
    }
  </script>
</body>

</html>
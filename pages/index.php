<?php
session_start();
include '../include/conn.php';
include 'forms/fungsi.php';
if (empty($_SESSION['username'])) { header("location:../"); exit; }

# bahasa
if ($_SESSION['bahasa']=="Indonesia") { include 'forms/id.php'; }
else if ($_SESSION['bahasa']=="Korean") { include 'forms/kr.php'; }
else { include 'forms/en.php'; }

$user = $_SESSION['username'];
$sql="select * from mastercompany";
$rs=mysql_fetch_array(mysql_query($sql));
$dalam_perbaikan=$rs["dalam_perbaikan"];
$nm_company=$rs["company"];
$signal_bit=$rs["logo_company"];
$link_to_security=$rs["link_to_security"];

if ($dalam_perbaikan=="Y") {
  echo "<script>window.location.href='../../maaf';</script>";
  exit;
}

$sql="select * from userpassword where username='$user'";
$rs=mysql_fetch_array(mysql_query($sql));

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Main Menu</title>
  <link rel="icon" href="../images/sagaris_icon.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 + AdminLTE 4 -->
  <link rel="stylesheet" href="../dist/css/adminlte4.min.css">
  <script src="../dist/js/adminlte.min.js"></script>

  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="../fontawesome/6.5.0/css/all.min.css">

  <!-- SweetAlert -->
  <link rel="stylesheet" href="../../dist/sweetalert.css">
  <script src="../../dist/sweetalert.js"></script>

  <style>
    body {
  min-height: 100vh;
  background:
    linear-gradient(
      rgba(244, 246, 249, 0.85),
      rgba(244, 246, 249, 0.85)
    ),
    url('../images/garment3.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
    }
    .menu-card {
      transition: transform 0.2s ease;
    }
    .menu-card:hover {
      transform: scale(1.05);
    }
    .menu-icon {
      width: 120px;
      height: 120px;
      object-fit: contain;
      margin-top: 10px;
    }
    .menu-title {
      font-weight: bold;
      padding: 10px 0;
    }
  </style>
      <div class="text-center">
        <?php
        if ($signal_bit=="S") { echo "<img src='../images/sagaris.png' style='width:500px;'>"; }
        else { echo "<img src='../images/logo_z.jpg' style='width:300px;'>"; }
		echo "<h3 class='mt-2'><strong>Selamat Datang, $user </strong></h3>";
        ?>
      </div>  
</head>
<body class="layout-top-nav">
<div class="wrapper">
  <div class="content-wrapper p-4">
    <div class="container-fluid">
      <div class="row start g-4">

        <?php
        # daftar menu utama
        $menus = array();

        if ($costing=="1") $menus[] = array('marketting/?mod=1','marketing.png',$cm_mkt);
        if ($purchasing=="1") $menus[] = array('pur/?mod=1001','procurement.png',$cm_pro);
        if ($inventory=="1") $menus[] = array('forms/?mod=1','inventory.png',$cm_inv);
        if ($production=="1") $menus[] = array('prod/?mod=1','production.png',$cm_prd);
        if ($shipping=="1") $menus[] = array('shp/?mod=1','shipping.png',$cm_shp);
        if ($master=="1") $menus[] = array('master/?mod=1','master.png',$cm_mstdt);
        if ($monitoring=="1") $menus[] = array('mon/?mod=24','monitoring.png',$cm_mon);
        if ($hr=="1") $menus[] = array('hr/?mod=1','hr.jpg',$cm_hr);
        if ($finance=="1" and $user != 'guest') {
          if ($signal_bit=="S") { $menus[] = array('fin/module/dsb/dashboard.php','finance.jpg',$cm_fin); }
          else { $menus[] = array('finz/?mod=1','finance.jpg',$cm_fin); }
        }
        if ($general=="1" && $kode_mkt!="") $menus[] = array('others/?mod=1L','others.png','General Request');
        if ($security=="1" && $link_to_security=="Y") $menus[] = array('sec/?mod=1v','security.jpg','Security');

        # tampilkan menu utama
        foreach ($menus as $m) {
          echo "
          <div class='col-md-3 col-sm-4 col-6'>
            <div class='card menu-card shadow-sm text-center'>
              <a href='$m[0]' class='text-decoration-none text-dark'>
                <img src='../images/$m[1]' class='menu-icon'>
                <div class='menu-title'>$m[2]</div>
              </a>
            </div>
          </div>";
        }

        # hitung notifikasi approval
        $cek=0;
        if ($app_costing=="1") {
          $cek += flookup("count(distinct ac.cost_no)","act_costing ac inner join act_costing_mat acm on ac.id=acm.id_act_cost 
            inner join userpassword up on ac.username=up.username",
            "app1='W' and status='CONFIRM' and kode_mkt='$kode_mkt'");
        }
        if ($app_po=="1") {
          $cek += flookup("count(distinct pono)","po_header a inner join po_item s on a.id=s.id_po","s.cancel='N' and app='W'");
        }
        if ($app_gen_req=="1") {
          $cek += flookup("count(distinct reqno)","reqnon_header a inner join reqnon_item s on a.id=s.id_reqno",
            "s.cancel='N' and ((a.app='W' and a.app_by='$user') or (a.app2='W' and a.app_by2='$user'))");
        }

        # menu approval
        if ($approval=="1") {
          $link = ($cek>0) ? "appr/?mod=1" : "#";
          echo "
          <div class='col-md-3 col-sm-4 col-6'>
            <div class='card menu-card shadow-sm text-center'>
              <a href='$link' class='text-decoration-none text-dark position-relative'>
                <img src='../images/approval.jpg' class='menu-icon'>
                <div class='menu-title'>$cm_app</div>";
          if ($cek>0) {
            echo "<span class='badge bg-danger position-absolute top-0 start-100 translate-middle'>
              <i class='fa-solid fa-bell'></i> $cek
            </span>";
          }
          echo "</a></div></div>";
        }

        # account, change password, logout
        if ($account=="1") {
          echo "
          <div class='col-md-3 col-sm-4 col-6'>
            <div class='card menu-card text-center'>
              <a href='setting/?mod=1' class='text-decoration-none text-dark'>
                <img src='../images/account.jpg' class='menu-icon'>
                <div class='menu-title'>$cm_usr</div>
              </a>
            </div>
          </div>";
        }

        if ($chpass=="1") {
          echo "
          <div class='col-md-3 col-sm-4 col-6'>
            <div class='card menu-card text-center'>
              <a href='forms/?mod=10' class='text-decoration-none text-dark'>
                <img src='../images/chpass.jpg' class='menu-icon'>
                <div class='menu-title'>$cm_chp</div>
              </a>
            </div>
          </div>";
        }
		
        if ($transfer_bpb=="1") {
          echo "
          <div class='col-md-3 col-sm-4 col-6'>
            <div class='card menu-card text-center'>
              <a href='doc_handover/?mod=1' class='text-decoration-none text-dark'>
                <img src='../images/document-handover.jpg' class='menu-icon'>
                <div class='menu-title'>Document Handover</div>
              </a>
            </div>
          </div>";
        }

        if ($production_new=="1") {
          echo "
          <div class='col-md-3 col-sm-4 col-6'>
            <div class='card menu-card text-center'>
              <a href='prod_new/?mod=1' class='text-decoration-none text-dark'>
                <img src='../images/prod_new.jpg' class='menu-icon'>
                <div class='menu-title'>Production New</div>
              </a>
            </div>
          </div>";
        }		

        if ($monitoring_bc=="1") {
          echo "
          <div class='col-md-3 col-sm-4 col-6'>
            <div class='card menu-card text-center'>
              <a href='monitoring_bc/?mod=1' class='text-decoration-none text-dark'>
                <img src='../images/monitoring_bc1.png' class='menu-icon'>
                <div class='menu-title'>Monitoring Pabean</div>
              </a>
            </div>
          </div>";
        }		
		
        echo "
        <div class='col-md-3 col-sm-4 col-6'>
          <div class='card menu-card text-center'>
            <a href='forms/logout.php' class='text-decoration-none text-dark'>
              <img src='../images/logout.jpg' class='menu-icon'>
              <div class='menu-title'>$cm_logout</div>
            </a>
          </div>
        </div>";
        ?>
      </div>


    </div>
  </div>
</div>
</body>
</html>

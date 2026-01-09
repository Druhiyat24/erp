<?php
session_start();
session_destroy();
include('include/conn.php');
include('pages/forms/fungsi.php');
$que = mysql_query("SELECT * FROM mastercompany");
$rs = mysql_fetch_array($que);
$nm_company = $rs['company'];
$ad1_company = $rs['alamat1'];
$ad2_company = $rs['alamat2'];
$kec_company = ($rs['kec'] != "") ? " Kec. " . $rs['kec'] : "";
$kota_company = $rs['kota'];
$prop_company = $rs['propinsi'];
$dalam_perbaikan = $rs['dalam_perbaikan'];
$st_company = $rs['status_company'];
$logo_ada_nama = $rs['logo_ada_nama'];
$logo_erp = $rs['logo_company'];

if ($dalam_perbaikan == "Y") {
  echo "<script>window.location.href='maaf';</script>";
  exit;
}
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php 
      if ($st_company=="MULTI_WHS") echo "Inventory Multi Warehouse";
      else if ($logo_erp=="S") echo "SAGARIS";
      else if ($logo_erp=="Z") echo "ZAST ERP";
      else echo "IT Inventory";
    ?> | Log in
  </title>
  <link rel="icon" href="images/sagaris_icon.png">

  <!-- Bootstrap 5 -->
  <link href="dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="fontawesome/6.5.0/css/all.min.css">

  <style>
    body {
      background: url("images/garment2.jpg");
	  background-size: cover;
	  background-position: center;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
    }
    .login-card {
      background: white;
      border-radius: 1rem;
      box-shadow: 0 0 25px rgba(0,0,0,0.1);
      overflow: hidden;
      max-width: 850px;
      width: 100%;
    }
    .login-left {
      background: white;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
    }
    .login-right {
      padding: 3rem;
    }
    .login-logo img {
      width: 90%;
      margin-bottom: 1rem;
    }
    .form-control {
      border-radius: 0.5rem;
    }
    .btn-primary {
      width: 100%;
      border-radius: 0.5rem;
      padding: 0.75rem;
    }
  </style>
</head>
<body>

<div class="login-card d-flex flex-column flex-md-row">
  <!-- Left side -->
  <div class="login-left col-md-5">
    <div class="login-logo mb-3">
      <img src="images/logo_s.jpg" alt="Logo">
    </div>
  </div>

  <!-- Right side -->
  <div class="login-right col-md-7">
    <form method="post" action="auth.php">

      <div class="mb-3">
        <label class="form-label"><i class="fa fa-address-book me-2"></i>User Name</label>
        <input type="text" name="user" class="form-control" placeholder="Enter username" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa fa-lock me-2"></i>Password</label>
        <input type="password" name="pass" class="form-control" placeholder="Enter password" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa fa-language me-2"></i>Language</label>
        <select name="txtbahasa" class="form-select">
          <option>Indonesia</option>
          <option>English</option>
          <option>Korean</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary mt-3">Login</button>
    </form>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

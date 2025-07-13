<?php
session_start();
session_destroy();
include('include/conn.php');
include('pages/forms/fungsi.php');
$que=mysql_query("select * from mastercompany");
$rs=mysql_fetch_array($que);
$nm_company=$rs['company'];
$ad1_company=$rs['alamat1'];
$ad2_company=$rs['alamat2'];
if ($rs['kec']!="") {$kec_company=" Kec. ".$rs['kec'];} else {$kec_company="";}
$kota_company=$rs['kota'];
$prop_company=$rs['propinsi'];
$dalam_perbaikan=$rs['dalam_perbaikan'];
$st_company=$rs['status_company'];
$logo_ada_nama=$rs['logo_ada_nama'];
$logo_erp=$rs['logo_company'];
if ($dalam_perbaikan=="Y")
{ echo "<script>window.location.href='maaf';</script>";
	exit;
}
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>
		<?php if ($st_company=="MULTI_WHS") 
					{ echo "Inventory Multi Warehouse"; } 
					else if ($logo_erp=="S") 
					{ echo "SIGNAL BIT"; } 
					else if ($logo_erp=="Z") 
					{ echo "ZAST ERP"; } 
					else 
					{ echo "IT Inventory"; } ?> | Log in</title>
  <link rel="icon" type="image/jpeg" href="include/icon2.jpg">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/util.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/main.css">
</head>
<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="include/img-01.png" alt="-">
					<br>
					<h5>
					<?php 
            if ($logo_ada_nama=="N") {echo $nm_company."<br>";}
              echo $ad1_company
              ."<br>".
              $ad2_company.$kec_company
              ."<br>".
              $kota_company." - ".$prop_company;
          ?>
					</h5>
				</div>

				<form class="login100-form validate-form" method="post" action="auth.php">
					<span class="login100-form-title">
						<?php
						if ($st_company=="MULTI_WHS")
						{	echo "Inventory Multi Warehouse";	}
						else if ($logo_erp=="S")
						{	echo '<img src="images/logo_s.jpg" width="100%" alt="-">'; }
						else if ($logo_erp=="Z")
						{	echo '<img src="images/logo_z.jpg" width="100%" alt="-">'; }
						else
						{	echo "IT Inventory (".$st_company.")";	}
						?>
					</span>
					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="text" name="user" placeholder="User Name">
						<span class="symbol-input100">
							<i class="fa fa-address-book" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="pass" placeholder="Password">
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<select class="input100" style='width: 100%;' name='txtbahasa'>
				          <option>Indonesia</option>
				          <option>English</option>
				          <option>Korean</option>
				        </select>
				        <span class="symbol-input100">
							<i class="fa fa-sign-language" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">Login</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="bootstrap/js/popper.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="bootstrap/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
	<script src="bootstrap/js/main.js"></script>
</body>
</html>
<?php
  session_start();
  include '../../conn/conn.php'; 
  $user = $_SESSION['username'];   
?>

<!DOCTYPE html>
<html lang="en">

<head>
<style>
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  height: 30px;
}
.box {
  border-style: outset;
  box-sizing: border-box;
}
.body {
    font-size: 14px;
}
.box .header {
    font-size: 14px;
}
.form-control-plaintext {
  border: 1px solid grey;
}
.form-row {
  margin-right: 0;
  margin-left: -10px;
}
.filter-option {
    font-size: 12px;
}
.datatable_wrapper{
    font-size: 12px;
}

.container-1 input#myInput{
  width: 220px;
  height: 32px;
  position: relative;
  background: white;
  font-size: 12pt;
  float: right;
  color: #63717f;
  padding-left: 15px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
}


/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.tableFix { /* Scrollable parent element */
  position: relative;
  overflow: auto;
  height: 100px;
}

.tableFix table{
  width: 100%;
  border-collapse: collapse;
}

.tableFix th,
.tableFix td{
  padding: 8px;
  text-align: left;
}

.tableFix thead th {
  position: sticky;  /* Edge, Chrome, FF */
  top: 0px;
  background: #2F4F4F;  /* Some background is needed */
  color: white;
}


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

  .dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: black;
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


  /* Modify the background color */
  .skin-green .main-header .navbar {
    background-color: black;
  }

  .swal-wide{
    width:400px !important;
    height: 200px !important;
}

</style>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SIGNAL BIT</title>

  <!-- Bootstrap core CSS -->
<link href="../css/4.1.1/main.css" rel="stylesheet">  
<link href="../css/4.1.1/bootstrap.min.css" rel="stylesheet">
<link href="../css/4.1.1/datatables.min.css" rel="stylesheet">
<link href="../css/4.1.1/bootstrap-select.min.css" rel="stylesheet">
<link href="../fontawesome/css/font-awesome.min.css" rel="stylesheet">
<link href="../css/4.1.1/datepicker3.css" rel="stylesheet">

<link href="../css/4.1.1/bootstrap-multiselect.min.css" rel="stylesheet">
<link href="../css/4.1.1/select2.min.css" rel="stylesheet">
<link href="../css/4.1.1/select2-bootstrap4.min.css" rel="stylesheet">
<link href="../css/4.1.1/responsive.bootstrap4.min.css" rel="stylesheet">
<link href="../css/4.1.1/sweetalert2.min" rel="stylesheet">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />
    <link rel="stylesheet" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" /> -->
</head>

<body>


<!-- Bootstrap NavBar -->


<nav class="navbar navbar-expand-md navbar-dark bg-primary">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">
        <img src="img/NAG logo SIGN.png" alt="">
    </a>
    <a class="navbar-brand text-white"><b style="font-size:15px;">PT.NIRWANA ALABARE GARMENT</b></a>

  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav mr-auto">
        <!-- <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div> -->

<!-- navbar master -->
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Master
        </a>
        <div class="dropdown-menu bg-dark " aria-labelledby="navbarDropdown" style="width:200px;">
            <a href="../master/master-coa.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-paperclip fa-fw "></span>
                <span class="menu-collapsed">Chart Of Account</span>
            </a>        
        </div>
      </li>

       <!-- navbar AP -->
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          AP
        </a>
        <div class="dropdown-menu bg-dark " aria-labelledby="navbarDropdown" style="width:250px;">
            <a href="../ap/kontrabon.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-ticket fa-fw "></span>
                <span class="menu-collapsed">Kontrabon</span>
            </a>     
            <a href="../ap/payment.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-tags fa-fw "></span>
                <span class="menu-collapsed">List Payment</span>
            </a>    
            <a href="../ap/pcs_detail.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-file-excel-o fa-fw "></span>
                <span class="menu-collapsed">Report</span>
            </a>        
        </div>
      </li>
        <!-- end -->

        <!-- navbar AR -->
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          AR
        </a>
        <div class="dropdown-menu bg-dark " aria-labelledby="navbarDropdown" style="width:250px;">
            <a href="../ar/book-invoice.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-ticket fa-fw "></span>
                <span class="menu-collapsed">Book Invoice</span>
            </a> 
            <a href="../ar/list-invoice.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-ticket fa-fw "></span>
                <span class="menu-collapsed">List Invoice</span>
            </a>     
            <a href="../ar/alokasi.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-ticket fa-fw "></span>
                <span class="menu-collapsed">Alokasi</span>
            </a>    
            <a href="../ar/report-detail.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-ticket fa-fw "></span>
                <span class="menu-collapsed">Report</span>
            </a>     
        </div>
      </li>
        <!-- end -->


        <!-- navbar cash -->
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Cash
        </a>
        <div class="dropdown-menu bg-dark " aria-labelledby="navbarDropdown" style="width:250px;">
            <a href="../cash/cash-in.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-sign-in fa-fw "></span>
                <span class="menu-collapsed">Cash In</span>
            </a>  
            <a href="../cash/cash-out.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-sign-out fa-fw "></span>
                <span class="menu-collapsed">Cash Out</span>
            </a>  
            <a href="../cash/petty-cashin.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-credit-card fa-fw "></span>
                <span class="menu-collapsed">Petty Cash In</span>
            </a>
            <a href="../cash/petty-cashout.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-credit-card fa-fw "></span>
                <span class="menu-collapsed">Petty Cash Out</span>
            </a>  
            <a href="../cash/cashreport.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-file-excel-o fa-fw "></span>
                <span class="menu-collapsed">Report Cash</span>
            </a>           
        </div>
      </li>
        <!-- end -->

        <!-- navbar cash -->
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Bank
        </a>
        <div class="dropdown-menu bg-dark " aria-labelledby="navbarDropdown" style="width:250px;">
            <a href="../bank/payment-voucher.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-money fa-fw "></span>
                <span class="menu-collapsed">Payment Voucher</span>
            </a>     
            <a href="../bank/bank-in.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-sign-in fa-fw "></span>
                <span class="menu-collapsed">Bank In</span>
            </a> 
            <a href="../bank/bank-out.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-sign-out fa-fw "></span>
                <span class="menu-collapsed">Bank Out</span>
            </a>   
            <a href="../bank/bankreport.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-file-excel-o fa-fw "></span>
                <span class="menu-collapsed">Report Bank</span>
            </a>            
        </div>
      </li>
        <!-- end -->

      <!-- navbar accounting -->
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Accounting
        </a>
        <div class="dropdown-menu bg-dark " aria-labelledby="navbarDropdown" style="width:270px;">
            <a href="../acct/memorial-journal.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-bars fa-fw "></span>
                <span class="menu-collapsed">Memorial Journal</span>
            </a> 

            <a href="../acct/list-journal.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-list-alt fa-fw "></span>
                <span class="menu-collapsed">List Journal</span>
            </a> 
            <a href="../acct/general-ledger.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-print fa-fw "></span>
                <span class="menu-collapsed">General Ledger</span>
            </a> 
            <a href="../acct/financial_statement_ytd.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-calendar fa-fw "></span>
                <span class="menu-collapsed">Financial Statement YTD</span>
            </a>  
            <a href="../acct/trial-balance-monthly.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-calendar-o fa-fw "></span>
                <span class="menu-collapsed">Financial Statement Monthly</span>
            </a>   
        </div>
      </li>


      <!-- navbar TAX -->
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Tax
        </a>
        <div class="dropdown-menu bg-dark " aria-labelledby="navbarDropdown" style="width:250px;">
            <a href="../tax/koreksi-fiscal.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-paperclip fa-fw "></span>
                <span class="menu-collapsed">Koreksi Fiscal</span>
            </a>     
            <a href="../tax/koreksi-fiscal-jurnal.php" class="dropdown-item bg-dark text-white">
                    <span class="fa fa-paperclip fa-fw "></span>
                <span class="menu-collapsed">Koreksi Fiscal Per Jurnal</span>
            </a>        
        </div>
      </li>
        <!-- end -->

      <li class="nav-item active">
                <a class="nav-link" href="/erp/pages/"><i class="fa fa-home" aria-hidden="true"></i></a>
            </li>

        </ul>
        </li>

      
    </ul>

</div>

</nav>

    <!-- sidebar-container END -->
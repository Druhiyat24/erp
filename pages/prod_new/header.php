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
$rsUser = mysql_fetch_array(mysql_query("select * from 
    userpassword where username='$user'"));

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

.skin-green .main-header .navbar {
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

<nav class="navbar navbar-static-top bg-warning">
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
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Master<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href='?mod=master_part'><i class='fa fa-wrench'></i>Master Part</a></li>
            <li><a href='?mod=stocker_h'><i class='fa fa-ticket'></i>Stocker</a></li>
            <li><a href='?mod=master_plan_h'><i class='fa fa-bar-chart'></i>Master Plan</a></li>
            <li><a href='?mod=cetak_qr'><i class='fa fa-qrcode'></i>Cetak Qr Code</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Proses<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li>
              <a href='?mod=scan_numbering_input'><i class='fa fa-cut'></i>Cutting Numbering</a>
              <!--               <ul class="dropdown-menu">
                <li><a href='?mod=scan_numbering_input'>Numbering</a></li>
              </ul> -->
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Laporan<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href='?mod=lap_prod'><i class='fa fa-tasks'></i>Laporan</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="../"><i class='fa fa-home'></i></a>
        </li>
    </div>
  </div>
</nav>
<script type="text/javascript">
  $('html').mousedown(function() {
    $('.dropdown-submenu').hide();
  });

  $('#navbar-collapse').mousedown(function(event) {
    event.stopPropagation();
  });

  $('.dropdown').mousedown(function() {
    var ele = $(this).find('.dropdown-submenu');
    $('#navbar-collapse').find('.dropdown-submenu').each(function(index) {
      if (!$(this).is(ele)) $(this).hide();
    });
    ele.toggle();
  });
</script>
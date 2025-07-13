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

<style type="text/css">
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
    border-left-color: #ccc;
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

  .nav>li>a:focus {
    background: none
  }

  @media (min-width: 768px) {
    #nav .navbar li:hover ul.dropdown-menu {
      visibility: visible;
      display: block !important
    }

    #nav .navbar li span {
      display: none
    }
  }
</style>

<nav class="navbar navbar-static-top bg-warning">
  <div class="container">
    <div class="navbar-header">
      <a href="?mod=1" class="navbar-brand"><b><?PHP echo $nm_company; ?></b></a>
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
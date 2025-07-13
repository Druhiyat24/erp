<?PHP

if (empty($_SESSION['username'])) { header("location:../../"); }

if (!isset($_SESSION['username'])) { header("location:../../"); }

$nm_company=flookup("company","mastercompany","company<>''");

$st_company=flookup("status_company","mastercompany","company<>''");

if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }

if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }

$rsUser=mysql_fetch_array(mysql_query("select * from 

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
  </style>

  <nav class="navbar navbar-static-top">

    <div class="container">

      <div class="navbar-header">

        <a href="?mod=1" class="navbar-brand"><b><?php $nm_company  //echo 'XYZ Garment';$nm_company;?></b></a>

        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">

          <i class="fa fa-bars"></i>

        </button>

      </div>

      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">

        <ul class="nav navbar-nav">

           <li class="dropdown">

            <a href="" class="dropdown-toggle" data-toggle="dropdown">Expand Modul<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
            <li><a href='http://10.10.5.60/ap/' target="blank">Accounting Payable</a></li>
            <li><a href='http://10.10.5.60/ar/' target="blank">Accounting Receivable</a></li>

          </ul>
          </li>

          

     <!-- Modul Report -->


    <!-- Modul Report -->

    <li class="dropdown">

      <a href="../">Main Menu</a>

    </li>



  </ul>

</div>

</div>

</nav>




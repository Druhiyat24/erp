<?PHP
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $nm_company=flookup("company","mastercompany","company<>''");
  $st_company=flookup("status_company","mastercompany","company<>''");
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }
  $user=$_SESSION['username'];
  $akses_read = flookup("username","userpassword","username='$user' and security='1'");
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
          <?php if($akses_read=="") {?>
            <a href="../forms/logout.php">Log Out</a>
          <?php } else { ?>
            <a href="../../pages">Main Menu</a>
          <?php } ?>
        </li>
    </div>
  </div>
</nav>
    
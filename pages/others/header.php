<?PHP
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $nm_company=flookup("company","mastercompany","company<>''");
  $st_company=flookup("status_company","mastercompany","company<>''");
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }
  $rs=mysql_fetch_array(mysql_query("select * from userpassword where username='$user'"));
  $m_gen_req=$rs['m_general_req'];
  $gen_req=$rs['general_req'];
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
      <?php if ($m_gen_req=="1") {?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../others/?mod=2L">Master</a>
        </li>
      </ul>
      <?php } ?>
      <?php if ($gen_req=="1") {?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../others/?mod=1L">General Request</a>
        </li>
      </ul>
      <?php } ?>
      <?php if ($m_gen_req=="1") {?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../others/?mod=4">Unapprove Gen. Req.</a>
        </li>
      </ul>
      <?php } ?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../others/?mod=3">Master Item Non Production</a>
        </li>
      </ul>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../">Main Menu</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
    
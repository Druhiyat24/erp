<?PHP
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $nm_company=flookup("company","mastercompany","company<>''");
  $st_company=flookup("status_company","mastercompany","company<>''");
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }
  $rsU=mysql_fetch_array(mysql_query("select * from userpassword where username='$user'"));
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

.skin-purple .main-header .navbar {
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
<nav class="navbar navbar-static-top">
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
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Laporan<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            echo "<li><a href='?mod=lap_mon'>Laporan Monitoring</a></li>";	
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="../">Main Menu</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
    


<?php 

if (empty($_SESSION['username'])) { header("location:../../../index.php"); }



$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];

# CEK HAK AKSES KEMBALI

 $akses = flookup("F_L_AP_KAR_HUT","userpassword","username='$user'");

 if ($akses=="0") 

 { echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }

# END CEK HAK AKSES KEMBALI
include '../forms/journal_interface.php';
include 'lap_kartu_AP.php';


?>



<link rel="stylesheet" href="./css/overlay.css"> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script src="js/global.js"></script> 

<script src="js/lap_kartu_AP.js"></script>
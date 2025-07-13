<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("PO_P_DRA_BW_FORM","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
include 'DraftPoBWForm.php';
?>

<div id="myOverlay">
    <div class="col-md-3 col-sm-offset-6" style="padding-top:400px">
		<img src="./img/loading.gif"></img>
    </div>
</div>

<link rel="stylesheet" href="./css/overlay.css"> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/global.js"></script>
<script src="js/DraftPoBWForm.js?<?php echo date('Ymdhms') ?>"></script>
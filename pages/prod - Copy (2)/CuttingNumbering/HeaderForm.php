<?php

if (empty($_SESSION['username'])) { 
    header("location:../../../index.php"); 
}

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("cut_number_form","userpassword","username='$user'");

if ($akses=="0") {
    echo "<script>alert('Access Not Permitted'); window.location.href='?mod=1';</script>"; 
}
# END CEK HAK AKSES KEMBALI
include 'CuttingNumberingForm.php';

?>

<div id="myOverlay">
    <div class="col-md-3 col-sm-offset-6" style="padding-top:400px">
    </div>
</div>

<link rel="stylesheet" href="./css/overlay.css"> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/global.js?<?php echo date('Ymdhms') ?>"></script>
<script src="js/CuttingNumberingForm.js?<?php echo date('Ymdhms') ?>"></script>
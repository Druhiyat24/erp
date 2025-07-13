
<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
# END CEK HAK AKSES KEMBALI
include 'BahanBakuKembaliPage.php';

?>


<div id="myOverlay">
<div class="col-md-3 col-sm-offset-6" style="padding-top:400px">

</div>
</div>
<link rel="stylesheet" href="./css/style_po.css"> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/BahanBakuKembali.js"></script>
<style>
.container{
	width:1300px !important;
	
}

</style>
<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
print_r($_GET['LaporanCash']);
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI


if($_GET['mod']  == 'LaporanCash'){
$akses = flookup("F_L_Cash","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

}

if($_GET['mod']== 'LaporanCashRekap'){

$akses = flookup("F_L_Cash_Rekap","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
}

if($_GET['mod'] == 'LaporanBank' ){
$akses = flookup("F_L_Bank","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
}

if($_GET['mod'] =='LaporanBankRekap'){
$akses = flookup("F_L_Bank_Rekap","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
}

include 'LaporanCashBankPage.php';

?>



<div id="myOverlay">
<div class="col-md-3 col-sm-offset-6" style="padding-top:400px">

</div>
</div>
<link rel="stylesheet" href="./css/overlay.css"> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/global.js"></script>
<script src="js/LaporanCashBank.js?v=<?php echo date('YmdhmsxxY') ?>"></script>
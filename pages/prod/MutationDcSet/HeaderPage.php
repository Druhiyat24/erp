
<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
include 'MutationDcSetPage.php';

?>


<div id="myOverlay">
<div class="col-md-3 col-sm-offset-6" style="padding-top:400px;display:block;">
<img src="./img/25.gif" class="img-responsive" width="20%">
</div>
</div>
<link rel="stylesheet" href="./css/overlay.css"> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/global.js?<?php echo date('Ymdhms') ?>"></script>
<script src="js/MutationDcSetPage.js?<?php echo date('Ymdhms') ?>"></script>
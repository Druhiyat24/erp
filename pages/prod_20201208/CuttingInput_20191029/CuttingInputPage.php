<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("act_costing","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI



?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Cutting Input</h3>
    <a href='../prod/?mod=CuttingInputForm' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Buyer</th>
        <th>Style #</th>
        <th>WS #</th>
        <th>SO #</th>
        <th>Buyer PO</th>
        <th>Dest</th>
        <th>Color</th>
        <th>Size</th>
        <th>Tgl Entri</th>
        <th>Tgl Input</th>
        <th>Qty Input</th>
      </tr>
      </thead>
      <tbody>
        <div id="render"></div>
      </tbody>
    </table>
  </div>
</div>

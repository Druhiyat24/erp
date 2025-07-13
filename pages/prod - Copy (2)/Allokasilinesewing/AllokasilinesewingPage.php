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
<!-- <h3>List Allokasi Line Sewing Page</h3> -->
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Allokasi Line Sewing </h3>&nbsp;&nbsp;&nbsp;
    <a href='../prod/?mod=AllokasilinesewingForm' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i>&nbsp; New
    </a>
  </div>
  <div class="box-body">
    <table id="MyTableAllokasilinesewing" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>WS #</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>Qty Costing</th>
        <th>SMV Min</th>
        <th>SMV Sec</th>
        <th>DELV</th>
        <th>Allokasi Line</th>
        <th>Created By</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody id="render">

      </tbody>
    </table>
  </div>
</div>

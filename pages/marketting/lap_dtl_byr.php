<?php 
session_start();
$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];
$mod  = $_GET['mod'];

if (isset($_GET['dest'])) {
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=lap_dtl_byr.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    include("lap_dtl_byr_xls.php");
    exit;
}
?>

<div class="box">
  <div class="box-body">
    <a class="btn btn-success" href="?mod=<?php echo $mod; ?>&dest=xls">Save To Excel</a>
  </div>
</div>

<div class="box">
  <div class="box-body">
    <table id="dtl_buyer" class="table table-bordered table-striped" style="font-size:12px;" width="100%">
      <thead>
        <tr>
          <th>No</th>
          <th>Buyer</th>
          <th>No WS</th>
          <th>Style</th>
          <th>Jenis SO</th>
          <th>Destination</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<script src="../../plugins/jQuery/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('#dtl_buyer').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "ajax_dtl_byr.php",
      "type": "POST"
    },
    "columns": [
      { "data": "no" },
      { "data": "buyer" },
      { "data": "kpno" },
      { "data": "styleno" },
      { "data": "jns_so" },
      { "data": "main_dest" }
    ]
  });
});
</script>

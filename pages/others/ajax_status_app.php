<?php
session_start();
include "../../include/conn.php";
include "../forms/fungsi.php";
$user=$_SESSION['username'];
$cri_item = isset($_POST['cri_item']) ? $_POST['cri_item'] : 'All';
$status_app = ($cri_item == 'All' || $cri_item == '') ? '' : substr($cri_item, 0, 1);
$status_map = [
  'Approve' => 'A',
  'Reject'  => 'R',
  'Waiting' => 'W',
  'Cancel'  => 'C'
];

if (array_key_exists($cri_item, $status_map)) {
  $status_app = $status_map[$cri_item];
} else {
  $status_app = ''; // All atau tidak ditemukan
}

$mod="1L";
$tt_hapus="data-toggle='tooltip' title='Cancel'";
$tt_hapus2="<i class='fa fa-times'></i>";
echo "
<head>
	<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>
	<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
echo "</head>";
?>
<table id="gen_req_filter" class="display responsive" style="width:100%">
  <thead>
  <tr>
    <th>Req #</th>
    <th>Req Date</th>
    <th>Supplier</th>
    <th>Description</th>
    <th>Notes</th>
    <th>Status</th>
    <th>Created By</th>
    <th>Created Date</th>
    <th>Approve By</th>
    <th>Approve By 2</th>
    <th>Rcvd By</th>
    <th>PO #</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  </thead>
  <tbody>

  </tbody>
</table>
<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
<script>
  var table;

  $(document).ready(function() {
    table = $('#gen_req_filter').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: 'ajax_status_gen.php', // file server-side
        type: 'POST',
        data: function(d) {
          d.cri_item = $('#cri_item').val(); // ambil nilai filter
        }
      },
      columns: [
        { data: 'reqno' },
        { data: 'reqdate' },
        { data: 'supplier' },
        { data: 'itemdesc' },
        { data: 'notes' },
        { data: 'status' },
        { data: 'username' },
        { data: 'dateinput' },
        { data: 'app_by' },
        { data: 'app_by2' },
        { data: 'userpo' },
        { data: 'pono' },
        { data: 'edit', orderable: false, searchable: false },
        { data: 'cancel', orderable: false, searchable: false },
        { data: 'print', orderable: false, searchable: false }
      ]
    });

    // Inisialisasi select2 (jika belum)
    $('#cri_item').select2();
  });

  function getStatusApp(value) {
    table.ajax.reload(); // reload data dengan filter baru
  }
</script>

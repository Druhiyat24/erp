<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mode = $_GET['mode'];
$mod = $_GET['mod'];
$txtsupplier=$_POST['txtsupplier'];
$txtbuyer=$_POST['txtbuyer'];
if($txtsupplier == '')
{
	$supplier = "";
}
else
{
	$supplier = "and poh.id_supplier='$txtsupplier'";
}
  if($txtbuyer == '')
  {
	  $buyer = "";
	  $buyer_gen = "";
  }
  else
  {
	  $buyer = "and id_buyer='$txtbuyer'";
	  $buyer_gen = "and reqno='-'";
  } 
$excel="N";
if (isset($_POST['txtfrom']) && trim($_POST['txtfrom'])!='') { $from=date('Y-m-d',strtotime($_POST['txtfrom'])); } else { $from=date('Y-m-01'); }
if (isset($_POST['txtto']) && trim($_POST['txtto'])!='') { $to=date('Y-m-d',strtotime($_POST['txtto'])); } else { $to=date('Y-m-d'); }
  
$titlenya="Laporan Purchase Order General";

if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }

echo "<div class='box lap-gen-info-box'>";
	echo "<div class='box-body'>";
		echo "<div class='lap-gen-period'><i class='fa fa-calendar'></i> Periode Dari <strong>".fd_view($from)."</strong> s/d <strong>".fd_view($to)."</strong></div>";
		echo "<a href='lap_gen_excel.php?frexc=$from&toexc=$to&txtsupplier=$txtsupplier&txtbuyer=$txtbuyer' class='btn-excel-download'><i class='fa fa-file-excel-o'></i> Save To Excel</a>";
	echo "</div>";	
echo "</div>";
echo "<div class='box lap-gen-table-box'>";
	echo "<div class='box-body'>";
		echo "<table id='tbl_lap_gen' style='width: 100%;' class='display responsive lap-gen-table'>";
			echo "<thead>";
				echo "
				<tr>
					<th>No</th>
					<th>Supplier</th>
					<th>PO</th>
					<th>PO Date</th>
					<th>Request No</th>
					<th>Description</th>
					<th>Qty PO</th>
					<th>Unit</th>
					<th>Price</th>
					<th>Curr</th>
					<th>Status</th>
				</tr>";
			echo "</thead>";
			echo "<tbody>";
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
?>

<!-- ============ LAP GEN CSS & ANIMATIONS ============ -->
<style type="text/css">
/* ===== ANIMATIONS ===== */
@keyframes fadeInUp { from { opacity:0; transform:translateY(20px);} to { opacity:1; transform:translateY(0);} }
@keyframes fadeIn { from { opacity:0;} to { opacity:1;} }
@keyframes slideInLeft { from { opacity:0; transform:translateX(-30px);} to { opacity:1; transform:translateX(0);} }
@keyframes pulseGlow { 0%,100% { box-shadow:0 0 5px rgba(39,174,96,.4);} 50% { box-shadow:0 0 20px rgba(39,174,96,.7);} }
@keyframes rowFadeIn { from { opacity:0; transform:translateX(-10px);} to { opacity:1; transform:translateX(0);} }

/* ===== INFO BOX (Periode) ===== */
.lap-gen-info-box { animation: fadeInUp .6s ease-out; border:none; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.08); background:linear-gradient(135deg,#ffffff 0%,#f8f9ff 100%); margin-bottom:20px; overflow:hidden; position:relative; }
.lap-gen-info-box::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,#667eea 0%,#764ba2 50%,#f093fb 100%); }
.lap-gen-info-box .box-body { padding:20px 25px; display:flex; align-items:center; justify-content:flex-start; flex-wrap:wrap; gap:15px; }
.lap-gen-period { font-size:15px; color:#4a5568; animation: slideInLeft .7s ease-out; }
.lap-gen-period i { color:#667eea; margin-right:8px; font-size:18px; }
.lap-gen-period strong { color:#2d3748; }

/* ===== EXCEL BUTTON ===== */
.btn-excel-download { display:inline-flex; align-items:center; gap:8px; padding:10px 22px; background:linear-gradient(135deg,#27ae60 0%,#2ecc71 100%); color:#fff !important; border-radius:8px; font-weight:600; font-size:13px; text-decoration:none !important; transition:all .3s cubic-bezier(.4,0,.2,1); animation:pulseGlow 2s infinite; box-shadow:0 4px 15px rgba(39,174,96,.3); }
.btn-excel-download:hover { transform:translateY(-2px) scale(1.03); box-shadow:0 8px 25px rgba(39,174,96,.5); background:linear-gradient(135deg,#219a52 0%,#27ae60 100%); }
.btn-excel-download:active { transform:translateY(0) scale(.98); }
.btn-excel-download i { font-size:16px; }

/* ===== TABLE BOX ===== */
.lap-gen-table-box { animation: fadeInUp .8s ease-out .2s both; border:none; border-radius:12px; box-shadow:0 4px 25px rgba(0,0,0,.1); background:#fff; overflow:hidden; }
.lap-gen-table-box .box-body { padding:20px; }

/* ===== TABLE STYLING ===== */
.lap-gen-table { font-size:13px !important; border-collapse:separate; border-spacing:0; }
.lap-gen-table thead tr { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); }
.lap-gen-table thead th { color:#fff !important; font-weight:600; padding:14px 12px !important; border:none !important; text-transform:uppercase; font-size:11px; letter-spacing:.5px; white-space:nowrap; }
.lap-gen-table thead th:first-child { border-radius:8px 0 0 0; }
.lap-gen-table thead th:last-child { border-radius:0 8px 0 0; }
.lap-gen-table tbody tr { animation: rowFadeIn .4s ease-out both; transition: all .25s ease; }
.lap-gen-table tbody tr:nth-child(even) { background-color:#f8fafc; }
.lap-gen-table tbody tr:hover { background:linear-gradient(90deg,#eef2ff 0%,#f0e6ff 100%) !important; box-shadow:0 2px 12px rgba(102,126,234,.15); }
.lap-gen-table tbody td { padding:12px !important; color:#4a5568; vertical-align:middle; border:none !important; border-bottom:1px solid #f0f4f8 !important; }
.lap-gen-table tbody tr:nth-child(1) { animation-delay:.05s; }
.lap-gen-table tbody tr:nth-child(2) { animation-delay:.1s; }
.lap-gen-table tbody tr:nth-child(3) { animation-delay:.15s; }
.lap-gen-table tbody tr:nth-child(4) { animation-delay:.2s; }
.lap-gen-table tbody tr:nth-child(5) { animation-delay:.25s; }
.lap-gen-table tbody tr:nth-child(6) { animation-delay:.3s; }
.lap-gen-table tbody tr:nth-child(7) { animation-delay:.35s; }
.lap-gen-table tbody tr:nth-child(8) { animation-delay:.4s; }
.lap-gen-table tbody tr:nth-child(9) { animation-delay:.45s; }
.lap-gen-table tbody tr:nth-child(10) { animation-delay:.5s; }

/* ===== DATATABLES OVERRIDES ===== */
.lap-gen-table-box .dataTables_filter input { border:2px solid #e2e8f0; border-radius:8px; padding:6px 12px; transition:all .3s ease; outline:none; }
.lap-gen-table-box .dataTables_filter input:focus { border-color:#667eea; box-shadow:0 0 0 3px rgba(102,126,234,.15); }
.lap-gen-table-box .dataTables_paginate .paginate_button { border-radius:6px; margin:0 2px; transition:all .2s ease; }
.lap-gen-table-box .dataTables_paginate .paginate_button:hover { background:linear-gradient(135deg,#667eea,#764ba2) !important; color:#fff !important; border-color:transparent !important; }
.lap-gen-table-box .dataTables_paginate .paginate_button.current { background:linear-gradient(135deg,#667eea,#764ba2) !important; color:#fff !important; border-color:transparent !important; box-shadow:0 2px 8px rgba(102,126,234,.4); }

/* ===== MODAL STYLING ===== */
#mypobpb .modal-content { border:none; border-radius:16px; box-shadow:0 25px 60px rgba(0,0,0,.3); animation: fadeInUp .3s ease-out; overflow:hidden; }
#mypobpb .modal-header { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); padding:20px 25px; border-bottom:none; }
#mypobpb .modal-header .modal-title { color:#fff; font-weight:700; font-size:16px; }
#mypobpb .modal-header .close { color:#fff; opacity:.8; font-size:24px; transition:all .3s ease; }
#mypobpb .modal-header .close:hover { opacity:1; transform:rotate(90deg); }
#mypobpb .modal-footer { border-top:1px solid #f0f4f8; padding:15px 25px; }
#mypobpb .modal-footer .btn-default { border-radius:8px; padding:8px 20px; transition:all .3s ease; border:1px solid #e2e8f0; }
#mypobpb .modal-footer .btn-default:hover { background:#667eea; color:#fff; border-color:#667eea; }

/* ===== RESPONSIVE ===== */
@media (max-width:768px) {
  .lap-gen-info-box .box-body { flex-direction:column; align-items:flex-start; }
  .lap-gen-table { font-size:11px !important; }
  .lap-gen-table thead th { padding:10px 8px !important; font-size:10px; }
  .lap-gen-table tbody td { padding:8px !important; }
}
</style>

<!--=============adyz================================================================ -->
<div class="modal fade" id="mypobpb"  tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" style="overflow-y:auto;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detail BPB pada PO dan Nama Barang ini</h4>
      </div>
      <div class="modal-body" style="overflow-y:auto; height:500px;">
        <div id='detail_pobpb'></div>    
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
    </div>
  </div>
</div>

<script type='text/javascript'>

function choose_barang(xpoid)
  { 
	
	var html = $.ajax
    ({  type: "POST",
        url: 'ajax_pobpb_detail.php?modeajax=view_detailbpb',
        data: {xpoid: xpoid},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_pobpb").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplefixbarang').DataTable
      ({  sorting: false,
          searching: false,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };

</script>

<script type='text/javascript'>
// NOTE: this file is included by pages/pur/content.php (line ~147 of index.php),
// which is rendered BEFORE jquery (line ~154) and jquery.dataTables (line ~197).
// Therefore no jQuery code may run while this block is parsed, otherwise the
// browser throws "Uncaught ReferenceError: $ is not defined" and the DataTable
// is never created (no AJAX call is sent at all). We wait for window 'load'.
(function () {
  function initLapGen() {
    if (typeof jQuery === 'undefined' || typeof jQuery.fn === 'undefined' || typeof jQuery.fn.DataTable === 'undefined') {
      if (window.console) { console.error('lap_gen: jQuery / DataTables library not loaded'); }
      return;
    }
    jQuery(function ($) {
      var $tbl = $("#tbl_lap_gen");
      if ($tbl.length === 0) { return; }
      if ($.fn.DataTable.isDataTable('#tbl_lap_gen')) { $tbl.DataTable().destroy(); }
      $tbl.DataTable({
        processing: true,
        serverSide: true,
        order: [[3, "desc"]],
        ajax: {
          url: "webservices/getLapGenData.php",
          type: "POST",
          data: {
            from: "<?php echo $from; ?>",
            to: "<?php echo $to; ?>",
            txtsupplier: "<?php echo $txtsupplier; ?>",
            txtbuyer: "<?php echo $txtbuyer; ?>"
          }
        },
        columns: [
          { data: "no", orderable: false, className: "text-center" },
          { data: "supplier" },
          { data: "pono" },
          { data: "podate" },
          { data: "buyer" },
          { data: "itemdesc" },
          { data: "qty", className: "text-right" },
          { data: "unit", className: "text-center" },
          { data: "price", className: "text-right" },
          { data: "curr", className: "text-center" },
          { data: "status_po", className: "text-center" }
        ],
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        language: {
          processing: "Loading data..."
        }
      });
    });
  }
  if (document.readyState === 'complete') { initLapGen(); }
  else { window.addEventListener('load', initLapGen); }
})();
</script>
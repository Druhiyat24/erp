<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("invoice","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI


?>

<!-- Modal -->
<div class="box">
<div class="box-body"  >
<!--<button class="btn btn-primary" onclick="myForm('ADD','9999999999x')"   >Add New</button> -->
</div>
</div>


<div class="box">

  <div class="box-body">
  	<table id="MasterInvoiceActualTable" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>

			<th>No</th>
			<th>Date</th>
            <th>PO</th>   

            <th width='14%'>Action</th>
		</tr>
      </thead>

      <tbody id='render'>

      </tbody>
    </table>
  </div>
	</div>







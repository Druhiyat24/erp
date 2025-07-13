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
<div class="box" >
  <div class="box-header">
	<h4>Perjanjian Kerja Page </h4>
	<button class="btn btn-primary"  onclick="AddNew()" >Add New</button>
  </div>
  <!--<div class="box-body"  style="overflow:scroll;height:500px"> -->
<div class="box-body" >

	<table id="example1" class="display responsive" style="width:100%;font-size:15px;">
      <thead>
      <tr>
			<th >No. Surat</th>
			<th >Nik</th>
			<th >Nama</th>
			<th >Action</th>
			</tr>
      </thead>
      <tbody id="bodyexamle1">

	  </tbody>
	  


	</div>




</div>



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
	<h4>Material List</h4>
  </div>
  <!--<div class="box-body"  style="overflow:scroll;height:500px"> -->
<div class="box-body" >
<div class="container" style="overflow:scroll">
	<table id="example1"  class="display responsive" style="width:100%;font-size:15px;">
      <thead>
      <tr >
			<th colspan="5" style="text-align:center">30 November 2019 </th>
			<th colspan="4" style="text-align:center">Saldo Awal</th>
			<th  >null</th>
			<th >null</th>
			<th colspan="4" style="text-align:center">Barang masuk</th>
			<th  >null</th>
			<th >null</th>		
			<th colspan="4" style="text-align:center">QC</th>
			<th  >null</th>
			<th >null</th>	
			<th colspan="4" style="text-align:center">Retur</th>
			<th  >null</th>
			<th >null</th>	
			<th colspan="4" style="text-align:center">Barang Keluar</th>		
			<th colspan="4" style="text-align:center">Saldo Akhir</th>			
			</tr>
      </thead>
      <tbody id="bodyexamle1">
	  <tr>
			<td >null</td>
			<td>null </td>
			<td>null </td>
			<td>null </td>
			<td>null </td>		


			<!-- SALDO AWAL -->
			<td>null </td>
			<td>null </td>
			<td>null </td>
			<td>null </td>
			
			<!-- Kosong -->
			<td>null </td>
			<td>null </td>
			<!--Barang Masuk -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>			
			<!-- Kosong -->
			<td>null </td>
			<td>null </td>
			<!-- QC -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>					
			<!-- kosong -->
			<td>null </td>
			<td>null </td>
			<!-- Retur -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>					

			<!-- kosong -->
			<td>null </td>
			<td>null </td>
				<!-- Barang Keluar -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>		

				<!-- Saldo Akhir -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>	
			
		</tr>	
		
	  <tr>
			<td >null </td>
				<td>null </td>
			<td>null </td>
			<td>null </td>
			<td>null </td>		


			<!-- SALDO AWAL -->
			<td>null </td>
			<td>null </td>
			<td>null </td>
			<td>null </td>
			
			<!-- Kosong -->
			<td>null </td>
			<td>null </td>
			<!--Barang Masuk -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>			
			<!-- Kosong -->
			<td>null </td>
			<td>null </td>
			<!-- QC -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>					
			<!-- kosong -->
			<td>null </td>
			<td>null </td>
			<!-- Retur -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>					

			<!-- kosong -->
			<td>null </td>
			<td>null </td>
				<!-- Barang Keluar -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>		

				<!-- Saldo Akhir -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>	
			
		</tr>	
		
	  <tr>
			<td >null </td>
				<td>null </td>
			<td>null </td>
			<td>null </td>
			<td>null </td>		


			<!-- SALDO AWAL -->
			<td>null </td>
			<td>null </td>
			<td>null </td>
			<td>null </td>
			
			<!-- Kosong -->
			<td>null </td>
			<td>null </td>
			<!--Barang Masuk -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>			
			<!-- Kosong -->
			<td>null </td>
			<td>null </td>
			<!-- QC -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>					
			<!-- kosong -->
			<td>null </td>
			<td>null </td>
			<!-- Retur -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>					

			<!-- kosong -->
			<td>null </td>
			<td>null </td>
				<!-- Barang Keluar -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>		

				<!-- Saldo Akhir -->
			<td>value </td>
			<td>value </td>
			<td>value </td>
			<td>value </td>	
			
		</tr>			
	  <tr>
			<th >Kode Mat </th>
				<th>Barang </th>
			<th>No PO</th>
			<th>Supplier</th>
			<th>Qty Kebutuhan </th>		
			<!-- SALDO AWAL -->
			<th> Qty /KG </th>
			<th>nu Qty /Yard  </th>
			<th> Qty /Roll </th>
			<th> Qty /Pcs </th>
			
			<!-- Kosong -->
			<th>Tgl BPB</th>
			<th>No BPB in</th>
			<!--Barang Masuk -->
			<th> Qty /KG </th>
			<th> Qty /Yard </th>
			<th> Qty /Roll </th>
			<th> Qty /Pcs </th>			
			<!-- Kosong -->
			<th>Tgl BPB</th>
			<th>No BPB in</th>
			<!-- QC -->
			<th> Qty /KG </th>
			<th> Qty /Yard </th>
			<th> Qty /Roll </th>
			<th> Qty /Pcs </th>					
			<!-- kosong -->
			<th>Tgl BPB	No </th>
			<th>BPB in </th>
			<!-- Retur -->
			<th> Qty /KG </th>
			<th>Qty /Yard </th>
			<th>Qty /Roll </th>
			<th>Qty /Pcs  </th>					
			<!-- kosong -->
			<th>Tgl BPB	No </th>
			<th>BPB out </th>
				<!-- Barang Keluar -->
			<th> Qty /KG </th>
			<th>Qty /Yard </th>
			<th> Qty /Roll</th>
			<th>Qty /Pcs </th>		
				<!-- Saldo Akhir -->
			<th> Qty /KG </th>
			<th>Qty /Yard  </th>
			<th> Qty /Roll</th>
			<th> Qty /Pcs</th>	
		</tr>			
	  </tbody>
	  
</table>
<div>
	</div>




</div>



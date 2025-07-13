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


<div class="box list" id="abcd" >
<div class="box-body" >
  <div class="box-body">
  
  <div class="col-md-3">
	                    <div class='form-group'>
                        <label>From</label>
                        <input type='text' id='fromdate' class='form-control' name='period' autocomplete="off"
                               placeholder= 'From MM/YYYY' value='<?=isset($row)?$row->period:''?>'>

                    </div>
					
		
	</div>				
  <div class="col-md-3">
	                    <div class='form-group'>
                        <label>To</label>
                        <input type='text' id='todate' class='form-control' name='period' autocomplete="off"
                               placeholder= 'From MM/YYYY' value='<?=isset($row)?$row->period:''?>'>

                    </div>
	</div>						
      <div class="col-md-12">

        <button type="button" id="search" onClick="getLaporan()" class="btn btn-primary">Submit</button>
		<img src="./img/25.gif" id="loading" style="display:none" class="img-responsive loading"width="4%">
      </div>			
  </div>

	</div>




</div>



<div class="box list" id="uijurnal" >
<div class="box-body" >   <button type="button" id="backs" onClick="back()" class="btn btn-primary" style="display:none">Back</button>
  <div class="box-body">

  	<table id="examplefix1010" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>
	    	<th>Kode Aktiva</th>
			<th>Tipe Aktiva</th>
            <th>Keterangan</th>   
            <th>Akun Aktiva</th>
            <th>Biaya Aktiva</th>  
			<th>Tanggal Pakai</th>  
             <th>Tanggal Beli</th>
            <th>Qty</th>
            <th>Umur Bulan Aktiva</th>  
			<th>%Penyusutan/tahun</th>  
			<th>Metode Penyusutan</th>  
			<th>Department</th>  

 
		</tr>
      </thead>
      <tbody id='render'>
	  
      </tbody>
    </table>
  </div>

	</div>




</div>



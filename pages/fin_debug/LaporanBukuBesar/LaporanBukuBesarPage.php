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
	<div class="col-md-3">
	                    <div class='form-group'>
                        <label>Nomor Akun</label>
                  <select id='idcoa' name="idcoa" class='form-control' onchange="getidcashbank(this.value)" >
					<option>--Select--</option>
                  </select>

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
	    	<th>&nbsp;</th>
			<th>No Akun</th>		
	    	<th>Nama Akun</th>
			<th>Tanggal</th>
			<th>No Jurnal</th>
            <th>Referensi Dokumen</th>   
            <th>Keterangan</th>
            <th>Penambahan</th>  
			<th>Pengurangan</th>  
            <th>Saldo</th>


 
		</tr>
      </thead>
      <tbody id='render'>
	  
      </tbody>
    </table>
  </div>

	</div>




</div>



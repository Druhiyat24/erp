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


<div class="box type" >
<div id="formBs" class='box'>
    <div class='box-body'>
            <div class="panel panel-default">
                <div class="panel-heading">Type Laporan</div>
                <div class="panel-body row">
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label for="period_from">&nbsp;</label>

							<button  class='btn btn-primary' onclick="Show('1')" >Laporan</button>
						<!--	<button  class='btn btn-primary' onclick="Show('2')">Account No.</button> -->
                        </div>
                    </div>
					

                </div>
            </div>
    </div>
</div>


</div>


<div class="box boxPencarian" >
<div id="formBs" class='box'>
    <div class='box-body'>
            <div class="panel panel-default">
                <div class="panel-heading">Periode Akuntansi</div>
                <div class="panel-body row">
				<div class="col-md-3 account">
                        <div class='form-group'>
                            <label for="period_from">Account :</label>

                            <select id='account' class='form-control select2' onchange="getAccountPeriode(this,'acc')">
                                <option value="" disabled selected>Pilih Account</option>

                            </select>
                        </div>		
				</div>
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label for="period_from">Dari</label>

                            <select id='period_from' class='form-control select2'onchange="getAccountPeriode(this,'from')" >
                                <option value="" disabled selected>Pilih Periode</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label>Sampai</label>

                            <select id='period_to' class='form-control select2' onchange="getAccountPeriode(this,'to')">
                                <option value="" disabled selected>Pilih Periode</option>

                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <button name='submit' class='btn btn-primary' onclick="getListData()" >Tampilkan</button>
							 <button type='submit' name='submit' class='btn btn-warning' onclick="back()">Back</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>


</div>


<div class="box list" >
<div id="formBs" class='box'>
    <div class='box-body'>
<div class="box-body" >

	<table id="example1" class="display responsive" style="width:100%;font-size:10px;">
      <thead>
    <!--  <tr>
			<th >&nbsp;</th>
			<th >Account Number</th>
			<th >Nama Akun</th>
			<th >Tanggal</th>
			<th>Tipe Journal</th>
			<th >Journal Nomor</th>
			<th >Deskripsi</th>
			<th >Debit</th>
			<th >Kredit</th>
			<th >Saldo Akhir</th>
			</tr>
			-->
<tr>
<th > &nbsp; </th>
			<th >Kode</th>
			<th >Nama</th>
			<th >TOP</th>
			<th >Based On</th>
			<th>Total</th>
			<th >M>6</th>
			<th >>M-5</th>
			<th >M-4</th>
			<th >M-3</th>
			<th >M-2</th>
			<th >M-1 </th>
			<th >Bulan Berjalan </th>			
			<th >Ardays</th>
			</tr>			
      </thead>
      <tbody id="bodyexamle1">
			
	  </tbody>
	  
</table>

	</div>

    </div>
</div>


</div>



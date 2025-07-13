<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];


// Get list of kategori
$list_kategori = array('' => '--SEMUA--');
include __DIR__ .'/../../../include/conn.php';
$q = "
		select * from masteractivatype order by nm_tipe_aktiva;
";
// echo $q;
$stmt = mysql_query($q);
while($_row = mysql_fetch_array($stmt)) {
    $list_kategori[$_row['kd_tipe_aktiva']] = $_row['nm_tipe_aktiva'];
}
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
              <label>Kategori</label>
              <select name="kd_tipe_activa" id="kd_tipe_activa" class='form-control'>
                  <?php if(@count($list_kategori)):?>
                  <?php foreach($list_kategori as $k=>$v):?>
                          <option value="<?=$k?>"><?=$v?></option>
                  <?php endforeach;?>
                  <?php endif;?>
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
            <th colspan="13" style="font-size: 12px; border-collapse: collapse; border: none;"><strong>PT.NIRWANA ALABARE GARMENT</strong></th>
        </tr>
        <tr>
            <th colspan="13" style=" border-collapse: collapse; border: none;"><strong>PENYUSUTAN AKTIVA TETAP</strong></th>
        </tr>
        <tr>
            <th colspan="13" style="font-size: 12px; border-collapse: collapse; border: none;" >
            <strong>PERIODE: <span id="label_from"> </span> s/d <span id="label_to"> </span></strong>
            </th>
        </tr>
        <tr>
            <th>NO</th>
            <th>GOL AT</th>
            <th>KODE AT</th>
            <th>NAMA AT</th>
            <th>SPESIFIKASI</th>
            <th>TGL PEROLEHAN</th>
            <th>JUMLAH UNIT</th>
            <th>REF DOKUMEN</th>
            <th>TARIF PENYUSUTAN</th>
            <th>NILAI PEROLEHAN</th>
            <th>BIAYA PENYUSUTAN PER BULAN</th>
            <th>AKUMULASI PENYUSUTAN</th>
            <th>NILAI BUKU</th>
		</tr>
      </thead>
      <tbody id='render'>
	  
      </tbody>
    </table>
  </div>

	</div>




</div>



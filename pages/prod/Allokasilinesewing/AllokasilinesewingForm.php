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
<!-- <h4>List Allokasi Line Sewing Form</h4> -->
<div class='box'>
  <div class='box-body'>
    <div class='row'>        
        <div class='col-md-3'>
          <div class='form-group'>
             <label>WS # *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtWS' id='ws' onchange="getDetail('DWT/1218/00001');handlekeyup(this)">
            </select>
          </div>
          <div class='form-group'>
            <label>Buyer</label>
            <input type="text" id='buyer' onchange="" name="txtbuyer" class='form-control' placeholder="Masukkan Buyer" readonly>
          </div>
          <div class='form-group'>
            <label>Style</label>
            <input type="text" id='style' onchange="" name="txtstyle" class='form-control' placeholder="Masukkan Style" readonly>
          </div>
        </div>

        <div class='col-md-3'>
          <div class='form-group'>            
            <label>SMV (Min) </label>
            <input type='text' class='form-control' id='smv_min' 
              name='txtsmv'  placeholder='Masukkan SMV Min' readonly>           
          </div>
           <div class='form-group'>            
            <label>SMV (Sec) </label>
            <input type='text' class='form-control' id='smv_sec' 
              name='txtsmv'  placeholder='Masukkan SMV Sec' readonly>           
          </div>
          <div class='form-group'>
            <label>Qty Costing</label>
            <input type='text' id="qty_cost" class='form-control' name='txtqtycosting' 
              placeholder='Masukkan Qty Costing' readonly>
          </div>
        </div>


        <div class='box-body'>
          <div id='detail_ws'></div>
        </div>
        <div class='col-md-3'>
          <a type='submit' name='submit' onClick="save()" class='btn btn-primary'>Simpan</a>
        </div>
    </div>
       <div class='box-body'>
          <div id='detail_item'></div>
        </div>
  </div>
</div>

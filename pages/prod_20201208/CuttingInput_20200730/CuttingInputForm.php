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
<div class='box'>
  <div class='box-body'>
    <div class='row'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Tanggal Input *</label>
            <input type='text' class='form-control' id='datepicker4' 
              name='txtdateout'  onchange="handlekeyup(this)" placeholder='Masukkan Tanggal Output' >
          </div>
          <div class='form-group'>
            <label>Notes</label>
            <input type='text' id="notes" class='form-control' name='txtnotes' 
              placeholder='Masukkan Notes' onkeyup="handlekeyup(this)" >
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>WS # *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtJOItem' id='ws' onchange="getDetail(this.value);handlekeyup(this)">

            </select>
          </div>
        </div>
        <div class='box-body'>
          <div id='detail_item'></div>
        </div>
        <div class='col-md-3'>
          <a type='submit' name='submit' onClick="save()" class='btn btn-primary'>Simpan</a>
        </div>
    </div>
  </div>
</div>

<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$akses = flookup("xxx","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var filenya = document.form.txtfile.value;
    if (filenya == '') { swal({ title: 'File Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI
# COPAS ADD
?>
<form method='post' name='form' enctype='multipart/form-data' 
  action='s_bpb_so.php?mod=$mod' onsubmit='return validasi()'>
  <div class='box'>
    <div class="box-header">
      <h3 class="box-title">Data Header</h3>
    </div>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label for='exampleInputFile'>Pilih File</label>
            <input type='file' name='txtfile' accept='.txt'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </div>
    </div>
  </div>
</form>
<?php
# END COPAS ADD
?>
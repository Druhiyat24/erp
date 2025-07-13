<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$akses = flookup("work_sheet","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
$id=$_GET['id'];
$sql="select a.jo_no from jo a inner join jo_det s on a.id=s.id_jo where a.id='$id'";
$rsjo=mysql_fetch_array(mysql_query($sql));
$jo_no=$rsjo['jo_no'];
?>
<script type='text/javascript'>
  function validasi()
  { var filenya = document.form.txtattach_file.value;
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
  action='s_ws_upl.php?mod=$mod' onsubmit='return validasi()'>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>JO #</label>
            <input type='text' class='form-control' name='txtjo' value='<?php echo $jo_no;?>' readonly>
          </div>
          <div class='form-group'>
            <label for='exampleInputFile'>Attach File</label>
            <input type='file' name='txtattach_file' accept='.jpg'>
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
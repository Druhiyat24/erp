<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$akses = flookup("update_notes_po","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
$id_po=$_GET['id'];
$rs=mysql_fetch_array(mysql_query("select * from po_header_dev where id='$id_po'"));
$pono=$rs['pono'];
$notes=$rs['notes'];

# END COPAS VALIDASI
# COPAS ADD
?>
<form method='post' name='form' action='s_po_notes_dev.php?mod=x3L&id=<?php echo $id_po;?>' onsubmit='return validasi()'>
  <div class='box'>
    <div class="box-header">
      <h3 class="box-title">Update PO Notes Development</h3>
    </div>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>PO # *</label>
            <input type='text' class='form-control' name='txtpono' value='<?php echo $pono;?>' readonly>
          </div>        
          <div class='form-group'>
            <label>Notes</label>
            <textarea class='form-control' name='txtnotes' rows='4'><?php echo $notes;?></textarea>
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
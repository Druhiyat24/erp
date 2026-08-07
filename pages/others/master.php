<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user = $_SESSION['username'];
$sql="SELECT m_general_req, edit_m_general_req FROM userpassword  WHERE username ='$user'";
$hsl=mysqli_fetch_array(mysqli_query($conn_li,$sql));
$m_general_req=$hsl['m_general_req'];
$edit_m_general_req=$hsl['edit_m_general_req'];

# START CEK HAK AKSES KEMBALI
$akses = flookup("m_general_req","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");
if (isset($_GET['id'])) {$id_item=$_GET['id'];} else {$id_item="";}
# COPAS EDIT
if ($id_item=="")
{ $mattype = "N";
  $goods_code = "";
  $itemdesc = "";
  $jenis_item="";
  $jenis_mut="";
  $color = "";
  $size = "";
  $coa_production = "";
  $coa_sup_production = "";
  $coa_sup_gen_adm = "";
  $coa_sup_selling = "";
  $file_gambar = "";
}
else
{ $query = mysql_query("SELECT * FROM masteritem where id_item='$id_item' ");
  $data = mysql_fetch_array($query);
  $mattype = $data['mattype'];
  $goods_code = $data['goods_code'];
  $itemdesc = $data['itemdesc'];
  $jenis_item = $data['tipe_item'];
  $jenis_mut = $data['tipe_mut'];
  $color = $data['color'];
  $persediaan = $data['n_code_category'];
  $size = $data['size'];
  $coa_production     = $data['coa_production'];
  $coa_sup_production = $data['coa_sup_production'];
  $coa_sup_gen_adm    = $data['coa_sup_gen_adm'];
  $coa_sup_selling    = $data['coa_sup_selling'];
  $file_gambar        = $data['file_gambar'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
if($id_item !=''){
	echo '<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>';
	echo "<script type='text/javascript'>";
	echo "$(function(){ $('#persediaan').val('".$persediaan."').trigger('change.select2'); applyCoaLock(); });";
	echo "</script>";
	
}

echo "<script type='text/javascript'>
  function validasi()
  { var mattype = document.form.txtmattype.value;
    var goods_code = document.form.txtgoods_code.value;
    var itemdesc = document.form.txtitemdesc.value;
    var color = document.form.txtcolor.value;
    var size = document.form.txtsize.value;
    var persediaan = document.getElementById('persediaan').value;
    if (mattype == '') { document.form.txtmattype.focus(); swal({ title: 'Mat Type Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (goods_code == '') { document.form.txtgoods_code.focus(); swal({ title: 'Item Code Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (itemdesc == '') { document.form.txtitemdesc.focus(); swal({ title: 'Description Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (color == '') { document.form.txtcolor.focus(); swal({ title: 'Color Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (size == '') { document.form.txtsize.focus(); swal({ title: 'Size Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (persediaan == '') { swal({ title: 'Mapping Persediaan Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if ((persediaan == '1' || persediaan == '2') && document.form.txt_coa_production.value == '') { swal({ title: 'COA Production Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if ((persediaan == '1' || persediaan == '2') && document.form.txt_coa_sup_production.value == '') { swal({ title: 'COA Supporting Production Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if ((persediaan == '1' || persediaan == '2') && document.form.txt_coa_sup_gen_adm.value == '') { swal({ title: 'COA Supporting General & Adm Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if ((persediaan == '1' || persediaan == '2') && document.form.txt_coa_sup_selling.value == '') { swal({ title: 'COA Supporting Selling Tidak Boleh Kosong', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";
# END COPAS VALIDASI
# COPAS ADD
if ($mod=="2") {
    $is_upload_only = ($m_general_req == "1" && $edit_m_general_req == "0");
    $ro = $is_upload_only ? " readonly" : "";
    $dis = $is_upload_only ? " disabled" : "";
    $onsubmit = $is_upload_only ? "" : " onsubmit='return validasi()'";
    $btn_label = $is_upload_only ? "Upload" : "Simpan";
    $btn_name = $is_upload_only ? "upload" : "submit";
?>

<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' enctype='multipart/form-data' action='<?php echo $is_upload_only ? "s_master_upload.php" : "s_master.php"; ?>?mod=<?php echo $mod; ?>&id=<?php echo $id_item; ?>'<?php echo $onsubmit; ?>>
        <div class='col-md-3'>              
          <div class='form-group'>
            <input type='hidden' class='form-control' name='txtmattype' value='<?php echo $mattype;?>' >
            <label>Item Code *</label>
            <input type='text' class='form-control' name='txtgoods_code' value='<?php echo $goods_code;?>'<?php echo $ro;?> >
          </div>        
          <div class='form-group'>
            <label>Description *</label>
            <input type='text' class='form-control' name='txtitemdesc' value='<?php echo $itemdesc;?>'<?php echo $ro;?> >
          </div>
          <div class='form-group'>
            <label>Item Type</label>
            <select class='form-control select2' style='width: 100%;' name='txtjenisitem'<?php echo $dis;?>>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil 
                  from masterpilihan where kode_pilihan='J_Item'";
                IsiCombo($sql,$jenis_item,'Pilih Item Type');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Mapping Persediaan</label>
            <select id='persediaan' class='form-control select2' style='width: 100%;' name='txtpersediaan'<?php echo $dis;?>>
              <?php 
                $sql = "select n_id isi,description tampil 
                  from mapping_category ";
                IsiCombo($sql,'','Pilih Persediaan');
              ?>
            </select>
          </div>		  
        </div>
        <div class='col-md-3'>
          <div class='row'>
            <div class='col-md-6'>        
              <div class='form-group'>
                <label>Color *</label>
                <input type='text' class='form-control' name='txtcolor' value='<?php echo $color;?>'<?php echo $ro;?> >
              </div>
            </div>
            <div class='col-md-6'>        
              <div class='form-group'>
                <label>Size *</label>
                <input type='text' class='form-control' name='txtsize' value='<?php echo $size;?>'<?php echo $ro;?> >
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label>Mutation Type</label>
            <select class='form-control select2' style='width: 100%;' name='txtjenismut'<?php echo $dis;?>>
              <?php 
                $sqlsel=" and nama_pilihan='Mesin'";
                $sql = "select nama_pilihan isi,if(nama_pilihan='Mesin','Barang Modal',nama_pilihan) tampil 
                  from masterpilihan where kode_pilihan='Type Mat' $sqlsel ";
                IsiCombo($sql,$jenis_mut,'Pilih Mutation Type');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label for='exampleInputFile'>Image File</label>
            <input type='file' name='txtfile' accept='.jpg'>
            <?php if (!empty($file_gambar)) { ?>
              <p class="help-block" style="margin-top:5px; font-size:12px; color:#777;">
                <i class='fa fa-paperclip'></i> File terupload: <strong><?php echo htmlspecialchars($file_gambar); ?></strong>
                <a href="s_master.php?mod=<?php echo $mod; ?>&id=<?php echo $id_item; ?>&act=del_img" class="btn btn-xs btn-danger" title="Hapus Gambar" onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')">
                  <i class="fa fa-trash"></i>
                </a>                
              </p>
            <?php } ?>
          </div>
          <button type='submit' name='<?php echo $btn_name; ?>' class='btn btn-primary'><?php echo $btn_label; ?></button>
        </div>
        <?php if (!$is_upload_only) { ?>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Production</label>
            <select class='form-control select2' style='width: 100%;' id='coa_production' name='txt_coa_production'>
              <option value='-'>-</option>
              <?php IsiCombo("select no_coa isi, CONCAT(no_coa,' - ',nama_coa) tampil from mastercoa_v2 where no_coa like '5%' OR no_coa like '1%' order by no_coa", $coa_production, 'Pilih COA'); ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Supporting Production</label>
            <select class='form-control select2' style='width: 100%;' id='coa_sup_production' name='txt_coa_sup_production'>
              <option value='-'>-</option>
              <?php IsiCombo("select no_coa isi, CONCAT(no_coa,' - ',nama_coa) tampil from mastercoa_v2 where no_coa like '5%' OR no_coa like '1%' order by no_coa", $coa_sup_production, 'Pilih COA'); ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Supporting Selling</label>
            <select class='form-control select2' style='width: 100%;' id='coa_sup_selling' name='txt_coa_sup_selling'>
              <option value='-'>-</option>
              <?php IsiCombo("select no_coa isi, CONCAT(no_coa,' - ',nama_coa) tampil from mastercoa_v2 where no_coa like '6%' OR no_coa like '1%' order by no_coa", $coa_sup_selling, 'Pilih COA'); ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Supporting General &amp; Administration</label>
            <select class='form-control select2' style='width: 100%;' id='coa_sup_gen_adm' name='txt_coa_sup_gen_adm'>
              <option value='-'>-</option>
              <?php IsiCombo("select no_coa isi, CONCAT(no_coa,' - ',nama_coa) tampil from mastercoa_v2 where no_coa like '7%' OR no_coa like '1%' order by no_coa", $coa_sup_gen_adm, 'Pilih COA'); ?>
            </select>
          </div>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script>
function applyCoaLock() {
    var $coaFields = $('#coa_production, #coa_sup_production, #coa_sup_selling, #coa_sup_gen_adm');
    var persediaan = document.getElementById('persediaan').value;
    var butuhCoa = (persediaan == '1' || persediaan == '2');
    if (!butuhCoa) {
        $coaFields.each(function() {
            if (!$(this).val()) { $(this).val('-'); }
        });
        $coaFields.prop('disabled', true).trigger('change');
    } else {
        $coaFields.each(function() {
            if ($(this).val() === '-') { $(this).val(''); }
        });
        $coaFields.prop('disabled', false).trigger('change');
    }
}
window.addEventListener('load', function() {
    $('#persediaan').on('change', applyCoaLock);
});
</script>
<?php
# END COPAS ADD
} else if ($id_item=="") {



if (isset($_POST['submit']))
{
  $persediaanfilter = ($_POST['txtpersediaanfilter']);
  if ($persediaanfilter=="ALL")
  {
  $queryfilter = '';
  }
  else
  {  
  $queryfilter = "and n_code_category = '$persediaanfilter'";
}
}
else
{
 $queryfilter = "and n_code_category = ''"; 
}
  ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Master Non Production</h3>
    <?php if (!($m_general_req == "1" && $edit_m_general_req == "0")) { ?>
    <a href='../others/?mod=2' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
    <?php } ?>
  </div>

<div class='row'>
    <form action="" method="post">

    <div class="box-header">
      <div class='col-md-3'>                            
        <label>Mapping Persediaan: </label>
            <select id='persediaanfilter' class='form-control select2' style='width: 100%;' name='txtpersediaanfilter' 
            value='<?php echo $persediaanfilter;?>' >
              <option value="ALL" >ALL</option>
              <option value="1" <?php if ($persediaanfilter == 1) { echo 'selected'; }?>>PERSEDIAAN ATK</option>
              <option value="2" <?php if ($persediaanfilter == 2) { echo 'selected'; }?> >PERSEDIAAN UMUM</option>
              <option value="3" <?php if ($persediaanfilter == 3) { echo 'selected'; }?> >PERSEDIAAN SPAREPARTS - FACTORY SUPPLIES</option>
              <option value="4" <?php if ($persediaanfilter == 4) { echo 'selected'; }?> >PERSEDIAAN MESIN</option>			  
            </select>            
      </div>
      <div class='col-md-3'>
          <div>
          <br>
              <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>              
          </div>         
      </div>

   </div>
    </form>
  </div>

  <div class="box-body">
    <table id="data_masteritem" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>ID</th>
        <th>Item Code</th>
		<th>Mapping Persediaan</th>
        <th>Description</th>
        <th>Color</th>
        <th>Size</th>
        <th>Item Type</th>
        <th>Non Aktif</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
<script src="../../plugins/jQuery/jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#data_masteritem').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        "columnDefs": [
            { "orderable": false, "targets": "_all" } // Matikan sorting untuk SEMUA kolom
        ],        
        "ajax": {
            "url": "server_masteritem.php",
            "type": "POST",
            "data": function(d) {
                d.persediaan = $('#persediaanfilter').val();
                d.user = '<?php echo $user; ?>';
            }
        }
    });
});
</script>
<script>
$('#persediaanfilter').change(function(){
    $('#data_masteritem').DataTable().ajax.reload();
});
</script>
<script>
$(document).on('click', '.coa-popup', function(e) {
    e.preventDefault();
    $('#coa_val_prod').text($(this).data('prod') || '-');
    $('#coa_val_spr').text($(this).data('spr') || '-');
    $('#coa_val_sga').text($(this).data('sga') || '-');
    $('#coa_val_ssl').text($(this).data('ssl') || '-');
    $('#coaModal').modal('show');
});
</script>
  </div>
</div>

<!-- Modal Preview Gambar -->
<div class="modal fade" id="imgModal" tabindex="-1" role="dialog" aria-labelledby="imgModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="imgModalLabel">Preview Gambar</h4>
      </div>
      <div class="modal-body text-center">
        <img id="imgPreview" src="" style="max-width:100%; max-height:80vh; object-fit:contain;">
      </div>
    </div>
  </div>
</div>

<!-- Modal COA -->
<style>
#coaModal .modal-header {
  background: #3c8dbc;
  color: #fff;
  border-radius: 3px 3px 0 0;
  padding: 12px 18px;
}
#coaModal .modal-header .close {
  color: #fff;
  opacity: 0.8;
  font-size: 20px;
  margin-top: 2px;
}
#coaModal .modal-title {
  font-size: 15px;
  font-weight: 700;
  letter-spacing: 0.3px;
}
#coaModal .modal-body {
  padding: 20px;
  background: #f9f9f9;
}
#coaModal .coa-table {
  width: 100%;
  border-collapse: collapse;
}
#coaModal .coa-table tr {
  border-bottom: 1px solid #e8e8e8;
}
#coaModal .coa-table tr:last-child {
  border-bottom: none;
}
#coaModal .coa-table td {
  padding: 10px 12px;
  font-size: 13px;
  vertical-align: middle;
}
#coaModal .coa-label {
  width: 42%;
  color: #555;
  font-weight: 600;
}
#coaModal .coa-badge {
  display: inline-block;
  background: #fff;
  border: 1px solid #d0d0d0;
  border-radius: 4px;
  padding: 3px 10px;
  font-size: 12px;
  color: #333;
  word-break: break-word;
}
#coaModal .coa-icon {
  display: inline-block;
  width: 26px;
  height: 26px;
  line-height: 26px;
  text-align: center;
  border-radius: 50%;
  color: #fff;
  font-size: 11px;
  margin-right: 8px;
  vertical-align: middle;
}
#coaModal .icon-prod     { background: #3c8dbc; }
#coaModal .icon-sup-prod { background: #00a65a; }
#coaModal .icon-gen      { background: #f39c12; }
#coaModal .icon-sell     { background: #dd4b39; }
</style>
<div class="modal fade" id="coaModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border:none; box-shadow: 0 5px 20px rgba(0,0,0,.2); border-radius:4px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-book" style="margin-right:7px;"></i>COA Item</h4>
      </div>
      <div class="modal-body">
        <table class="coa-table">
          <tr>
            <td class="coa-label">
              <span class="coa-icon icon-prod"><i class="fa fa-industry"></i></span>Production
            </td>
            <td><span class="coa-badge" id="coa_val_prod">-</span></td>
          </tr>
          <tr>
            <td class="coa-label">
              <span class="coa-icon icon-sup-prod"><i class="fa fa-cogs"></i></span>Sup. Production
            </td>
            <td><span class="coa-badge" id="coa_val_spr">-</span></td>
          </tr>
          <tr>
            <td class="coa-label">
              <span class="coa-icon icon-gen"><i class="fa fa-building"></i></span>Sup. Gen. &amp; Adm.
            </td>
            <td><span class="coa-badge" id="coa_val_sga">-</span></td>
          </tr>
          <tr>
            <td class="coa-label">
              <span class="coa-icon icon-sell"><i class="fa fa-tag"></i></span>Sup. Selling
            </td>
            <td><span class="coa-badge" id="coa_val_ssl">-</span></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("general_req","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");
if (isset($_GET['idd'])) {$id_item=$_GET['idd'];} else {$id_item="";}
if (isset($_GET['id'])) {$id_reqno=$_GET['id'];} else {$id_reqno="";}
if ($id_reqno=="") 
{ echo "<script>alert('Request Tidak Ditemukan'); window.location.href='../others/?mod=1';</script>"; }
# COPAS EDIT
if ($id_item=="")
{ $id_item = "";
  $id_supplier = "";
  $itemdesc = "";
  $qty = "";
  $unit = "";
  $curr = "";
  $price = "";
}
else
{ $query = mysql_query("SELECT * FROM reqnon_item where id='$id_item' ");
  $data = mysql_fetch_array($query);
  $id_item2 = $data['id_item'];
  $id_supplier = $data['id_supplier'];
  $qty = $data['qty'];
  $unit = $data['unit'];
  $curr = $data['curr'];
  $price = $data['price'];
}
if($id_reqno!="")
{ $userpo=flookup("username","reqnon_header","id='$id_reqno'"); }
else
{ $userpo=""; }
$purch = flookup("gen_req_purch","userpassword","username='$user'");
if($purch=="1" and $userpo!=$user) { $read="readonly"; } else { $read=""; }
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
  function validasi()
  { var id_item = document.form.txtid_item.value;
    var qty = document.form.txtqty.value;
    var unit = document.form.txtunit.value;
    var price = document.form.txtprice.value;
    if (id_item == '') { document.form.txtid_item.focus(); swal({ title: 'Item Code Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (qty == '') { document.form.txtqty.focus(); swal({ title: 'Qty Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (unit == '') { document.form.txtunit.focus(); swal({ title: 'Unit Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (price == '') { document.form.txtprice.focus(); swal({ title: 'Price Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (price < '0') { document.form.txtprice.focus(); swal({ title: 'Price Tidak Boleh Kurang dari Nol', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";

# END COPAS VALIDASI
# COPAS ADD
if ($mod=="1a") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_reqno_d.php?mod=<?php echo $mod; ?>&id=<?php echo $id_reqno; ?>&idd=<?php echo $id_item; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>                
          <img id="output" width="270px" height="500px">  
          <script>
            var loadFile = function(event) 
            { var id_itemnya = document.form.txtid_item.value;
              var output = document.getElementById('output');
              jQuery.ajax
              ({  
                url: "../marketting/ajax_act_cost.php?mdajax=get_nama_file",
                method: 'POST',
                data: {id_itemnya: id_itemnya},
                dataType: 'json',
                success: function(response)
                { output.src = "upload_files/"+response[0]; },
                error: function (request, status, error) 
                { alert(request.responseText); },
              });
            };
          </script>
        </div>
        <div class='col-md-3'>                
          <div class='form-group'>
            <label>Item Code *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_item' id='txtid_item' 
              onchange='loadFile(event)' >
              <?php 
                $sql = "select id_item isi,concat(IFNULL(goods_code,''),' ',IFNULL(itemdesc,''),' ',IFNULL(color,''),' ',IFNULL(size,'')) tampil 
                  from masteritem where mattype in ('N','M') and non_aktif='N'";
                IsiCombo($sql,$id_item2,'Pilih Item Code');
              ?>
            </select>
          </div>

          <div class='form-group'>
            <label>Supplier</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_supplier'>
              <?php 
                $sql = "select id_supplier isi,supplier tampil 
                  from mastersupplier where tipe_sup='S'";
                IsiCombo($sql,$id_supplier,'Pilih Supplier');
              ?>
            </select>
          </div>        

          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>Qty *</label>
            <input type='number' class='form-control' name='txtqty' 
              placeholder='Masukkan Qty' step='any' value='<?php echo $qty;?>' <?php echo $read; ?> >
          </div>          
          <div class='form-group'>
            <label>Unit *</label>
            <select class='form-control select2' style='width: 100%;' name='txtunit'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil 
                  from masterpilihan where kode_pilihan='Satuan'";
                IsiCombo($sql,$unit,'Pilih Unit');
              ?>
            </select>
          </div>
        </div>
        <div class='col-md-3'>          

          <div class='form-group'>
            <label>Currency *</label>
            <select class='form-control select2' style='width: 100%;' name='txtcurr'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil 
                  from masterpilihan where kode_pilihan='Curr'";
                IsiCombo($sql,$curr,'Pilih Currency');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label>Price *</label>
            <input type='number' class='form-control' name='txtprice' step='any' placeholder='Masukkan Price' value='<?php echo $price;?>' >
          </div>

          <!-- <button type='submit' name='submit' class='btn btn-primary'>Simpan</button> -->
        </div>
      </form>
    </div>
  </div>
</div><?php 
# END COPAS ADD
} ?>
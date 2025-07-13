<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$akses = flookup("prorata_po","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='../pur/?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var pono = document.form.txtpono.value;
    var poitem = document.form.txtpoitem.value;
    var qtypo = document.form.txtqtypo.value;
    var qtyprorate = document.form.txtqtyprorate.value;
    if (pono == '') { swal({ title: 'PO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (poitem == '') { swal({ title: 'Item # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (qtypo == '') { document.form.txtqtypo.focus(); swal({ title: 'Qty PO Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (qtyprorate == '') { document.form.txtqtyprorate.focus(); swal({ title: 'Qty Pro Rata Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (isNaN(qtyprorate)) { document.form.txtqtyprorate.focus(); swal({ title: 'Qty Pro Rata Tidak Valid', <?php echo $img_alert; ?> }); valid = false;}
    else if (Number(qtyprorate) <= Number(qtypo)) { document.form.txtqtyprorate.focus(); swal({ title: 'Qty Pro Rata Tidak Boleh Kurang Dari Qty PO', <?php echo $img_alert; ?> }); valid = false;}
    else valid = true;
    return valid;
    exit;
  };
</script>
<?php
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getDetList()
  { var id_po = document.form.txtpono.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=get_list_item_prorata',
        data: {id_po: id_po},
        async: false
    }).responseText;
    if(html)
    { $("#txtpoitem").html(html); }
  };
  function getQtyPO()
  { var id_po = document.form.txtpono.value;
    var id_item_po = document.form.txtpoitem.value;
    jQuery.ajax
    ({  
      url: "ajax_po.php?modeajax=get_qty_po",
      method: 'POST',
      data: {id_po: id_po,id_item_po: id_item_po},
      dataType: 'json',
      success: function(response)
      {
        $('#txtqtypo').val(response[0]);
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };  
</script>
<?php
# COPAS ADD
?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_prorata.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>                
          <div class='form-group'>
            <label>PO # *</label>
            <select class='form-control select2' style='width: 100%;' name='txtpono' onchange='getDetList()'>
              <?php 
                $sql = "select a.id isi,concat(a.pono,'|',ms.supplier) tampil from po_header a inner join 
                  (select id_po,count(distinct id_jo) t_jo from po_item group by id_po) tmp_det 
                  on a.id=tmp_det.id_po inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
                  where tmp_det.t_jo>1";
                IsiCombo($sql,'','Pilih PO #');
              ?>
            </select>
          </div>          
          <div class='form-group'>
            <label>Item # *</label>
            <select class='form-control select2' style='width: 100%;' name='txtpoitem' 
              id='txtpoitem' onchange="getQtyPO()">
            </select>
          </div>
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>Qty PO *</label>
            <input type='text' class='form-control' name='txtqtypo' id='txtqtypo' placeholder='Masukkan Qty PO' readonly>
          </div>        
          <div class='form-group'>
            <label>Qty Pro Rata *</label>
            <input type='text' class='form-control' name='txtqtyprorate' placeholder='Masukkan Qty Pro Rata'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
# END COPAS ADD
?>
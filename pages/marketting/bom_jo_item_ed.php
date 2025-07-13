<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_jo=$_GET['id'];
$id_item=$_GET['idd'];
$rs=mysql_fetch_array(mysql_query("select * from bom_jo_item where id_jo='$id_jo' and id='$id_item'"));
$cons=$rs['cons'];
$unit=$rs['unit'];
$id_gen=$rs['id_item'];
# COPAS EDIT
$cek=flookup("count(*)","po_item","id_jo='$id_jo' and id_gen='$id_gen'");
if ($cek!="0")
{ $_SESSION['msg'] = 'XData Tidak Bisa Diubah Karena Sudah Dibuat PO'; 
  echo "<script>window.location.href='../marketting/?mod=14&id=$id_jo';</script>";
  exit;
}
# END COPAS EDIT
?>
<!--COPAS VALIDASI BUANG ELSE di IF pertama-->
<script type='text/javascript'>
  function validasi()
  { var jenitem = document.form.txtJItem.value;
    var itemcontents = document.form.txtItemCS.value;
    var cons = document.form.txtcons.value;
    var unit = document.form.txtunit.value;
    var jmlsize = document.form.txtroll.value;
    if (itemcontents == '') 
    { swal({ title: 'Item Contents Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (cons == '') 
    { document.form.txtcons.focus();
      swal({ title: 'Cons Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (unit == '') 
    { swal({ title: 'Unit Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (jenitem == 'Material' && jmlsize == '') 
    { swal({ title: 'Rule BOM Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else {valid = true;}
    var inps = document.form.getElementsByClassName('jmlclass');
    for (var i = 0; i < inps.length; i++) 
    { if (inps[i].value == '') 
      { swal({ title: 'Item Detail Tidak Boleh Kosong', <?php echo $img_alert; ?>});
        valid = false;
      }
    }
    return valid;
    exit;
  }
</script>
<!--END COPAS VALIDASI-->
<script type="text/javascript">
  function getListData()
  { var id_contents = document.form.txtItemCS.value;
    var id_jo = <?php echo $id_item; ?>;  
    var rulebom = document.form.txtroll.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bom_jo.php?modeajax=view_list_size',
        data: {rulebom: rulebom,id_contents: id_contents,id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
  function getRule(cri_item)
  { var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bom_jo.php?modeajax=view_rule',
        data: "cri_item=" +cri_item,
        async: false
    }).responseText;
    if(html)
    { $("#cborule").html(html); }
  }
  function getItemCS(cri_item)
  { var id_jo = <?php echo $id_item; ?>;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bom_jo.php?modeajax=view_list_cost',
        data: {cri_item: cri_item,id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    { $("#txtItemCS").html(html); }
  }
</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_bom_jo_it_ed.php?mod=$mod&id=$id_jo&idd=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Item *</label>";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtJItem' onchange='getItemCS(this.value)'>";
              $sql = "SELECT id_gen isi,itemdesc tampil
                from masteritem where id_gen='$id_gen'";
              IsiCombo($sql,$id_gen,'Pilih Jenis Item');
            echo "</select>";
          echo "</div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Cons *</label>
              <input type='text' class='form-control' name='txtcons'  
                placeholder='Masukkan Cons' value='$cons'>
            </div>
          </div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Unit *</label>
              <select class='form-control select2' style='width: 100%;' 
                name='txtunit'>";
                $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                  masterpilihan where kode_pilihan='Satuan'";
                IsiCombo($sql,$unit,'Pilih Unit');
              echo "
              </select>
            </div>
          </div>";
        echo "</div>";
        echo "<div class='box-body'>";
         echo "<div id='detail_item'></div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>
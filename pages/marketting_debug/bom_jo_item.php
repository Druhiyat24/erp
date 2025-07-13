<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item=$_POST['txtid_pre_cost'];
$cekapp=flookup("app","jo","id='$id_item'");

# COPAS EDIT

# END COPAS EDIT
?>
<!--COPAS VALIDASI BUANG ELSE di IF pertama-->
<script type='text/javascript'>
  function validasi()
  { var jenitem = document.form.txtJItem.value;
    var itemcontents = document.form.txtItemCS.value;
    var itemkat = document.form.txtkat.value;
    var cons = document.form.txtcons.value;
    var unit = document.form.txtunit.value;
    var panel = document.form.txtpanel.value;
    var jmlsize = document.form.txtroll.value;
    // var datakos = 0;
    // var itemisi = document.form.getElementsByClassName('jmlclass');
    // for (var i = 0; i < itemisi.length; i++) 
    // { if (itemisi[i].value!='') 
    //   { 
    //     datakos = datakos + 1;
    //   }
    // }
    <?php echo "var cekapp = '".$cekapp."';"; ?>
    if (cekapp == 'A') 
    { swal({ title: 'BOM Tidak Bisa Diubah', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (itemcontents == '') 
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
    else if (panel == '' && itemkat=="FABRIC") 
    { swal({ title: 'Nama Panel Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (jenitem == 'M' && jmlsize == '') 
    { swal({ title: 'Rule BOM Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (Number(datakos) == 0) 
    { swal({ title: 'Item Detail Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else {valid = true;}
    return valid;
    exit;
  }
</script>
<!--END COPAS VALIDASI-->
<script type="text/javascript">
  function getListData()
  { var j_item = document.form.txtJItem.value;
    var id_contents = document.form.txtItemCS.value;
    var id_jo = <?php echo $id_item; ?>;  
    var rulebom = document.form.txtroll.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bom_jo.php?modeajax=view_list_size',
        data: {j_item: j_item,rulebom: rulebom,id_contents: id_contents,id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
    $(".select2").select2();
    
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
    var id_jo = <?php echo $id_item; ?>;
    var cri_item = document.form.txtItemCS.value;
    jQuery.ajax
    ({  
      url: "ajax_bom_jo.php?modeajax=view_cons",
      method: 'POST',
      data: {cri_item: cri_item,id_jo: id_jo},
      dataType: 'json',
      success: function(response)
      { $('#txtcons').val(response[0]); 
        $('#txtkat').val(response[1]);
        var $newOption = $("<option selected='selected'></option>").val(response[2]).text(response[2])
        $("#cbounit").append($newOption).trigger('change');
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
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
      echo "<form method='post' name='form' action='s_bom_jo_it.php?mod=$mod&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Jenis Item *</label>";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtJItem' onchange='getItemCS(this.value)'>";
              $sql = "SELECT if(nama_pilihan='Material','M','P') isi,if(nama_pilihan='Others','Manufacturing',nama_pilihan) tampil
                from masterpilihan where kode_pilihan='J_BOM_Det'";
              IsiCombo($sql,'','Pilih Jenis Item');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Item Contents *</label>";
            echo "<input type='hidden' id='txtkat'>";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtItemCS' id='txtItemCS' onchange='getRule()'>";
            echo "</select>";
          echo "</div>";
          echo "
          <div class='row'>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Cons *</label>
                <input type='text' class='form-control' name='txtcons' id='txtcons'  
                  placeholder='Masukkan Cons'>
              </div>
            </div>";
            echo "
            <div class='col-md-6'>
              <div class='form-group'>
                  <label>Unit *</label>
                  <select class='form-control select2' style='width: 100%;' 
                    name='txtunit' id='cbounit'>";
                  $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Satuan'";
                  IsiCombo($sql,$unit,'Pilih Unit');
                  echo "
                  </select>
              </div>
            </div>
          </div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Destination *</label>";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtdest[]' multiple>";
              $sql = "select dest isi, dest tampil 
                from jo_det a inner join so_det s on a.id_so=s.id_so 
                where a.id_jo='$id_item' group by dest";
              IsiCombo($sql,'','');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>SKU</label>";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtsku[]' multiple>";
              $sql = "select sku isi, sku tampil 
                from jo_det a inner join so_det s on a.id_so=s.id_so 
                where a.id_jo='$id_item' and ifnull(sku,'')!='' group by sku";
              IsiCombo($sql,'','');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Panel</label>";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtpanel'>";
              $sql = "select id isi, concat(nama_panel,' ',description) tampil 
                from masterpanel order by v_codepanel";
              IsiCombo($sql,'','Pilih Panel');
            echo "</select>";
          echo "</div>";  
          echo "<div class='form-group'>";
            echo "<label>Rule BOM *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtroll' id='cborule' onchange='getListData(this.value)'>";
            echo "</select>";
          echo "</div>";
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
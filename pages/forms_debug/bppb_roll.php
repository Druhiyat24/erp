<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item="";

# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  {
    var bpbno = document.form.txtsjno.value;
    var jmlbpb = document.form.txtqtysj.value;
    var pilih = 0;
    var jmlpil = 0;
    var cekpil = document.form.getElementsByClassName('bchkclass').length;
    var chks = document.form.getElementsByClassName('bchkclass');
    var qtys = document.form.getElementsByClassName('qtyclass');
    pilih = chks.length;
    // for (var i = 0; i < chks.length; i++) 
    // { if (chks[i].checked) 
    //   { 
    //     pilih = pilih + 1;
    //     if (Number(qtys[i].value) > 0)
    //     { jmlpil = jmlpil + Number(qtys[i].value); }
    //   }
    // }
    if (bpbno == '') { swal({ title: 'No. SJ Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false; }
    else if (Number(pilih) == 0) { swal({ title: cekpil, <?php echo $img_alert; ?> });valid = false; }
    // else if (Number(jmlpil) != Number(jmlbpb)) { swal({ title: jmlpil, <?php echo $img_alert; ?> });valid = false; }
    else {valid = true;}
    return valid;
    exit;
  };
  function getQtyBPB(cri_item)
  { jQuery.ajax
    ({  url: 'ajax2.php?modeajax=cari_qty_sj',
        method: 'POST',
        data: {cri_item: cri_item},
        dataType: 'json',
        success: function(response)
        { $('#txtqtysj').val(response[0]);
          $('#txtnorak').val(response[1]);
          $('#txtunit').val(response[2]);
          $('#txtunitkonv').val(response[3]);
          $('#txtqtykonv').val(response[4]);  
        },
        error: function (request, status, error) {
            alert(request.responseText);
        },
    });
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bpb_jo.php?modeajax=view_list_loc',
        data: {cri_item: cri_item},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
  function getListBPB()
  { var cri_item = document.form.txttglcut.value;
    var mat_nya = document.form.txtmat.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax2.php?modeajax=cari_list_bppb',
        data: {cri_item: cri_item,mat_nya: mat_nya},
        async: false
    }).responseText;
    if(html)
    {
        $("#cbosj").html(html);
    }
  };
</script>
<?php 
# END COPAS VALIDASI
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_bppb_roll.php?mod=$mod' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Type Material *</label>
              <select class='form-control select2' style='width: 100%;' name='txtmat' id='cbomat' 
                onchange='getListBPB()'>";
              $sql="select matclass isi,matclass tampil from masteritem where matclass!='' 
                group by matclass";
              IsiCombo($sql,"","Pilih Material");
              echo "
              </select>  
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Tgl. SJ *</label>
              <input type='text' class='form-control' name='txttglcut' id='datepicker1' 
                placeholder='Masukkan Filter' onchange='getListBPB()'>
            </div>
          </div>
          <div class='form-group'>
            <label>Nomor SJ *</label>
            <select class='form-control select2' style='width: 100%;' name='txtsjno' id='cbosj' 
              onchange='getQtyBPB(this.value)'>
            </select>
          </div>
        </div>";
        echo "<div class='col-md-3'>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>$c31 SJ</label>
              <input type='text' class='form-control' id='txtqtysj' name='txtqtysj' readonly>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Unit SJ</label>
              <input type='text' class='form-control' id='txtunit' name='txtunit' readonly>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>$c31 Konversi</label>
              <input type='text' class='form-control' id='txtqtykonv' name='txtqtykonv' readonly>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Unit Konversi</label>
              <input type='text' class='form-control' id='txtunitkonv' name='txtunitkonv' readonly>
            </div>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Nomor Rak</label>
            <input type='text' class='form-control' id='txtnorak' name='txtnorak' readonly>
          </div>
        </div>";
        echo "</div>";
        echo "<div class='box-body'>";
          echo "<div id='detail_item'></div>";
          echo "</div>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>
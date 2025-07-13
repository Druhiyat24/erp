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
  { var bpbno = document.form.cbosj.value;
    var jmlroll = document.form.txtroll.value;
    var btsbpb = Number(document.form.txtqtybpb.value);
    var qtydet = 0;
    var detkosong = 0;
    var qtyo = document.form.getElementsByClassName('rollclass');
    var deto = document.form.getElementsByClassName('item_detclass');
    for (var i = 0; i < qtyo.length; i++) 
    { qtydet = qtydet + Number(qtyo[i].value); }
    for (var i = 0; i < deto.length; i++) 
    { if (deto[i].value == '') 
      { detkosong = Number(detkosong) + 1; break; } 
    }
    if (bpbno == '') { swal({ title: 'BPB Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false; }
    else if (jmlroll == '') { swal({ title: 'Jumlah Detail Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (Number(detkosong) > 0) { swal({ title: 'Item Detail Tidak Noleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (Number(qtydet) != Number(btsbpb)) { swal({ title: 'Qty Detail Tidak Sesuai Qty BPB', <?php echo $img_alert; ?> }); valid = false;}
    else {valid = true;}
    return valid;
    exit;
  }
</script>
<!--END COPAS VALIDASI-->
<script type="text/javascript">
  function getQtyBPB(cri_item)
  { jQuery.ajax
    ({  url: 'ajax2.php?modeajax=cari_qty_bpb',
        method: 'POST',
        data: {cri_item: cri_item},
        success: function(response){
            jQuery('#txtqtybpb').val(response);  
        },
        error: function (request, status, error) {
            alert(request.responseText);
        },
    });
  }

  function getListBPB(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=cari_list_bpb_det',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#cbosj").html(html);
      }
  }
  function getListData(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=view_list_detail',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#detail_item").html(html);
      }
  }
</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data_bpb_det.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Filter Tgl Penerimaan *</label>";
            echo "<input type='text' class='form-control' name='txttglcut' id='datepicker1' 
            placeholder='Masukkan Filter Tgl Penerimaan' onchange='getListBPB(this.value)'>";
          echo "</div>";
          echo "<div class='form-group'>";
          	echo "<label>Nomor BPB *</label>";
          	echo "<select class='form-control select2' style='width: 100%;' name='txtsjno' id='cbosj' onchange='getQtyBPB(this.value)'>";
          	echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c31 Penerimaan</label>";
            echo "<input type='text' class='form-control' id='txtqtybpb' name='txtqtybpb' readonly>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Jumlah Detail *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtroll' onchange='getListData(this.value)'>";
              echo "<option value='' disabled selected>Pilih Jumlah Detail</option>";
              for ($x = 1; $x <= 100; $x++) 
              { echo "<option value='$x'>$x</option>"; }
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
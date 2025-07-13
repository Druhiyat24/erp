<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$id_item=$_GET['id'];
if (isset($_GET['idd'])) {$id_det=$_GET['idd'];} else {$id_det="";}
$unit=flookup("unit","so","id='$id_item'");
# COPAS EDIT
if ($id_det=="")
{ $deldate=flookup("ac.deldate","so inner join act_costing ac on so.id_cost=ac.id","so.id='$id_item'");
  $deldate=fd_view($deldate);
  $dest="";
  $color="";
  $sku="";
  $notes="";
  $barcode="";
  $price="";
}
else
{ $rs=mysql_fetch_array(mysql_query("select * from so_det where id='$id_det'"));
  $deldate=fd_view($rs['deldate_det']);
  $dest=$rs['dest'];
  $color=$rs['color'];
  $sku=$rs['sku'];
  $notes=$rs['notes'];
  $barcode=$rs['barcode'];
  $qty=$rs['qty'];
  $price=$rs['price'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
$cek_jo=flookup("count(*)","jo_det","id_so='$id_item'");
$dateskrg=date('Y-m-d');
$cek2=flookup("so_no","unlock_so","id_so='$id_item' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "
    var cek_jo = $cek_jo;
    var dest = document.form.txtdest.value;
    var color = document.form.txtcolor.value;
    var deldate = document.form.txtdeldate.value;
    var cek_unlock = '".$cek2."';
    var szkos = 0;
    var qtykos = 0;
    var szs = document.form.getElementsByClassName('rollclass');
    var qtys = document.form.getElementsByClassName('jmlclass');
    for (var i = 0; i < qtys.length; i++) 
    { if (qtys[i].value == '')
      { qtykos = qtykos + 1; }
    }
    for (var i = 0; i < szs.length; i++) 
    { if (szs[i].value == '')
      { szkos = szkos + 1; }
    }";
    if ($id_det=="") 
    { echo "var jmlsize = document.form.txtroll.value;"; 
      echo "var unit = document.form.txtunit.value;";
    }
    ?>
    <?php
    echo "
    if (cek_jo != '' && cek_unlock == '') 
      { swal({ title: 'Data Tidak Bisa Dirubah Karena SO Sudah Dibuat Worksheet', $img_alert }); valid = false;}
    else if (deldate == '') 
      { swal({ title: 'Delv. Date Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (dest == '') 
      { swal({ title: 'Destination Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (color == '') 
      { swal({ title: 'Color Tidak Boleh Kosong', $img_alert }); valid = false;}";
    if ($id_det=="") 
    { echo "
      else if (jmlsize == '') 
        { swal({ title: 'Jml Size Tidak Boleh Kosong', $img_alert }); valid = false;}
      else if (unit == '') 
        { swal({ title: 'Unit Tidak Boleh Kosong', $img_alert }); valid = false;}
      else if (szkos > 0) 
        { swal({ title: 'Size Tidak Boleh Kosong', $img_alert }); valid = false;}
      else if (qtykos > 0) 
        { swal({ title: 'Qty Tidak Boleh Kosong', $img_alert }); valid = false;}";
    }
    echo "else {valid = true;}";
    echo "return valid;";
    echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getListData()
  {   var id_so = <?php echo $_GET['id']; ?>;
      var cri_item = document.form.txtroll.value;
      var html = $.ajax
      ({  type: "POST",
          url: '../forms/ajax2.php?modeajax=view_list_size',
          data: {cri_item: cri_item, id_so: id_so},
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
      echo "<form method='post' name='form' action='s_sales_ord_det.php?mod=$mod&id=$id_item&idd=$id_det' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>
            <label>Delivery Date *</label>
            <input type='text' class='form-control' name='txtdeldate' id='datepicker1'  
            placeholder='Masukkan Delivery Date' value='$deldate'>
          </div>";
          echo "<div class='form-group'>
            <label>Destination *</label>
            <input type='text' class='form-control' name='txtdest'  
            placeholder='Masukkan Destination' value='$dest'>
          </div>";
          echo "<div class='form-group'>
            <label>Color *</label>
            <input type='text' class='form-control' name='txtcolor'  
            placeholder='Masukkan Color' value='$color'>
          </div>";
          echo "<div class='form-group'>";
            echo "<label>SKU</label>";
            echo "<input type='text' class='form-control' name='txtsku'  
            placeholder='Masukkan SKU' value='$sku'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Barcode</label>";
            echo "<input type='text' class='form-control' name='txtbarcode'  
            placeholder='Masukkan Barcode' value='$barcode'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Notes</label>";
            echo "<input type='text' class='form-control' name='txtnotes'  
            placeholder='Masukkan Notes' value='$notes'>";
          echo "</div>";
          echo "<div class='form-group'>";
            if ($id_det=="")
            { echo "<label>Unit *</label>";
              echo "
                <input type='hidden' name='txtunit' value='$unit'>
                <select disabled class='form-control select2' style='width: 100%;' 
                name='txtunit2'>";
                $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                  masterpilihan where kode_pilihan='Satuan'";
                IsiCombo($sql,$unit,'Pilih Unit');
              echo "</select>";
            }
            else
            { echo "
              <label>Qty *</label>
              <input type='text' class='form-control' name='txtqty' 
                placeholder='Masukkan Qty' value='$qty'>";
            }
          echo "</div>";
          if($id_det!="")
          { echo "<div class='form-group'>";
              echo "
                <label>Price *</label>
                <input type='text' class='form-control' name='txtprice' 
                  placeholder='Masukkan Price' value='$price'>";                
            echo "</div>";  
          }
          echo "<div class='form-group'>";
            if ($id_det=="")
            { #$ceksize=flookup("count(distinct size)","so_det","id_so='$_GET[id]' and cancel='N'");
              $ceksize=1;
              echo "<label>Number Of Size *</label>";
              echo "<select class='form-control select2' style='width: 100%;' name='txtroll' onchange='getListData()'>";
                echo "<option value='' disabled selected>Number Of Size</option>";
                if ($ceksize>0)
                { for ($x = $ceksize; $x <= 100; $x++) 
                  { echo "<option value='$x'>$x</option>"; }
                }
                else
                { for ($x = 1; $x <= 100; $x++) 
                  { echo "<option value='$x'>$x</option>"; }
                }
              echo "</select>";
            }
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
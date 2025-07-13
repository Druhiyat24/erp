<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item="";
$akses=flookup("bom_erp","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
<script type='text/javascript'>
  function validasi()
  { var cust = document.form.cbocust.value;
    var seas = document.form.cboseason.value;
    var styleno = document.form.txtstyle.value;
    var styledesc = document.form.txtstyledesc.value;
    var jcol = document.form.txtjcolor.value;
    var jsize = document.form.txtjsize.value;
    if (cust == '') { alert('Customer tidak boleh kosong');valid = false;}
    else if (seas == '') { alert('Season tidak boleh kosong');valid = false;}
    else if (styleno == '') { alert('Style # tidak boleh kosong');valid = false;}
    else if (styledesc == '') { alert('Deskripsi Style tidak boleh kosong');valid = false;}
    else if (jcol == '') { alert('Jumlah Color tidak boleh kosong');valid = false;}
    else if (jsize == '') { alert('Jumlah Size tidak boleh kosong');valid = false;}
    else {valid = true;}
    return valid;
    exit;
  }
</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getListData(cri_item)
  {   var jcol = document.form.txtjcolor.value;
      var jsize = document.form.txtjsize.value;
      var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=view_list_color',
          data: {cri_size: jsize,cri_col: jcol},
          async: false
      }).responseText;
      if(html)
      {	 
          $("#detail_item").html(html);
      }
  };
</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data_bom.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
          	echo "<label>Customer *</label>";
          	echo "<select class='form-control select2' style='width: 100%;' name='cbocust'>";
          	IsiCombo("select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier","","Pilih Customer");
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Season *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='cboseason'>";
            IsiCombo("select id_season isi,season tampil from masterseason order by season","","Pilih Season");
            echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Style #</label>";
            echo "<input type='text' class='form-control' name='txtstyle' placeholder='Masukan Style #'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Deskripsi</label>";
            echo "<input type='text' class='form-control' name='txtstyledesc' placeholder='Masukan Deskripsi Style'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Jumlah Color Garment *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtjcolor' id='txtjcolor' onchange='getListData(this.value)'>";
              echo "<option value='' disabled selected>Pilih Jumlah Color Garment</option>";
              for ($x = 1; $x <= 100; $x++) 
              { echo "<option value='$x'>$x</option>"; }
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Jumlah Size Garment *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtjsize' onchange='getListData(this.value)'>";
              echo "<option value='' disabled selected>Pilih Jumlah Size Garment</option>";
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
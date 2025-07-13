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
    var buyer = document.form.txtbuyer.value;
    if (buyer == '') { swal({ title: 'Buyer Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false; }
    else {valid = true;}
    return valid;
    exit;
  };
</script>
<?php 
# END COPAS VALIDASI
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='../marketting/?mod=vclose_bhnbaku' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Buyer *</label>
            <select class='form-control select2' style='width: 100%;' name='txtbuyer' id='cbobuyer'>";
            $sql1="select id_supplier isi, supplier tampil from mastersupplier where tipe_sup = 'C' order by supplier asc";
            IsiCombo($sql1,"","Pilih Buyer");
            echo "
            </select>  
          </div>          
          <button type='submit' class='btn btn-primary'>Check</button>
        </div>";
        echo "<div class='box-body'>";
         echo "<div id='detail_item'></div>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>
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
    var wsno = document.form.txtws.value;
    if (wsno == '') { swal({ title: 'No. WS Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false; }
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
      echo "<form method='post' name='form' action='../marketting/?mod=vclose' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>WS *</label>
            <select class='form-control select2' style='width: 100%;' name='txtws' id='cbows'>";
            // $sql="select a.id_jo isi,concat(ac.kpno,'|',ac.styleno) tampil 
            //   from bpb_roll_h a inner join jo_det jod on a.id_jo=jod.id_jo 
            //   inner join so on jod.id_so=so.id 
            //   inner join act_costing ac on so.id_cost=ac.id 
            //   inner join bpb_roll br on a.id=br.id_h 
            //   where br.id_rak_loc is null group by a.id_jo";
            $sql="select jod.id_jo isi,concat(ac.kpno,'|',ac.styleno) tampil 
              from jo_det jod inner join so on jod.id_so=so.id 
              inner join act_costing ac on so.id_cost=ac.id 
              group by jod.id_jo";
            IsiCombo($sql,"","Pilih WS");
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
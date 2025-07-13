<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item="";
$id_h = $_GET['id'];

$where = "and br.id_rak_loc is null";
$display = "";
if(ISSET($_GET['vi'])){
$where = "";	
$display = "display:none";
$readonly = "readonly";	
}
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
      echo "<form method='post' name='form' action='save_data_bpb_roll_ed.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='box-body'>";
          echo "<table id='examplefix' width='100%' style='font-size:12px;'>";
            echo "
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Deskripsi</th>
                  <th>#</th>
                  <th>Lot #</th>
                  <th>Qty</th>
                  <th>Qty FOC</th>
                  <th>Unit</th>
                  <th>Barcode</th>
                  <th>Temp Rak #</th>
                </tr>
              </thead>
              <tbody>";
              $sql="select br.id,br.id_h,roll_no,lot_no,roll_qty,roll_foc,br.unit,
                concat(kode_rak,' ',nama_rak) raknya,br.barcode,mi.goods_code,mi.itemdesc from bpb_roll br inner join 
                bpb_roll_h brh on br.id_h=brh.id 
                inner join master_rak mr on br.id_rak=mr.id 
                inner join masteritem mi on brh.id_item=mi.id_item where 
                brh.id='$id_h' $where ";
             // echo $sql;  
              $i=1;
              $query=mysql_query($sql);
              while($data=mysql_fetch_array($query))
              { $x = $data['id']."|".$data['id_h'];
                echo "
                  <tr>
                    <td>$data[goods_code]</td>
                    <td>$data[itemdesc]</td>
                    <td>$data[roll_no]</td>
                    <td>$data[lot_no]</td>
                    <td>
                      <input type='text' size='5' $readonly name='txtqtyact[$x]' id='txtqtyact' class='txtqtyactclass' 
                        value='$data[roll_qty]'>
                    </td>
                    <td>
                      <input type='text' size='5' $readonly name='txtqtyfoc[$x]' id='txtqtyfoc' class='txtqtyfpcclass' 
                        value='$data[roll_foc]'>
                    </td>
                    <td>$data[unit]</td>
                    <td>$data[barcode]</td>
                    <td>$data[raknya]</td>
                  </tr>";
                $i++;
              };
          echo "</tbody></table>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<button type='submit' name='submit' class='btn btn-primary' style='$display'>Simpan</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>
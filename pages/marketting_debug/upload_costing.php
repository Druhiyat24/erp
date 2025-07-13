<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$akses = flookup("upload_costing","userpassword","username='$user'");
$aksescomp = flookup("upload_costing","mastercompany","company!=''");
if ($akses=="0" or $aksescomp=="N") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var filenya = document.form.txtfile.value;
    if (filenya == '') { swal({ title: 'File Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI
# COPAS ADD
?>
<?php if($_GET['mod']=="23") { ?>
  <form method='post' name='form' enctype='multipart/form-data' 
    action='../marketting/?mod=23U' onsubmit='return validasi()'>
    <div class='box'>
      <div class='box-body'>
        <div class='row'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label for='exampleInputFile'>Pilih File</label>
              <input type='file' name='txtfile' accept='.xls'>
            </div>
            <button type='submit' name='submit' class='btn btn-primary'>Upload</button>
          </div>
        </div>
      </div>
    </div>
  </form>
<?php } else { ?>
  <form method='post' name='form2' action='../marketting/?mod=23S'>
    <div class='box'>
      <div class='box-body'>
        <table id="examplefix" class="display responsive" style="width:100%;font-size:10px;">
          <thead>
          <tr>
            <th>cost_date</th>
            <th>notes</th>
            <th>status</th>
            <th>status_order</th>
            <th>buyer_code</th>
            <th>product</th>
            <th>curr</th>
            <th>vat</th>
            <th>cfm_price</th>
            <th>item_code</th>
            <th>price</th>
            <th>cons</th>
            <th>unit</th>
            <th>allow</th>
            <th>material_source</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $sql="select * from act_costing_temp where username='$user' and sesi='$sesi'";
            #echo $sql;
            $query = mysql_query($sql); 
            $no = 1; 
            while($data = mysql_fetch_array($query))
            { echo "<tr>";
                echo "
                <td>".fd_view($data["cost_date"])."</td>
                <td>$data[notes]</td>
                <td>$data[status]</td>
                <td>$data[status_order]</td>
                <td>$data[buyer_code]</td>
                <td>$data[product]</td>
                <td>$data[curr]</td>
                <td>$data[vat]</td>
                <td>$data[cfm_price]</td>
                <td>$data[item_code]</td>
                <td>$data[price]</td>
                <td>$data[cons]</td>
                <td>$data[unit]</td>
                <td>$data[allow]</td>
                <td>$data[material_source]</td>"; 
              echo "</tr>";
              $no++; // menambah nilai nomor urut
            }
            ?>
          </tbody>
        </table>
        <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
      </div>
    </div>
  </form>
<?php } 
# END COPAS ADD
?>
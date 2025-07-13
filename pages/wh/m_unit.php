<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI

# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];

?>
<?php if ($mod == "m_unit") {
  if (isset($_POST['submit_excel'])) //KLIK SUBMIT
  {
    $master_name = "UNIT";
    echo "<script>
  window.open ('?mod=laporan_master_excel&master_name=$master_name&dest=xls', '_blank');
    </script>";
  } else {
  }
?>
  <div class="box">
    <div class='row'>
      <form action="save_m_unit.php?mod=simpan_data" method="post">
        <div class="box-header">

          <div class='col-md-3'>
            <label>Kode Unit : </label>
            <input type='text' class='form-control' id='txt_kd' name='txt_kd' placeholder='Masukkan Kode Unit' autocomplete='off' required>
          </div>
          <div class='col-md-3'>
            <label>Nama Unit : </label>
            <input type='text' class='form-control' id='txt_nm' name='txt_nm' placeholder='Masukkan Nama Unit' autocomplete='off' required>
          </div>
          <div class='col-md-1'>
            <div style="padding:4px">
              <br>
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>
        </div>
    </div>
    </form>


    <div class="box-body">
      <form method="post">
        <table id="examplefix3" class="display responsive" style="width: 100%;font-size:14px;">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th>Kode Unit</th>
              <th>Nama Unit</th>
              <th>Dibuat</th>
              <th>Tgl. Input</th>
              <th>Status</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("select * from m_unit");
            $no = 1;
            while ($data = mysql_fetch_array($query)) {

              $id = $data['id'];
              $tgl_input = fd_view_dt($data['tgl_input']);

              if ($data['cancel'] == "Y") {
                $fontcol  = "style='color:red;'";
                $status   = "Cancel";
                $icon     = "fa-check-circle-o fa-lg";
                $col      = "style='color:blue;'";
              } else {
                $fontcol  = "";
                $status   = "Aktif";
                $icon     = "fa-ban fa-lg";
                $col      = "style='color:red;'";
              }

              echo "<tr $fontcol>";
              echo "
            <td>$id</td>
            <td>$data[kode_unit]</td>
            <td>$data[nama_unit]</td>
            <td>$data[user_input]</td>
            <td>$tgl_input</td>
            <td>$status</td>
            <td> 
            <a href='?mod=edit_m_unit&id=$id'<i class='fa fa-pencil'></a>
            </td>
            <td>
            <a href='save_m_unit.php?mod=update_status&id=$id'<i class='fa $icon' $col></a>
            </td>
            ";
              echo "</tr>";
              $no++; // menambah nilai nomor urut
            }
            ?>
          </tbody>
        </table>
        <button type='submit' name='submit_excel' class='btn btn-success'>Export</button>
    </div>
    </form>
  </div>

<?php }
?>

<?php if ($mod == "edit_m_unit") {
  $id          = $_GET['id'];
  $querydata   = mysql_query("SELECT * from m_unit where id='$id' limit 1");
  $data        = mysql_fetch_array($querydata);
  $kd         = $data['kode_unit'];
  $nm      = $data['nama_unit'];
  $user_input = $data['user_input'];
  $tgl_input  = fd_view($data['tgl_input']);

?>
  <div class="box">
    <div class='row'>
      <form action="save_m_unit.php?mod=update&id=<?php echo $id; ?>" method="post">
        <div class="box-header">

          <div class='col-md-3'>
            <label>Kode Unit : </label>
            <input type='text' class='form-control' id='txt_kd' name='txt_kd' placeholder='A' autocomplete='off' required value='<?php echo $kd; ?>'>
          </div>
          <div class='col-md-3'>
            <label>Nama Unit : </label>
            <input type='text' class='form-control' id='txt_nm' name='txt_nm' placeholder='1' autocomplete='off' required value='<?php echo $nm; ?>'>
          </div>
          <div class='col-md-1'>
            <div style="padding:4px">
              <br>
              <button type='submit' name='submit' class='btn btn-warning'>Update</button>
            </div>
          </div>
          <div class='col-md-1'>
            <div style="padding:4px">
              <br>
              <a href='?mod=m_unit' button type="button" class="btn btn-primary btn-block"><i class="fa fa-arrow-left"></i></button></a>

            </div>
          </div>
        </div>
    </div>
    </form>


    <div class="box-body">
      <form action="index.php?mod=master_part_exc&dest=xls" method="post">
        <table id="examplefix3" class="display responsive" style="width: 100%;font-size:14px;">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th>Kode Unit</th>
              <th>Nama Unit</th>
              <th>Dibuat</th>
              <th>Tgl. Input</th>
              <th>Status</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("select * from m_unit 
            ORDER BY CASE WHEN id='$id' THEN 0
            ELSE 1 END, id");

            $no = 1;
            while ($data = mysql_fetch_array($query)) {
              // echo $query;
              $id = $data['id'];
              $tgl_input = fd_view_dt($data['tgl_input']);

              if ($data['cancel'] == "Y") {
                $fontcol  = "style='color:red;'";
                $status   = "Cancel";
                $icon     = "fa-check-circle-o fa-lg";
                $col      = "style='color:blue;'";
              } else {
                $fontcol  = "";
                $status   = "Aktif";
                $icon     = "fa-ban fa-lg";
                $col      = "style='color:red;'";
              }

              echo "<tr $fontcol>";
              echo "
            <td>$no</td>
            <td>$data[kode_unit]</td>
            <td>$data[nama_unit]</td>
            <td>$data[user_input]</td>
            <td>$tgl_input</td>
            <td>$status</td>
            <td> 
            <a href='?mod=edit_m_unit&id=$id'<i class='fa fa-pencil'></a>
            </td>
            <td>
            <a href='save_m_unit.php?mod=update_status&id=$id'<i class='fa $icon' $col></a>
            </td>
            ";
              echo "</tr>";
              $no++; // menambah nilai nomor urut
            }
            ?>
          </tbody>
        </table>
        <button type='submit' name='submit' class='btn btn-success'>Export</button>
    </div>
    </form>
  </div>

<?php }
?>



<?php if ($mod == "master_part_exc") {
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=lap_master_part.xls"); //ganti nama sesuai keperluan 
  header("Pragma: no-cache");
  header("Expires: 0");
?>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Master Part</h3>
    </div>
    <div>
      List Master Part
    </div>
    <div class="box-body">
      <table border="1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode Part</th>
            <th>Nama Part</th>
            <th>Tgl Input</th>
            <th>Dibuat</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $sql = "select * from master_part ";
          #echo $sql;
          $query = mysql_query($sql);
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_input = fd_view_dt($data['tgl_input']);
            if ($data['cancel'] == "Y") {
              $fontcol  = "style='color:red;'";
              $status   = "Cancel";
              $icon     = "fa-check-circle-o fa-lg";
              $col      = "style='color:blue;'";
            } else {
              $fontcol  = "";
              $status   = "Aktif";
              $icon     = "fa-ban fa-lg";
              $col      = "style='color:red;'";
            }
            echo "<tr $fontcol>";
            echo "<td>$no</td>";
            echo "
          <td>$data[kode_part]</td>
          <td>$data[nama_part]</td>
          <td>$tgl_input</td>
          <td>$data[user_input]</td>
          <td>$status</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  </div>
<?php }
?>
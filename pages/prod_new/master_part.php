<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
$akses = flookup("mnuBPB", "userpassword", "username='$user'");
if ($akses == "0") {
  echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
}
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];

?>
<script type="text/javascript">


</script>
<?php if ($mod == "master_part") {

  $sql_cari  = mysql_query("select max(kode_part) urut from master_part");
  $row_cari = mysql_fetch_array($sql_cari);
  $kodepay = $row_cari['urut'];
  $urutan = (int) substr($kodepay, 3, 5);
  if ($urutan == "") {
    $urutan = "00001";
  } else {
    $urutan = $urutan;
  }
  $urutan++;
  $kode_part = "MP" . sprintf("%05s", $urutan);

?>
  <div class="box">
    <div class='row'>
      <form action="save_master_part.php?mod=simpan_data" method="post">
        <div class="box-header">

          <div class='col-md-2'>
            <label>Kode Part : </label>
            <input type='text' class='form-control' id='txt_kode' name='txt_kode' placeholder='Masukan Kode Part' readonly value='<?php echo $kode_part; ?>'>
          </div>
          <div class='col-md-3'>
            <label>Nama Part : </label>
            <input type='text' class='form-control' id='txt_nmpart' name='txt_nmpart' placeholder='Masukan Nama Part' autocomplete='off' required>
          </div>
          <div class='col-md-3'>
            <div style="padding:4px">
              <br>
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>

        </div>
    </div>
    </form>


    <div class="box-body">
      <form action="index.php?mod=master_part_exc&dest=xls" method="post">
        <table id="examplefix3" class="display responsive" style="width: 100%;font-size:13px;">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode Part</th>
              <th>Nama Part</th>
              <th>Dibuat</th>
              <th>Tgl. Input</th>
              <th>Status</th>
              <th>Act</th>
            </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("select * from master_part");
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
            <td>$no</td>
            <td>$data[kode_part]</td>
            <td>$data[nama_part]</td>
            <td>$data[user_input]</td>
            <td>$tgl_input</td>
            <td>$status</td>
            <td> 
            <a href='save_master_part.php?mod=update_status&id=$id'<i class='fa $icon' $col></a>
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
          $sql = "select * from master_part";
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
<?php
if (empty($_SESSION['username'])) {
    header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
$akses = flookup("lap_inventory", "userpassword", "username='$user'");
if ($akses == "0") {
    echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
}
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php if ($mod == "laporan_master") {

    if (isset($_POST['submit'])) //KLIK SUBMIT
    {
        $master_name = $_POST['master_name'];
        echo "<script>
  window.open ('?mod=laporan_master_excel&master_name=$master_name&dest=xls', '_blank');
    </script>";
    } else {
    }

    if (isset($_POST['submit_cari'])) {
        $from = date('Y-m-d', strtotime($_POST['txtfrom']));
        $to = date('Y-m-d', strtotime($_POST['txtto']));
        $kd = $_POST['kd'];
        $kode_rak = $_POST['kode_rak'];
    }

?>
    <!--   <script type='text/javascript'>
    function getdetail() {
      var tipe_inv = document.form.tipe_inv.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_lap_data.php?modeajax=view_list_tipe',
        data: {
          tipe_data: tipe_data,
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbotipe").html(html);
      }
    };
  </script> -->


    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <form method='post' name='form'>

                    <div class='col-md-3'>
                        <div class='form-group'>
                            <label>Jenis Data #</label>
                            <select class='form-control select2' id='master_name' name='master_name'>
                                <option value="RAK" <?php if ($master_name == "RAK") {
                                                        // echo "selected";
                                                    } ?>>MASTER RAK</option>
                                <option value="UNIT" <?php if ($master_name == "UNIT") {
                                                            // echo "selected";
                                                        } ?>>MASTER UNIT</option>
                                <option value="STOK" <?php if ($master_name == "STOK") {
                                                            // echo "selected";
                                                        } ?>>KARTU STOK</option>
                            </select>
                        </div>
                    </div>


                    <div class='col-md-6'>
                        <div class='form-group' style='padding-top:25px'>
                            <!-- <button type='submit' name='submit_cari' class='btn btn-info'><i class="fa fa-search" aria-hidden="true"></i> Search</button> -->
                            <button type='submit' name='submit' class='btn btn-success'><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
                        </div>
                    </div>
            </div>
        </div>
        <div id="divtabel_master" name="divtabel_master">
            <table id="tabel_datamaster" name="tabel_datamaster" class="display responsive" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Rak</th>
                        <th>Nama Rak</th>
                        <th>Kapasitas</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Tanggal Input</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    # QUERY TABLE

                    $query = mysql_query("select * from m_rak");


                    $no = 1;
                    while ($data = mysql_fetch_array($query)) {
                        $tgl_input = date('d M Y', strtotime($data[tgl_input]));
                        echo "<tr>";
                        echo "<td>$no</td>";
                        echo "<td>$data[kode_rak]</td>";
                        echo "<td>$data[nama_rak]</td>";
                        echo "<td>$data[kapasitas]</td>";
                        echo "<td>$data[unit]</td>";
                        echo "<td></td>";
                        echo "<td>$data[user_input]</td>";
                        echo "<td>$tgl_input</td>";
                        echo "</tr>";
                        $no++; // menambah nilai nomor urut
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    </form>
    </div>
    </div><?php }

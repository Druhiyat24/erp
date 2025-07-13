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
<?php if ($mod == "laporan_rak") {

    if (isset($_POST['submit'])) //KLIK SUBMIT
    {
        $from = date('Y-m-d', strtotime($_POST['txtfrom']));
        $to = date('Y-m-d', strtotime($_POST['txtto']));
        $kd = $_POST['kd'];
        $kode_rak = $_POST['kode_rak'];
        echo "<script>
  window.open ('?mod=laporan_rak_excel&from=$from&to=$to&kd=$kd&kode_rak=$kode_rak&dest=xls', '_blank');
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

                    <!-- <div class='col-md-3'>
            <div class='form-group'>
              <label>Jenis Invoice #</label>
              <select class='form-control select2' id='tipe_inv' name='tipe_inv' >
                <option value="ALL" <?php if ($tipe_inv == "ALL") {
                                        echo "selected";
                                    } ?>>ALL</option>
                <option value="INVOICE" <?php if ($tipe_inv == "INVOICE") {
                                            echo "selected";
                                        } ?>>INVOICE</option>
                <option value="NON INVOICE" <?php if ($tipe_inv == "NON INVOICE") {
                                                echo "selected";
                                            } ?>>NON INVOICE</option>
              </select>
            </div>
          </div> -->

                    <div class='col-md-3'>
                        <div class='form-group'>
                            <label>Rak</label>
                            <select class="form-control select2" name="kd" id="kd" data-dropup-auto="false" data-live-search="true">
                                <option value="ALL" <?php if ($kd == "ALL") {
                                                        echo "selected";
                                                    } ?>>ALL</option>
                                <?php
                                $data2 = '';
                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $data2 = isset($_POST['kd']) ? $_POST['kd'] : null;
                                }
                                $sql = mysql_query("select DISTINCT kd from m_rak order by kd asc");
                                while ($row = mysql_fetch_array($sql)) {
                                    $data = $row['kd'];
                                    $data2 = $row['kd'];
                                    if ($row['kd'] == $_POST['kd']) {
                                        $isSelected = ' selected="selected"';
                                    } else {
                                        $isSelected = '';
                                    }
                                    echo '<option value="' . $data2 . '"' . $isSelected . '">' . $data . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class='col-md-3'>
                        <div class='form-group'>
                            <label>Kode Rak</label>
                            <select class="form-control select2" name="kode_rak" id="kode_rak" data-dropup-auto="false" data-live-search="true">
                                <option value="ALL" <?php if ($kode_rak == "ALL") {
                                                        echo "selected";
                                                    } ?>>ALL</option>
                                <?php
                                $nama_buyer = '';
                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $nama_buyer = isset($_POST['kode_rak']) ? $_POST['kode_rak'] : null;
                                }
                                $sql = mysql_query("select DISTINCT kode_rak from m_rak order by kode_rak asc");
                                while ($row = mysql_fetch_array($sql)) {
                                    $data = $row['kode_rak'];
                                    $data2 = $row['kode_rak'];
                                    if ($row['kode_rak'] == $_POST['kode_rak']) {
                                        $isSelected = ' selected="selected"';
                                    } else {
                                        $isSelected = '';
                                    }
                                    echo '<option value="' . $data2 . '"' . $isSelected . '">' . $data . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>

            </div>
            <div class='row'>
                <div class='col-md-2'>
                    <div class='form-group'>
                        <label>Dari *</label>
                        <input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom' required placeholder='Masukkan Dari Tanggal' value="<?php
                                                                                                                                                                        $txtfrom = isset($_POST['txtfrom']) ? $_POST['txtfrom'] : null;
                                                                                                                                                                        if (!empty($_POST['txtfrom'])) {
                                                                                                                                                                            echo $_POST['txtfrom'];
                                                                                                                                                                        } else {
                                                                                                                                                                            echo date("d M Y");
                                                                                                                                                                        } ?>">
                    </div>
                </div>

                <div class='col-md-2'>
                    <div class='form-group'>
                        <label>Sampai *</label>
                        <input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' required placeholder='Masukkan Dari Tanggal' value="<?php
                                                                                                                                                                        $txtto = isset($_POST['txtto']) ? $_POST['txtto'] : null;
                                                                                                                                                                        if (!empty($_POST['txtto'])) {
                                                                                                                                                                            echo $_POST['txtto'];
                                                                                                                                                                        } else {
                                                                                                                                                                            echo date("d M Y");
                                                                                                                                                                        } ?>">
                    </div>
                </div>

                <div class='col-md-6'>
                    <div class='form-group' style='padding-top:25px'>
                        <button type='submit' name='submit_cari' class='btn btn-info'><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                        <button type='submit' name='submit' class='btn btn-success'><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
                    </div>
                </div>
            </div>
        </div>
        <table id="examplefix" class="display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Rak</th>
                    <th>Kode Rak</th>
                    <th>Nomor Lot</th>
                    <th>No Dokumen</th>
                    <th>Tanggal Dokumen</th>
                    <th>nama Supplier</th>
                    <th>Nomor Po</th>
                    <th>Nomor SJ</th>
                    <th>Job Order</th>
                    <th>Tipe Pembelian</th>
                    <th>Nama Barang</th>
                    <th>Nomor Roll</th>
                    <th>Qty Roll</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                <?php
                # QUERY TABLE

                if ($kd == 'ALL' and $kode_rak == 'ALL') {
                    $where = "where c.tgl_dok >= '$from' and c.tgl_dok <= '$to'";
                } elseif ($kd != 'ALL' and $kode_rak == 'ALL') {
                    $where = "where a.kd = '$kd' and c.tgl_dok >= '$from' and c.tgl_dok <= '$to'";
                } elseif ($kd == 'ALL' and $kode_rak != 'ALL') {
                    $where = "where a.kode_rak = '$kode_rak' and c.tgl_dok >= '$from' and c.tgl_dok <= '$to'";
                } else {
                    $where = "where a.kd = '$kd' and a.kode_rak = '$kode_rak' and c.tgl_dok >= '$from' and c.tgl_dok <= '$to'";
                }

                $query = mysql_query("select a.nama_rak,a.kode_rak,b.id_barcode,b.lot_no,b.roll_no,concat(b.roll_qty,' ',c.unit) roll_qty, count(b.roll_qty) qty, c.no_dok,c.tgl_dok,c.supplier,c.no_po,c.tipe_pembelian,c.no_sj,c.keterangan, c.kode_barang, c.nama_barang, c.job_order,b.user,b.date_input from m_rak a inner join in_material_det b on b.kode_rak = a.kode_rak inner join in_material c on c.id = b.id_in_material  $where group by b.id");


                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                    $tgl_memo = date('d M Y', strtotime($data[tgl_dok]));
                    $date_input = date('d M Y', strtotime($data[date_input]));
                    echo "<tr>";
                    echo "<td>$no</td>";
                    echo "<td>$data[nama_rak]</td>";
                    echo "<td>$data[kode_rak]</td>";
                    echo "<td>$data[lot_no]</td>";
                    echo "<td>$data[no_dok]</td>";
                    echo "<td>$tgl_memo</td>";
                    echo "<td>$data[supplier]</td>";
                    echo "<td>$data[no_po]</td>";
                    echo "<td>$data[no_sj]</td>";
                    echo "<td>$data[job_order]</td>";
                    echo "<td>$data[tipe_pembelian]</td>";
                    echo "<td>$data[nama_barang]</td>";
                    echo "<td>$data[roll_no]</td>";
                    echo "<td>$data[roll_qty]</td>";
                    echo "<td>$data[keterangan]</td>";
                    echo "<td></td>";
                    echo "<td>$data[user]</td>";
                    echo "<td>$date_input</td>";
                    echo "</tr>";
                    $no++; // menambah nilai nomor urut
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>
    </form>
    </div>
    </div><?php }

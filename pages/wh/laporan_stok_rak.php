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
<?php if ($mod == "laporan_stok_rak") {

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
        $kd_rak = $_POST['kd_rak'];
        $unit_rak = $_POST['unit_rak'];
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
                            <select class="form-control select2" name="kd_rak" id="kd_rak" data-dropup-auto="false" data-live-search="true">
                                <option value="ALL" <?php if ($kd_rak == "ALL") {
                                                        echo "selected";
                                                    } ?>>ALL</option>
                                <?php
                                $data2 = '';
                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $data2 = isset($_POST['kd_rak']) ? $_POST['kd_rak'] : null;
                                }
                                $sql = mysql_query("select DISTINCT kd from m_rak order by kd asc");
                                while ($row = mysql_fetch_array($sql)) {
                                    $data = $row['kd'];
                                    $data2 = $row['kd'];
                                    if ($row['kd'] == $_POST['kd_rak']) {
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
                            <label>Unit</label>
                            <select class="form-control select2" name="unit_rak" id="unit_rak" data-dropup-auto="false" data-live-search="true">
                                <?php
                                $nama_buyer = '';
                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $nama_buyer = isset($_POST['unit_rak']) ? $_POST['unit_rak'] : null;
                                }
                                $sql = mysql_query("select DISTINCT kode_unit,nama_unit from m_unit order by id asc");
                                while ($row = mysql_fetch_array($sql)) {
                                    $data = $row['nama_unit'];
                                    $data2 = $row['kode_unit'];
                                    if ($row['kode_unit'] == $_POST['unit_rak']) {
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
                    <th>No Dokumen</th>
                    <th>Tanggal Dokumen</th>
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
                    $where = "where q1. >= '$from' and c.tgl_dok <= '$to'";
                } elseif ($kd != 'ALL' and $kode_rak == 'ALL') {
                    $where = "where a.kd = '$kd' and c.tgl_dok >= '$from' and c.tgl_dok <= '$to'";
                } elseif ($kd == 'ALL' and $kode_rak != 'ALL') {
                    $where = "where a.kode_rak = '$kode_rak' and c.tgl_dok >= '$from' and c.tgl_dok <= '$to'";
                } else {
                    $where = "where a.kd = '$kd' and a.kode_rak = '$kode_rak' and c.tgl_dok >= '$from' and c.tgl_dok <= '$to'";
                }

                $query = mysql_query("select q1.no_dok,q1.tgl_dok,q1.supplier,q1.kd,q1.nama_barang,q1.unit,q1.debit,q1.credit, (@runtot :=@runtot + q1.debit - q1.credit) AS saldo
FROM
   (select a.no_dok,a.tgl_dok,a.supplier,c.kd,a.nama_barang,sum(b.roll_qty) debit, '0' credit ,a.unit from in_material a inner join in_material_det b on b.id_in_material = a.id inner join m_rak c on c.kode_rak = b.kode_rak where b.cancel = 'N' GROUP BY a.no_dok
UNION 
select no_out,tgl_out,supplier,kd,nama_barang,debit,credit,unit from (select a.no_out,a.tgl_out,b.supplier,e.kd,a.nama_barang,'0' debit from out_material a inner join req_material b on b. no_req = a.no_req inner join in_material c on c.job_order = a.job_order inner join in_material_det d on d.id_in_material = c.id inner join m_rak e on e.kode_rak = d.kode_rak where a.cancel = 'N' GROUP BY a.no_out) a inner join 
(select no_out noout,sum(qty) credit,unit from out_material GROUP BY no_out) b on b.noout = a.no_out GROUP BY a.no_out) AS q1 JOIN
     (SELECT @runtot:= 0) runtot order by q1.tgl_dok asc");


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

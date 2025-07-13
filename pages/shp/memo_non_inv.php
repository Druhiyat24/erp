<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}
$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php
if ($mod == "memo_list_non_inv") {

  $frdate = date("d M Y");
  $kedate = date("d M Y");

  $tglf = date("d M Y");
  $tglt = date("d M Y");

  $dtf = date("d M Y");
  $dtt = date("d M Y");

  $perf = date("d M Y");
  $pert = date("d M Y");

  if (isset($_POST['submit_filter'])) {
    $tglf = fd($_POST['frdate']);
    $perf = date('d M Y', strtotime($tglf));
    $tglt = fd($_POST['kedate']);
    $pert = date('d M Y', strtotime($tglt));
  }

  ?>
  <div class="box">
    <div class="box-header">
      <a href='../shp/?mod=memo_new_non_inv&id_h=' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>

    </div>

    <div class='row'>
      <form action="" method="post">

        <div class="box-header">
          <div class='col-md-2'>
            <label>From Date (Memo) : </label>
            <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>

          </div>
          <div class='col-md-2'>
            <label>To Date (Memo) : </label>
            <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert; ?>'>
          </div>
          <div class='col-md-3'>
            <div>
              <br>
              <button type='submit' name='submit_filter' class='btn btn-primary'>Tampilkan</button>
            </div>
          </div>

        </div>
      </div>


      <div class="box-body">
        <table id="examplefix" class="display responsive" style="width:100%">
          <thead>
            <tr>
              <th>No</th>
              <th>No Memo</th>
              <th>Tgl Memo</th>
              <th>Kepada</th>
              <th>Supplier</th>
              <th>Jenis Trans</th>
              <th>Jenis Pengiriman</th>
              <th>Buyer</th>
              <th>Tgl Input</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
          # QUERY TABLE
            $query = mysql_query("select a.*, ms.supplier supplier, mb.supplier buyer from memo_h a
              inner join mastersupplier ms on a.id_supplier = ms.id_supplier
              inner join mastersupplier mb on a.id_buyer = mb.id_supplier where jns_inv = 'NON INVOICE' and tgl_memo >= '$tglf' and tgl_memo <= '$tglt' order by id_h desc");
            $no = 1;
            while ($data = mysql_fetch_array($query)) {
              if ($data['status'] == "CANCEL") {
                $fontcol = "style='color:red;'";
              } else {
                $fontcol = "";
              }
              if ($data['stat_lokasi'] == "Done") {
                $icon = "<i class='fa fa-check'></i>";
              } else {
                $icon = "<i class='fa fa-times'></i>";
              }
              echo "<tr $fontcol>";
              echo "
              <td>$no</td>
              <td>$data[nm_memo]</td>";
              echo "
              <td>" . fd_view($data[tgl_memo]) . "</td>";
              echo "
              <td>$data[kepada]</td>
              <td>$data[supplier]</td>
              <td>$data[jns_trans]</td>
              <td>$data[jns_pengiriman]</td>
              <td>$data[buyer]</td>";
              echo "
              <td>" . fd_view_dt($data[date_input]) . "</td>";
              echo "
              <td>$data[status]</td>
              <td>";
              if ($data[status] == 'DRAFT') {
              // code...
                echo "<a href='../shp/?mod=memo_edit_non_inv&id_h=$data[id_h]'
                data-toggle='tooltip' ><i class='fa fa-pencil'></i>
                </a>";
              }
              echo "<a href='cetak_memo_non_inv.php?id_h=$data[id_h]'
              data-toggle='tooltip' ><i class='fa fa-print'></i>
              </a>            
              </td>";
              echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          ?>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php }
?>
<?php if ($mod == "memo_new_non_inv") {

  $username =  $_SESSION['username'];
  $sql_clear_tmp  = mysql_query("delete from memo_det_tmp where user = '$user'");
  $row_cari = mysql_fetch_array($sql_clear_tmp);

  $reqdate = date("d M Y");
  $jatemdate = date("d M Y");

  ?>
  <script type='text/javascript'>
    function getinvoice() {
      var id_buyer = document.form.cbobuyer.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_memo.php?modeajax=view_list_invoice',
        data: {
          id_buyer: id_buyer
        },
        async: false
      }).responseText;
      if (html) {
        $("#cboinv").html(html);
      }
    };


    function getsubkat() {
      var id_kat = $('#cbokat').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_memo.php?modeajax=view_sub_category',
        data: {
          id_kat: id_kat
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbosubkat").html(html);
      }
    };

    function additem() {
      var id_kat_add = $('#cbokat').val();
      var id_sub_kat_add = $('#cbosubkat').val();
      var biaya_add = $('#txtbiaya').val();
      var inv_vendor_add = $('#txtinv_vendor').val();
      var inv_faktur_add = $('#txtfak_pajak').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_memo.php?modeajax=simpan_temp',
        data: {
          id_kat_add: id_kat_add,
          id_sub_kat_add: id_sub_kat_add,
          biaya_add: biaya_add,
          inv_vendor_add: inv_vendor_add,
          inv_faktur_add: inv_faktur_add
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_temp").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          scrollCollapse: true,
          paging: false,
          searching: false,
          fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
          }
        });
      });
    };

    function del_item(id_book_temp) {
      var html = $.ajax({
        type: "POST",
        url: 'ajax_memo.php?modeajax=delete_temp',
        data: {
          id_book_temp: id_book_temp
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_temp").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          scrollCollapse: true,
          paging: false,
          searching: false,
          fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
          }
        });
      });
    };
  </script>


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_memo.php?mod=simpan_header_non_inv'>
          <div class='col-md-5'>

            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Tanggal :</label>
                  <input type='text' class='form-control' id='datepicker1_memo' name='txtmemodate' required placeholder='Masukkan Request Date' value='<?php echo $reqdate; ?>'>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Kepada :</label>
                  <select class='form-control select2' style='width: 100%;' name='cbokpd' id='cbokpd' required>
                    <?php
                    $sql = "select UPPER(department_name) isi, department_name tampil from masterdepartment group by department_name";
                    IsiCombo($sql, $cbokpd, 'Pilih Department #');
                    ?>
                  </select>
                </div>
              </div>
              
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Profit Center :</label>
                  <select class='form-control select2' style='width: 100%;' name='profit_center' id='profit_center' required>
                    <?php
                    $sql = "select kode_pc isi, nama_pc tampil from master_pc where status = 'Active'";
                    IsiCombo($sql, $profit_center, 'Pilih Profit Center #');
                    ?>
                  </select>
                </div>
              </div>

              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Nama Penerima :</label>
                  <select class='form-control select2' style='width: 100%;' name='cbosupp' id='cbosupp' required>
                    <?php
                    $sql = "select a.id_supplier isi, supplier tampil from mastersupplier a where tipe_sup = 'S' order by supplier asc";
                    IsiCombo($sql, $cbosupp, 'Pilih Supplier #');
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Jenis Transaksi :</label>
                  <select class='form-control select2' id='jns_trans' name='jns_trans' value='<?php echo $jns_trans; ?>' required>
                    <option disabled selected value> -- Pilih Jenis Transaksi -- </option>
                    <option value="EXPORT" <?php if ($jns_trans == "EXPORT") {
                      echo "selected";
                    } ?>>EXPORT</option>
                    <option value="IMPORT" <?php if ($jns_trans == "IMPORT") {
                      echo "selected";
                    } ?>>IMPORT</option>
                    <option value="LOKAL" <?php if ($jns_trans == "LOKAL") {
                      echo "selected";
                    } ?>>LOKAL</option>
                  </select>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Jalur Pengiriman :</label>
                  <select class='form-control select2' id='jns_pengiriman' name='jns_pengiriman' value='<?php echo $jns_pengiriman; ?> ' required>
                    <option disabled selected value> -- Pilih Jenis Pengiriman -- </option>
                    <option value="UDARA" <?php if ($jns_pengiriman == "UDARA") {
                      echo "selected";
                    } ?>>UDARA</option>
                    <option value="LAUT" <?php if ($jns_pengiriman == "LAUT") {
                      echo "selected";
                    } ?>>LAUT</option>
                    <option value="DARAT" <?php if ($jns_pengiriman == "DARAT") {
                      echo "selected";
                    } ?>>DARAT</option>
                  </select>
                </div>
              </div>
            </div>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Keperluan Buyer :</label>
                  <select class='form-control select2' style='width: 100%;' name='cbobuyer' id='cbobuyer' onchange='getinvoice()' required>
                    <?php
                    $sql = "select a.id_supplier isi, supplier tampil from mastersupplier a where tipe_sup = 'C' order by supplier asc";
                    IsiCombo($sql, $cbobuyer, 'Pilih Buyer #');
                    ?>
                  </select>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Ditagihkan Ke Buyer :</label>
                  <select class='form-control select2' id='ditagihkan' name='ditagihkan' value='<?php echo $ditagihkan; ?> ' required>
                    <option disabled selected value> -- Pilih -- </option>
                    <option value="Y" <?php if ($ditagihkan == "Y") {
                      echo "selected";
                    } ?>>Y</option>
                    <option value="N" <?php if ($ditagihkan == "N") {
                      echo "selected";
                    } ?>>N</option>
                  </select>
                </div>
              </div>
            </div>

            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Currency :</label>
                  <select class='form-control select2' style='width: 100%;' name='curr' id='curr' required>
                    <?php
                    $sql = "select nama_pilihan isi, nama_pilihan tampil 
                    from masterpilihan where kode_pilihan = 'Curr' and nama_pilihan in ('IDR','USD')";
                    IsiCombo($sql, $curr, 'Pilih Currency #');
                    ?>
                  </select>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Jatuh Tempo :</label>
                  <!-- <input type='text' class='form-control' name='txtjth_tempo' required placeholder='Masukkan Jatuh Tempo'> -->
                  <input type='text' class='form-control' id='datepicker1' name='txtjth_tempo' required placeholder='Masukkan Jatuh Tempo' value='<?php echo $jatemdate; ?>'>

                </div>
              </div>
            </div>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Dok Pendukung :</label>
                  <input type='text' class='form-control' name='dok_pendukung' required placeholder='Masukkan Dokumen Pendukung'>
                </div>
              </div>

              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Item :</label>
                  <select class="form-control select2" name="id_item" id="id_item" data-dropup-auto="false" data-live-search="true" >   
                    <option disabled selected value> -- Pilih -- </option>                                      
                    <?php
                    $data2 ='';
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                      $data2 = isset($_POST['id_item']) ? $_POST['id_item']: null;
                    }                 
                    $sql = mysql_query("select id,item_name from master_memo_item where aktif = 'Y'");
                    while ($row = mysql_fetch_array($sql)) {
                      $data = $row['item_name'];
                      $data2 = $row['id'];
                      if($row['id'] == $_POST['id_item']){
                        $isSelected = ' selected="selected"';
                      }else{
                        $isSelected = '';

                      }
                      echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                    }?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class='col-md-3'>
<!--             <div class='form-group'>
              <label>No Invoice * :</label>
              <input type='text' class='form-control' name='inv_buyer' required placeholder='Masukkan No Invoice'>
            </div> -->
            <div class='form-group'>
              <label>No Aju :</label>
              <input type='text' class='form-control' name='txtno_aju' required placeholder='Masukkan No Aju'>
            </div>
          </div>
          <div class='col-md-6'>
<!--             <div class='form-group'>
              <label>No Invoice * :</label>
              <input type='text' class='form-control' name='inv_buyer' required placeholder='Masukkan No Invoice'>
            </div> -->
            <div class='form-group'>
              <label>Notes :</label>
              <textarea id='txtnotes' class='form-control' name='txtnotes' rows='4' cols='20' required></textarea>
            </div>
            <div class='form-group' style="padding:20px">
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class='box-body'>
      <form method='post' name='form1'>
        <h4> Rincian Biaya Lain - Lain </h4>
        <div class='row'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Kategori :</label>
              <select class='form-control select2' style='width: 100%;' name='cbokat' id='cbokat' onchange='getsubkat()'>
                <?php
                $sql = "select id_ctg isi, UPPER(nm_ctg) tampil 
                from master_memo_ctg";
                IsiCombo($sql, '', 'Pilih Kategori #');
                ?>
              </select>
            </div>
          </div>
          <div class='col-md-2'>
            <div class='form-group'>
              <label>Sub Kategori :</label>
              <select class='form-control select2' style='width: 100%;' name='cbosubkat' id='cbosubkat'>
              </select>
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Biaya :</label>
              <input type='text' class='form-control' name='txtbiaya' onfocus="this.value=''" id='txtbiaya'>
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Invoice Vendor :</label>
              <input type='text' class='form-control' name='txtinv_vendor' id='txtinv_vendor' required>
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Faktur Pajak:</label>
              <input type='text' class='form-control' name='txtfak_pajak' id='txtfak_pajak' >
            </div>
          </div>

          <div class='col-md-1'>
            <div class='form-group'>

            </div>
            <div class='form-group' style="padding:10px">
              <button type='button' name='submit_temp' class='btn btn-primary' onclick='additem()'>Tambah</button>
            </div>
          </div>

        </div>
        <div id='detail_temp'></div>
      </form>
    </div>
  </div>
<?php }
?>
<?php if ($mod == "memo_edit_non_inv") {
  $id_h = $_GET['id_h'];
  $username =  $_SESSION['username'];
  $sql_clear_tmp  = mysql_query("delete from memo_det_tmp where user = '$user'");
  $row_cari = mysql_fetch_array($sql_clear_tmp);

  $sql_cek_data  = mysql_query("select * from memo_h a 
    inner join (SELECT id_h,GROUP_CONCAT(DISTINCT(inv_vendor) SEPARATOR ' , ') inv_vendor FROM memo_det where id_h = '$id_h') b on a.id_h = b.id_h
    where a.id_h = '$id_h'");
  $datamemo = mysql_fetch_array($sql_cek_data);
  $nm_memo = $datamemo['nm_memo'];
  $reqdate = fd_view($datamemo['tgl_memo']);
  $cbokpd = $datamemo['kepada'];
  $cbosupp = $datamemo['id_supplier'];
  $jns_trans = $datamemo['jns_trans'];
  $jns_pengiriman = $datamemo['jns_pengiriman'];
  $cbobuyer = $datamemo['id_buyer'];
  $ditagihkan = $datamemo['ditagihkan'];
  $curr = $datamemo['curr'];
  $txtjth_tempo = $datamemo['jatuh_tempo'];
  $dok_pendukung = $datamemo['dok_pendukung'];
  $txtnotes = $datamemo['notes'];
  $inv_vendor = $datamemo['inv_vendor'];

  ?>
  <script type='text/javascript'>
    function getsubkat() {
      var id_kat = $('#cbokat').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_memo.php?modeajax=view_sub_category',
        data: {
          id_kat: id_kat
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbosubkat").html(html);
      }
    };
  </script>


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_memo.php?mod=update_header_non_inv&id_h=<?php echo $id_h; ?>'>
          <div class='col-md-5'>
            <div class='form-group'>
              <label>Nomor Memo :</label>
              <input type='text' class='form-control' name='nm_memo' readonly value='<?php echo $nm_memo; ?>'>
            </div>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Tanggal :</label>
                  <input type='text' class='form-control' id='datepicker1_memo' name='txtmemodate' required placeholder='Masukkan Request Date' value='<?php echo $reqdate; ?>'>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Kepada :</label>
                  <select class='form-control select2' style='width: 100%;' name='cbokpd' id='cbokpd' required>
                    <?php
                    $sql = "select UPPER(department_name) isi, department_name tampil from masterdepartment group by department_name";
                    IsiCombo($sql, $cbokpd, 'Pilih Department #');
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <div class='form-group'>
              <label>Nama Penerima :</label>
              <select class='form-control select2' style='width: 100%;' name='cbosupp' id='cbosupp' required>
                <?php
                $sql = "select a.id_supplier isi, supplier tampil from mastersupplier a where tipe_sup = 'S' order by supplier asc";
                IsiCombo($sql, $cbosupp, 'Pilih Supplier #');
                ?>
              </select>
            </div>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Jenis Transaksi :</label>
                  <select class='form-control select2' id='jns_trans' name='jns_trans' value='<?php echo $jns_trans; ?>' required>
                    <option disabled selected value> -- Pilih Jenis Transaksi -- </option>
                    <option value="EXPORT" <?php if ($jns_trans == "EXPORT") {
                      echo "selected";
                    } ?>>EXPORT</option>
                    <option value="IMPORT" <?php if ($jns_trans == "IMPORT") {
                      echo "selected";
                    } ?>>IMPORT</option>
                    <option value="LOKAL" <?php if ($jns_trans == "LOKAL") {
                      echo "selected";
                    } ?>>LOKAL</option>
                  </select>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Jalur Pengiriman :</label>
                  <select class='form-control select2' id='jns_pengiriman' name='jns_pengiriman' value='<?php echo $jns_pengiriman; ?> ' required>
                    <option disabled selected value> -- Pilih Jenis Pengiriman -- </option>
                    <option value="UDARA" <?php if ($jns_pengiriman == "UDARA") {
                      echo "selected";
                    } ?>>UDARA</option>
                    <option value="LAUT" <?php if ($jns_pengiriman == "LAUT") {
                      echo "selected";
                    } ?>>LAUT</option>
                    <option value="DARAT" <?php if ($jns_pengiriman == "DARAT") {
                      echo "selected";
                    } ?>>DARAT</option>
                  </select>
                </div>
              </div>
            </div>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Keperluan Buyer :</label>
                  <select class='form-control select2' style='width: 100%;' name='cbobuyer' id='cbobuyer'>
                    <?php
                    $sql = "select a.id_supplier isi, supplier tampil from mastersupplier a where tipe_sup = 'C' and id_supplier = '$cbobuyer' order by supplier asc";
                    IsiCombo($sql, $cbobuyer, 'Pilih Buyer #');
                    ?>
                  </select>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Ditagihkan Ke Buyer :</label>
                  <select class='form-control select2' id='ditagihkan' name='ditagihkan' value='<?php echo $ditagihkan; ?> ' required>
                    <option disabled selected value> -- Pilih -- </option>
                    <option value="Y" <?php if ($ditagihkan == "Y") {
                      echo "selected";
                    } ?>>Y</option>
                    <option value="N" <?php if ($ditagihkan == "N") {
                      echo "selected";
                    } ?>>N</option>
                  </select>
                </div>
              </div>
            </div>

            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Currency :</label>
                  <select class='form-control select2' style='width: 100%;' name='curr' id='curr' required>
                    <?php
                    $sql = "select nama_pilihan isi, nama_pilihan tampil 
                    from masterpilihan where kode_pilihan = 'Curr' and nama_pilihan in ('IDR','USD')";
                    IsiCombo($sql, $curr, 'Pilih Currency #');
                    ?>
                  </select>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Jatuh Tempo :</label>
                  <!-- <input type='text' class='form-control' name='txtjth_tempo' required value='<?php echo $txtjth_tempo; ?> ' placeholder='Masukkan Jatuh Tempo'> -->
                  <input type='text' class='form-control' id='datepicker1' name='txtjth_tempo' required placeholder='Masukkan Jatuh Tempo' value='<?php echo $txtjth_tempo; ?>'>
                </div>
              </div>
            </div>
            <div class='form-group'>
              <label>Dok Pendukung :</label>
              <input type='text' class='form-control' name='dok_pendukung' required value='<?php echo $dok_pendukung; ?> ' placeholder='Masukkan Dok Pendukung'>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>No Invoice * :</label>
              <input type='text' class='form-control' name='inv_buyer' required value='<?php echo $inv_vendor; ?> ' readonly placeholder='Masukkan No Invoice'>
            </div>
            <div class='form-group'>
              <label>Notes :</label>
              <textarea id='txtnotes' class='form-control' name='txtnotes' rows='4' cols='20'><?php echo $txtnotes; ?></textarea>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' class='btn btn-primary'>Update</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class='box-body'>
      <form method='post' name='form1' action='save_memo.php?mod=tambah_det&id_h=<?php echo $id_h; ?>'>
        <h4> Rincian Biaya Lain - Lain </h4>
        <div class='row'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Kategori :</label>
              <select class='form-control select2' style='width: 100%;' name='cbokat' id='cbokat' onchange='getsubkat()'>
                <?php
                $sql = "select id_ctg isi, UPPER(nm_ctg) tampil 
                from master_memo_ctg";
                IsiCombo($sql, '', 'Pilih Kategori #');
                ?>
              </select>
            </div>
          </div>
          <div class='col-md-2'>
            <div class='form-group'>
              <label>Sub Kategori :</label>
              <select class='form-control select2' style='width: 100%;' name='cbosubkat' id='cbosubkat'>
              </select>
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Biaya :</label>
              <input type='text' class='form-control' name='txtbiaya' onfocus="this.value=''" id='txtbiaya'>
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Invoice Vendor :</label>
              <input type='text' class='form-control' name='txtinv_vendor' id='txtinv_vendor' required>
            </div>
          </div>    

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Faktur Pajak:</label>
              <input type='text' class='form-control' name='txtfak_pajak' id='txtfak_pajak' >
            </div>
          </div>       

          <div class='col-md-1'>
            <div class='form-group'>

            </div>
            <div class='form-group' style="padding:10px">
              <button type='submit' name='submit_header' class='btn btn-primary'>Tambah</button>
            </div>
          </div>
        </form>
      </div>
      <form method='post' name='form1' action='save_memo.php?mod=update_biaya&id_h=<?php echo $id_h; ?>'>
        <div class='col-md-3'>
          <div class='form-group'>
            <button type='submit' name='submit' class='btn btn-success'>Update Biaya</button>
          </div>
        </div>
        <table id="examplefix2" class="display responsive" style="width:100%">
          <thead>
            <tr>
              <th>No #</th>
              <th>Kategori</th>
              <th>Sub Kategori</th>
              <th>Biaya</th>
              <th>Invoice Vendor</th>
              <th>Faktur Pajak</th>
              <th>Status</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php
          # QUERY TABLE   
            $query = mysql_query("select * from memo_det where id_h = '$id_h'");
            $no = 1;
            while ($data = mysql_fetch_array($query)) {
              $id = $data['id'];
              if ($data['cancel'] == "Y") {
                $fontcol = "style='color:red;'";
                $status = "CANCEL";
                $biaya_fix = "0";
              } else {
                $fontcol = "";
                $status = "";
                $biaya_fix = $data['biaya'];
              }
              echo "<tr $fontcol>";
              echo "
              <td>
              <input type ='hidden' name='chkhide[$data[id]]' value='$data[id]'>
              <input type='hidden' id='id_cek' checked  name='id_cek[$id]' value='$data[id]'>
              <input type='hidden' id='cancel' checked  name='cancel[$id]' value='$data[cancel]'>
              $no
              </td>
              <td>$data[nm_ctg]</td>
              <td>$data[nm_sub_ctg]</td>
              <td><input type = 'text' value = '$biaya_fix' name='edit_biaya[$id]' id ='edit_biaya$id' size = '8'></td>
              <td>$data[inv_vendor]</td>
              <td>$data[faktur_pajak]</td>
              <td>$status</td>
              <td>
              <a href='save_memo.php?mod=cancel_item&id_h=$data[id_h]&idd=$data[id]'<i class='fa fa-trash'></a>          
              </td>";
              echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          ?>
        </tbody>
        <?php
        $query2 = mysql_query("select sum(biaya) total_biaya from memo_det where id_h = '$id_h' and cancel != 'Y'");
        $total = mysql_fetch_array($query2);
        $total_biaya = $total['total_biaya'];
        ?>
        <tfoot>
          <tr>
            <td>Total</td>
            <td> </td>
            <td> </td>
            <td><?php echo $total_biaya; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td> </td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<?php }
?>
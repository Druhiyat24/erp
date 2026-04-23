<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
$akses = flookup("konfirmasi_sj", "userpassword", "username='$user'");
// if ($akses == "0") {
//   echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
// }
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php if ($mod == "create_transfer_memo") {

  $frdate = date("d M Y");
  $kedate = date("d M Y");

  $tglf = date("d M Y");
  $tglt = date("d M Y");

  $dtf = date("d M Y");
  $dtt = date("d M Y");

  $perf = date("d M Y");
  $pert = date("d M Y");

  $trfdate = date("d M Y");

  if (isset($_POST['submit_filter'])) {
    $tglf = fd($_POST['frdate']);
    $perf = date('d M Y', strtotime($tglf));
    $tglt = fd($_POST['kedate']);
    $pert = date('d M Y', strtotime($tglt));
    $nama_supp = $_POST['nama_supp'];
    $addwhere = "1=1";
    if ($nama_supp == 'ALL') {
      $addwhere = "";
    }else{
      $addwhere = "and ms.supplier = '$nama_supp'";
    }
    $transfer_to = $_POST['transfer_to'];
    $trdate = fd($_POST['trfdate']);
    $trfdate = date('d M Y', strtotime($trdate));
  }

  ?>

  <script type='text/javascript'>
    function gettipe() {
      $("#examplefix1 tr").remove();
      var tipe_konf = document.form.tipe_konf.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_konfirmasi_new.php?modeajax=view_list_tipe',
        data: {
          tipe_konf: tipe_konf,
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbotipe").html(html);
      }
    };



    function getdata() {
      var id_tipe = document.form.cbotipe.value;
      var tipe_konf = document.form.tipe_konf.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_konfirmasi_new.php?modeajax=view_list_data',
        data: {
          id_tipe: id_tipe,
          tipe_konf: tipe_konf
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_item").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          scrollCollapse: true,
          paging: false,
          orderClasses: false
        });
      });
    };
  </script>

  <div class='box'>
    <div class='box-body'>
      <h4><b>FORM TRANSFER BPB</b></h4>
      <div class='row'>
        <form method='post' name='form' >
          <div class='col-md-2'>
            <div class='form-group'>
              <label>Transfer To #</label>
              <select class="form-control select2" name="transfer_to" id="transfer_to" data-dropup-auto="false" data-live-search="true" onchange="clearTableTransfer()">

                <?php
                $transfer_to ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $transfer_to = isset($_POST['transfer_to']) ? $_POST['transfer_to']: null;
                }                 
                $sql = mysql_query("select UPPER(id) id, UPPER(kode) kode from (select 'MARKETING' id, 'MARKETING' kode
                  UNION
                  select 'FINANCE' id, 'FINANCE' kode) a");
                while ($row = mysql_fetch_array($sql)) {
                  $data = $row['kode'];
                  $data2 = $row['id'];
                  if($row['id'] == $_POST['transfer_to']){
                    $isSelected = ' selected="selected"';
                  }else{
                    $isSelected = '';

                  }
                  echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
              </select>
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Transfer Date</label>
              <input type='text' class='form-control' id='trfdate' name='trfdate' placeholder='Masukkan From Date' value='<?php echo $trfdate; ?>'>
            </div>
          </div>


          <div class='col-md-3'>
            <div class='form-group'>
              <label>Supplier</label>
              <select class="form-control select2" name="nama_supp" id="nama_supp" data-dropup-auto="false" data-live-search="true">
                <option value="ALL" <?php if (isset($_POST['nama_supp']) && $_POST['nama_supp'] == "ALL") echo "selected"; ?>>ALL</option>

                <?php
                $sql = mysql_query("SELECT DISTINCT(Supplier), id_supplier FROM mastersupplier WHERE tipe_sup = 'S' ORDER BY Supplier ASC");
                while ($row = mysql_fetch_array($sql)) {
                  $supplier = $row['Supplier'];
                  $selected = (isset($_POST['nama_supp']) && $_POST['nama_supp'] == $supplier) ? ' selected' : '';
                  echo '<option value="'.$supplier.'"'.$selected.'>'.$supplier.'</option>';
                }
                ?>
              </select>

            </div>

            <input type="hidden" style="font-size: 15px;" name="unik_code" id="unik_code" class="form-control" 
            value="<?php 
            $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789!@#$%^&*()?';
            $shuffle  = substr(str_shuffle($karakter), 0, 32);
            echo $shuffle; ?>" autocomplete='off' readonly>
          </div>
        </div>
          <div class='row'>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>From</label>
              <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>
            </div>

          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>To</label>
              <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert; ?>'>
            </div>
          </div>

          <div class='col-md-1'>
            <div class='form-group' style='padding-top:25px'>
              <button type='submit' name='submit_filter' class='btn btn-info'><span class="fa fa-search"></span> Tampilkan</button>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-7 col-md-7">
            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" id="txt_keterangan" name="txt_keterangan" rows="3" placeholder="Isi Description..."></textarea>
            </div>
          </div>

          <div class='box-body'>
            <!-- <div class="card-body table-responsive p-0" style="height: 300px;"> -->
              <table id="tbl_transfer" class="display responsive table-head-fixed" style="width:100%">
                <thead>
                  <tr>
                    <th class="text-center">Check</th>
                    <th class="text-center">No Memo</th>
                    <th class="text-center">Tgl Memo</th>
                    <th class="text-center">Kepada</th>
                    <th class="text-center">Supplier</th>
                    <th class="text-center">Jenis Transaksi</th>
                    <th class="text-center">Jenis Pengiriman</th>
                    <th class="text-center">Buyer</th>
                    <th class="text-center">Description</th>
                    <th hidden>id_memo</th>
                    <th hidden>id_memo</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
          # QUERY TABLE

                  if ($transfer_to == 'MARKETING') {
                    $query = mysql_query("select a.*, ms.supplier supplier, mb.supplier buyer from memo_h a
                    inner join mastersupplier ms on a.id_supplier = ms.id_supplier
                    inner join mastersupplier mb on a.id_buyer = mb.id_supplier where tgl_memo between '$tglf' and '$tglt' and a.status != 'CANCEL' and a.status_transfer = 'PENDING' $addwhere order by id_h desc");
                  }else{
                    $query = mysql_query("select a.*, ms.supplier supplier, mb.supplier buyer from memo_h a
                    inner join mastersupplier ms on a.id_supplier = ms.id_supplier
                    inner join mastersupplier mb on a.id_buyer = mb.id_supplier
                    INNER JOIN (select id_h, status from memo_file where status != 'CANCEL' GROUP BY id_h) fu on fu.id_h = a.id_h
                    where tgl_memo between '$tglf' and '$tglt' and a.status != 'CANCEL' and (a.status_transfer = 'PENDING' OR a.status_transfer = 'A-TMTE') $addwhere order by id_h desc");
                  }


                  $no = 1;
                  while ($data = mysql_fetch_array($query)) {

                    echo "<tr>";
                    echo "<td style='text-align: center;'><input type='checkbox' id='select' name='select[]' value='' <?php if(in_array('1',$_POST[select])) echo 'checked=checked';?></td>
                    <td value= '$data[nm_memo]'>$data[nm_memo]</td>
                    <td value= '" . $data[tgl_memo] . "'>" . fd_view($data[tgl_memo]) . "</td>
                    <td value= '$data[kepada]'>$data[kepada]</td>
                    <td value= '$data[supplier]'>$data[supplier]</td>
                    <td value= '$data[jns_trans]'>$data[jns_trans]</td>
                    <td value= '$data[jns_pengiriman]'>$data[jns_pengiriman]</td>
                    <td value= '$data[buyer]'>$data[buyer]</td>";
                    echo "<td><input style='font-size: 12px;' type='text' class='form-control' name='keterangan[]' placeholder='' autocomplete='off'></td>
                    ";
                    echo "<td value= '$data[id_h]' hidden>$data[id_h]</td>";
                    echo "<td value= '$data[status_transfer]' hidden>$data[status_transfer]</td>";
                    echo "</tr>";

            $no++; // menambah nilai nomor urut
          }
          ?>
        </tbody>
      </table>
    </div>
  </form>

  <form id="form-simpan" >
   <div class="form-row">
    <div class="col-md-3 mb-3">                            
      <button type="button" class="btn btn-primary btn-s" name="transfer_memo" id="transfer_memo"><span class="fa fa-paper-plane"></span> Transfer</button>                
      <a href='../shp/?mod=transfer_memo' class='btn btn-warning btn-s'>
        <i class='fa fa-arrow-left'></i> Back
      </a>          
    </div>
  </div>                                   
</form>    

</div>
</div>
</div>

<div class='modal fade' id='modal_memo_trf' data-target='#modal_memo_trf' tabindex='-1' role='dialog' aria-labelledby='edit' aria-hidden='true'>
  <div class='modal-dialog modal-lg'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'><span class='fa fa-times'></span></button>
        <h3 class='modal-title' id='txt_title_trf'>Detail Dokumen</h3>
      </div>
      <div class='container-fluid'>
        <div class='row'>
          <div id='txt_tglbpb' class='col col-6' style='font-size: 12px; padding: 0.5rem;'></div>
          <div id='detail_memo_trf' class='modal-body col-12' style='font-size: 12px; padding: 0.5rem;'></div>
        </div>
      </div>
    </div>

  </div>
</div>



<?php }

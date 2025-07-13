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
<?php if ($mod == "create_trans_bpb") {

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
    $type_item = $_POST['type_item'];
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
          <div class='col-md-3'>
            <div class='form-group'>
              <label>No Transfer</label>
              <?php
              $sql = mysql_query("select max(SUBSTR(no_trans,15)) no_trans from ir_log_trans where kode_trans = 'TBPB'");
              $row = mysql_fetch_array($sql);
              $kodepay = $row['no_trans'];
              $urutan = (int) substr($kodepay, 0, 5);
              $urutan++;
              $bln = date("m");
              $thn = date("y");
              $huruf = "TBPB/NAG/$bln$thn/";
              $kodepay = $huruf . sprintf("%05s", $urutan);

              echo'<input type="text" readonly style="font-size: 14px;" class="form-control" id="no_doc" name="no_doc" value="'.$kodepay.'">'
              ?>
            </div>
          </div>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Transfer Date</label>
              <input type='text' class='form-control' id='trfdate' name='trfdate' placeholder='Masukkan From Date' value='<?php echo $trfdate; ?>'>
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Type Item #</label>
              <select class="form-control select2" name="type_item" id="type_item" data-dropup-auto="false" data-live-search="true" >

                <?php
                $type_item ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $type_item = isset($_POST['type_item']) ? $_POST['type_item']: null;
                }                 
                $sql = mysql_query("select UPPER(id) id, UPPER(kode) kode from (select '%gk%' id, 'Fabric' kode
                  UNION
                  select '%gacc%' id, 'Accessories' kode
                  UNION
                  select '%wip%' id, 'Barang Dalam Proses' kode
                  UNION
                  select '%gen%' id, 'General' kode) a");
                while ($row = mysql_fetch_array($sql)) {
                  $data = $row['kode'];
                  $data2 = $row['id'];
                  if($row['id'] == $_POST['type_item']){
                    $isSelected = ' selected="selected"';
                  }else{
                    $isSelected = '';

                  }
                  echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
              </select>
            </div>
          </div>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Supplier</label>
              <select class="form-control select2" name="nama_supp" id="nama_supp" data-dropup-auto="false" data-live-search="true" >
                <option value="ALL" <?php if ($nama_supp == "ALL") { echo "selected"; } ?>>ALL</option>                                                 
                <?php
                $data2 ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $data2 = isset($_POST['nama_supp']) ? $_POST['nama_supp']: null;
                }                 
                $sql = mysql_query("select distinct(Supplier),id_supplier from mastersupplier where tipe_sup = 'S' order by Supplier ASC");
                while ($row = mysql_fetch_array($sql)) {
                  $data = $row['Supplier'];
                  $data2 = $row['Supplier'];
                  if($row['Supplier'] == $_POST['nama_supp']){
                    $isSelected = ' selected="selected"';
                  }else{
                    $isSelected = '';

                  }
                  echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
              </select>
            </div>

            <input type="hidden" style="font-size: 15px;" name="unik_code" id="unik_code" class="form-control" 
            value="<?php 
            $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789!@#$%^&*()?';
            $shuffle  = substr(str_shuffle($karakter), 0, 25);
            echo $shuffle; ?>" autocomplete='off' readonly>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>From Date</label>
              <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>
            </div>

          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>To Date</label>
              <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert; ?>'>
            </div>
          </div>

          <div class='col-md-1'>
            <div class='form-group' style='padding-top:25px'>
              <button type='submit' name='submit_filter' class='btn btn-info'><span class="fa fa-search"></span> Tampilkan</button>
            </div>
          </div>

          <div class='box-body'>
            <!-- <div class="card-body table-responsive p-0" style="height: 300px;"> -->
              <table id="tbl_memo" class="display responsive table-head-fixed" style="width:100%">
                <thead>
                  <tr>
                    <th class="text-center">Check</th>
                    <th class="text-center">No BPB</th>
                    <th class="text-center">Tgl BPB</th>
                    <th class="text-center">No PO</th>
                    <th class="text-center">Supplier</th>
                    <th class="text-center">TOP</th>
                    <th class="text-center">Payment Terms</th>
                    <th class="text-center">Descriptions</th>
                    <th hidden></th>
                    <th hidden></th>
                    <th hidden></th>
                    <th hidden></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
          # QUERY TABLE
          // $query = mysql_query("select a.*, ms.supplier supplier, mb.supplier buyer from memo_h a
          // inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          // inner join mastersupplier mb on a.id_buyer = mb.id_supplier where a.nm_memo = 'MEMO/NAG/2401/01434' order by id_h desc");

                  if ($nama_supp == 'ALL') {
                    $query = mysql_query("select bpb.id ,bpb.bpbno_int, bpb.pono, bpb.bpbdate, mastersupplier.Supplier, po_header.jml_pterms, masterpterms.kode_pterms, bpb.curr, bpb.confirm_by,DATE_FORMAT(bpb.confirm_date,'%Y-%m-%d') confirm_date,round(sum((((IF(bpb.qty_reject IS NULL,(bpb.qty), (bpb.qty - bpb.qty_reject))) * bpb.price) + (((IF(bpb.qty_reject IS NULL,(bpb.qty), (bpb.qty - bpb.qty_reject))) * bpb.price) * (po_header.tax /100)))),2) as total, po_header.podate, po_header_draft.tipe_com
                      from bpb 
                      INNER JOIN po_header on po_header.pono = bpb.pono 
                      left JOIN po_header_draft on po_header_draft.id = po_header.id_draft
                      INNER JOIN mastersupplier on mastersupplier.Id_Supplier = bpb.id_supplier 
                      inner join masterpterms on masterpterms.id = po_header.id_terms 
                      where bpbno_int like '$type_item' AND (stat_trf IS NULL OR stat_trf = '') and bpb.confirm='Y' and bpb.cancel='N' and bpb.bpbdate between '$tglf' and '$tglt' and po_header_draft.tipe_com is null || bpbno_int like '$type_item' AND (stat_trf IS NULL OR stat_trf = '') and bpb.confirm='Y' and bpb.cancel='N' and bpb.bpbdate between '$tglf' and '$tglt' and po_header_draft.tipe_com IN ('REGULAR','BUYER','','FOC') group by bpb.bpbno_int
                      UNION 
                      select id,bppb.bppbno_int, '-' pono, bppb.bppbdate, mastersupplier.Supplier , '' ,'' , bppb.curr,bppb.confirm_by,DATE_FORMAT(bppb.confirm_date,'%Y-%m-%d') confirm_date, sum(bppb.qty * bppb.price) as total, '','' from bppb inner join mastersupplier on mastersupplier.Id_Supplier = bppb.id_supplier where bppbno_int like '$type_item' and confirm = 'Y' and cancel != 'Y' and  bppb.bppbdate between '$tglf' and '$tglt' AND (stat_trf IS NULL OR stat_trf = '') and tipe_sup = 'S' group by bppbno_int");
                  }else{
                    $query = mysql_query("select bpb.id ,bpb.bpbno_int, bpb.pono, bpb.bpbdate, mastersupplier.Supplier, po_header.jml_pterms, masterpterms.kode_pterms, bpb.curr, bpb.confirm_by,DATE_FORMAT(bpb.confirm_date,'%Y-%m-%d') confirm_date,round(sum((((IF(bpb.qty_reject IS NULL,(bpb.qty), (bpb.qty - bpb.qty_reject))) * bpb.price) + (((IF(bpb.qty_reject IS NULL,(bpb.qty), (bpb.qty - bpb.qty_reject))) * bpb.price) * (po_header.tax /100)))),2) as total, po_header.podate, po_header_draft.tipe_com
                      from bpb 
                      INNER JOIN po_header on po_header.pono = bpb.pono 
                      left JOIN po_header_draft on po_header_draft.id = po_header.id_draft
                      INNER JOIN mastersupplier on mastersupplier.Id_Supplier = bpb.id_supplier 
                      inner join masterpterms on masterpterms.id = po_header.id_terms 
                      where bpbno_int like '$type_item' AND (stat_trf IS NULL OR stat_trf = '') and bpb.confirm='Y' and bpb.cancel='N' and bpb.bpbdate between '$tglf' and '$tglt' and po_header_draft.tipe_com is null  and supplier = '$nama_supp' || bpbno_int like '$type_item' AND (stat_trf IS NULL OR stat_trf = '') and bpb.confirm='Y' and bpb.cancel='N' and bpb.bpbdate between '$tglf' and '$tglt' and po_header_draft.tipe_com IN ('REGULAR','BUYER','','FOC') and supplier = '$nama_supp' group by bpb.bpbno_int
                      UNION
                      select id,bppb.bppbno_int, '-' pono, bppb.bppbdate, mastersupplier.Supplier , '' ,'' , bppb.curr,bppb.confirm_by,DATE_FORMAT(bppb.confirm_date,'%Y-%m-%d') confirm_date, sum(bppb.qty * bppb.price) as total, '','' from bppb inner join mastersupplier on mastersupplier.Id_Supplier = bppb.id_supplier where bppbno_int like '$type_item' and confirm = 'Y' and cancel != 'Y' and  bppb.bppbdate between '$tglf' and '$tglt' AND (stat_trf IS NULL OR stat_trf = '') and tipe_sup = 'S' and supplier = '$nama_supp' group by bppbno_int");
                  }


                  $no = 1;
                  while ($data = mysql_fetch_array($query)) {

                    echo "<tr>";
                    echo "<td style='text-align: center;'><input type='checkbox' id='select' name='select[]' value='' <?php if(in_array('1',$_POST[select])) echo 'checked=checked';?></td>
                    <td value= '$data[bpbno_int]'>$data[bpbno_int]</td>
                    <td value= '" . $data[bpbdate] . "'>" . fd_view($data[bpbdate]) . "</td>
                    <td value= '$data[pono]'>$data[pono]</td>
                    <td value= '$data[Supplier]'>$data[Supplier]</td>
                    <td value= '$data[jml_pterms]'>$data[jml_pterms]</td>
                    <td value= '$data[kode_pterms]'>$data[kode_pterms]</td>";
                    echo "<td><input style='font-size: 12px;' type='text' class='form-control' name='keterangan[]' placeholder='' autocomplete='off'></td>
                    <td hidden value= '$data[curr]'>$data[curr]</td>
                    <td hidden value= '$data[total]'>$data[total]</td>
                    <td hidden value= '$data[confirm_by]'>$data[confirm_by]</td>
                    <td hidden value= '$data[confirm_date]'>$data[confirm_date]</td>";
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
      <button type="button" class="btn btn-primary btn-s" name="transf_bpb" id="transf_bpb"><span class="fa fa-paper-plane"></span> Transfer</button>                
      <a href='../doc_handover/?mod=trans_bpb' class='btn btn-warning btn-s'>
        <i class='fa fa-arrow-left'></i> Back
      </a>          
    </div>
  </div>                                   
</form>    

</div>
</div>
</div>

<div class='modal fade' id='modal_memo_app' data-target='#modal_memo_app' tabindex='-1' role='dialog' aria-labelledby='edit' aria-hidden='true'>
  <div class='modal-dialog modal-lg'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'><span class='fa fa-times'></span></button>
        <h3 class='modal-title' id='txt_title'>Detail Dokumen</h3>
      </div>
      <div class='container-fluid'>
        <div class='row'>
          <div id='txt_tglbpb' class='col col-6' style='font-size: 12px; padding: 0.5rem;'></div>
          <div id='detail_memo' class='modal-body col-12' style='font-size: 12px; padding: 0.5rem;'></div>
        </div>
      </div>
    </div>

  </div>
</div>



<?php }

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
<?php if ($mod == "create_maintain_bpb") {

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
      <h4><b><u>FORM REVERSE BPB</u></b></h4>
      <div class='row'>
        <form method='post' name='form' >
          <div class='col-md-3'>
            <div class='form-group'>
              <label>No Reverse</label>
              <?php
              $sql = mysql_query("select CONCAT(kode,'/',bulan,tahun,'/',nomor) kode from (select 'RVS/BPB' kode, DATE_FORMAT(CURRENT_DATE(), '%m') bulan, DATE_FORMAT(CURRENT_DATE(), '%y') tahun,if(MAX(no_maintain) is null,'00001',LPAD(SUBSTR(max(SUBSTR(no_maintain,15)),1,5)+1,5,0)) nomor from maintain_bpb_h) a");
              $row = mysql_fetch_array($sql);
              $kodepay = $row['kode'];
              // $urutan = (int) substr($kodepay, 0, 5);
              // $urutan++;
              // $bln = date("m");
              // $thn = date("y");
              // $huruf = "TBPB/NAG/$bln$thn/";
              // $kodepay = $huruf . sprintf("%05s", $urutan);

              echo'<input type="text" readonly style="font-size: 14px;" class="form-control" id="no_doc" name="no_doc" value="'.$kodepay.'">'
              ?>
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Maintain Date</label>
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
                  select '%fg%' id, 'Barang Jadi' kode
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
              <select class="form-control select2" name="nama_supp" id="nama_supp" data-dropup-auto="false" data-live-search="true">
                <?php
                $selected_supp = isset($_POST['nama_supp']) ? $_POST['nama_supp'] : 'ALL';

                $isAllSelected = ($selected_supp == "ALL") ? 'selected' : '';
                echo '<option value="ALL" '.$isAllSelected.'>ALL</option>';

                $sql = mysql_query("SELECT DISTINCT(Supplier), id_supplier FROM mastersupplier WHERE tipe_sup = 'S' ORDER BY Supplier ASC");
                while ($row = mysql_fetch_array($sql)) {
                  $supplier = $row['Supplier'];
                  $isSelected = ($supplier == $selected_supp) ? 'selected' : '';
                  echo '<option value="'.$supplier.'" '.$isSelected.'>'.$supplier.'</option>';
                }
                ?>
              </select>

            </div>

            <input type="hidden" style="font-size: 15px;" name="unik_code" id="unik_code" class="form-control" 
            value="<?php 
            $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789!@#$%^&*()?';
            $shuffle  = substr(str_shuffle($karakter), 0, 25);
            echo $shuffle; ?>" autocomplete='off' readonly>
          </div>

          <div class="col-5 col-md-5">
            <div class="form-group">
              <label>Keterangan</label>
              <textarea class="form-control" id="txt_keterangan" name="txt_keterangan" rows="3" placeholder="Isi Description..."></textarea>
            </div>
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
                    <?php if ($type_item == '%FG%') { echo'<th class="text-center">No SO</th>';}else{echo'<th class="text-center">No PO</th>';} ?>
                    <th class="text-center">Supplier</th>
                    <th class="text-center">TOP</th>
                    <th class="text-center">Payment Terms</th>
                    <th class="text-center">Descriptions</th>
                    <th hidden></th>
                    <th hidden></th>
                    <th hidden></th>
                    <th hidden></th>
                    <th hidden></th>
                    <th hidden></th>
                    <th class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
          # QUERY TABLE
          // $query = mysql_query("select a.*, ms.supplier supplier, mb.supplier buyer from memo_h a
          // inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          // inner join mastersupplier mb on a.id_buyer = mb.id_supplier where a.nm_memo = 'MEMO/NAG/2401/01434' order by id_h desc");
                  // echo $type_item;
                  if ($type_item == '%FG%') {
                    if ($nama_supp == 'ALL') {
                      $query = mysql_query("select a.*, concat(b.status_closing, if(c.no_bpb is null,'',' - Verif')) status_closing from (select * from (SELECT id, bpbno_int, no_so pono, bpbdate, supplier, '' jml_pterms, '' kode_pterms, curr, confirm_by, confirm_date, round(sum(total),4) total, so_date po_date, '' tipe_com, dateinput, sum(qty) qty FROM (SELECT a.id, a.bpbno_int, a.bpbdate, a.curr, a.qty, a.price , (a.qty * a.price) total,ac.kpno,s.goods_code,s.itemname itemdesc, jod.id_jo idjonya,jo_no,ac.Styleno, a.bcdate, a.dateinput, a.last_date_bpb ,a.reffno, SO.so_no no_so, a.confirm_by,DATE_FORMAT(a.confirm_date,'%Y-%m-%d') confirm_date, ms.supplier, So.so_date FROM bpb a inner join 
                        masterstyle s on a.id_item=s.id_item inner join 
                        mastersupplier ms on a.id_supplier=ms.id_supplier inner join 
                        so_det sod on a.id_so_det=sod.id inner join 
                        so SO on sod.id_so=SO.id left join 
                        jo_det jod on SO.id=jod.id_so left join 
                        jo on jod.id_jo=jo.id inner join 
                        act_costing ac on SO.id_cost=ac.id 
                        where left(bpbno,2)='FG' and a.bpbdate between '$tglf' and '$tglt' and a.bpbdate<> '' AND (status_maintain IS NULL OR status_maintain = '') and a.confirm='Y' and a.cancel='N' GROUP BY a.id ASC) a GROUP BY bpbno_int) a
                        UNION
                        SELECT id, bppbno_int, no_so pono, bppbdate, supplier, '', '', curr, confirm_by, confirm_date, sum(total_price) total, so_date po_date, '', dateinput, sum(qty) qty FROM (SELECT a.so_no AS no_so, c.bppbno AS sj, c.bppbdate, c.bppbno_int, d.kpno AS ws,  
                        d.styleno, e.product_group, e.product_item, b.color, b.size,  
                        c.curr, c.unit AS uom, c.qty, Round(c.price,4) AS unit_price,  
                        ROUND(c.qty * Round(c.price,4), 4) AS total_price,  b.id_so, c.id,c.grade, ms.supplier, c.confirm_by,DATE_FORMAT(c.confirm_date,'%Y-%m-%d') confirm_date, c.dateinput, a.so_date
                        FROM so AS a INNER JOIN 
                        so_det AS b ON a.id = b.id_so INNER JOIN 
                        bppb AS c ON b.id = c.id_so_det INNER JOIN 
                        act_costing AS d ON a.id_cost = d.id INNER JOIN 
                        masterproduct AS e ON d.id_product = e.id INNER JOIN
                        mastersupplier ms on ms.id_Supplier = c.id_supplier
                        WHERE c.bppbdate between '$tglf' and '$tglt' and c.id_supplier != '1038' AND (status_maintain IS NULL OR status_maintain = '') and c.confirm='Y' and c.cancel='N' 
                        ORDER BY c.bppbno_int) a GROUP BY bppbno_int) a LEFT JOIN tbl_closing_periode b on a.bpbdate BETWEEN b.tgl_awal AND b.tgl_akhir LEFT JOIN (select * from (select no_bpb, supplier from bpb_new where tgl_bpb between '$tglf' and '$tglt' and status != 'Cancel' GROUP BY no_bpb 
                        UNION
                        select no_bppb, supplier from bppb_new where tgl_bppb between '$tglf' and '$tglt' and status != 'Cancel' GROUP BY no_bppb) a) c on c.no_bpb = a.bpbno_int");
                    }else{
                      $query = mysql_query("select a.*, concat(b.status_closing, if(c.no_bpb is null,'',' - Verif')) status_closing from (select * from (SELECT id, bpbno_int, no_so pono, bpbdate, supplier, '' jml_pterms, '' kode_pterms, curr, confirm_by, confirm_date, round(sum(total),4) total, so_date po_date, '' tipe_com, dateinput, sum(qty) qty FROM (SELECT a.id, a.bpbno_int, a.bpbdate, a.curr, a.qty, a.price , (a.qty * a.price) total,ac.kpno,s.goods_code,s.itemname itemdesc, jod.id_jo idjonya,jo_no,ac.Styleno, a.bcdate, a.dateinput, a.last_date_bpb ,a.reffno, SO.so_no no_so, a.confirm_by,DATE_FORMAT(a.confirm_date,'%Y-%m-%d') confirm_date, ms.supplier, So.so_date FROM bpb a inner join 
                        masterstyle s on a.id_item=s.id_item inner join 
                        mastersupplier ms on a.id_supplier=ms.id_supplier inner join 
                        so_det sod on a.id_so_det=sod.id inner join 
                        so SO on sod.id_so=SO.id left join 
                        jo_det jod on SO.id=jod.id_so left join 
                        jo on jod.id_jo=jo.id inner join 
                        act_costing ac on SO.id_cost=ac.id 
                        where left(bpbno,2)='FG' and a.bpbdate between '$tglf' and '$tglt' and a.bpbdate<> '' AND (status_maintain IS NULL OR status_maintain = '') and a.confirm='Y' and a.cancel='N' and supplier = '$nama_supp' GROUP BY a.id ASC) a GROUP BY bpbno_int) a
                        UNION
                        SELECT id, bppbno_int, no_so pono, bppbdate, supplier, '', '', curr, confirm_by, confirm_date, sum(total_price) total, so_date po_date, '', dateinput, sum(qty) qty FROM (SELECT a.so_no AS no_so, c.bppbno AS sj, c.bppbdate, c.bppbno_int, d.kpno AS ws,  
                        d.styleno, e.product_group, e.product_item, b.color, b.size,  
                        c.curr, c.unit AS uom, c.qty, Round(c.price,4) AS unit_price,  
                        ROUND(c.qty * Round(c.price,4), 4) AS total_price,  b.id_so, c.id,c.grade, ms.supplier, c.confirm_by,DATE_FORMAT(c.confirm_date,'%Y-%m-%d') confirm_date, c.dateinput, a.so_date
                        FROM so AS a INNER JOIN 
                        so_det AS b ON a.id = b.id_so INNER JOIN 
                        bppb AS c ON b.id = c.id_so_det INNER JOIN 
                        act_costing AS d ON a.id_cost = d.id INNER JOIN 
                        masterproduct AS e ON d.id_product = e.id INNER JOIN
                        mastersupplier ms on ms.id_Supplier = c.id_supplier
                        WHERE c.bppbdate between '$tglf' and '$tglt' and c.id_supplier != '1038' AND (status_maintain IS NULL OR status_maintain = '') and c.confirm='Y' and c.cancel='N' and supplier = '$nama_supp'
                        ORDER BY c.bppbno_int) a GROUP BY bppbno_int) a LEFT JOIN tbl_closing_periode b on a.bpbdate BETWEEN b.tgl_awal AND b.tgl_akhir LEFT JOIN (select * from (select no_bpb, supplier from bpb_new where tgl_bpb between '$tglf' and '$tglt' and status != 'Cancel' GROUP BY no_bpb 
                        UNION
                        select no_bppb, supplier from bppb_new where tgl_bppb between '$tglf' and '$tglt' and status != 'Cancel' GROUP BY no_bppb) a) c on c.no_bpb = a.bpbno_int");
                    }
                  }else{

                    if ($nama_supp == 'ALL') {
                      $query = mysql_query("select a.*, concat(b.status_closing, if(c.no_bpb is null,'',' - Verif')) status_closing from (select bpb.id ,bpb.bpbno_int, bpb.pono, bpb.bpbdate, mastersupplier.Supplier, po_header.jml_pterms, masterpterms.kode_pterms, bpb.curr, bpb.confirm_by,DATE_FORMAT(bpb.confirm_date,'%Y-%m-%d') confirm_date,round(sum((((IF(bpb.qty_reject IS NULL,(bpb.qty), (bpb.qty - bpb.qty_reject))) * bpb.price) + (((IF(bpb.qty_reject IS NULL,(bpb.qty), (bpb.qty - bpb.qty_reject))) * bpb.price) * (po_header.tax /100)))),2) as total, po_header.podate, po_header_draft.tipe_com, bpb.dateinput, sum(bpb.qty) qty
                        from bpb 
                        INNER JOIN po_header on po_header.pono = bpb.pono 
                        left JOIN po_header_draft on po_header_draft.id = po_header.id_draft
                        INNER JOIN mastersupplier on mastersupplier.Id_Supplier = bpb.id_supplier 
                        inner join masterpterms on masterpterms.id = po_header.id_terms 
                        where bpbno_int like '$type_item' AND (status_maintain IS NULL OR status_maintain = '') and bpb.confirm='Y' and bpb.cancel='N' and bpb.bpbdate between '$tglf' and '$tglt' and po_header_draft.tipe_com is null || bpbno_int like '$type_item' AND (status_maintain IS NULL OR status_maintain = '') and bpb.confirm='Y' and bpb.cancel='N' and bpb.bpbdate between '$tglf' and '$tglt' and po_header_draft.tipe_com IN ('REGULAR','BUYER','','FOC') group by bpb.bpbno_int
                        UNION 
                        select id,bppb.bppbno_int, '-' pono, bppb.bppbdate, mastersupplier.Supplier , '' ,'' , bppb.curr,bppb.confirm_by,DATE_FORMAT(bppb.confirm_date,'%Y-%m-%d') confirm_date, sum(bppb.qty * bppb.price) as total, '','', bppb.dateinput, sum(bppb.qty) qty from bppb inner join mastersupplier on mastersupplier.Id_Supplier = bppb.id_supplier where bppbno_int like '$type_item' and confirm = 'Y' and cancel != 'Y' and  bppb.bppbdate between '$tglf' and '$tglt' AND (status_maintain IS NULL OR status_maintain = '') and tipe_sup = 'S' group by bppbno_int) a LEFT JOIN tbl_closing_periode b on a.bpbdate BETWEEN b.tgl_awal AND b.tgl_akhir LEFT JOIN (select * from (select no_bpb, supplier from bpb_new where tgl_bpb between '$tglf' and '$tglt' and status != 'Cancel' GROUP BY no_bpb 
                        UNION
                        select no_bppb, supplier from bppb_new where tgl_bppb between '$tglf' and '$tglt' and status != 'Cancel' GROUP BY no_bppb) a) c on c.no_bpb = a.bpbno_int");
                    }else{
                      $query = mysql_query("select a.*, concat(b.status_closing, if(c.no_bpb is null,'',' - Verif')) status_closing from (select bpb.id ,bpb.bpbno_int, bpb.pono, bpb.bpbdate, mastersupplier.Supplier, po_header.jml_pterms, masterpterms.kode_pterms, bpb.curr, bpb.confirm_by,DATE_FORMAT(bpb.confirm_date,'%Y-%m-%d') confirm_date,round(sum((((IF(bpb.qty_reject IS NULL,(bpb.qty), (bpb.qty - bpb.qty_reject))) * bpb.price) + (((IF(bpb.qty_reject IS NULL,(bpb.qty), (bpb.qty - bpb.qty_reject))) * bpb.price) * (po_header.tax /100)))),2) as total, po_header.podate, po_header_draft.tipe_com, bpb.dateinput, sum(bpb.qty) qty
                        from bpb 
                        INNER JOIN po_header on po_header.pono = bpb.pono 
                        left JOIN po_header_draft on po_header_draft.id = po_header.id_draft
                        INNER JOIN mastersupplier on mastersupplier.Id_Supplier = bpb.id_supplier 
                        inner join masterpterms on masterpterms.id = po_header.id_terms 
                        where bpbno_int like '$type_item' AND (status_maintain IS NULL OR status_maintain = '') and bpb.confirm='Y' and bpb.cancel='N' and bpb.bpbdate between '$tglf' and '$tglt' and po_header_draft.tipe_com is null  and supplier = '$nama_supp' || bpbno_int like '$type_item' AND (status_maintain IS NULL OR status_maintain = '') and bpb.confirm='Y' and bpb.cancel='N' and bpb.bpbdate between '$tglf' and '$tglt' and po_header_draft.tipe_com IN ('REGULAR','BUYER','','FOC') and supplier = '$nama_supp' group by bpb.bpbno_int
                        UNION
                        select id,bppb.bppbno_int, '-' pono, bppb.bppbdate, mastersupplier.Supplier , '' ,'' , bppb.curr,bppb.confirm_by,DATE_FORMAT(bppb.confirm_date,'%Y-%m-%d') confirm_date, sum(bppb.qty * bppb.price) as total, '','', bppb.dateinput, sum(bppb.qty) qty from bppb inner join mastersupplier on mastersupplier.Id_Supplier = bppb.id_supplier where bppbno_int like '$type_item' and confirm = 'Y' and cancel != 'Y' and  bppb.bppbdate between '$tglf' and '$tglt' AND (status_maintain IS NULL OR status_maintain = '') and tipe_sup = 'S' and supplier = '$nama_supp' group by bppbno_int) a LEFT JOIN tbl_closing_periode b on a.bpbdate BETWEEN b.tgl_awal AND b.tgl_akhir LEFT JOIN (select * from (select no_bpb, supplier from bpb_new where tgl_bpb between '$tglf' and '$tglt' and status != 'Cancel' GROUP BY no_bpb 
                        UNION
                        select no_bppb, supplier from bppb_new where tgl_bppb between '$tglf' and '$tglt' and status != 'Cancel' GROUP BY no_bppb) a) c on c.no_bpb = a.bpbno_int");
                    }

                  }




                  $no = 1;
                  while ($data = mysql_fetch_array($query)) {

                    if ($data[status_closing] == 'Open') {
                      $disabled = '';
                    }else{
                      $disabled = 'disabled';
                    }

                    echo "<tr>";
                    echo "<td style='text-align: center;'><input type='checkbox' id='select' name='select[]' value='' <?php if(in_array('1',$_POST[select])) echo 'checked=checked';? $disabled></td>
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
                    <td hidden value= '$data[confirm_date]'>$data[confirm_date]</td>
                    <td hidden value= '$data[dateinput]'>$data[dateinput]</td>
                    <td hidden value= '$data[qty]'>$data[qty]</td>
                    <td value= '$data[status_closing]'>$data[status_closing]</td>";
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
      <button type="button" class="btn btn-primary btn-s" name="maintain_bpb" id="maintain_bpb"><span class="fa fa-floppy-o"></span> Save</button>                
      <a href='../doc_handover/?mod=maintain_bpb' class='btn btn-warning btn-s'>
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

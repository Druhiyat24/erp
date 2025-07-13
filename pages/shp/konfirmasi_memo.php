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
<?php if ($mod == "konfirmasi_memo") {

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
      <h4><b>Approval Memo</b></h4>
      <div class='row'>
        <form method='post' name='form' >

          <div class='col-md-2'>
            <label>From Date (Memo) : </label>
            <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>

          </div>
          <div class='col-md-2'>
            <label>To Date (Memo) : </label>
            <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert; ?>'>
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
            <th>No</th>
            <th>No Memo</th>
            <th>Tgl Memo</th>
            <th>Ditagihkan</th>
            <th>Jenis Invoice</th>
            <th hidden>Kepada</th>
            <th>Supplier</th>
            <th>Jenis Trans</th>
            <th>Jenis Pengiriman</th>
            <th>Buyer</th>
            <th>Currency</th>
            <th>Total</th>
            <th>Tgl Input</th>
            <?php 
            $query_all = mysql_query("select id_h,nm_memo,b.id_sub_ctg id_mapping from (select a.id_h,nm_memo,id_sub_ctg,nm_sub_ctg from memo_h a inner join memo_det b on b.id_h = a.id_h where a.status = 'Draft' and b.cancel = 'N' GROUP BY id_sub_ctg,nm_memo ORDER BY nm_memo asc) a left join (select id_sub_ctg,nm_sub_ctg from memo_mapping_v2 GROUP BY id_sub_ctg) b on b.id_sub_ctg = a.id_sub_ctg where b.id_sub_ctg is null");

            $cek_all = mysql_fetch_array($query_all);
            $id_all = isset($cek_all['id_h']) ?  $cek_all['id_h'] : 0;

            if ($id_all != 0) {
              $kolom = '<th><input type="checkbox" id="select_all_memo" disabled></th>';
            } else {
              $kolom = '<th><input type="checkbox" id="select_all_memo"></th>';
            } 
            echo $kolom;?>
            <th hidden></th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          // $query = mysql_query("select a.*, ms.supplier supplier, mb.supplier buyer from memo_h a
          // inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          // inner join mastersupplier mb on a.id_buyer = mb.id_supplier where a.nm_memo = 'MEMO/NAG/2401/01434' order by id_h desc");

          $query = mysql_query("select a.*, ms.supplier supplier, mb.supplier buyer, sum(d.biaya) total, IF(a.ditagihkan = 'Y','YA','TIDAK') tagih from memo_h a
          inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          inner join mastersupplier mb on a.id_buyer = mb.id_supplier
          INNER JOIN memo_det d on d.id_h = a.id_h where status = 'DRAFT' and tgl_memo >= '$tglf' and tgl_memo <= '$tglt' GROUP BY a.id_h order by a.id_h desc");
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $memo_num = $data[nm_memo];

            $query2 = mysql_query("select id_h,nm_memo,b.id_sub_ctg id_mapping from (select a.id_h,nm_memo,id_sub_ctg,nm_sub_ctg from memo_h a inner join memo_det b on b.id_h = a.id_h where a.status = 'Draft' and b.cancel = 'N' GROUP BY id_sub_ctg,nm_memo ORDER BY nm_memo asc) a left join (select id_sub_ctg,nm_sub_ctg from memo_mapping_v2 GROUP BY id_sub_ctg) b on b.id_sub_ctg = a.id_sub_ctg where b.id_sub_ctg is null and nm_memo = '$memo_num'");

            $cek_data = mysql_fetch_array($query2);
            $id_memo = isset($cek_data['id_h']) ?  $cek_data['id_h'] : 0;

            if ($id_memo != 0) {
              $disabled = "disabled";
              $fontcol = "style='color:red;'";
            } else {
              $disabled = "";
              $fontcol = "";
            }

            // if ($data['status'] == "CANCEL") {
            //   $fontcol = "style='color:red;'";
            // } else {
            //   $fontcol = "";
            // }
            if ($data['stat_lokasi'] == "Done") {
              $icon = "<i class='fa fa-check'></i>";
            } else {
              $icon = "<i class='fa fa-times'></i>";
            }
            echo "<tr $fontcol>";
            echo "
            <td>$no</td>
            <td value= '$data[nm_memo]'>$data[nm_memo]</td>";
            echo "
            <td value= '" . fd_view($data[tgl_memo]) . "'>" . fd_view($data[tgl_memo]) . "</td>";
            echo "
            <td value= '$data[tagih]'>$data[tagih]</td>
            <td value= '$data[jns_inv]'>$data[jns_inv]</td>
            <td hidden value= '$data[kepada]'>$data[kepada]</td>
            <td value= '$data[supplier]'>$data[supplier]</td>
            <td value= '$data[jns_trans]'>$data[jns_trans]</td>
            <td value= '$data[jns_pengiriman]'>$data[jns_pengiriman]</td>
            <td value= '$data[buyer]'>$data[buyer]</td>
            <td value= '$data[curr]'>$data[curr]</td>
            <td class='text-right' value= '$data[total]'>".number_format($data[total],2)."</td>";
            echo "
            <td>" . fd_view_dt($data[date_input]) . "</td>";
            echo "
            <td style='text-align: center;'><input type='checkbox' id='select' name='select[]' value='' <?php if(in_array('1',$_POST[select])) echo 'checked=checked';? $disabled></td>
            <td hidden value= '$data[id_h]'>$data[id_h]</td>";
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
            <button style="border-radius: 7px" type="button" class="btn-primary" name="approve_memo" id="approve_memo"><span class="fa fa-thumbs-up"></span> Approve</button>                
            <button style="border-radius: 7px" type="button" class="btn-danger" name="cancel_memo" id="cancel_memo"><span class="fa fa-ban"></span> Cancel</button>           
            </div>
            </div>                                   
        </form>    

      </div>
    </div>
  </div>

  <div class='modal fade' id='modal_memo_app' data-target='#modal_memo_app' tabindex='-1' role='dialog' aria-labelledby='edit' aria-hidden='true'>
    <div class='modal-dialog modal-lg'>
      <div class='modal-content'>
        <div class='modal-header bg-dark text-white'>
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

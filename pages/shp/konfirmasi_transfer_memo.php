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
<?php if ($mod == "konfirmasi_transfer_memo") {

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
      <h4><b>Approval Transfer Memo</b></h4>
      <div class='row'>
        <form method='post' name='form' >

          <div class='col-md-2'>
            <label>From : </label>
            <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>

          </div>
          <div class='col-md-2'>
            <label>To : </label>
            <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert; ?>'>
          </div>
          <div class='col-md-1'>
            <div class='form-group' style='padding-top:25px'>
              <button type='submit' name='submit_filter' class='btn btn-info'><span class="fa fa-search"></span> Tampilkan</button>
            </div>
          </div>
          <div class='box-body'>
            <!-- <div class="card-body table-responsive p-0" style="height: 300px;"> -->
            <table id="tbl_app_transfer" class="display responsive table-head-fixed" style="width:100%">
        <thead>
          <tr>
            <th>No Transfer</th>
            <th>Tgl Transfer</th>
            <th>Keterangan</th>
            <th>User Create</th>
            <th>Action</th>
            <th hidden="">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php

          $query = mysql_query("select a.no_trans, tgl_trans, CONCAT(a.created_by,' (',a.created_at,')') create_user, a.status, a.id, upper(IFNULL(a.keterangan,b.keterangan)) keterangan from transfer_memo_exim_h a INNER JOIN transfer_memo_exim_det b on b.no_trans = a.no_trans where tgl_trans BETWEEN '$tglf' and '$tglt' and a.status = 'POST' and a.no_trans like '%TMTE%' GROUP BY a.id");
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
          
            
            echo "<tr>
                    <td>{$data['no_trans']}</td>
                    <td>" . fd_view($data['tgl_trans']) . "</td>
                    <td>{$data['keterangan']}</td>
                    <td>{$data['create_user']}</td>
                    <td style='text-align:center;'>
                        <div class='d-flex gap-1 justify-content-center'>
                            <a href='javascript:void(0)' 
                               class='btn btn-sm btn-info d-flex align-items-center gap-1'
                               onclick=\"showDetail('{$data['id']}')\">
                                <i class='fa-solid fa-circle-info'></i> Action
                            </a>
                        </div>
                    </td>
                </tr>";

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

  <style>
  .table-gradient th {
    background: #1E3A8A;
    color: #fff;
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
}
div.dataTables_wrapper .dataTables_paginate {
    float: right;
    margin-top: 10px;
}
div.dataTables_wrapper .dataTables_info {
    float: left;
    margin-top: 10px;
}

#modalDetail table{
    font-size:14px;
}

#modalDetail table th{
    font-size:14px;
    font-weight:600;
}

#modalDetail table td{
    font-size:14px;
}

#modalDetail table input,
#modalDetail table select{
    font-size:14px;
    height:36px;
}
#modalDetail table th:nth-child(3),
#modalDetail table td:nth-child(3){
    width:220px;
    min-width:220px;
}

#modalDetail table th:nth-child(8),
#modalDetail table td:nth-child(8){
    width:50px;
    min-width:50px;
}

#modalDetail textarea{
    overflow:hidden;
    resize:none;
    width:100%;
}
#modalDetail .select2-container{
    width:100% !important;
    font-size:14px;
}

#modalDetail .select2-dropdown{
    width:auto !important;
    min-width:250px !important;
    max-width:420px !important;
}

#modalDetail .select2-results__options{
    max-height:220px !important;
    overflow-y:auto !important;
}

#modalDetail .select2-search--dropdown .select2-search__field{
    width:100% !important;
    font-size:14px;
}


</style>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:75%;width:75%;">
    <div class="modal-content">

      <div class="modal-header text-white skin-purple">
        <h5 class="modal-title">
          <i class="far fa-list-alt"></i> Detail Transfer Memo
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          &times;
        </button>
      </div>


      <div class="modal-body">

        <!-- HEADER -->
        <div class="row">
          <div class="col col-6 mb-1">
            <strong>No Transfer</strong><br>
            <span id="d_no_trans">-</span>
          </div>

          <div class="col col-6 mb-1">
            <strong>Transfer Date</strong><br>
            <span id="d_tgl_trans">-</span>
          </div>
        </div>

        <hr>

        <table class="table table-bordered table-striped table-sm" id="detailTable">
            <thead class="table-light">
                <tr>
                    <th>No Memo</th>
                    <th>Tgl Memo</th>
                    <th>Supplier</th>
                    <th>Jenis Transaksi</th>
                    <th>Jenis Pengiriman</th>
                    <th>Buyer</th>
                    <th>Description</th>
                    <th>check</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

      </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-success" id="btnApprove">
        Approve
    </button>
    <button type="button" class="btn btn-danger" id="btnCancelAll">
        Cancel
    </button>
</div>


    </div>
  </div>
</div>


<?php }

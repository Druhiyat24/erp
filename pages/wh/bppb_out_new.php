<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
// $akses = flookup("bppb_req", "userpassword", "username='$user'");
// if ($akses == "0") {
//   echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
// }
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {
  $id_req = $_GET['id'];
} else {
  $id_req = "";
}
$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$harus_bpb = $rscomp["req_harus_bpb"];
$logo_company = $rscomp["logo_company"];
$id_item = "";
if ($mod == "611rvp") {
  // $nmpdf="pdfPickList.php";
  $nmpdf = "pdfPLGDOUT.php";
} else {
  $nmpdf = "pdfPickListReq.php";
}
# COPAS EDIT
if ($id_req == "") {
  $reqno = "";
  $reqdate = date("d M Y");
  $txtbcdate = date("d M Y");
  $bppbno = "";
  $sentto = "";
  $notes = "";
} else {
  $cekbppb = flookup("count(*)", "bppb", "bppbno_req='$id_req'");
  if ($cekbppb <> "0") {
    $_SESSION['msg'] = "XRequest # Sudah Ada Pengeluaran";
    echo "
    <script>
      window.location.href='?mod=1';
    </script>";
  }
  $query = mysql_query("SELECT a.* FROM bppb_req a where a.bppbno='$id_req' ");
  $data = mysql_fetch_array($query);
  $reqno = $data['bppbno'];
  $reqdate = fd_view($data['bppbdate']);
  $sentto = $data['id_supplier'];
  $notes = $data['remark'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
# END COPAS VALIDASI

# COPAS ADD
?>
<?php if ($mod == "new_req_material") {
?>

  <script type="text/javascript">
    function getreq() {
      $('#detail_item_req tbody tr').remove();
      var tipe_mat = $('#cbotipe').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bppb_out_new.php?modeajax=view_list_req',
        data: {
          tipe_mat: tipe_mat
        },
        async: false
      }).responseText;
      if (html) {
        $("#cboReq").html(html);
      }
    };

    function getJO() {
      var no_req = $('#cboReq').val();
      var tipe_mat = $('#cbotipe').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bppb_out_new.php?modeajax=view_list_stock_req',
        data: {
          no_req: no_req,
          tipe_mat: tipe_mat
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_item_req").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          scrollCollapse: true,
          paging: false,
          fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
          }
        });
      });

      jQuery.ajax({

        url: 'ajax_bppb_out_new.php?modeajax=cari_supp_req',

        method: 'POST',

        data: {
          cri_item: no_req
        },

        dataType: 'json',

        success: function(response)

        {
          $('#cbosupp').val(response[0]);

          $('#txtjono').val(response[1]);
          $('#txtws').val(response[2]);
          $('#txtws_act').val(response[3]);
          $('#txtbuyer').val(response[4]);
        },

        error: function(request, status, error) {

          alert(request.responseText);

        },

      });
    };


    function checkAll(ele) {
      var checkboxes = document.getElementsByClassName('chkclass');
      if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = true;
          }
        }
      } else {
        for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = false;
          }
        }
      }
    }


    function calc_chk() {
      var chkroll = document.getElementsByClassName('chkclass');
      var sum = 0;
      var chkqty = document.getElementsByClassName('txtuseclass');
      for (var i = 0; i < chkroll.length; i++) {
        if (chkroll[i].checked) {
          sum += Number(chkqty[i].value);
          sumfix = sum.toFixed(2);
        }
      }
      $('#total_qty_chk').show();
      $('#total_qty_chk').text(sumfix);
    }

    function choose_rak(id_jo, id_item) {
      var tipe_mat = $('#cbotipe').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bppb_out_new.php?modeajax=view_list_rak_loc',
        data: {
          id_jo: id_jo,
          id_item: id_item,
          tipe_mat: tipe_mat
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_rak").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix').DataTable({
          info: false,
          paging: false,
          footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
              return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            total = api
              .column(6)
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);
              }, 0);

            // Total over this page
            pageTotal = api
              .column(6, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);
              }, 0);

            // Update footer
            $(api.column(6).footer()).html(pageTotal);
          },
        });
      });
    };


    function save_rak() {
      var chkroll = document.getElementsByClassName('chkclass');
      var chkqty = document.getElementsByClassName('txtuseclass');
      var chkcri = document.getElementsByClassName('txtcriclass');
      var qtydet = document.getElementsByClassName('qtyrollclass');
      var totqtydet = document.getElementsByClassName('totqtyrollclass');
      var qtydetori = document.getElementsByClassName('qtyrolloriclass');
      var qtyrollpil = 0;
      var crinya = "";
      var crirollnya = "";
      for (var i = 0; i < chkroll.length; i++) {
        if (chkroll[i].checked) {
          qtyrollpil += Number(chkqty[i].value);
          qtyrollpilfix = qtyrollpil.toFixed(2);
          crinya = chkcri[i].value;
          if (crirollnya == '') {
            crirollnya = chkcri[i].value + "|" + Number(chkqty[i].value);
          } else {
            crirollnya = crirollnya + "X" + chkcri[i].value + "|" + Number(chkqty[i].value);
          }
        }
      }
      var res = crinya.split("|");
      var rescri = res[0] + "|" + res[1];
      for (var i = 0; i < qtydet.length; i++) {
        if (qtydetori[i].value == rescri) {
          qtydet[i].value = crirollnya;
          totqtydet[i].value = qtyrollpilfix;
        }
      }
    };
  </script>

  <div class="modal fade" id="myRak" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="overflow-y:auto;" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Pilih Detail</h4>
        </div>
        <div class="modal-body" style="overflow-y:auto; height:450px;">
          <div id='detail_rak'></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="save_rak()" data-dismiss="modal">Simpan</button>
        </div>
      </div>
    </div>
  </div>




  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_bppb_out_new.php?mod=simpan'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Tipe Material #</label>
              <select class='form-control select2' style='width: 100%;' name='cbotipe' id='cbotipe' onchange='getreq()'>
                <?php
                $sql = "select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
              when 'A' then 'ACCESSORIES'
              end tampil
              from masteritem where mattype in ('F','A')
              order by 
              case mattype when 'F' then '1'
              else '2'
              end ";
                IsiCombo($sql, '', 'Pilih Tipe #');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Request #</label>
              <select class='form-control select2' style='width: 100%;' name='txtreqno' id='cboReq' onchange='getJO()'>
              </select>
            </div>
            <div class='form-group'>
              <label>Tgl BPPB *</label>
              <input type='text' class='form-control' id='datepicker1' name='txtbppbdate' placeholder='Masukkan Request Date' value='<?php echo $reqdate; ?>'>
            </div>
            <div class='form-group'>
              <label>Jenis Pengeluaran *</label>
              <select class='form-control select2' style='width: 100%;' name='txtjns_out' required>
                <?php
                $sqljns_out = "select nama_trans isi,nama_trans tampil from mastertransaksi where 
                            jenis_trans='OUT' and jns_gudang = 'FACC' order by id";
                IsiCombo($sqljns_out, '', 'Pilih Jenis Pengeluaran');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>WS *</label>
              <input type='text' class='form-control' name='txtws' id='txtws' readonly>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Jenis Dokumen *</label>
              <select class='form-control select2' style='width: 100%;' name='txtstatus_kb' required>
                <?php
                $sqljns_kb = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                    kode_pilihan='Status KB Out' order by nama_pilihan";
                IsiCombo($sqljns_kb, '', 'Pilih Jenis Dokumen');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Nomor Daftar *</label>
              <input type='text' maxlength='6' class='form-control' name='txtbcno' id='txtbcno' placeholder='Masukan No Daftar'>
            </div>
            <div class='form-group'>
              <label>Tgl Daftar *</label>
              <input type='text' class='form-control' id='datepicker2' name='txtbcdate' value='<?php echo $txtbcdate; ?>' placeholder='Masukkan Tgl. Daftar'>
            </div>
            <div class='form-group'>
              <label>Dikirim Ke *</label>
              <input type='text' class='form-control' name='txtid_supplier' id='cbosupp' readonly>
            </div>
            <div class='form-group'>
              <label>WS Aktual *</label>
              <input type='text' class='form-control' name='txtws_act' id='txtws_act' readonly>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Buyer *</label>
              <input type='text' class='form-control' name='txtbuyer' id='txtbuyer' readonly>
            </div>
            <div class='form-group'>
              <label>Keterangan</label>
              <textarea row='5' class='form-control' name='txtremark' id='txtremark' placeholder='Masukkan Notes'><?php echo $notes; ?></textarea>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>
          <div class='box-body'>
            <div id='detail_item_req'></div>
          </div>
        </form>
      </div>
    </div>
  </div><?php }
      # END COPAS ADD
      #if ($id_req=="") {
      if ($mod == "req_material") {

        $frdate = date("d M Y");
        $kedate = date("d M Y");

        $tglf = date("d M Y");
        $tglt = date("d M Y");

        $dtf = date("d M Y");
        $dtt = date("d M Y");

        $perf = date("d M Y");
        $pert = date("d M Y");

        if (isset($_POST['submit'])) {
          $excel = "N";
          $tglf = fd($_POST['frdate']);
          $perf = date('d M Y', strtotime($tglf));
          $tglt = fd($_POST['kedate']);
          $pert = date('d M Y', strtotime($tglt));
        }



        ?>

  <div class="box">
    <div class="box-header">
      <!-- <h3 class="box-title">Pengeluaran Bahan Baku New</h3> -->
      <a href='../wh/?mod=new_req_material' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
    </div>

    <div class='row'>
      <form action="" method="post">

        <div class="box-header">
          <div class='col-md-2'>
            <label>From Date (BPPB Req) : </label>
            <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>

          </div>
          <div class='col-md-2'>
            <label>To Date (BPPB Req) : </label>
            <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert; ?>'>
          </div>
          <div class='col-md-3'>
            <div style="padding:2px;">
              <br>
              <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>
            </div>
          </div>

        </div>
      </form>
    </div>


    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width: 100%;font-size:12px;">
        <thead>
          <tr>
            <th>No</th>
            <th>Nomor BPPB</th>
            <th>Tgl BPPB</th>
            <th>Nomor Req</th>
            <th>Buyer</th>
            <th>Style</th>
            <th>WS</th>
            <th>Penerima</th>
            <th>No Dokumen</th>
            <th>Tgl Dokumen</th>
            <th>Jenis BC</th>
            <th>Jenis Trans</th>
            <th>Dibuat</th>
            <th>Status</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $query = mysql_query("select a.bppbno, a.bppbno_int, a.bppbdate, a.bppbno_req, ms.supplier, c.buyer, c.styleno, c.kpno, a.jenis_trans, a.bcdate, a.bcno, a.invno, a.jenis_dok, a.username, a.dateinput, a.confirm, a.confirm_by, a.confirm_date, a.cancel, a.cancel_date from bppb a
          inner join bppb_det b on a.bppbno = b.bppbno
          inner join (select ac.id_buyer, mb.supplier buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd
                   inner join so on jd.id_so = so.id
                   inner join act_costing ac on so.id_cost = ac.id
                   inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
                   where jd.cancel = 'N'
                   group by id_cost 
                   order by id_jo asc) c on a.id_jo = c.id_jo
                   inner join mastersupplier ms on a.id_supplier = ms.id_supplier
                   where a.bppbdate >= '$tglf' and a.bppbdate <= '$tglt' 
                   and substring(a.bppbno,4,1) = 'A'
                   group by a.bppbno order by a.bppbdate desc ");
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $confirm = $data['confirm'];

            // if ($data['confirm'] == 'Y' or $data['cancel'] == 'Y') {
            //   if ($data['confirm'] == 'Y') {
            //     $captses = "Confirmed By " . $data['confirm_by'] . " (" . fd_view_dt($data['confirm_date']) . ")";
            //     $code = "eye";
            //   } else {
            //     $reason = flookup("reason", "cancel_trans", "trans_no='$data[bpbno]'");
            //     $captses = "Cancelled By " . $data['cancel_by'] . " (" . fd_view_dt($data['cancel_date']) . ") Reason " . $reason;
            //     $code = "eye";
            //   }
            // } else {
            //   $code = "pencil";
            // }

            // if ($confirm == 'Y') {
            //   $status_view = "<a href='../forms/?mod=det_bppb_out&id=$data[bppbno]'
            //   data-toggle='tooltip' target='_blank' ><i class='fa fa-eye'></i> </a> ";
            //   $status_print = "<a href='pdfDO_.php?mode=Out&noid=$data[bppbno]'
            //   data-toggle='tooltip'  title='Preview'><i class='fa fa-print'></i>
            //   </a> ";
            //     $captses = "Confirmed By " . $data['confirm_by'] . " (" . fd_view_dt($data['confirm_date']) . ")";
            //     $code = "eye";              
            // } else if ($confirm == 'N') {
            //   $status_view = "<a href='../forms/?mod=det_bppb_out&id=$data[bppbno]'
            //   data-toggle='tooltip' target='_blank' ><i class='fa fa-pencil'></i> </a> ";
            //   $captses = "test";
            //   $status_print = "";
            //   $code = "pencil";
            // }

            if ($confirm == 'Y')
            {
              $captses = "Confirmed By " . $data['confirm_by'] . " (" . fd_view_dt($data['confirm_date']) . ")";
             $status_view = "<a href='../forms/?mod=det_bppb_out&id=$data[bppbno]'
             data-toggle='tooltip' target='_blank' ><i class='fa fa-eye'></i> </a> ";
              $code = "eye";
               $status_print = "<a href='pdfDO_.php?mode=Out&noid=$data[bppbno]'
               data-toggle='tooltip'  title='Preview'><i class='fa fa-print'></i> ";             
            }
            elseif ($confirm == 'N')
            {
              $captses = "";
               $status_view = "<a href='../forms/?mod=det_bppb_out&id=$data[bppbno]'
               data-toggle='tooltip' target='_blank' ><i class='fa fa-pencil'></i> </a> ";              
              $code = "pencil";
              $status_print = "";
            }


            echo "<tr>";
            echo "
            <td>$no</td>
            <td>$data[bppbno_int]</td>";
            echo "
            <td>" . fd_view($data[bppbdate]) . "</td>";
            echo "
            <td>$data[bppbno_req]</td>
            <td>$data[buyer]</td>
            <td>$data[styleno]</td>
            <td>$data[kpno]</td>
            <td>$data[supplier]</td>
            <td>$data[bcno]</td>";
            echo "
            <td>" . fd_view($data[bcdate]) . "</td>";
            echo "
            <td>$data[jenis_dok]</td>
            <td>$data[jenis_trans]</td>
            <td>$data[username] (" . fd_view_dt($data['dateinput']) . "</td>";
            echo "<td>$captses";
            echo "</td>";
            echo "<td align = 'center'>";
            echo "$status_view";
            echo "</td>";
            echo "<td align = 'center'>";
            echo "$status_print";
            echo "</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

<?php }
      if ($mod == "det_bppb_out") {
        $bppbno = $_GET['id'];
        $query = mysql_query("select bppb.*, ms.supplier,idws_act,ac.kpno, mb.supplier buyer from bppb 
  inner join mastersupplier ms on bppb.id_supplier = ms.id_supplier
  inner join bppb_req br on bppb.bppbno_req = br.bppbno
  inner join jo_det jd on bppb.id_jo = jd.id_jo
  inner join so on jd.id_so = so.id
  inner join act_costing ac on so.id_cost = ac.id
  inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
  where bppb.bppbno = '$bppbno' and bppb.cancel = 'N' limit 1 ");
        $data = mysql_fetch_array($query);
        $bppbno_int = $data['bppbno_int'];
        $bppbno_req = $data['bppbno_req'];
        $bppbdate = fd_view($data['bppbdate']);
        $sentto = $data['id_supplier'];
        $notes = $data['remark'];
        $mattype = substr($bppbno, 3, 1);
        $jns_trans = $data['jenis_trans'];
        $jns_dok = $data['jenis_dok'];
        $bcno = $data['bcno'];
        $bcdate = fd_view($data['bcdate']);
        $id_supp = $data['supplier'];
        $idws_act = $data['idws_act'];
        $kpno = $data['kpno'];
        $buyer = $data['buyer'];
        $confirm = $data['confirm'];
        $cancel = $data['cancel'];

?>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_bppb_out_new.php?mod=simpan'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Nomor BPPB #</label>
              <input type='text' class='form-control' disabled value='<?php echo $bppbno_int ?>'>
            </div>
            <div class='form-group'>
              <label>Tipe Material #</label>
              <select class='form-control select2' style='width: 100%;' name='cbotipe' id='cbotipe' disabled>
                <?php
                $sql = "select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
              when 'A' then 'ACCESSORIES'
              end tampil
              from masteritem where mattype in ('F','A')
              order by 
              case mattype when 'F' then '1'
              else '2'
              end ";
                IsiCombo($sql, $mattype, 'Pilih Tipe #');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Request #</label>
              <input type='text' class='form-control' disabled value='<?php echo $bppbno_req ?>'>
            </div>
            <div class='form-group'>
              <label>Tgl BPPB *</label>
              <input type='text' class='form-control' id='datepicker1' <?php echo $status ?> name='txtbppbdate' value='<?php echo $bppbdate; ?>'>
            </div>
            <div class='form-group'>
              <label>Jenis Pengeluaran *</label>
              <select class='form-control select2' style='width: 100%;' name='txtjns_out' required>
                <?php
                $sqljns_out = "select Upper(nama_trans) isi,nama_trans tampil from mastertransaksi where 
                            jenis_trans='OUT' and jns_gudang = 'FACC' order by id";
                IsiCombo($sqljns_out, $jns_trans, 'Pilih Jenis Pengeluaran');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>WS *</label>
              <input type='text' class='form-control' name='txtws' id='txtws' value='<?php echo $kpno; ?>' readonly>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Jenis Dokumen *</label>
              <select class='form-control select2' style='width: 100%;' name='txtstatus_kb' required>
                <?php
                $sqljns_kb = "select UPPER(nama_pilihan) isi,nama_pilihan tampil from masterpilihan where 
                    kode_pilihan='Status KB Out' order by nama_pilihan";
                IsiCombo($sqljns_kb, $jns_dok, 'Pilih Jenis Dokumen');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Nomor Daftar *</label>
              <input type='text' maxlength='6' class='form-control' name='txtbcno' id='txtbcno' value='<?php echo $bcno; ?>' placeholder='Masukan No Daftar'>
            </div>
            <div class='form-group'>
              <label>Tgl Daftar *</label>
              <input type='text' class='form-control' id='datepicker2' name='txtbcdate' value='<?php echo $bcdate; ?>' placeholder='Masukkan Tgl. Daftar'>
            </div>
            <div class='form-group'>
              <label>Buyer *</label>
              <input type='text' class='form-control' name='txtbuyer' id='txtbuyer' value='<?php echo $buyer; ?>' readonly>
            </div>
            <div class='form-group'>
              <label>Dikirim Ke *</label>
              <input type='text' class='form-control' name='txtid_supplier' id='cbosupp' value='<?php echo $id_supp; ?>' readonly>
            </div>
            <div class='form-group'>
              <label>WS Aktual *</label>
              <input type='text' class='form-control' name='txtws_act' id='txtws_act' value='<?php echo $idws_act; ?>' readonly>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Keterangan</label>
              <textarea row='5' class='form-control' name='txtremark' id='txtremark' placeholder='Masukkan Notes'><?php echo $notes; ?></textarea>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' disabled class='btn btn-primary'>Simpan</button>
            </div>
          </div>
          <div class='box-body'>
            <table id="example_bppb_global" class="display responsive" style="width:100%">
              <thead>
                <tr>
                  <th colspan='3'>Detail Item</th>
                  <th colspan='4'></th>
                </tr>
                <tr>
                  <th>No #</th>
                  <th>Style #</th>
                  <th>ID Item</th>
                  <th>Kode Barang</th>
                  <th>Kode Bahan Baku</th>
                  <th>Qty BPPB</th>
                  <th>Unit</th>
                </tr>
              </thead>
              <tbody>
                <?php
                # QUERY TABLE
                $query = mysql_query(
                  "select ac.styleno, a.id_item, mi.goods_code, mi.itemdesc, round(coalesce(a.qty,0),2) qty, a.unit from bppb a
        inner join jo_det jd on a.id_jo = jd.id_jo
        inner join so on jd.id_so = so.id
        inner join act_costing ac on so.id_cost = ac.id
        inner join masteritem mi on a.id_item = mi.id_item
        where a.bppbno = '$bppbno'
        "
                );
                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                  $id_item = $data['id_item'];
                  echo "<tr>";
                  echo "
            <td>$no</td>
            <td>$data[styleno]</td>
            <td>$data[id_item]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[qty]</td>
            <td>$data[unit]</td>";
                  echo "</tr>";
                  $no++; // menambah nilai nomor urut
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="5" style="text-align:right">Total:</th>
                  <th></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>

          <div class='box-body'>
            <table id="example_bppb_global_lokasi" class="display responsive" style="width:100%">
              <thead>
                <tr>
                  <th colspan='3'>Detail Lokasi</th>
                  <th colspan='7'></th>
                </tr>
                <tr>
                  <th>No #</th>
                  <th>ID Item</th>
                  <th>Kode Barang</th>
                  <th>Nama Bahan Baku</th>
                  <th>No BPB </th>
                  <th>No Pack</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Kode Rak</th>
                  <th>Nama Rak</th>
                </tr>
              </thead>
              <tbody>
                <?php
                # QUERY TABLE
                $query = mysql_query(
                  "select a.id_item,mi.goods_code,mi.itemdesc, b.bpbno_int,b.no_pack,a.roll_qty, a.unit, kode_rak, nama_rak from bppb_det a 
       inner join masteritem mi on a.id_item = mi.id_item
       inner join master_rak mr on a.id_rak_loc = mr.id
       inner join bpb_det b on a.id_bpb_det = b.id
        where a.bppbno = '$bppbno'
        "
                );
                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                  $id_item = $data['id_item'];
                  echo "<tr>";
                  echo "
            <td>$no</td>
            <td>$data[id_item]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[bpbno_int]</td>
            <td>$data[no_pack]</td>
            <td>$data[roll_qty]</td>
            <td>$data[unit]</td>
            <td>$data[kode_rak]</td>
            <td>$data[nama_rak]</td>";
                  echo "</tr>";
                  $no++; // menambah nilai nomor urut
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="5" style="text-align:right">Total:</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>

        </form>
      </div>
    </div>
  </div>
<?php } ?>
<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
# END CEK HAK AKSES KEMBALI
// if (isset($_GET['id'])) {
//   $id_req = $_GET['id'];
// } else {
//   $id_req = "";
// }

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$harus_bpb = $rscomp["req_harus_bpb"];
$logo_company = $rscomp["logo_company"];
$id_item = "";
if ($mod == "61rvp") {
  // $nmpdf="pdfPickList.php";
  $nmpdf = "pdfPLGDOUT.php";
} else {
  $nmpdf = "pdfPickListReq.php";
}
# COPAS EDIT
if ($id_req == "") {
  $reqno = "";
  $reqdate = date("d M Y");
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
  // $query = mysql_query("SELECT a.* FROM bppb_req a where a.bppbno='$id_req' ");
  // $data = mysql_fetch_array($query);
  // $reqno=$data['bppbno'];
  // $reqdate=fd_view($data['bppbdate']);
  // $sentto=$data['id_supplier'];
  // $notes=$data['remark'];
}

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi() {
    var id_supplier = document.form.txtid_supplier.value;
    var id_jo = document.form.txtJOItem.value;
    var idwsact = document.form.txtidws_act.value;
    var txtremark = document.form.txtremark.value;


    var pilih = 0;
    var qtykos = 0;
    var qtyover = 0;
    var qtys = document.form.getElementsByClassName('qtybpbclass');
    var qtybtss = document.form.getElementsByClassName('qtysisaclass');
    for (var i = 0; i < qtys.length; i++) {
      if (qtys[i].value !== '') {
        qtykos = qtykos + 1;
      }
    }
    for (var i = 0; i < qtys.length; i++) {
      if (qtys[i].value != '') {
        if (Number(qtys[i].value) > Number(qtybtss[i].value)) {
          qtyover = qtyover + 1;
        }
      }
    }
    if (id_supplier == '') {
      swal({
        title: 'Dikirim Ke Tidak Boleh Kosong',
        <?php echo $img_alert; ?>
      });
      valid = false;
    } else if (id_jo == '') {
      swal({
        title: 'JO # Tidak Boleh Kosong',
        <?php echo $img_alert; ?>
      });
      valid = false;
    } else if (qtykos == 0) {
      swal({
        title: 'Tidak Ada Data',
        <?php echo $img_alert; ?>
      });
      valid = false;
    } else if (qtyover > 0) {
      swal({
        title: 'Qty Melebihi Stock',
        <?php echo $img_alert; ?>
      });
      valid = false;
    } else if (idwsact == '') {
      swal({
        title: 'ID WS Actual# Tidak Boleh Kosong',
        <?php echo $img_alert; ?>
      });
      valid = false
    } else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI

# COPAS ADD
?>
<!-- <script type="text/javascript">
  function getJO()
  { var id_jo = $('#cboJO').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bppb_req_new.php?modeajax=view_list_stock',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  $(document).ready(function() {
      var table = $('#examplefix2').DataTable
      ({  scrollCollapse: true,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };
</script> -->
<?php if ($mod == "new_bppb_gen") {
?>

  <script type="text/javascript">
    function gettipe() {
      var tipe_mat = $('#cbotipe').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bppb_req_new.php?modeajax=view_list_tipe',
        data: {
          tipe_mat: tipe_mat
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbotipews").html(html);
      }
    };


    function getws() {
      var tipews = $('#cbotipews').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bppb_req_new.php?modeajax=view_list_ws',
        data: {
          tipews: tipews
        },
        async: false
      }).responseText;
      if (html) {
        $("#cboJO").html(html);
      }
    };


    function getJO() {
      var id_jo = $('#cboJO').val();
      var tipe_mat = $('#cbotipe').val();
      var tipews = $('#cbotipews').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bppb_req_new.php?modeajax=view_list_stock',
        data: {
          id_jo: id_jo,
          tipe_mat: tipe_mat,
          tipews: tipews
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_item").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix2').DataTable({
          scrollCollapse: true,
          paging: false,
          fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
          }
        });
      });
    };

    function getwsact() {
      var id_jo = $('#cboJO').val();
      var tipe_mat = $('#cbotipe').val();
      var tipews = $('#cbotipews').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bppb_req_new.php?modeajax=view_list_ws_act',
        data: {
          id_jo: id_jo,
          tipe_mat: tipe_mat,
          tipews: tipews
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbowsact").html(html);
      }
    };



    function startCalcReq() {
      intervalReq = setInterval('findTotalReq()', 1);
    }

    function findTotalReq() {
      var arr = document.getElementsByClassName('form-control qtyreqclass');
      var tot = 0;
      for (var i = 0; i < arr.length; i++) {
        if (parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
      }
      document.getElementById('total_qty_req').value = tot;
    }

    function stopCalcReq() {
      clearInterval(intervalReq);
    }

  </script>

<script type="text/javascript">

  function getitem()

  { var id_item = $('#cboitem').val();

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax_bpb_gen_new_1.php?modeajax=view_list_jo',

        data: {id_item: id_item},

        async: false

    }).responseText;

    if(html)

    {  

        $("#detail_item").html(html);

    }

    $(document).ready(function() {

      var table = $('#examplefix2').DataTable

      ({  scrollCollapse: true,

          paging: false,

          fixedColumns:   

          { leftColumns: 1,

            rightColumns: 1

          }

      });

    });

  };

</script>



  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_bppb_gen_new_1.php?mod=simpan'>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Tgl. BPPB *</label>
              <input type='text' class='form-control' id='datepicker1' name='txtbppbdate' placeholder='Masukkan Request Date' value='<?php echo $reqdate; ?>'>
            </div>   
              <div class='form-group'>
              <label>Dikirim Ke *</label>
              <select class='form-control select2' style='width: 100%;' name='cbosupp' id='cbosupp' required>
                <?php
                $sql = "select id_supplier isi,supplier tampil from mastersupplier  where tipe_sup in ('D','S')
                order by tipe_sup asc, supplier asc ";
                IsiCombo($sql, $sentto, 'Pilih Dikirim Ke');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Pilih Item *</label>
              <select class='form-control select2'  multiple="multiple" style='width: 100%;' name='cboitem' id='cboitem' onchange='getitem()'>
                <?php
                $sql = "select id_item isi, concat(id_item,' | ',goods_code,' | ', itemdesc ) tampil 
from masteritem where mattype in ('M') and non_aktif = 'N'
UNION
select id_item isi, concat(id_item,' | ',goods_code,' | ', itemdesc ) tampil 
from masteritem mi
inner join mapping_category mc on mi.n_code_category = mc.n_id
where mattype in ('N') and non_aktif = 'N' and mc.description in ('PERSEDIAAN SPAREPARTS - FACTORY SUPPLIES','PERSEDIAAN MESIN')
or
mattype in ('N') and non_aktif = 'N' and tipe_item = 'ASSET'
group by id_item ";
                IsiCombo($sql, '', '');
                ?>
              </select>
            </div>            
          </div>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Tgl. Daftar *</label>
              <input type='text' class='form-control' id='datepicker2' name='txtbcdate' placeholder='Masukkan Tgl. Daftar' value='<?php echo $reqdate; ?>'>
            </div>             
            <div class='form-group'>
              <label>Nomor Rak *</label>
              <input type='text' class='form-control' id='txtnomor_rak' name='txtnomor_rak' placeholder='Masukkan Nomor Rak' value=''>
            </div>
             <div class='form-group'>
              <label>Nomor Invoice / Nomor SJ  *</label>
              <input type='text' class='form-control' id='txtinvno' name='txtinvno' placeholder='Masukkan Nomor Invoice / Nomor SJ ' value=''>
            </div>             
          </div>

<div class='col-md-4'>
            <div class='form-group'>
              <label>Jenis Dokumen *</label>
              <select class='form-control select2' style='width: 100%;' name='cbokb' id='cbokb' required>
                <?php
                $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                    kode_pilihan='Status KB Out' order by nama_pilihan ";
                IsiCombo($sql, $status_kb, 'Pilih Jenis Dokumen');
                ?>
              </select>
            </div>   
            <div class='form-group'>
              <label>Notes</label>
              <textarea row='5' class='form-control' name='txtremark' id='txtremark' placeholder='Masukkan Notes'><?php echo $notes; ?></textarea>
            </div>
</div>  

          <div class='box-body'>
              <div id='detail_item'></div>
          </div>
          <div class='col-md-3'>
            <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div><?php }
      # END COPAS ADD
      #if ($id_req=="") {
      if ($mod == "list_bppb_gen") {



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
        <h3 class="box-title">List Pengeluaran Item General</h3>
        <a href='../forms/?mod=new_bppb_gen'target="_blank" class='btn btn-primary btn-s'>
          <i class='fa fa-plus'></i> New
        </a>

    </div>


    <div class='row'>
      <form action="" method="post">

        <div class="box-header">
          <div class='col-md-2'>
            <label>From Date : </label>
            <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>
          </div>
          <div class='col-md-2'>
            <label>To Date : </label>
            <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert; ?>'>
          </div>
          <div class='col-md-3'>
            <div>
              <br>
              <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>
            </div>
          </div>

        </div>
      </form>
    </div>

    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width:100%;font-size:13px;">
        <thead>
            <tr>
              <th>Nomor BPPB</th>
              <th>Tanggal BPPB</th>
              <th>Penerima</th>
              <th>No. Invoice</th>
              <th>No. Dokumen</th>
              <th>Jenis BC</th>
              <th>Created By</th>
              <th>Status</th>
              <th></th>
              <th></th>
            </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
            $query = mysql_query("SELECT max(a.bppbno_int) bppbno_int_n,a.*,s.goods_code,s.itemdesc itemdesc,supplier , a.last_date_bppb,ms.area FROM bppb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier ms on a.id_supplier=ms.id_supplier where 1=1 and mid(bppbno,4,1)='N' and a.bppbdate >='$tglf' and a.bppbdate <='$tglt' GROUP BY a.bppbno ASC order by bppbdate desc");
            $no = 1;
            while ($data = mysql_fetch_array($query)) {
              echo "<tr>";
              echo "
            <td>$data[bppbno_int_n]</td>
            <td>$data[bppbdate]</td>
            <td>$data[supplier]</td>
            <td>$data[invno]</td>
            <td>$data[bcno]</td>
            <td>$data[jenis_dok]</td>
            <td>$data[username] ($data[dateinput])</td>";

      if($data['confirm']=='N') {
            echo " <td></td>
      <td>

              <a href='?mod=31e&mode=$mode&noid=$data[bpbno]'

                data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>

              </a>

              <a href='?mod=edit_bppb&bppbno=$data[bppbno]' target='_blank'
          data-toggle='tooltip' title='Edit New'><i class='fa fa-pencil-square-o text-success' aria-hidden='true'></i>
          </a>

            </td>"; } else { echo "<td>Confirmed by $data[confirm_by] $data[confirm_date]</td><td></td>"; }

            if ($print_sj=="1")

            { echo "

              <td>

                <a href='cetaksj.php?mode=Out&noid=$data[bppbno]' 

                  data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i>

                </a>

              </td>"; 

            }

            else

            { echo "<td></td>"; }

          echo "</tr>";

        }
  

          ?>
        </tbody>
      </table>
    </div>
  </div>
<?php }
      if ($mod == "det_bppb_req") {
        $id_req = $_GET['id'];
        $query = mysql_query("SELECT a.* FROM bppb_req a where a.bppbno='$id_req' ");
        $data = mysql_fetch_array($query);
        $reqno = $data['bppbno'];
        $reqdate = fd_view($data['bppbdate']);
        $sentto = $data['id_supplier'];
        $notes = $data['remark'];
?>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_bppb_req_new.php?mod=update&id_req=<?php echo $id_req; ?>'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Request #</label>
              <input type='text' readonly class='form-control' name='txtreqno' placeholder='Masukkan Request #' value='<?php echo $reqno; ?>'>
            </div>
            <div class='form-group'>
              <label>Request Date *</label>
              <input type='text' class='form-control' id='datepicker1' name='txtreqdate' placeholder='Masukkan Request Date' value='<?php echo $reqdate; ?>'>
            </div>
            <div class='form-group'>
              <label>Dikirim Ke *</label>
              <select class='form-control select2' style='width: 100%;' name='cbosupp' id='cbosupp' required>
                <?php
                $sql = "select id_supplier isi,supplier tampil from mastersupplier where area!='LINE' ";
                IsiCombo($sql, $sentto, 'Pilih Dikirim Ke');
                ?>
              </select>
            </div>
          </div>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Notes</label>
              <textarea row='5' class='form-control' name='txtremark' id='txtremark' placeholder='Masukkan Notes'><?php echo $notes; ?></textarea>
            </div>
          </div>
          <div class="box-body">
            <table id="examplefix3" class="display responsive" style="width:100%">
              <thead>
                <tr>
                  <th>No</th>
                  <th>JO #</th>
                  <th>WS #</th>
                  <th>WS Act #</th>
                  <th>Style #</th>
                  <th>ID Item</th>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Qty Req</th>
                  <th>Qty Out</th>
                  <th>Unit</th>
                </tr>
              </thead>
              <tbody>
                <?php
                # QUERY TABLE
                $query = mysql_query("select ac.kpno,idws_act,ac.styleno,jo.jo_no,mi.id_item, a.id_jo, mi.goods_code, mi.itemdesc, a.qty, round(coalesce(sum(bppb.qty),0),2) qty_out, a.unit 
        from bppb_req a
        inner join masteritem mi on a.id_item = mi.id_item
        left join bppb on a.bppbno = bppb.bppbno_req and a.id_item = bppb.id_item and a.id_jo = bppb.id_jo
        inner join jo_det jd on a.id_jo = jd.id_jo
        inner join jo on jd.id_jo = jo.id
        inner join so on jd.id_so = so.id
        inner join act_costing ac on so.id_cost = ac.id
        where a.bppbno = '$id_req'
        group by a.bppbno, a.id_item");
                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                  // $cekbppb=flookup("group_concat(distinct(bppbno_int))","bppb","bppbno_req='$data[bppbno]' group by bppbno_req");
                  echo "<tr>";
                  echo "
            <td>$no</td>
            <td>$data[jo_no]</td>
            <td>$data[kpno]</td>
            <td>$data[idws_act]</td>
            <td>$data[styleno]</td>
            <td>$data[id_item]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>
            <input class='form-control qty' size = '20' name ='qtyreq[$no]' value = '$data[qty]'>
            <input type='hidden' name='id_cek[$no]' value='$data[id_item]'>
            </td>
            <td>$data[qty_out]</td>
            <td>$data[unit]</td>";
                  echo "</tr>";
                  $no++; // menambah nilai nomor urut
                }
                ?>
              </tbody>
            </table>
          </div>
          <div class='col-md-3'>
            <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php } ?>
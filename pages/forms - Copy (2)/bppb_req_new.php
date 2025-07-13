<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
$akses = flookup("req_mat", "userpassword", "username='$user'");
if ($akses == "0") {
  echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
}
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
<?php if ($mod == "new_bppb_req") {
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


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_bppb_req_new.php?mod=simpan'>
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
            <div class='form-group'>
              <label>Notes</label>
              <textarea row='5' class='form-control' name='txtremark' id='txtremark' placeholder='Masukkan Notes'><?php echo $notes; ?></textarea>
            </div>
          </div>
          <div class='col-md-5'>
            <div class='form-group'>
              <label>Tipe Material #</label>
              <select class='form-control select2' style='width: 100%;' name='cbotipe' id='cbotipe' onchange='gettipe()'>
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
              <label>Tipe WS # *</label>
              <select class='form-control select2' style='width: 100%;' name='txttipews' id='cbotipews' onchange='getws()'>
              </select>
            </div>
            <div class='form-group'>
              <label>Job Order # / WS # *</label>
              <select class='form-control select2' style='width: 100%;' name='txtJOItem' id='cboJO' onchange='getJO(); getwsact();'>
              </select>
            </div>
            <div class='form-group'>
              <label>WS Actual # *</label>
              <select class='form-control select2' style='width: 100%;' name='cbowsact' required id='cbowsact'>
              </select>
            </div>
            <!-- <div class='form-group'>
            <label>No WS Actual *</label>
                <select class='form-control select2' style='width: 100%;' name='txtidws_act' id='cbowsact' >
                </select>
          </div> -->
          </div>
          <div class='box-body'>
            <?php if ($mod == "61re") {
              echo "<table id='examplefix3' style='width: 100%;'>";
              echo "
              <thead>
                <tr>
                  <th>Kode Bahan Baku</th>
                  <th>Deskripsi</th>
                  <th>Qty Req</th>
                  <th>Unit</th>
                </tr>
              </thead>
              <tbody>";
              $sql = "select a.id_item,s.goods_code,s.itemdesc,a.qty,a.unit from bppb_req a inner join masteritem s on a.id_item=s.id_item 
                where bppbno='$id_req'";
              $i = 1;
              $query = mysql_query($sql);
              while ($data = mysql_fetch_array($query)) {
                $id_item_req = $data['id_item'];
                echo "
                  <tr>
                    <td>$data[goods_code]</td>
                    <td>$data[itemdesc]</td>
                    <td><input type ='text' name ='txtqtyed[$id_item_req]' value='$data[qty]' id='qtyed$i' class='qtyedclass'></td>
                    <td><input type ='text' name ='txtuomed' value='$data[unit]' readonly></td>
                  </tr>";
                $i++;
              };
              echo "</tbody></table>";
            } else { ?>
              <div id='detail_item'></div>
            <?php } ?>
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
      if ($mod == "list_bppb_req") {



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
          $tipe_tampilan = nb($_POST['tipe_tampilan']);
        }


        ?>
  <div class="box">
    <div class="box-header">
      <?php if ($mod == "list_bppb_req") { ?>
        <h3 class="box-title">Permintaan Bahan Baku</h3>
      <?php } ?>
      <?php if ($mod == "list_bppb_req") { ?>
        <a href='../forms/?mod=new_bppb_req' class='btn btn-primary btn-s'>
          <i class='fa fa-plus'></i> New
        </a>
      <?php } ?>
    </div>


    <div class='row'>
      <form action="" method="post">

        <div class="box-header">
          <div class='col-md-3'>
            <label>Tipe Data</label>
            <select class='form-control select2' id='tipe_tampilan' name='tipe_tampilan' value='<?php echo $tipe_tampilan; ?> '>
              <option value="HEADER" <?php if ($tipe_tampilan == "HEADER") {
                                        echo "selected";
                                      } ?>>HEADER</option>
              <option value="LIST" <?php if ($tipe_tampilan == "LIST") {
                                      echo "selected";
                                    } ?>>LIST</option>
            </select>
          </div>
          <div class='col-md-2'>
            <label>From Date (BPPB Req) : </label>
            <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>
          </div>
          <div class='col-md-2'>
            <label>To Date (BPPB Req) : </label>
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
      <table id="examplefix3" class="display responsive" style="width:100%;font-size:12px;">
        <thead>
          <?php if ($tipe_tampilan == "HEADER") { ?>
            <tr>
              <th>No</th>
              <th>Request #</th>
              <th>Request Date</th>
              <th>Buyer</th>
              <th>Style #</th>
              <th>WS #</th>
              <th>WS ACT#</th>
              <th>Sent To</th>
              <th>Created By</th>
              <th>No. BPPB</th>
              <th></th>
              <th></th>
            </tr>
          <?php } ?>
          <?php if ($tipe_tampilan == "LIST") { ?>
            <tr>
              <th>No</th>
              <th>Request #</th>
              <th>Request Date</th>
              <th>Buyer</th>
              <th>Style #</th>
              <th>WS #</th>
              <th>WS ACT#</th>
              <th>Qty Req</th>
              <th>Qty Out</th>
              <th>No. BPPB</th>
              <th>Unit</th>
            </tr>
          <?php } ?>
          <?php if ($tipe_tampilan == "") { ?>
            <tr>
              <th>No</th>
              <th>Request #</th>
              <th>Request Date</th>
              <th>Buyer</th>
              <th>Style #</th>
              <th>WS #</th>
              <th>WS ACT#</th>
              <th>Qty Req</th>
              <th>Qty Out</th>
              <th>No. BPPB</th>
              <th>Unit</th>
            </tr>
          <?php } ?>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          if ($tipe_tampilan == 'HEADER') {
            $query = mysql_query("select a.username,bppbno,bppbdate,s.supplier,ac.kpno,ac.styleno,ms.supplier buyer, a.idws_act  
        from bppb_req a inner join mastersupplier s on a.id_supplier=s.id_supplier 
        inner join jo_det jod on a.id_jo=jod.id_jo 
        inner join so on jod.id_so=so.id 
        inner join act_costing ac on so.id_cost=ac.id 
        inner join mastersupplier ms on ac.id_buyer=ms.id_supplier
        where a.bppbdate >='$tglf' and a.bppbdate <='$tglt'
        group by bppbno order by bppbdate desc");
            $no = 1;
            while ($data = mysql_fetch_array($query)) {
              $cekbppb = flookup("group_concat(distinct(bppbno_int))", "bppb", "bppbno_req='$data[bppbno]' group by bppbno_req");
              echo "<tr>";
              echo "
            <td>$no</td>
            <td>$data[bppbno]</td>
            <td>$data[bppbdate]</td>
            <td>$data[buyer]</td>
            <td>$data[styleno]</td>
            <td>$data[kpno]</td>
            <td>$data[idws_act]</td>
            <td>$data[supplier]</td>
            <td>$data[username]</td>
            <td>$cekbppb</td>";
              if ($cekbppb == "") {
                echo "
              <td>
                <a href='?mod=det_bppb_req&mode=Bahan_Baku&id=$data[bppbno]'
                  data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>
              </td>";
              } else {
                echo "<td></td>";
              }
              echo "
            <td>
              <a href='$nmpdf?id=$data[bppbno]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>
            </td>";
              echo "</tr>";
              $no++; // menambah nilai nomor urut
            }
          }

          if ($tipe_tampilan == 'LIST') {
            $query = mysql_query("select a.username,a.bppbno,a.id_item, a.id_jo,a.bppbdate,s.supplier,ac.kpno,ac.styleno,ms.supplier buyer, a.idws_act, round(coalesce(sum(a.qty),0),2) qty_req, round(coalesce(sum(bppb.qty),0),2) qty_out, group_concat(distinct(bppb.bppbno_int)) bppbno_int, bppb.unit 
        from bppb_req a inner join mastersupplier s on a.id_supplier=s.id_supplier 
        inner join jo_det jod on a.id_jo=jod.id_jo 
        inner join so on jod.id_so=so.id 
        inner join act_costing ac on so.id_cost=ac.id 
        inner join mastersupplier ms on ac.id_buyer=ms.id_supplier
				left join (select bppbno,bppbno_int,bppbno_req, id_item, id_jo, sum(qty) qty, unit from bppb group by bppbno) bppb on a.id_item = bppb.id_item and a.id_jo = bppb.id_jo and a.bppbno = bppb.bppbno_req
        where a.bppbdate >='$tglf' and a.bppbdate <='$tglt'
				group by a.bppbno, a.id_item, a.id_jo
        order by a.bppbdate desc");
            $no = 1;
            while ($data = mysql_fetch_array($query)) {
              $cekbppb = flookup("group_concat(distinct(bppbno_int))", "bppb", "bppbno_req='$data[bppbno]' group by bppbno_req");
              echo "<tr>";
              echo "
            <td>$no</td>
            <td>$data[bppbno]</td>
            <td>$data[bppbdate]</td>
            <td>$data[buyer]</td>
            <td>$data[styleno]</td>
            <td>$data[kpno]</td>
            <td>$data[idws_act]</td>
            <td>$data[qty_req]</td>
            <td>$data[qty_out]</td>
            <td>$data[bppbno_int]</td>
            <td>$data[unit]</td>
            ";
              echo "</tr>";
              $no++; // menambah nilai nomor urut
            }
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
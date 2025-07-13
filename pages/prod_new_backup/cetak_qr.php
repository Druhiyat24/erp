<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
// $akses = flookup("mnuBPB", "userpassword", "username='$user'");
// if ($akses == "0") {
//   echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
// }
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$harus_bpb = $rscomp["req_harus_bpb"];
$logo_company = $rscomp["logo_company"];

?>
<script type="text/javascript">


</script>
<?php if ($mod == "cetak_qr") {
?>

  <script type='text/javascript'>
    function getws() {
      var id_buyer = document.form.cbobuyer.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_cetak_qr.php?modeajax=view_list_ws',
        data: {
          id_buyer: id_buyer
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbows").html(html);
      }
    };

    function getcolor() {
      var id_buyer = document.form.cbobuyer.value;
      var id_ws = document.form.cbows.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_cetak_qr.php?modeajax=view_list_color',
        data: {
          id_buyer: id_buyer,
          id_ws: id_ws

        },
        async: false
      }).responseText;
      if (html) {
        $("#cbocolor").html(html);
      }
    };

    function getsize() {
      var id_buyer = document.form.cbobuyer.value;
      var id_ws = document.form.cbows.value;
      var id_color = document.form.cbocolor.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_cetak_qr.php?modeajax=view_list_size',
        data: {
          id_buyer: id_buyer,
          id_ws: id_ws,
          id_color: id_color,
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbosize").html(html);
      }
    };

    function getdata() {
      var id_buyer = $('#cbobuyer').val();
      var id_ws = document.form.cbows.value;
      var id_color = document.form.cbocolor.value;
      var id_size = document.form.cbosize.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_cetak_qr.php?modeajax=view_list_data',
        data: {
          id_buyer: id_buyer,
          id_ws: id_ws,
          id_color: id_color,
          id_size: id_size
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
        <form method='post' target='_blank' name='form' action='cetak_qr_print.php'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Buyer #</label>
              <select class='form-control select2' style='width: 100%;' name='cbobuyer' id='cbobuyer' onchange=getws()>
                <?php
                $sql = "select * from
                (select distinct(id_buyer) isi, supplier tampil from act_costing ac
                                inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
                                where ac.cost_date >= '2023-01-01'
                                group by id_buyer
                                order by supplier asc) a";
                IsiCombo($sql, '', 'Pilih Buyer #');
                ?>
              </select>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>No WS *</label>
              <select class='form-control select2' style='width: 100%;' name='cbows' id='cbows' onchange='getdata(); getcolor(); getsize()'>
              </select>
            </div>
            <div class='row'>
              <div class='col-md-4'>
                <div class='form-group'>
                  <label>Cut Number</label>
                  <input type='text' class='form-control' name='txtcutnumber' id='txtcutnumber' required autocomplete="off">
                </div>
              </div>
              <div class='col-md-4'>
                <div class='form-group'>
                  <label>Dari *</label>
                  <input type='text' class='form-control' name='txtdari' id='txtdari' required>
                </div>
              </div>
              <div class='col-md-4'>
                <div class='form-group'>
                  <label>Sampai *</label>
                  <input type='text' class='form-control' name='txtsampai' id='txtsampai' required>
                </div>
              </div>
            </div>
          </div>
          <div class='col-md-2'>
            <div class='form-group'>
              <label>Color *</label>
              <select class='form-control select2' style='width: 100%;' name='cbocolor' id='cbocolor' required onchange='getdata(); getsize();'>
              </select>
            </div>
            <div class='form-group' style="padding-top: 25px;">
              <button type='submit' name='submit' class='btn btn-primary'>Cetak</button>
            </div>
          </div>
          <div class='col-md-2'>
            <div class='form-group'>
              <label>Size *</label>
              <select class='form-control select2' style='width: 100%;' name='cbosize' id='cbosize' required onchange='getdata();'>
              </select>
            </div>
          </div>
          <div class='box-body'>
            <div id='detail_item'></div>
          </div>
        </form>
      </div>
    </div>
  </div><?php } ?>

<?php if ($mod == "bpb_po_item_lokasi") {

  $bpbno = $_GET['bpbno'];
  $id_item = $_GET['id_item'];
  $id_jo = $_GET['id_jo'];

  $querybpb = mysql_query("SELECT bpb.*, mi.mattype, ac.kpno, mi.goods_code, mi.itemdesc, 
  round(coalesce(roll_qty,0),2) roll_qty_lokasi, round(coalesce(bpb.qty,0) - coalesce (roll_qty,0),2) sisa_qty 
  FROM bpb 
  inner join masteritem mi on bpb.id_item = mi.id_item
  inner join jo_det jd on bpb.id_jo = jd.id_jo
  inner join so on jd.id_so = so.id
  inner join act_costing ac on so.id_cost = ac.id
  left join (select bpbno,id_item,id_jo,sum(roll_qty) roll_qty from bpb_det group by bpbno, id_item, id_jo) sr on bpb.bpbno = sr.bpbno and bpb.id_item = sr.id_item and sr.id_jo = bpb.id_jo
  where bpb.bpbno='$bpbno' and bpb.id_item = '$id_item' and bpb.id_jo = '$id_jo'");
  $databpb      = mysql_fetch_array($querybpb);
  $bpbno_int    = $databpb['bpbno_int'];
  $tipe_mat     = $databpb['mattype'];
  $no_ws        = $databpb['kpno'];
  $goods_code   = $databpb['goods_code'];
  $itemdesc     = $databpb['itemdesc'];
  $qty          = $databpb['qty'];
  $bpbno        = $databpb['bpbno'];
  $id_jo        = $databpb['id_jo'];
  $roll_qty_lokasi = $databpb['roll_qty_lokasi'];
  $sisa_qty     = $databpb['sisa_qty'];
  $unit     = $databpb['unit'];

?>
  <script type='text/javascript'>
    function validasi() {
      var total = document.getElementById('total').value;
      var sisa_qty_a = document.form.txtsisa_qty.value;
      if (total > sisa_qty_a) {
        swal({
          title: 'Qty tidak Boleh Melebihi Sisa',
          <?php echo $img_alert; ?>
        });
        valid = false;
      } else valid = true;
      return valid;
      exit;
    }

    function startCalc() {
      interval = setInterval('findTotal()', 1);
    }

    function findTotal() {
      var arr = document.getElementsByClassName('form-control jmlclass');
      var tot = 0;
      for (var i = 0; i < arr.length; i++) {
        if (parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
      }
      document.getElementById('total').value = tot;
    }

    function stopCalc() {
      clearInterval(interval);
    }

    function getListData() {
      var cri_item = document.form.txtroll.value;
      var sat_nya = document.form.txtunitdet.value;
      var txtrak = document.form.txtrak.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bpb_po_std.php?modeajax=view_list_roll',
        data: {
          cri_item: cri_item,
          sat_nya: sat_nya,
          txtrak: txtrak
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_item_roll").html(html);
      }
      $(".select2").select2();
    };
  </script>

  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_bpb_po_std.php?mod=simpan_lokasi' onsubmit='return validasi()'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Nomor BPB</label>
              <input type='text' class='form-control' disabled value='<?php echo $bpbno_int ?>'>
              <input type='hidden' name='bpbnoint_lok' class='form-control' value='<?php echo $bpbno_int ?>'>
              <input type='hidden' name='bpbno_lok' class='form-control' value='<?php echo $bpbno ?>'>
              <input type='hidden' name='id_item_lok' class='form-control' value='<?php echo $id_item ?>'>
              <input type='hidden' name='id_jo_lok' class='form-control' value='<?php echo $id_jo ?>'>
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
                IsiCombo($sql, $tipe_mat, 'Pilih Tipe #');
                ?>
              </select>
            </div>

            <div class='form-group'>
              <label>Jml Detail *</label>
              <select class='form-control select2' style='width: 100%;' name='txtroll'>
                <option value='' disabled selected>Pilih Jml Detail</option>
                <?php
                for ($x = 1; $x <= 100; $x++) {
                  echo "<option value='$x'>$x</option>";
                }
                ?>
              </select>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Kode Barang #</label>
              <input type='text' class='form-control' disabled value='<?php echo $goods_code ?>'>
            </div>

            <div class='form-group'>
              <label>No Ws Global #</label>
              <input type='text' class='form-control' disabled value='<?php echo $no_ws ?>'>
            </div>
            <div class='form-group'>
              <label>Unit Detail *</label>
              <select class='form-control select2' style='width: 100%;' name='txtunitdet'>
                <?php
                $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                    kode_pilihan='Satuan' order by nama_pilihan";
                IsiCombo($sql, $unit, "Pilih Unit")
                ?>
              </select>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Nama Barang #</label>
              <input type='text' class='form-control' disabled value='<?php echo $itemdesc ?>'>
            </div>
            <div class='row'>
              <div class='col-md-3'>
                <div class='form-group'>
                  <label>Qty BPB #</label>
                  <input type='text' size='5' class='form-control' readonly value='<?php echo $qty ?>'>
                </div>
              </div>
              <div class='col-md-3'>
                <div class='form-group'>
                  <label>Qty Lokasi #</label>
                  <input type='text' size='5' class='form-control' readonly value='<?php echo $roll_qty_lokasi ?>'>
                </div>
              </div>
              <div class='col-md-3'>
                <div class='form-group'>
                  <label>Sisa Qty #</label>
                  <input type='hidden' size='5' class='form-control' name='txtsisa_qty' id='txtsisa_qty' value='<?php echo $sisa_qty ?>'>
                  <input type='text' size='5' class='form-control' readonly value='<?php echo $sisa_qty ?>'>
                </div>
              </div>
            </div>
            <div class='form-group'>
              <label>No. Rak *</label>
              <select class='form-control select2' style='width: 100%;' name='txtrak' onchange='getListData()'>
                <?php
                $sql = "select id isi,concat(kode_rak,' ',nama_rak) tampil from master_rak 
                  order by kode_rak";
                IsiCombo($sql, "", "Pilih Rak")
                ?>
              </select>
            </div>
          </div>
          <div class='box-body'>
            <div id='detail_item_roll'></div>
          </div>
        </form>
      </div>
    </div>
  </div><?php }
        ?>
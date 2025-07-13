<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
// $akses = flookup("update_dok_pab","userpassword","username='$user'");
// if ($akses=="0") 
//   { echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$mod=$_GET['mod'];
$bppbno = $_GET['bppbno'];
$username =  $_SESSION['username'];

$sql_cek_data  = mysql_query("select bppbno_int, bppbdate, a.id_supplier, remark, invno, bppbno_req pono, jenis_dok, jenis_trans, supplier from bppb a INNER JOIN mastersupplier b on b.id_supplier = a.id_supplier where bppbno = '$bppbno' limit 1");
$databpb = mysql_fetch_array($sql_cek_data);

$bppbno_int = $databpb['bppbno_int'];
$bpbdate = fd_view($databpb['bppbdate']);
$id_supplier = $databpb['id_supplier'];
$remark = $databpb['remark'];
$invno = $databpb['invno'];
$pono = $databpb['pono'];
$jenis_dok = $databpb['jenis_dok'];
$jenis_trans = $databpb['jenis_trans'];
$supplier = $databpb['supplier'];

?>
<script type='text/javascript'>
  function validasi()
  { var bpbdate = document.form.bpbdate.value;
    var id_supplier = document.form.id_supplier.value;
    var jenis_dok = document.form.jenis_dok.value;
    var jenis_trans = document.form.jenis_trans.value;
    var invno = document.form.invno.value;
    var remark = document.form.remark.value;;

    if (bpbdate == '') { alert('Tgl BPB Kosong'); valid = false;}
    else if (id_supplier == '') { alert('Supplier Kosong'); valid = false;}
    else if (jenis_dok != '-' && jenis_dok == '') { alert('Jenis Dokumen Kosong'); valid = false;}
    else if (jenis_trans == '') { alert('Jenis Transaksi Kosong'); valid = false;}
    else if (remark == '') { alert('Keterangan Kosong'); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>

<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' enctype='multipart/form-data' action='update_bppb_header.php?bppbno=<?php echo $bppbno; ?>' onsubmit='return validasi()'>

        <div class="row" style="margin-left:8px;">
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Nomor BPB</label>
              <input type='text' class='form-control' name='bpbno_int' readonly value='<?php echo $bppbno_int;?>' >
            </div> 
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Tgl. BPB *</label>
              <input type='text' class='form-control' id='date_editbpb' name='bpbdate' required placeholder='Masukkan Tanggal' value='<?php echo $bpbdate; ?>'>
            </div>    
          </div>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Dikirim Ke *</label>
              <select class='form-control select2' style='width: 100%;' name='id_supplier' id='id_supplier'>
                <?php
                $sql = "select a.id_supplier isi, supplier tampil from mastersupplier a order by supplier asc";
                IsiCombo($sql, $id_supplier, 'Pilih Tujuan Kirim #');
                ?>
              </select>
            </div> 
          </div>
        </div>

        <div class='row' style="margin-left:8px;">
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Nomor Request</label>
              <input type='text' class='form-control' name='pono' readonly value='<?php echo $pono;?>' >
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Jenis Dokumen *</label>
              <select class='form-control select2' style='width: 100%;' name='jenis_dok' id='jenis_dok'>
                <?php 
                $sql = "select * from (SELECT nama_pilihan isi, nama_pilihan tampil FROM masterpilihan WHERE kode_pilihan='JENIS_DOK_OUT'  UNION select 'INHOUSE' isi, 'INHOUSE' tampil) a";
                IsiCombo($sql, $jenis_dok, 'Pilih Jenis Dokumen');
                ?>
              </select>
            </div>  
          </div>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Jenis Transaksi *</label>
              <select class='form-control select2' style='width: 100%;' name='jenis_trans' id='jenis_trans'>
                <?php 
                $sql = "select upper(nama_trans) isi, upper(nama_trans) tampil from mastertransaksi where jenis_trans='OUT' and jns_gudang = 'FACC' order by id";
                IsiCombo($sql, $jenis_trans, 'Pilih Jenis Transaksi');
                ?>
              </select>
            </div>
          </div>
        </div>

        <div class='row' style="margin-left:8px;">
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Nomor SJ/Inv *</label>
              <input type='text' class='form-control' name='invno' value='<?php echo $invno;?>' >
            </div>
          </div>

          <div class='col-md-5'>
            <div class='form-group'>
              <label>Keterangan *</label>
              <input type='text' class='form-control' name='remark' value='<?php echo $remark;?>' >
            </div>
          </div>
        </div>
        <div class='form-group' style="margin-left:22px;">
          <button type='submit' name='submit' class='btn btn-success'>Update</button>
        </div>
      </form> 

      <div class='col-md-12'>
        <form method="post" action="update_bppb_detail.php">
          <table id="examplefix" class="display responsive" style="width:100%; font-size: 13px;">
            <thead>
              <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 15%;">WS #</th>
                <th style="width: 10%;">Id Item</th>
                <th style="width: 15%;">Kode Barang</th>
                <th style="width: 26%;">Nama Barang</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 10%;">Satuan</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $bppbno = $_GET['bppbno'];

              $sql = "select a.id, a.bppbno, jo.kpno, a.id_item, b.goods_code, b.itemdesc, a.qty, a.unit
              FROM bppb a
              INNER JOIN masteritem b ON b.id_item = a.id_item
              LEFT JOIN (
              SELECT id_jo, kpno, styleno 
              FROM act_costing ac
              INNER JOIN so ON ac.id = so.id_cost
              INNER JOIN jo_det jod ON so.id = jod.id_so
              GROUP BY id_jo
              ) jo ON jo.id_jo = a.id_jo
              WHERE bppbno = '$bppbno'
              GROUP BY a.id";

              $query = mysql_query($sql);
              while($data = mysql_fetch_array($query)) {
                echo "<tr>";
                echo "<td><input type='hidden' name='iddata[]' value='{$data['id']}'>{$data['id']}</td>";
                echo "<td>{$data['kpno']}</td>";
                echo "<td>{$data['id_item']}</td>";
                echo "<td>{$data['goods_code']}</td>";
                echo "<td>{$data['itemdesc']}</td>";
                echo "<td><input type='text' class='form-control text-right' size='8' name='totqty[]' value='{$data['qty']}'></td>";
                echo "<td>{$data['unit']}</td>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>

          <!-- hidden input untuk bpbno -->
          <input type="hidden" name="bppbno" value="<?= htmlspecialchars($bppbno) ?>">
          <br>
          <div class="text-left mt-3">
            <button type="submit" name="update_all" class="btn btn-primary">Update Detail</button>
          </div>
        </form>


      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
 $(document).ready(function() {

    // var jenisDokElement = document.getElementById('jenis_dok').value;
    // var jenisDok = jenisDokElement ? jenisDokElement.value : null;
    //   jenisDok = jenisDok.substring(3); // Menghilangkan 3 karakter pertama
    // jenisDok = jenisDok.replace(/\./g, ''); // Menghapus semua titik (.)
    // // alert(jenisDok)
    // $.ajax({
    //     url: 'getNomorPengajuan.php', // Script untuk mengambil nomor pengajuan berdasarkan jenis dokumen
    //     method: 'GET',
    //     data: { jenis_dok: jenisDok },
    //     success: function(response) {
    //       console.log(response);
    //       $('#nomor_aju').html(response); // Update pilihan nomor pengajuan
    //     }
    //   });
  });
</script>
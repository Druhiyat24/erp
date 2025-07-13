<?php
if (empty($_SESSION['username'])) {
    header("location:../../../index.php");
}
$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("PO_P_DRA_BW_FORM", "userpassword", "username='$user'");
if ($akses == "0") {
    echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>";
}

# END CEK HAK AKSES KEMBALI
?>
<style>
    .form {
        width: 65% !important;
        margin-bottom: 5px;
        height: 27px;
    }

    .select2 {
        /* height: 27px !important; */
        margin-bottom: 5px;
    }

    th {
        text-align: center;
    }
</style>

<script type="text/javascript">
    function additem() {
      var cbokat = $('#cbokat').val();
      var txt_value = $('#txt_value').val();
      var txt_desc = $('#txt_desc').val();
      var chk_ppn = $('#chk_ppn').val();
      // alert(cbokat+txt_value+txt_desc);
      var html = $.ajax({
        type: "POST",
        url: 'ajaxpo.php?modeajax=simpan_temp',
        data: {
          cbokat: cbokat,
          txt_value: txt_value,
          chk_ppn: chk_ppn,
          txt_desc: txt_desc
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_temp").html(html);
        // alert('test');
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          scrollCollapse: true,
          paging: false,
          searching: false,
          fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
          }
        });
      });
    };

    function del_item(id_temp) {
      var html = $.ajax({
        type: "POST",
        url: 'ajaxpo.php?modeajax=delete_temp',
        data: {
          id_temp: id_temp
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_temp").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          scrollCollapse: true,
          paging: false,
          searching: false,
          fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
          }
        });
      });
    };
</script>

<div class="box">
    <div class="box-body">
        <form method='post' name='form'>
            <div class='col-md-3'>
                <div class='form-group'>
                    <label>DRAFT PO #</label>
                    <input type='text' readonly class='form-control' id="txtpono" name='txtpono' placeholder='Masukkan Draft PO #'>
                </div>
                <div class='form-group'>
                    <label>Draft PO Date *</label>
                    <input type='text' autocomplete="off" class='form-control' id='datepicker1' name='txtpodate' placeholder='Masukkan Draft PO Date' value=''>
                </div>
                <div class='form-group'>
                    <label>Jenis Item *</label>
                    <select id="jenis_item" class='form-control select2' style='width: 100%;' name='txtJItem' onchange='getListSuppGlobal(this.value)'>
                    </select>
                </div>
                <div class='form-group'>
                    <label>Supplier *</label>
                    <select class='form-control select2' style='width: 100%;' name='txtid_supplier' id='cbosupp'>
                    </select>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='form-group'>
                    <label>Currency *</label>
                    <select class='form-control ' id="curr" style='width: 100%;' name='txtcurr' onchange='getJOListGlobal()'>
                    </select>
                </div>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='form-group'>
                            <label>Payment Terms *</label>
                            <select class='form-control select2' id="txtid_terms" style='width: 100%;' name='txtid_terms'>
                            </select>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Days *</label>
                            <input type='text' id='txtdays' class='form-control' name='txtdays' value=''>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Day Terms *</label>
                            <select class='form-control select2' style='width: 100%;' id="txtid_dayterms" name='txtid_dayterms'>
                            </select>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>ETD Date *</label>
                            <input type='text' autocomplete="off" class='form-control' id='datepicker2' name='txtetddate' placeholder='Masukkan ETD Date' value=''>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>ETA Date *</label>
                            <input type='text' autocomplete="off" class='form-control' id='datepicker3' name='txtetadate' placeholder='Masukkan ETA Date' value=''>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='form-group'>
                    <label>Expected Date *</label>
                    <input type='text' class='form-control' id='datepicker4' name='txtexpdate' autocomplete="off" placeholder='Masukkan Expected Date' value=''>
                </div>
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Discount</label>
                            <input type='text' class='form-control' id="txtdisc" name='txtdisc' placeholder='Masukkan Discount' value=''>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>PPN %</label>
                            <input type='text' id='ppn_nya' readonly class='form-control' name='txtppn' placeholder='Masukkan PPN' value=''>
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    <label>Notes</label>
                    <textarea row='2' style=" height:40px;" class='form-control' id="txtnotes" name='txtnotes' placeholder='Masukkan Notes'></textarea>
                </div>
            </div>
            <div class='col-md-3'>
                <input type="checkbox" onclick="checkpkp()" name="pkp" id="pkp" value="" />PKP
                <div class='form-group'>
                    <label>Tax</label>
                    <select class='form-control select2' id="triger_ppn" style='width: 100%;' onchange="" name='tax_nya'>
                    </select>
                </div>
                <div class='form-group'>
                    <label>Kurs *</label>
                    <input type='text' class='form-control' id='n_kurs' name='n_kurs' placeholder='Masukkan Kurs' value=''>
                    <label>Tipe Commercial *</label>
                    <select class='form-control select2' style='width: 100%;' id="txt_tipecom" name='txt_tipecom'>
                        <option value="">--- Pilih Tipe Commersial PO ---</option>
                        <option value="REGULAR">REGULAR</option>
                        <option value="FOC">FOC</option>
                        <option value="BUYER">BUYER</option>
                    </select>
                </div>
            </div>

            <div class='col-md-6'>
                <div class='form-group'>
                    <label>Job Order # *</label>
                    <select class='form-control select2' multiple='multiple' style='width: 100%;' name='txtJOItem[]' id='cboJO' onchange='getJOGlobal()'>
                    </select>
                </div>
            </div>

            <div class='box-body'>
                <div id='detail_item'></div>
            </div>

            <div class='box-body'>
      <form method='post' name='form1'>
        <h4> Rincian Biaya Lain - Lain </h4>
        <div class='row'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Kategori :</label>
              <select class='form-control select2' style='width: 100%;' name='cbokat' id='cbokat'>
                <?php
                $sql = "select id isi, UPPER(nama_kategori) tampil 
              from po_master_pilihan where status = 'Y'";
                IsiCombo($sql, '', '-- Pilih Kategori --');
                ?>
              </select>
            </div>
          </div>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Value *:</label>
              <input type='text' class='form-control' name='txt_value' id='txt_value'  required>
            </div>
          </div>

          <div class='col-md-1'>
            <div class='form-group'>
              <label>PPN 11% :</label>
              <br>
              <input type="checkbox" style="width: 40px;height: 20px;"  onclick="checkitem()" name="chk_ppn" id="chk_ppn" value="" /> 
            </div>
          </div>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Description</label>
              <input type='text' class='form-control' name='txt_desc' id='txt_desc' >
            </div>
          </div>

          <div class='col-md-1'>
            <div class='form-group'>

            </div>
            <div class='form-group' style="padding:10px">
              <button type='button' name='submit_temp' class='btn btn-primary' onclick='additem()'>Tambah</button>
            </div>
          </div>

        </div>
        <div id='detail_temp'></div>
      </form>
    </div>

            <div class='col-md-3'>
                <a href="#" class='btn btn-primary' onclick="Save_global()">Simpan</a>
                <button type='button' class='btn btn-primary' onclick='select_all()'>Select All</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="/css/overlay.css">
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/global.js"></script>
<script src="js/DraftPoBWForm.js?<?php echo date('Ymdhms') ?>"></script>
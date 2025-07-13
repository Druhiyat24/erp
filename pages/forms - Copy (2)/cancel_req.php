<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("cancel_all_out","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { 
    var reason = document.form.txtreason.value;
    var sono = document.form.txtid_cost.value;
    var ketrinya = 0;
    var keto = document.form.getElementsByClassName('ketcl');
    for (var i = 0; i < keto.length; i++) 
    { 
      if (keto[i].value == 'X')
      { ketrinya = ketrinya + 1; break; }
    }
    
    if (sono == '') { swal({ title: 'Transaksi # Tidak Boleh Kosong', type: 'warning' }); valid = false;}
    else if (reason == '') { document.form.txtreason.focus(); swal({ title: 'Reason Tidak Boleh Kosong', type: 'warning' }); valid = false;}
    else if (ketrinya > 0) { swal({ title: 'Req Tidak Bisa Dicancel, Sudah Ada BKB', type: 'warning' }); valid = false;}
    else valid = true;
    return valid;
    exit;
  };
  function get_list_data()
  {
    var bppbnya = document.form.txtid_cost.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_get_list_car.php',
        data: { bppbnya: bppbnya },
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
    $(".select2").select2();
    $(document).ready(function() {
      var table = $('#examplefix3').DataTable
      ({  scrollCollapse: true,
          paging: true,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };
  function get_list_trx(perinya)
  {   
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_get_list_req_cancel.php',
        data: {perinya: perinya},
        async: false
    }).responseText;
    if(html)
    {  
        $("#cbotrx").html(html);
    }
  };
</script>
<?php
# END COPAS VALIDASI

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' enctype='multipart/form-data' action='s_cancel_req.php?mod=$mod' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          ?>
          <div class='form-group'>
            <label>Pilih Bulan Req *</label>
            <input type='text' class='form-control monthpick' onchange='get_list_trx(this.value)' 
              autocomplete='off' onkeydown='return false' placeholder='Pilih Bulan'>
          </div>
          <div class='form-group'>
            <label>Req # *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_cost' id='cbotrx' 
              onchange='get_list_data()'>
              <?php
                // $sql = "select bppbno isi,if(bppbno_int!='',bppbno_int,bppbno) tampil from 
                //   bppb group by bppbno";
                // IsiCombo($sql,$id_cost,'Pilih SJ #');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Reason *</label>
            <textarea row="4" class='form-control' name='txtreason' placeholder="Masukan Reason"></textarea>
          </div>
        </div>
        <div class='col-md-12'>
          <div id='detail_item'>
          </div>
          <button type='submit' name='submit' class='btn btn-warning'>Simpan</button>
        </div>
      <?php 
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>
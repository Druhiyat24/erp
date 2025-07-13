<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("setting_payroll","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {$usernya=$_GET['id'];} else {$usernya="";}
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
# END COPAS VALIDASI
# COPAS ADD
$mod=$_GET['mod'];
?>
<?php 
# END COPAS ADD
?>
<script type="text/javascript">
  function getList()
  { var nik = document.form.txtNIK.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_set_pay.php',
        data: {nik: nik},
        async: false
    }).responseText;
    if(html)
    { $("#detail_item").html(html); }
  };
</script>
<form method='post' name='form' action='s_set_pay.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Setting Payroll</h3>
    </div>
    <div class='col-md-3'>
      <div class='form-group'>
        <label>NIK *</label>
        <select class='form-control select2' style='width: 100%;' name='txtNIK' onchange='getList()'>
          <?php 
            $sql = "select nik isi,concat(nik,':',nama) tampil from 
              hr_masteremployee where selesai_kerja='0000-00-00' or selesai_kerja is null";
            IsiCombo($sql,'','Pilih NIK');
          ?>
        </select>
      </div>
    </div>
    <div class='box-body'>
      <div id='detail_item'></div>
    </div>
    <div class='col-md-3'>
      <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
    </div>
  </div>
</form>
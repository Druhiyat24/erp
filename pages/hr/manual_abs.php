<?php 
include '../../include/conn.php';
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if (isset($_GET['mode'])) { $mode=$_GET['mode']; } else { $mode=""; }
$titlenya="Absensi Manual";
if (isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }

# COPAS EDIT
if ($id_item=="")
{ $nik = "";
  $tanggal = "";
  $TimeIn = "";
  $TimeOut = "";
}
else
{ $cri=split(":",$id_item);
  $nik = $cri[0];
  $tanggal = fd($cri[1]);
  $query = mysql_query("SELECT * FROM hr_timecard where empno='$nik' 
    and workdate='$tanggal'");
  $data = mysql_fetch_array($query);
  $tanggal = fd_view($cri[1]);
  $TimeIn = $data['TimeIn'];
  $TimeOut = $data['TimeOut'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var nik = document.form.txtnik.value;";
    echo "var tanggal = document.form.txttanggal.value;";
    echo "var TimeIn = document.form.txtTimeIn.value;";
    echo "var TimeOut = document.form.txtTimeOut.value;";

    if ($mode=="dept")
    { echo "if (nik == '') { alert('Department tidak boleh kosong'); document.form.txtnik.focus();valid = false;}";  }
    else
    { echo "if (nik == '') { alert('NIK tidak boleh kosong'); document.form.txtnik.focus();valid = false;}";  }
    echo "else if (tanggal == '') { alert('Tanggal tidak boleh kosong'); document.form.txttanggal.focus();valid = false;}";
    echo "else if (TimeIn == '') { alert('Jam Masuk tidak boleh kosong'); document.form.txtTimeIn.focus();valid = false;}";
    echo "else if (TimeOut == '') { alert('Jam Pulang tidak boleh kosong'); document.form.txtTimeOut.focus();valid = false;}";
    echo "else valid = true;";
    echo "return valid;";
    echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getListData()
  { var dept = document.formfilter.txtdept.value;
    var tglnya = document.formfilter.txttglfilter.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_absman.php?mdajax=view_list_nik2',
        data: {dept: dept,tglnya: tglnya},
        async: false
    }).responseText;
    if(html)
    { $("#detail_item").html(html); }
  };
</script>

  <div class="box">
  <?PHP
  # COPAS ADD
  if ($mod=="13") {
  echo "<div class='box-body'>";
  echo "<div class='row'>";
  echo "<form method='post' name='form' action='save_absman.php?mode=$mode&id=$id_item' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    if ($mode=="dept")
    { echo "<label>Department *</label>";
      $sql = "select department isi,department tampil from hr_masteremployee 
        where department!='' group by department order by department";
      echo "<select class='form-control select2' style='width: 100%;' name='txtnik'>";
      IsiCombo($sql,$nik,'Pilih Department');
      echo "</select>";
    }
    else
    { echo "<label>NIK *</label>";
      if ($id_item!="") {$sql_wh=" where nik='$nik'";} else {$sql_wh="";}
      $sql = "select nik isi,concat(nik,'|',nama) tampil from hr_masteremployee $sql_wh order by nik";
      echo "<select class='form-control select2' style='width: 100%;' name='txtnik'>";
      IsiCombo($sql,$nik,'Pilih NIK');
      echo "</select>";
    }
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tanggal *</label>";
    if ($id_item!="") {$read=" readonly";} else {$read=" id='datepicker1' ";}
    echo "<input type='text' $read class='form-control' name='txttanggal' placeholder='Masukkan Tanggal' value='$tanggal'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Jam Masuk *</label>";
    echo "<input type='text' class='form-control' name='txtTimeIn' placeholder='Masukkan Jam Masuk' value='$TimeIn'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Jam Pulang *</label>";
    echo "<input type='text' class='form-control' name='txtTimeOut' placeholder='Masukkan Jam Pulang' value='$TimeOut'>";
  echo "</div>";
  echo "<div class='box-footer'>";
    echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
  echo "</div>";
  echo "</form>";
  echo "</div>";
  echo "</div>";
  echo "</div>";
  echo "</div>";
  # END COPAS ADD
  } else {
  ?>
  <div class="box">
  <div class="box-header">
      <h3 class="box-title">Detil <?PHP echo $titlenya; ?></h3>
      <a href='../hr/?mod=13&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
    <form name='formfilter'>
      <div class='col-md-3'>
        <div class='form-group'>
          <label>Tanggal *</label>
          <input type='text' $read class='form-control' id='datepicker2' 
            name='txttglfilter' onchange='getListData()' placeholder='Masukkan Tanggal'>
        </div>
      </div>
      <div class='col-md-3'>
        <div class='form-group'>
          <label>Dept *</label>
          <?php 
          $sql = "select nama_pilihan isi, nama_pilihan tampil from 
            masterpilihan where kode_pilihan='Dept'";
          echo "<select class='form-control select2' style='width: 100%;' 
            name='txtdept' onchange='getListData()'>";
          IsiCombo($sql,'','Pilih Dept');
          echo "</select>";
          ?>
        </div>
      </div>
      <div id='detail_item'></div>
    </form>
  </div>
  <?php } ?>
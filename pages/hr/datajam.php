<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode="";
$titlenya="Data Jam Kerja dan Shift";
if (isset($_GET['id'])) { $idhari=$_GET['id']; } else { $idhari=""; }
if (isset($_GET['pro'])) { $pro=$_GET['pro']; } else { $pro=""; }

$hari_skrg=date('l');
# COPAS EDIT
if ($idhari =="")
{ $hari       = "";
  $jabatan    = "";
  $jam_masuk  = "";
  $jam_pulang = "";
  $status     = "";
  $createdate = "";
}
else
{ $query = mysql_query("SELECT * FROM hr_masterjamkerja where idhari='$idhari'");
  $data = mysql_fetch_array($query);
  $idhari = $_GET['id'];
  $hari     = $data['hari'];
  $jabatan  = $data['kode_pilihan'];
  $jam_masuk  = $data['jam_masuk'];
  $jam_pulang  = $data['jam_pulang'];
  $status   = $data['status'];
  $createdate  = date('d M y');

  if ($pro=="Copy") { $idhari=""; }
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
  function validasi()
  { var hari = document.form.txthari.value;
    var jabatan = document.form.txtjbt.value;
    var jam_masuk = document.form.txtjam_masuk.value;
    var jam_pulang = document.form.txtjam_pulang.value;
    var status = document.form.txtstatus.value;
    var createdate = document.form.txtcreatedate.value;

    if (hari == '')
    { alert('Hari tidak boleh kosong');
      document.form.txthari.focus();valid = false;
    }
    else if (jabatan == '')
    { alert('Jabatan tidak boleh kosong');
      document.form.txtjabatan.focus();valid = false;
    }
    else if (jam_masuk == '')
    { alert('Jam Masuk tidak boleh kosong');
      document.form.txtjam_masuk.focus();valid = false;
    }
    else if (jam_pulang== '')
    { alert('Jam Pulang tidak boleh kosong');
      document.form.txtjam_pulang.focus();valid = false;
    }
    else if (status == '')
    { alert('status tidak boleh kosong');
      document.form.txtstatus.focus();valid = false;
    }
    else if (createdate == '')
    { alert('Create Date tidak boleh kosong');
      document.form.txtcreatedate.focus();valid = false;
    }";
      echo "else valid = true;";
      echo "return valid;";
      echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
if ($mod=="35DP") {
?>
<div class="box box">
  <?PHP
  # COPAS ADD
  echo "<div class='box-body'>";
  echo "<div class='row'>";
  echo "<form method='post' name='form' action='s_datajam.php?id=$idhari' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
  echo "<input type='hidden' class='form-control' name='txtidhari' value='$idhari' >";
  echo "<div class='form-group'>";
    echo "<label>Hari *</label>";
    echo "<input type='text' class='form-control' name='txthari' placeholder='Masukkan hari' value='$hari_skrg' readonly>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Jabatan *</label>";
    $sql = "select nama_pilihan isi, nama_pilihan tampil from
          masterpilihan where kode_pilihan='Jabatan'";
      echo "<select class='form-control select2' style='width: 100%;' name='txtjabatan'>";
      IsiCombo($sql,$jabatan,'Pilih Jabatan');
      echo "</select>";
    // echo "<input type='text' class='form-control' name='txtjabatan' placeholder='Masukkan Jabatan' value='$jabatan'>";
  echo "</div>";

  echo "<div class='bootstrap-timepicker'>";
  echo "<div class='form-group'>";
        echo "<label>Jam Masuk *</label>";
        echo "<input type='time' class='form-control timepicker' name='txtjam_masuk' value=<?= echo date('H:m'); ?>";
  echo "</div>";       
  echo "</div>";
  echo "</div>";

  echo "<div class='col-md-3'>";

  echo "<div class='form-group'>";
    echo "<label>Status *</label>";
    $sql = "select nama_pilihan isi, nama_pilihan tampil from
          masterpilihan where kode_pilihan='STATUS SHIFT'";
      echo "<select class='form-control select2' style='width: 100%;' name='txtstatus'>";
      IsiCombo($sql,$status,'Pilih Status');
      echo "</select>";
    // echo "<input type='text' class='form-control' name='txtjabatan' placeholder='Masukkan Jabatan' value='$jabatan'>";
  echo "</div>";

  echo "<div class='bootstrap-timepicker'>";
  echo "<div class='form-group'>";
         echo "<label>Jam Pulang</label>";
         echo "<input type='time' class='form-control timepicker1'  name='txtjam_pulang' value=<?= echo date('H:m'); ?>";
    echo "</div>";
    echo "</div>";
    echo "
      <div class='form-group'>";
           echo "<label>Create Date</label>";
           echo "<input type='text' class='form-control' id='datepicker1' name='txtcreatedate' placeholder='Masukkan Create Date' value='$createdate'>";
      echo "</div>";

  echo "<div class='box-footer'>";
    echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
  echo "</div>";
  echo "</form>";
  echo "</div>";
  echo "</div>";
  echo "</div>";
echo "</div>";
} else {
  # END COPAS ADD
  ?>
  <div class="box">
  <div class="box-header">
      <h3 class="box-title">List <?php echo $titlenya; ?></h3>
      <a href='../hr/?mod=35DP' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
      <table id="example1" class="display responsive" style="font-size:11px;">
          <thead>
          <tr>
				      <th>No</th>
              <th>Hari</th>
              <th>Jabatan</th>
              <th>Jam Masuk</th>
              <th>Jam Pulang</th>
              <th>Status</th>
              <th>Create Date</th>
              <th width='10%'>Aksi</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("SELECT * FROM hr_masterjamkerja order by hari desc ");
  				  $no = 1;
    				while($data = mysql_fetch_array($query))
    			  { echo "<tr>";
      				  echo "<td>$no</td>";
      				  echo "<td>$data[hari]</td>";
      				  echo "<td>$data[kode_pilihan]</td>";
      				  echo "<td>$data[jam_masuk]</td>";
                echo "<td>$data[jam_pulang]</td>";
      				  echo "<td>$data[status]</td>";
                echo "<td>$data[createdate]</td>";
                echo "
                	<td>
                		<a href='?mod=35DP&id=$data[idhari]'
			              	$tt_ubah</a>
			            </td>";
      				echo "</tr>";
    				  $no++; // menambah nilai nomor urut
    				}
  				  ?>
          </tbody>
      </table>
      </div>
      <?php } ?>
<script type='text/javascript'>
    $('.clockpicker').clockpicker({
      placement: 'top',
      align: 'left',
      donetext: 'Done'
    });
</script>
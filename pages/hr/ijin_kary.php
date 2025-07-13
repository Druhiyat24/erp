<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$akses = flookup("hr_ijin","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");
if (isset($_GET['id'])) {$id_item=$_GET['id'];} else {$id_item="";}
# COPAS EDIT
if ($id_item=="")
{ $nik = "";
  $tanggal = "";
  $kode_absen = "";
  $alasan = "";
  $keterangan = "";
}
else
{ $cri=split(":",$id_item);
  $nik=$cri[0];
  $tgl=$cri[1];
  $query = mysql_query("SELECT * FROM hr_ijinkaryawan where nik='$nik' 
    and tanggal='$tgl'");
  $data = mysql_fetch_array($query);
  $nik = $data['nik'];
  $tanggal = fd_view($data['tanggal']);
  $kode_absen = $data['kode_absen'];
  $alasan = $data['alasan'];
  $keterangan = $data['keterangan'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
  function validasi()
  { var nik = document.form.txtnik.value;
    var tanggal = document.form.txttanggal.value;
    var kode_absen = document.form.txtkode_absen.value;
    var alasan = document.form.txtalasan.value;
    var keterangan = document.form.txtketerangan.value;
    if (nik == '') { document.form.txtnik.focus(); swal({ title: 'NIK Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (tanggal == '') { document.form.txttanggal.focus(); swal({ title: 'Tanggal Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (kode_absen == '') { document.form.txtkode_absen.focus(); swal({ title: 'Kode Absen Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (keterangan == '') { document.form.txtketerangan.focus(); swal({ title: 'Keterangan Tidak Boleh Kosong', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";
# END COPAS VALIDASI
# COPAS ADD
if ($mod=="20") {
?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_ijin.php?mod=<?php echo $mod; ?>&id=<?php echo $id_item; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>                
          <div class='form-group'>
            <label>NIK *</label>
            <select class='form-control select2' style='width: 100%;' name='txtnik'>
              <?php 
                if ($id_item=="") {$sql_wh="";} else {$sql_wh=" and nik='$nik'";}
                $sql = "select nik isi,concat(nik,' ',nama) tampil 
                  from hr_masteremployee where 
                  selesai_kerja is null $sql_wh ";
                IsiCombo($sql,$nik,'Pilih NIK');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label>Tanggal *</label>
            <?php 
            if ($id_item=="") {$readonly="";} else {$readonly=" readonly ";}
            ?>
            <input type='text' <?php echo $readonly; ?> class='form-control' id='datepicker1' name='txttanggal' placeholder='Masukkan Tanggal' value='<?php echo $tanggal;?>' >
          </div>          
          <div class='form-group'>
            <label>Kode Absen *</label>
            <select class='form-control select2' style='width: 100%;' name='txtkode_absen'>
              <?php 
                $sql = "select kodeabsen isi,concat(kodeabsen,' ',keterangan) tampil
                  from hr_masterabsen";
                IsiCombo($sql,$kode_absen,'Pilih Kode Absen');
              ?>
            </select>
          </div>
        </div>
        <div class='col-md-3'>          
          <div class='form-group'>
            <label>Alasan *</label>
            <select class='form-control select2' style='width: 100%;' name='txtalasan'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil
                  from masterpilihan where kode_pilihan='Alasan'";
                IsiCombo($sql,$alasan,'Pilih Alasan');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label>Keterangan *</label>
            <input type='text' class='form-control' name='txtketerangan' placeholder='Masukkan Keterangan' value='<?php echo $keterangan;?>' >
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><?php 
# END COPAS ADD
} else { ?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Data Ijin Karyawan</h3>
      <a href='../hr/?mod=20' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>NIK</th>
          <th>Nama</th>
          <th>Tanggal</th>
          <th>Kode Absen</th>
          <th>Alasan</th>
          <th>Keterangan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("SELECT a.*,s.nama from hr_ijinkaryawan a inner join hr_masteremployee s on a.nik=s.nik");
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$data[nik]</td>";
            echo "<td>$data[nama]</td>";
            echo "<td>$data[tanggal]</td>";
            echo "<td>$data[kode_absen]</td>";
            echo "<td>$data[alasan]</td>";
            echo "<td>$data[keterangan]</td>";
            $proed=$data['nik'].":".$data['tanggal'];
            echo "<td><a href='?mod=20&id=$proed'>Ubah</a>";
            echo " | <a href='del_data.php?id=$proed&pro=Ijin'";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo "Hapus</a></td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>
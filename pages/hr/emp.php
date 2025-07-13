<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode="";
$titlenya="Data Karyawan";
if (isset($_GET['id'])) { $nik=$_GET['id']; } else { $nik=""; }
if (isset($_GET['pro'])) { $pro=$_GET['pro']; } else { $pro=""; }
# COPAS EDIT
if ($nik=="")
{ $nama = "";
  $jenis_kelamin = "";
  $tmp_lahir = "";
  $tgl_lahir = "";
  $base_lokasi = "";
  $status_karyawan = "";
  $no_kontrak = "";
  $divisi_kerja = "";
  $lokasi_kerja = "";
  $jabatan = "";
  $dept = "";
  $bagian = "";
  $line = "";
  $mulai_kerja = "";
  $selesai_kontrak1 = "";
  $mulai_kontrak2 = "";
  $selesai_kontrak2 = "";
  $tgl_permanent = "";
  $agama = "";
  $alamat_karyawan = "";
  $no_npwp = "";
  $jenis_jamkes = "";
  $no_ktp_tk = "";
  $exp_ktp_tk = "";
  $no_bpjs_kes_tk = "";
  $no_bpjs_tk = "";
  $id_absen = "";
}
else
{ $query = mysql_query("SELECT * FROM hr_masteremployee where nik='$nik'");
  $data = mysql_fetch_array($query);
  $nama = $data['nama'];
  $jenis_kelamin = $data['jenis_kelamin'];
  $tmp_lahir = $data['tempat_lahir'];
  $tgl_lahir = date('d M Y',strtotime($data['tgl_lahir']));
  $base_lokasi = $data['base_lokasi'];
  $status_karyawan = $data['status_karyawan'];
  $no_kontrak = $data['no_kontrak'];
  $divisi_kerja = $data['divisi_kerja'];
  $lokasi_kerja = $data['lokasi_kerja'];
  $jabatan = $data['jabatan'];
  $dept = $data['department'];
  $bagian = $data['bagian'];
  $line = $data['line'];
  $mulai_kerja = fd_view($data['mulai_kerja']);
  $selesai_kontrak1 = fd_view($data['selesai_kotrak1']);
  if ($data['mulai_kontrak2']=="")
  { $mulai_kontrak2 = ""; }
  else
  { $mulai_kontrak2 = fd_view($data['mulai_kontrak2']); }
  $selesai_kontrak2 = $data['selesai_kontrak2'];
  $tgl_permanent = $data['tgl_permanent'];
  $agama = $data['agama'];
  $alamat_karyawan = $data['alamat_karyawan'];
  $no_npwp = $data['no_npwp'];
  $jenis_jamkes = $data['jenis_jamkes'];
  $no_ktp_tk = $data['no_ktp_tk'];
  $exp_ktp_tk = fd_view($data['exp_date_ktp_kk']);
  $no_bpjs_kes_tk = $data['no_bpjs_kes_tk'];
  $no_bpjs_tk = $data['no_bpjs_tk'];
  $id_absen = $data['id_absen'];
  if ($pro=="Copy") { $nik=""; }
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
  function validasi()
  { var nik = document.form.txtnik.value;
    var nama = document.form.txtnama.value;
    var jenis_kelamin = document.form.txtjenis_kelamin.value;
    var tgl_lahir = document.form.txttgl_lahir.value;
    var divisi_kerja = document.form.txtdivisi_kerja.value;
    var serikat = document.form.txtserikat.value;
    var jabatan = document.form.txtjabatan.value;
    var dept = document.form.txtdept.value;
    var line = document.form.txtline.value;
    var mulai_kerja = document.form.txtmulai_kerja.value;
    var agama = document.form.txtagama.value;
    var alamat_karyawan = document.form.txtalamat_karyawan.value;
    var no_ktp_tk = document.form.txtno_ktp_tk.value;
    var id_absen = document.form.txtid_absen.value;

    if (nama == '') 
    { alert('Nama tidak boleh kosong'); 
      document.form.txtnama.focus();valid = false;
    }
    else if (jenis_kelamin == '') 
    { alert('Jenis Kelamin tidak boleh kosong'); 
      document.form.txtjenis_kelamin.focus();valid = false;
    }
    else if (tgl_lahir == '') 
    { alert('Tgl. Lahir tidak boleh kosong'); 
      document.form.txttgl_lahir.focus();valid = false;
    }
    else if (divisi_kerja == '') 
    { alert('Base Lokasi tidak boleh kosong'); 
      document.form.txtdivisi_kerja.focus();valid = false;
    }
    else if (serikat == '') 
    { alert('Lokasi Kerja tidak boleh kosong'); 
      document.form.txtserikat.focus();valid = false;
    }
    else if (jabatan == '') 
    { alert('Jabatan tidak boleh kosong'); 
      valid = false;
    }
    else if (dept == '') 
    { alert('Dept tidak boleh kosong'); 
      valid = false;
    }
    else if (mulai_kerja == '') 
    { alert('Tgl. Mulai Kerja tidak boleh kosong'); 
      document.form.txtmulai_kerja.focus();valid = false;
    }";
      echo "else if (agama == '') { alert('Agama tidak boleh kosong'); document.form.txtagama.focus();valid = false;}";
      echo "else if (alamat_karyawan == '') { alert('Alamat tidak boleh kosong'); document.form.txtalamat_karyawan.focus();valid = false;}";
      echo "else if (no_ktp_tk == '') { alert('No. KTP tidak boleh kosong'); document.form.txtno_ktp_tk.focus();valid = false;}";
      echo "else valid = true;";
      echo "return valid;";
      echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
if ($mod=="2") {
?>
<div class="box box">
  <?PHP
  # COPAS ADD
  echo "<div class='box-body'>";
  echo "<div class='row'>";
  echo "<form method='post' name='form' action='save_emp.php?id=$nik' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>NIK *</label>";
    echo "<input type='text' class='form-control' name='txtnik' placeholder='Masukkan NIK' value='$nik' readonly>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Nama *</label>";
    echo "<input type='text' class='form-control' name='txtnama' placeholder='Masukkan Nama' value='$nama'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Jenis Kelamin *</label>";
    $sql = "select nama_pilihan isi,nama_pilihan tampil 
      from masterpilihan where kode_pilihan='Sex'";
    echo "<select class='form-control select2' style='width: 100%;' name='txtjenis_kelamin'>";
    IsiCombo($sql,$jenis_kelamin,'Pilih Jenis Kelamin');
    echo "</select>";
  echo "</div>";
  echo "
  <div class='col-md-6'>
    <div class='form-group'>
      <label>Tempat Lahir *</label>
      <input type='text' class='form-control' name='txttmp_lahir' 
        placeholder='Masukkan Tempat Lahir' value='$tmp_lahir'>
    </div>
  </div>";
  echo "
  <div class='col-md-6'>
    <div class='form-group'>
      <label>Tgl. Lahir *</label>
      <input type='text' class='form-control' id='datepicker1' name='txttgl_lahir' 
        placeholder='Masukkan Tgl. Lahir' value='$tgl_lahir'>
    </div>
  </div>";
  echo "
  <div class='col-md-6'>
    <div class='form-group'>";
    echo "<label>Status Karyawan *</label>";
    $sql = "select nama_pilihan isi, nama_pilihan tampil from 
      masterpilihan where kode_pilihan='STKRY'";
    echo "<select class='form-control select2' style='width: 100%;' name='txtstatus_karyawan'>";
    IsiCombo($sql,$status_karyawan,'Pilih Status');
    echo "</select>";
    echo "
    </div>
  </div>";
  echo "
  <div class='col-md-6'>
    <div class='form-group'>
      <label>No. Kontrak</label>
      <input type='text' class='form-control' name='txtno_kontrak' placeholder='Masukkan No. Kontrak' 
        value='$no_kontrak'>
    </div>
  </div>";
  echo "
  <div class='col-md-6'>
    <div class='form-group'>
      <label>Divisi Kerja *</label>";
      $sql = "select divisi_kerja isi, divisi_kerja tampil from 
        hr_masteremployee group by divisi_kerja ";
      echo "<select class='form-control select2' style='width: 100%;' name='txtdivisi_kerja'>";
      IsiCombo($sql,$divisi_kerja,'Pilih Divisi Kerja');
      echo "</select>
    </div>
  </div>";
  echo "
  <div class='col-md-6'>
    <div class='form-group'>
      <label>Lokasi Kerja *</label>";
      $sql = "select lokasi_kerja isi, lokasi_kerja tampil from 
        hr_masteremployee group by lokasi_kerja";
      echo "<select class='form-control select2' style='width: 100%;' name='txtserikat'>";
      IsiCombo($sql,$lokasi_kerja,'Pilih Lokasi Kerja');
      echo "</select>
    </div>
  </div>";
  echo "
    <div class='form-group'>
      <label>Department *</label>";
      $sql = "select nama_pilihan isi, nama_pilihan tampil from 
        masterpilihan where kode_pilihan='Dept'";
      echo "<select class='form-control select2' style='width: 100%;' 
        name='txtdept'>";
      IsiCombo($sql,$dept,'Pilih Dept');
      echo "</select>
    </div>";
  echo "</div>";
  echo "<div class='col-md-3'>";
  echo "
    <div class='form-group'>
      <label>Bagian *</label>";
      $sql = "select nama_pilihan isi, nama_pilihan tampil from 
        masterpilihan where kode_pilihan='Bagian'";
      echo "<select class='form-control select2' style='width: 100%;' 
        name='txtbagian'>";
      IsiCombo($sql,$bagian,'Pilih Bagian');
      echo "</select>
    </div>";
  echo "
    <div class='form-group'>
      <label>Line *</label>";
      $sql = "select nama_pilihan isi, nama_pilihan tampil from 
        masterpilihan where kode_pilihan='Line'";
      echo "<select class='form-control select2' style='width: 100%;' 
        name='txtline'>";
      IsiCombo($sql,$line,'Pilih Line');
      echo "</select>
    </div>";
  echo "<div class='form-group'>";
    echo "<label>Jabatan *</label>";
    $sql = "select nama_pilihan isi, nama_pilihan tampil from 
      masterpilihan where kode_pilihan='Jabatan'";
    echo "<select class='form-control select2' style='width: 100%;' name='txtjabatan'>";
    IsiCombo($sql,$jabatan,'Pilih Jabatan');
    echo "</select>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tgl. Mulai Kerja *</label>";
    echo "<input type='text' class='form-control' id='datepicker2' name='txtmulai_kerja' placeholder='Masukkan Tgl. Mulai Kerja' value='$mulai_kerja'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tgl. Selesai Kontrak 1</label>";
    echo "<input type='text' class='form-control' id='datepicker3' name='txtselesai_kontrak1' placeholder='Masukkan Tgl. Selesai Kontrak 1' value='$selesai_kontrak1'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tgl. Mulai Kontrak 2</label>";
    echo "<input type='text' class='form-control' id='datepicker5' name='txtmulai_kontrak2' placeholder='Masukkan Tgl. Mulai Kontrak 2' value='$mulai_kontrak2'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tgl. Selesai Kontrak 2</label>";
    echo "<input type='text' class='form-control' id='datepicker4' name='txtselesai_kontrak2' placeholder='Masukkan Tgl. Selesai Kontrak 2' value='$selesai_kontrak2'>";
  echo "</div>";
  echo "</div>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>Tgl. Permanent</label>";
    echo "<input type='text' class='form-control' id='datepicker6' name='txttgl_permanent' placeholder='Masukkan Tgl. Permanent' value='$tgl_permanent'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Agama *</label>";
    $sql = "select nama_pilihan isi,nama_pilihan tampil from 
      masterpilihan where kode_pilihan='Agama'";
    echo "<select class='form-control select2' style='width: 100%;' name='txtagama'>";
    IsiCombo($sql,$agama,'Pilih Agama');
    echo "</select>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Alamat *</label>";
    echo "<input type='text' class='form-control' name='txtalamat_karyawan' placeholder='Masukkan Alamat' value='$alamat_karyawan'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>No. NPWP</label>";
    echo "<input type='text' class='form-control' name='txtno_npwp' placeholder='Masukkan No. NPWP' value='$no_npwp'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Asuransi</label>";
    $sql = "select nama_pilihan isi, nama_pilihan tampil from 
      masterpilihan where kode_pilihan='Asuransi'";
    echo "<select class='form-control select2' style='width: 100%;' name='txtjenis_jamkes'>";
    IsiCombo($sql,$jenis_jamkes,'Pilih Asuransi');
    echo "</select>";
  echo "</div>";
  echo "
  <div class='col-md-6'>
    <div class='form-group'>
      <label>No. KTP *</label>
      <input type='text' class='form-control' name='txtno_ktp_tk' 
        placeholder='Masukkan No. KTP' value='$no_ktp_tk'>
    </div>
  </div>";
  echo "
  <div class='col-md-6'>
    <div class='form-group'>
      <label>Exp. Date *</label>
      <input type='text' class='form-control' name='txtexp_ktp_tk' 
        placeholder='Masukkan Exp. Date' value='$exp_ktp_tk'>
    </div>
  </div>";
  echo "</div>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>No. BPJS Kes</label>";
    echo "<input type='text' class='form-control' name='txtno_bpjs_kes_tk' placeholder='Masukkan No. BPJS Kes' value='$no_bpjs_kes_tk'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>No. BPJS TK</label>";
    echo "<input type='text' class='form-control' name='txtno_bpjs_tk' placeholder='Masukkan No. BPJS TK' value='$no_bpjs_tk'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>ID Absen *</label>";
    echo "<input type='text' class='form-control' name='txtid_absen' placeholder='Masukkan ID Absen' value='$id_absen'>";
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
      <a href='../hr/?mod=2' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
      <table id="example1" class="display responsive" style="font-size:11px;">
          <thead>
          <tr>
				      <th>No</th>
              <th>Nik</th>
              <th>Nama</th>
              <th>Jenis Kelamin</th>
              <th>Tgl. Lahir</th>
              <th>Dept</th>
              <th>Bagian</th>
              <th>Line</th>
              <th>Jabatan</th>
              <th>Tgl. Masuk</th>
              <th width='10%'>Aksi</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("SELECT * FROM hr_masteremployee where selesai_kerja is null or selesai_kerja='0000-00-00' order by nik desc ");
  				  $no = 1; 
    				while($data = mysql_fetch_array($query))
    			  { echo "<tr>";
      				  echo "<td>$no</td>"; 
      				  echo "<td>$data[nik]</td>"; 
      				  echo "<td>$data[nama]</td>"; 
      				  echo "<td>$data[jenis_kelamin]</td>";
                echo "<td>".fd_view($data['tgl_lahir'])."</td>"; 
      				  echo "<td>$data[department]</td>"; 
                echo "<td>$data[bagian]</td>";
                echo "<td>$data[line]</td>";
                echo "<td>$data[jabatan]</td>";
                echo "<td>".fd_view($data['mulai_kerja'])."</td>"; 
                echo "
                	<td>
                		<a href='?mod=2&id=$data[nik]'
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
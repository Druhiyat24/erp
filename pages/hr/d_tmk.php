<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("hr_lokasi","userpassword","username='$user'");
if ($akses=="0")
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_tmk = $_GET['id']; } else {$id_tmk = "";}
if (isset($_GET['pro'])) { $pro=$_GET['pro']; } else { $pro=""; }

$titlenya="Master Tunjangan Masa Kerja";
$mode="";
$mod=$_GET['mod'];
$tgl_skrg=date('Y-m-d');


$carikode = mysql_query("SELECT kdtunjangan from hr_mastertmk");
    $datakode = mysql_fetch_array($carikode);
    $jumlah_data = mysql_num_rows($carikode);

    $nilaikode = substr($jumlah_data[0], 4);
    $kode = (int) $nilaikode;
    $kode = $jumlah_data + 1;
    $kode_otomatis = "NJ-".str_pad($kode, 4, "0", STR_PAD_LEFT);

# COPAS EDIT
if ($id_tmk=="")
{ $kdtunjangan ="";
  $kd_employee = "";
  $dept      = "";
  $bagian    = "";
  $tahun = "";
  $tunjangan = "";
}
else
{ $query = mysql_query("SELECT * FROM hr_mastertmk where kdtunjangan='$id_tmk'");
  $data = mysql_fetch_array($query);
  $kdtunjangan = $_GET['id'];
  $kd_employee = $data['kd_employee'];
  $dept  = $data['departemen'];
  $bagian  = $data['bagian'];
  $tahun  = $data['tahun_masuk'];
  $tunjangan  = $data['nilai_tunjangan'];
 if ($pro=="Copy") { $id_tmk=""; }
}

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var kdtunjangan     = document.form.txtkdtunjangan.value;
      var kd_employee  = document.form.txtkdemployee.value;
      var dept    = document.form.txtdept.value;
      var bagian  = document.form.txtbagian.value;
      var tahun = document.form.txttahun.value;
      var tgl_skrg = document.form.txttgl_skrg.value;
      var tunjangan  = document.form.txtnilaitunjangan.value;

      if (kdtunjangan == '') {
         alert('Kode Tunjangan Tidak Boleh Kosong'); document.form.txtkodetunjangan.focus();valid = false;
       }
       else if (dept == '') {
         alert('Dept Tidak Boleh Kosong'); document.form.txtdept.focus();valid = false;
       }
      else if (bagian == '') {
        alert('Bagian Tidak Boleh Kosong'); document.form.txtbagian.focus();valid = false;
      }
      else if (tahun == '') {
        alert('Tahun Tidak Boleh Kosong'); document.form.txttahun.focus();valid = false;
      }

      else valid = true;
      return valid;
      exit;
    }
  </script>";
# END COPAS VALIDASI

# COPAS ADD
if ($mod=="36t") {
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_tmk.php?mod=$mod&id=$id_tmk' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
              <label>Kode Tunjangan *</label>
              <input type='text' class='form-control' name='txtkodetunjangan'
              placeholder='Masukkan Dept' value=' $kode_otomatis ' readonly>
          </div>";
        echo "
              <div class='form-group'>
                    <label>Nama Karyawan *</label>";          
              echo "<select class='form-control select2' style='width: 100%;' 
                name='txtkdemployee' onchange='changeValue(this.value)'>";
              echo "<option value=''>Pilih Karyawan</option>";
                  $query = mysql_query("SELECT * from hr_masteremployee");
                  $jsArray = "var prdName = new Array();\n";
                  while ($row = mysql_fetch_assoc($query)) {
         
             echo "<option value='$row[nik]' >$row[nik] || $row[nama]</option>";
                    $jsArray .= "prdName['" .$row['nik']. "'] = {
                    tahun:'".addslashes($row['mulai_kerja'])."'};\n";
                      }
              echo "</select></div>";
              
              echo "
              <div class='form-group'>
                    <label>Tahun Masuk *</label>
                      <input type='text' class='form-control' id='tahun' name='txttahun' value='$tahun' readonly>
              </div>";
              echo "
                <div class='form-group'>
                  <label>Tanggal Hari ini</label>
                  <input type='text' class='form-control' value='$tgl_skrg' id='txttgl_skrg' name='txttgl_skrg' readonly>
                </div></div>";   
             
      
      echo "
         <div class='col-md-6'>
            <div class='form-group'>
              <label>Department *</label>";
         $sql = "select nama_pilihan isi, nama_pilihan tampil from 
          masterpilihan where kode_pilihan='Dept'";
        echo "<select class='form-control select2' style='width: 100%;' 
          name='txtdept'>";
        IsiCombo($sql,$dept,'Pilih Dept');
        echo "</select>
      </div>";
    echo"
          <div class='form-group'>
                  <label>Nilai Tunjangan *</label>
                  <input type='text' class='form-control' id='txtnilaitunjangan' name='txtnilaitunjangan'
                  placeholder='Masukkan tunjangan' value='$tunjangan'>
          </div>";      
     echo "
          <div class='form-group'>
            <label>Bagian *</label>";
            $sql = "select nama_pilihan isi, nama_pilihan tampil from 
            masterpilihan where kode_pilihan='Bagian'";
      echo "<select class='form-control select2' style='width: 100%;' 
        name='txtbagian'>";
      IsiCombo($sql,$bagian,'Pilih Bagian');
      echo "</select>
    </div>
    <br>&nbsp;&nbsp;
       <button type='submit' name='submit' class='btn btn-primary'>
       Simpan</button>
    </div>";


      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
} else {
?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
      <a href='../hr/?mod=36t' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>Kode Tunjangan</th>
          <th>Kode Karyawan</th>
          <th>Departemen</th>
          <th>Bagian</th>
          <th>Tahun Masuk</th>
          <th>Tanggal Sekarang</th>
          <th>Nilai Tunjangan</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>

        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM hr_mastertmk");
			  $no = 1;
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>";
          echo "<td>$data[kdtunjangan]</td>";
          echo "<td>$data[kd_employee]</td>";
          echo "<td>$data[departemen]</td>";
          echo "<td>$data[bagian]</td>";
          echo "<td>$data[tahun_masuk]</td>";
          echo "<td>$data[tgl_skrg]</td>";
          echo "<td>$data[nilai_tunjangan]</td>";
          echo "
          <td>
            <a $cl_ubah href='../hr/?mod=36t&id=$data[kdtunjangan]'
              class='btn btn-info btn-s' $tt_ubah
            </a>
          </td>";
          echo "</tr>";
				  $no++;
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>
<script type="text/javascript"> 
<?php echo $jsArray; 
   // var_dump($jsArray);
   //         die();
?>
function changeValue(id){
  // console.log(id);
  document.getElementById('tahun').value = prdName[id].tahun;
  var str1 = prdName[id].tahun;
  var tahun = str1.substr(0,4);
  var str2 = document.getElementById('txttgl_skrg').value;
  var tahun2 = str2.substr(0,4);
  // var tahun2 = year();
  document.getElementById('txtnilaitunjangan').value = (Number(tahun2) - Number(tahun)) * 1000;
  // document.getElementById('tahun').value = prdName[id].tahun;
  // console.log(dum);
};
</script>

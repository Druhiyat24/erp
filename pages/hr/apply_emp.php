<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mod=$_GET['mod'];
# CEK HAK AKSES KEMBALI
if ($mod=="29")
{ $akses = flookup("hr_apply_emp","userpassword","username='$user'"); }
else if ($mod=="29a")
{ $akses = flookup("hr_form_doc","userpassword","username='$user'"); }
else if ($mod=="29i")
{ $akses = flookup("hr_interview","userpassword","username='$user'"); }
else if ($mod=="29p")
{ $akses = flookup("hr_permintaan_tk","userpassword","username='$user'"); }

if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_lokasi = $_GET['id']; } else {$id_lokasi = "";}
$titlenya="Master Lokasi";
$mode="";

# COPAS EDIT
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
# END COPAS VALIDASI

# COPAS ADD

# END COPAS ADD
if ($mod=="29") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Data Apply Karyawan</h3>
    <a href='../hr/form_apply_karyawan.php' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="tbl_apply_emp" class="display responsive" style="width:100%;font-size:11px;">
    <?php 
    $sql= "SELECT * from data_pribadi order by id_dp desc";
    $result = $con_new->query($sql);
    echo '
    <thead>
      <tr>
        <th>Nama Lengkap</th>
        <th>Tempat Tanggal Lahir</th>
        <th>Alamat</th>
        <th>Jenis Kelamin</th>
        <th>Kewarganegaraan</th>
        <th>Status Pernikahan</th>
        <th>No. Handphone</th>
        <th>Referensi</th>
        <th>Created Date</th>
        <th>Created By</th>
        <th>Aksi</th>
      </tr>
    </thead>';
    echo "<tbody>";
    while($row = $result->fetch_assoc()) 
    { echo '
      <tr>
        <td>'.$row["nama_lengkap"].'</td>
        <td>'.$row["ttl"].'</td>
        <td>'.$row["alamat_tetap"].'</td>
        <td>'.$row["jenis_kelamin"].'</td>
        <td>'.$row["kewarganegaraan"].'</td>
        <td>'.$row["status_pernikahan"].'</td>
        <td>'.$row["no_hp"].'</td>
        <td>'.$row["ref_kerja"].'</td>
        <td>'.fd_view_dt($row["dateinput"]).'</td>
        <td>'.$row["username"].'</td>
        <td>
          <a class="btn btn-primary btn-s" href="form_apply_karyawan.php?id='.$row["id_dp"].'" 
            data-toggle="tooltip" title="Update"><i class="fa fa-pencil"></i>
          </a> 
          <a class="btn btn-danger btn-s" href="del_data.php?pro=Apply&id='.$row["id_dp"].'"'; 
            ?>
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"
            <?php 
            echo 'data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i>
          </a>  
          <a class="btn btn-warning btn-s" href="pdfAply.php?id='.$row["id_dp"].'" 
            data-toggle="tooltip" title="Preview"><i class="fa fa-print"></i>
          </a>
        </td>
      </tr>';
    }
    echo "</tbody>";
    ?>
    </table>
  </div>
</div>
<?php } else if ($mod=="29a") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Data Form Document</h3>
    <a href='../hr/form_document.php' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="tbl_dok" class="display responsive" style="width:100%;font-size:12px;">
    <?php 
    $sql2= "SELECT a.nama_lengkap,s.* FROM data_pribadi a inner join form_dokumen s on a.id_dp=s.id_dp 
      order by id_form_dk desc";
    $result2 = $con_new->query($sql2);
    echo '
    <thead>
      <tr>
        <th>Nama Karyawan</th>
        <th>Department</th>
        <th>NIK</th>
        <th>Bagian</th>
        <th>Tgl. Masuk</th>
        <th>Created Date</th>
        <th>Created By</th>
        <th>Aksi</th>
      </tr>
    </thead>';
    echo "<tbody>";
    while($row = $result2->fetch_assoc()) 
    { echo '
      <tr>
        <td>'.$row["nama_lengkap"].'</td>
        <td>'.$row["department"].'</td>
        <td>'.$row["nik"].'</td>
        <td>'.$row["bagian"].'</td>
        <td>'.$row["tanggalmasuk"].'</td>
        <td>'.fd_view_dt($row["dateinput"]).'</td>
        <td>'.$row["username"].'</td>
        <td>
          <a class="btn btn-primary btn-s" href="form_document.php?id='.$row["id_form_dk"].'" 
            data-toggle="tooltip" title="Update"><i class="fa fa-pencil"></i>
          </a> 
          <a class="btn btn-danger btn-s" href="del_data.php?pro=Dok&id='.$row["id_form_dk"].'"'; 
            ?>
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"
            <?php 
            echo 'data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i>
          </a>  
          <a class="btn btn-warning btn-s" href="pdfCeklist.php?id='.$row["id_form_dk"].'"
            data-toggle="tooltip" title="Preview"><i class="fa fa-print"></i></a>
        </td> 
      </tr>';
    }
    echo "</tbody>";
    ?>
    </table>
  </div>
</div>
<?php } else if ($mod=="29i") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Data Form Interview Karyawan</h3>
    <a href='../hr/form_interview.php' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="tbl_interview" class="display responsive" style="width:100%;font-size:11px;">
    <?php 
    $sql3 = "SELECT * FROM form_interview order by id_fi desc";
    $result3 = $con_new->query($sql3);
    echo '
    <thead>
      <tr>
        <th>Nama Kandidat</th>
        <th>Di Interview Oleh</th>
        <th>Tanggal</th>
        <th>Kesimpulan</th>
        <th>Pewawancara</th>
        <th>Created Date</th>
        <th>Created By</th>
        <th>Aksi</th>
      </tr>
    </thead>';
    echo "<tbody>";
    while($row = $result3->fetch_assoc()) 
    { echo '
      <tr>
        <td>'.$row["nama_kandidat"].'</td>
        <td>'.$row["interview_by"].'</td>
        <td>'.fd_view($row["tanggal"]).'</td>
        <td>'.$row["putusan"].'</td>
        <td>'.$row["pewawancara"].'</td>
        <td>'.fd_view_dt($row["dateinput"]).'</td>
        <td>'.$row["username"].'</td>
        <td>
        	<a class="btn btn-primary btn-s" href="form_interview.php?id='.$row["id_fi"].'" 
            data-toggle="tooltip" title="Update"><i class="fa fa-pencil"></i>
          </a> 
          <a class="btn btn-danger btn-s" href="del_data.php?pro=Interview&id='.$row["id_fi"].'"'; 
            ?>
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"
            <?php 
            echo 'data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i>
          </a>  
          <a class="btn btn-warning btn-s" href="pdfInterview.php?id='.$row["id_fi"].'"
          	data-toggle="tooltip" title="Preview"><i class="fa fa-print"></i></a>
        </td>  
      </tr>';
    }
    echo "</tbody>";
    ?>
    </table>
  </div>
</div>
<?php } else if ($mod=="29p") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Data Form Permintaan TK</h3>
    <a href='../hr/form_permintaantk.php' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%;font-size:11px;">
    <?php 
    $sql3 = "SELECT * FROM form_tenaga_kerja order by id_tk desc";
    $result3 = $con_new->query($sql3);
    echo '
    <thead>
      <tr>
        <th>Diajukan Oleh</th>
        <th>NIK</th>
        <th>Department</th>
        <th>Bagian</th>
        <th>Rencana Kebutuhan</th>
        <th>Bagian</th>
        <th>Tanggal</th>
        <th>Jumlah</th>
        <th>Created Date</th>
        <th>Created By</th>
        <th>Approval</th>
        <th>Aksi</th>
      </tr>
    </thead>';
    echo "<tbody>";
    while($row = $result3->fetch_assoc()) 
    { $approve=$row["setuju1"]." (".$row["setuju1_app"].")";
      $approve=$approve." ".$row["setuju2"]." (".$row["setuju2_app"].")";
      $approve=$approve." ".$row["setuju3"]." (".$row["setuju3_app"].")";
      $approve=$approve." ".$row["ketahui"]." (".$row["ketahui_app"].")";
      echo '
      <tr>
        <td>'.$row["do_nama"].'</td>
        <td>'.$row["do_nik"].'</td>
        <td>'.$row["do_department"].'</td>
        <td>'.$row["do_bagian"].'</td>
        <td>'.$row["rk_department"].'</td>
        <td>'.$row["rk_bagian"].'</td>
        <td>'.$row["rk_tanggal"].'</td>
        <td>'.$row["rk_jumlah"].'</td>
        <td>'.$row["dateinput"].'</td>
        <td>'.$row["username"].'</td>
        <td>'.$approve.'</td>
        <td>
          <a class="btn btn-primary btn-s" href="form_permintaantk.php?id='.$row["id_tk"].'" 
            data-toggle="tooltip" title="Update"><i class="fa fa-pencil"></i>
          </a> 
          <a class="btn btn-danger btn-s" href="del_data.php?pro=FPTK&id='.$row["id_tk"].'"'; 
            ?>
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"
            <?php 
            echo 'data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i>
          </a>  
          <a class="btn btn-warning btn-s" href="pdfPTK.php?id='.$row["id_tk"].'" 
            data-toggle="tooltip" title="Preview"><i class="fa fa-print"></i>
          </a>
        </td>
      </tr>';
    }
    echo "</tbody>";
    ?>
    </table>
  </div>
</div>
<?php } ?>
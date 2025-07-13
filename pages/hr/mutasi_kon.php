<?php
    include '../../include/conn.php';

    // untuk table head_table
    // $iddok = $_POST['id_dok'];
    // $revisi = $_POST['revisi'];
    // $revisi_date = $_POST['revisi_date'];
    // $berlaku_date = $_POST['berlaku_date'];

    //untuk table emp_history
    $nik                = $_POST['nik'];
    // $nama               = $_POST['nama'];
    $info               = $_POST['info'];
    $bagian             = $_POST['bagian'];
    $department         = $_POST['department'];
    $depthis            = $_POST['depthis'];
    $tanggal_PL         = $_POST['tanggal_PL'];
    $tanggal_PL  = date('d M Y', strtotime($tanggal_PL ));
    $tanggal_PL  = date('Y-m-d', strtotime($tanggal_PL ));
    $chief_diajukan     = $_POST['chief_diajukan'];
    $hr_diketahui       = $_POST['hr_diketahui'];
    $manager_diketahui  = $_POST['manager_diketahui'];
    $general_disetujui  = $_POST['general_disetujui'];
    $gm_pro_disetujui   = $_POST['gm_pro_disetujui'];
    $grading_upah       = $_POST['grading_upah'];
    $file_uraian        = $_POST['file_uraian'];
    $ket_mut_pro_dem    = $_POST['ket_mut_pro_dem'];
 

 $input = mysql_query("INSERT INTO emp_history VALUES ('$nik','', '$info', '$bagian', '$depthis', '$tanggal_PL', 
  '$chief_diajukan', '$hr_diketahui', '$manager_diketahui', '$general_disetujui', '$gm_pro_disetujui', '$grading_upah', 
  '$file_uraian', '$ket_mut_pro_dem')");

 $update = mysql_query("UPDATE hr_masteremployee SET department = '$department' WHERE nik='$nik'");

 
if ($input) {
  echo "<script> alert('Data berhasil INPUT') </script>";
 echo '<script>location.replace("../hr/?mod=m17")</script>';  
}
if ($update) {
 echo "<script> alert('Data berhasil UPDATE') </script>";
 echo '<script>location.replace("../hr/?mod=m17")</script>';
}
else {
  echo "<script> alert('Data Gagal') </script>";
  echo '<script>location.replace("../hr/form_mutasi_karyawan.php")</script>';
}
?>
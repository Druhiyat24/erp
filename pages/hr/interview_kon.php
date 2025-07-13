<?php
    //include 'koneksi2.php';
    include '../../include/conn.php';
    include '../forms/fungsi.php';
    error_reporting (E_ALL ^ E_NOTICE);

    session_start();
    $username=$_SESSION['username'];
    $dateinput = date("Y-m-d H:i:s");

    if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}

    // untuk table head_table

    $iddok = $_POST['id_dok'];
    $revisi = $_POST['revisi'];
    $revisi_date = $_POST['revisi_date'];
    $berlaku_date = $_POST['berlaku_date'];

    $namakandidat = $_POST['namakandidat'];
    $interviewby = $_POST['interviewby'];
    $tanggalin = $_POST['tanggalin'];
    $nilai1 = $_POST['nilai1'];
    $keterangan1 = $_POST['keterangan1'];
    $nilai2 = $_POST['nilai2'];
    $keterangan2 = $_POST['keterangan2'];
    $nilai3 = $_POST['nilai3'];
    $keterangan3 = $_POST['keterangan3'];
    $nilai4 = $_POST['nilai4'];
    $keterangan4 = $_POST['keterangan4'];
    $nilai5 = $_POST['nilai5'];
    $keterangan5 = $_POST['keterangan5'];
    $nilai6 = $_POST['nilai6'];
    $keterangan6 = $_POST['keterangan6'];
    $nilai7 = $_POST['nilai7'];
    $keterangan7 = $_POST['keterangan7'];
    $nilai8 = $_POST['nilai8'];
    $keterangan8 = $_POST['keterangan8'];
    $nilai9 = $_POST['nilai9'];
    $keterangan9 = $_POST['keterangan9'];
    $nilai10 = $_POST['nilai10'];
    $keterangan10 = $_POST['keterangan10'];
    $penerimaan = $_POST['penerimaan'];
    $pewawancara = $_POST['pewawancara'];
    
    if ($id=="")
    { $sql2= "INSERT INTO form_interview(id_fi, nama_kandidat, interview_by, tanggal, penguasaan_kerja, ket1, komunikasi, ket2,
        problem_solving, ket3, kerjasama, ket4, motivasi, ket5, integritas, ket6, inisiatif, ket7, cara_pandang, ket8, perencanaan, ket9, kepemimpinan, ket10, putusan, pewawancara,dateinput,username)
        VALUES(null,'$namakandidat','$interviewby','$tanggalin','$nilai1','$keterangan1','$nilai2','$keterangan2','$nilai3','$keterangan3','$nilai4','$keterangan4','$nilai5','$keterangan5','$nilai6','$keterangan6','$nilai7','$keterangan7','$nilai8','$keterangan8','$nilai9','$keterangan9','$nilai10','$keterangan10','$penerimaan', '$pewawancara','$dateinput','$username');";
      insert_log($sql2,$username);
      $_SESSION['msg']="Data Berhasil Disimpan";
    }
    else
    { $sql2= "update form_interview set nama_kandidat='$namakandidat',interview_by='$interviewby', 
        tanggal='$tanggalin', penguasaan_kerja='$nilai1', ket1='$keterangan1', 
        komunikasi='$nilai2', ket2='$keterangan2',
        problem_solving='$nilai3',ket3='$keterangan3', 
        kerjasama='$nilai4', ket4='$keterangan4', 
        motivasi='$nilai5', ket5='$keterangan5', 
        integritas='$nilai6', ket6='$keterangan6', 
        inisiatif='$nilai7', ket7='$keterangan7', 
        cara_pandang='$nilai8', ket8='$keterangan8', 
        perencanaan='$nilai9', ket9='$keterangan9', 
        kepemimpinan='$nilai10', ket10='$keterangan10', 
        putusan='$penerimaan', pewawancara='$pewawancara'
        where id_fi='$id'";
      insert_log($sql2,$username);
      $_SESSION['msg']="Data Berhasil Dirubah";
    }
    echo '<script>location.replace("../hr/?mod=29i")</script>';
    
    mysqli_close($con_new);
    
    ?>
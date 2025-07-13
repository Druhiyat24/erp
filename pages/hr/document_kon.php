<?php
    //include 'koneksi2.php';
    //next gunakan $con_new 
    // bukan :
    include '../../include/conn.php';
    include '../forms/fungsi.php';
    error_reporting (E_ALL ^ E_NOTICE);

    session_start();
    $username=$_SESSION['username'];
    $dateinput = date("Y-m-d H:i:s");

    if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}

    $iddok = $_POST['id_dok'];
    $revisi = $_POST['revisi'];
    $revisi_date = $_POST['revisi_date'];
    $berlaku_date = $_POST['berlaku_date'];

    $id_dp = $_POST['karyawan'];
    $namakaryawan = $_POST['karyawan'];
    $tanggalmasuk = $_POST['tanggalmasuk'];
    $departemen = $_POST['departemen'];
    $nik = $_POST['nik'];
    $bagian = $_POST['bagian'];
    $pilihan1 = $_POST['choose1'];
    $pilihan2 = $_POST['choose2'];
    $pilihan3 = $_POST['choose3'];
    $pilihan4 = $_POST['choose4'];
    $pilihan5 = $_POST['choose5'];
    $pilihan6 = $_POST['choose6'];
    $pilihan7 = $_POST['choose7'];
    $pilihan8 = $_POST['choose8'];
    $pilihan9 = $_POST['choose9'];
    $pilihan10 = $_POST['choose10'];
    $pilihan11 = $_POST['choose11'];
    $pilihan12 = $_POST['choose12'];
    $pilihan13 = $_POST['choose13'];
    $pilihan14 = $_POST['choose14'];
    $pilihan15 = $_POST['choose15'];
    $pilihan16 = $_POST['choose16'];

    #$sql1 = "INSERT INTO head_table(id_dok, kode_dok, jenis_dok, revisi, tanggal_revisi, tanggal_berlaku) VALUES
    #(null, '$iddok', 'form dokumen', '$revisi', '$revisi_date', '$berlaku_date');";
    if ($id=="")
    { $sql2 = "INSERT INTO form_dokumen(id_form_dk,id_dp,nama_karyawan, department,
        nik, bagian, tanggalmasuk, fc_ktp_sim, pas_foto, surat_lamaran, daftar_riwayat_hidup, 
        ijazah_terakhir, skck, fc_kk, surat_dokter, pengalaman_kerja, izin_suami_or_ortu, form_permintaan_tk,
        perjanjian_kerja, surat_perbedaan_dokument, form_aplikasi_karyawan, form_ringkasan_psikotest_skill, 
        form_pernyataan_orientasi,dateinput,username)
        VALUES(null,'$id_dp',
        '$namakaryawan',
        '$departemen',
        '$nik',
        '$bagian',
        '$tanggalmasuk',
        '$pilihan1',
        '$pilihan2',
        '$pilihan3',
        '$pilihan4',
        '$pilihan5',
        '$pilihan6',
        '$pilihan7',
        '$pilihan8',
        '$pilihan9',
        '$pilihan10',
        '$pilihan11',
        '$pilihan12',
        '$pilihan13',
        '$pilihan14',
        '$pilihan15',
        '$pilihan16','$dateinput','$username');";
    }
    else
    { $sql2 = "update form_dokumen set nama_karyawan='$namakaryawan', 
        department='$departemen',nik='$nik',bagian='$bagian',tanggalmasuk='$tanggalmasuk',
        fc_ktp_sim='$pilihan1',pas_foto='$pilihan2',surat_lamaran='$pilihan3',
        daftar_riwayat_hidup='$pilihan4',ijazah_terakhir='$pilihan5',skck='$pilihan6',
        fc_kk='$pilihan7',surat_dokter='$pilihan8',pengalaman_kerja='$pilihan9',
        izin_suami_or_ortu='$pilihan10',form_permintaan_tk='$pilihan11',
        perjanjian_kerja='$pilihan12',surat_perbedaan_dokument='$pilihan13',
        form_aplikasi_karyawan='$pilihan14',form_ringkasan_psikotest_skill='$pilihan15', 
        form_pernyataan_orientasi='$pilihan16'
        where id_form_dk='$id'";

    }
    insert_log($sql2,$username);
    $_SESSION['msg']="Data Berhasil Disimpan";
    echo '<script>location.replace("../hr/?mod=29a")</script>';

mysqli_close($con_new);

?>
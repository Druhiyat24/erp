<?php
    //include 'koneksi2.php';
    include '../../include/conn.php';
    include '../forms/fungsi.php';
    session_start();
    $username=$_SESSION['username'];
    $dateinput = date("Y-m-d H:i:s");
    if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}

    // error_reporting (E_ALL ^ E_NOTICE);

    // untuk table head_table

    $iddok = $_POST['id_dok'];
    $revisi = $_POST['revisi'];
    $revisi_date = $_POST['revisi_date'];
    $berlaku_date = $_POST['berlaku_date'];

    if (isset($_POST['inputpermintaan1'])) 
    {$inputpermintaan1 = $_POST['inputpermintaan1'];}
    else
    {$inputpermintaan1 = '0';}
    $tanggalaju = $_POST['tanggalaju'];
    $namapengaju = $_POST['namapengaju'];
    $nikpengaju = $_POST['nikpengaju'];
    $departmentpengaju = $_POST['departmentpengaju'];
    $bagianpengaju = $_POST['bagianpengaju'];
    $rk_department = $_POST['rk_department'];
    $rk_bagian = $_POST['rk_bagian'];
    $rk_tanggal = $_POST['rk_tanggal'];
    $rk_jumlah = $_POST['rk_jumlah'];
    if (isset($_POST['inputpermintaan2'])) 
    {$inputpermintaan2 = $_POST['inputpermintaan2'];}
    else
    {$inputpermintaan2 = '0';}
    if (isset($_POST['sekolah']))
    {$sekolah = $_POST['sekolah'];}
    else
    {$sekolah = '0';}
    if (isset($_POST['pengalamankerja']))
    {$pengalamankerja = $_POST['pengalamankerja'];}
    else
    {$pengalamankerja = '0';}
    $lamakerja = $_POST['lamakerja'];
    $jurusan = $_POST['jurusan'];
    $keterangan1 = $_POST['keterangan1'];
    $keterangan2 = $_POST['keterangan2'];
    $keterangan3 = $_POST['keterangan3'];
    $keterangan4 = $_POST['keterangan4'];
    $keterangan5 = $_POST['keterangan5'];
    $keterangan6 = $_POST['keterangan6'];
    $keterangan7 = $_POST['keterangan7'];
    $keterangan8 = $_POST['keterangan8'];
    $keterangan9 = $_POST['keterangan9'];
    $keterangan10 = $_POST['keterangan10'];
    $gaji = $_POST['gaji'];
    $fasilitas = $_POST['fasilitas'];
    $kontrak = $_POST['kontrak'];
    $lainnya = $_POST['lainnya'];
    $chief = $_POST['chief'];
    $hr = $_POST['hr'];
    $gmp = $_POST['gmp'];
    $gmf = $_POST['gmf'];
    $kumpul_ket = "$keterangan1,$keterangan2,$keterangan3,$keterangan4,$keterangan5,
        $keterangan6,$keterangan7,$keterangan8,$keterangan9,$keterangan10";
    if ($id<>"")
    { $sql2="update form_tenaga_kerja set 
        status_permintaan='$inputpermintaan1', 
        do_tglaju='$tanggalaju', 
        do_nama='$namapengaju', 
        do_nik='$nikpengaju', 
        do_department='$departmentpengaju', 
        do_bagian='$bagianpengaju', 
        rk_department='$rk_department', 
        rk_bagian='$rk_bagian', 
        rk_tanggal='$rk_tanggal', 
        rk_jumlah='$rk_jumlah', 
        rencana_jabatan='$inputpermintaan2', 
        pendidikanakhir='$sekolah',
        pengalamankerja='$pengalamankerja', 
        lamakerja_dlmthn='$lamakerja', 
        jurusan='$jurusan', 
        uraian_tugas='$kumpul_ket', 
        gaji='$gaji', 
        fasilitas='$fasilitas', 
        waktu_kontrak='$kontrak', 
        keteranganlain='$lainnya', 
        setuju1='$chief', 
        setuju2='$hr', 
        setuju3='$gmp', 
        ketahui='$gmf' where id_tk='$id'";
      insert_log($sql2,$username);
      $_SESSION['msg']="Data Berhasil Dirubah";
    }
    else
    { $sql2="INSERT INTO form_tenaga_kerja(
        id_tk, 
        status_permintaan, 
        do_tglaju, 
        do_nama, 
        do_nik, 
        do_department, 
        do_bagian, 
        rk_department, 
        rk_bagian, 
        rk_tanggal, 
        rk_jumlah, 
        rencana_jabatan, 
        pendidikanakhir,
        pengalamankerja, 
        lamakerja_dlmthn, 
        jurusan, 
        uraian_tugas, 
        gaji, 
        fasilitas, 
        waktu_kontrak, 
        keteranganlain, 
        setuju1, 
        setuju2, 
        setuju3, 
        ketahui,
        username,dateinput)
        VALUES(
        null,
        '$inputpermintaan1',
        '$tanggalaju',
        '$namapengaju',
        '$nikpengaju',
        '$departmentpengaju',
        '$bagianpengaju',
        '$rk_department',
        '$rk_bagian',
        '$rk_tanggal',
        '$rk_jumlah',
        '$inputpermintaan2',
        '$sekolah',
        '$pengalamankerja',
        '$lamakerja',
        '$jurusan',
        '$kumpul_ket',
        '$gaji',
        '$fasilitas',
        '$kontrak',
        '$lainnya',
        '$chief',
        '$hr',
        '$gmp',
        '$gmf','$username','$dateinput');";
      insert_log($sql2,$username);
      $_SESSION['msg']="Data Berhasil Disimpan";
    }
    echo '<script>location.replace("../hr/?mod=29p")</script>';            
    mysqli_close($con_new);
    
    ?>
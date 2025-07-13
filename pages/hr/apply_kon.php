<?php
    //include 'koneksi2.php';
    include '../../include/conn.php';
    include '../forms/fungsi.php';
    error_reporting (E_ALL ^ E_NOTICE);
    
    if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}

    session_start();
    $username=$_SESSION['username'];
    $dateinput = date("Y-m-d H:i:s");
    // untuk table head_table
    $iddok = $_POST['id_dok'];
    $revisi = $_POST['revisi'];
    $revisi_date = $_POST['revisi_date'];
    $berlaku_date = $_POST['berlaku_date'];
    //untuk table data_pribadi
    $posisi = $_POST['posisi'];
    $info = $_POST['info'];
    $ref_kerja = $_POST['referensi'];
    $fullname = $_POST['fullname'];
    $nickname = $_POST['nickname'];
    $gender = $_POST['gender'];
    $ttl = $_POST['ttl'];
    $warganegara = $_POST['warganegara'];
    $alamattetap = $_POST['alamattetap'];
    $alamatsementara = $_POST['alamatsementara'];
    $agama = $_POST['agama'];
    $ktp = $_POST['ktp'];
    $jenissim = $_POST['jenissim'];
    $nosim = $_POST['nosim'];
    $goldar = $_POST['goldar'];
    $marital = $_POST['marital'];
    $nohp = $_POST['nohp'];
    $email = $_POST['email'];
    $ukuranbaju = $_POST['ukuranbaju'];

    //untuk table sk_nama_keluarga
    $namaayah = $_POST['namaayah'];
    $ttlayah = $_POST['ttlayah'];
    $kerjaanayah = $_POST['kerjaanayah'];
    $jk_ayah = $_POST['jk_ayah'];
    $namaibu = $_POST['namaibu'];
    $ttlibu = $_POST['ttlibu'];
    $kerjaanibu = $_POST['kerjaanibu'];
    $jk_ibu = $_POST['jk_ibu'];
    $namas1 = $_POST['namas1'];
    $ttls1 = $_POST['ttls1'];
    $kerjaans1 = $_POST['kerjaans1'];
    $jk_s1 = $_POST['jk_s1'];
    $namas2 = $_POST['namas2'];
    $ttls2 = $_POST['ttls2'];
    $kerjaans2 = $_POST['kerjaans2'];
    $jk_s2 = $_POST['jk_s2'];
    $namas3 = $_POST['namas3'];
    $ttls3 = $_POST['ttls3'];
    $kerjaans3 = $_POST['kerjaans3'];
    $jk_s3 = $_POST['jk_s3'];
    $namas4 = $_POST['namas4'];
    $ttls4 = $_POST['ttls4'];
    $kerjaans4 = $_POST['kerjaans4'];
    $jk_s4 = $_POST['jk_s4'];
    $namas_i = $_POST['namas_i'];
    $ttls_i = $_POST['ttls_i'];
    $kerjaans_i = $_POST['kerjaans_i'];
    $jk_s_i = $_POST['jk_s_i'];
    $namaank1 = $_POST['namaank1'];
    $ttlank1 = $_POST['ttlank1'];
    $kerjaanank1 = $_POST['kerjaanank1'];
    $jk_ank1 = $_POST['jk_ank1'];
    $namaank2 = $_POST['namaank2'];
    $ttlank2 = $_POST['ttlank2'];
    $kerjaanank2 = $_POST['kerjaanank2'];
    $jk_ank2 = $_POST['jk_ank2'];
    $namaank3 = $_POST['namaank3'];
    $ttlank3 = $_POST['ttlank3'];
    $kerjaanank3 = $_POST['kerjaanank3'];
    $jk_ank3 = $_POST['jk_ank3'];
    $namaank4 = $_POST['namaank4'];
    $ttlank4 = $_POST['ttlank4'];
    $kerjaanank4 = $_POST['kerjaanank4'];
    $jk_ank4 = $_POST['jk_ank4'];

    //untuk table edu_skill, pelatihan dan penguasaan_bahasa
    //1. untuk table edu_skill
        $txtid_edu1 = $_POST['txtid_edu1'];
				$txtid_edu2 = $_POST['txtid_edu2'];
				$txtid_edu3 = $_POST['txtid_edu3'];
				$txtid_edu4 = $_POST['txtid_edu4'];
				$txtid_edu5 = $_POST['txtid_edu5'];
				$txtid_edu6 = $_POST['txtid_edu6'];
				$txtid_edu7 = $_POST['txtid_edu7'];
				$txtid_edu8 = $_POST['txtid_edu8'];
				$sekolah1 = $_POST['sekolah1'];
        $kota1 = $_POST['kota1'];
        $negara1 = $_POST['negara1'];
        $jurusan1 = $_POST['jurusan1'];
        $thnmasuk1 = $_POST['thnmasuk1'];
        $thnlulus1 = $_POST['thnlulus1'];
        $keterangan1 = $_POST['keterangan1'];
        $sekolah2 = $_POST['sekolah2'];
        $kota2 = $_POST['kota2'];
        $negara2 = $_POST['negara2'];
        $jurusan2 = $_POST['jurusan2'];
        $thnmasuk2 = $_POST['thnmasuk2'];
        $thnlulus2 = $_POST['thnlulus2'];
        $keterangan2 = $_POST['keterangan2'];
        $sekolah3 = $_POST['sekolah3'];
        $kota3 = $_POST['kota3'];
        $negara3 = $_POST['negara3'];
        $jurusan3 = $_POST['jurusan3'];
        $thnmasuk3 = $_POST['thnmasuk3'];
        $thnlulus3 = $_POST['thnlulus3'];
        $keterangan3 = $_POST['keterangan3'];
        $sekolah4 = $_POST['sekolah4'];
        $kota4 = $_POST['kota4'];
        $negara4 = $_POST['negara4'];
        $jurusan4 = $_POST['jurusan4'];
        $thnmasuk4 = $_POST['thnmasuk4'];
        $thnlulus4 = $_POST['thnlulus4'];
        $keterangan4 = $_POST['keterangan4'];
        $sekolah5 = $_POST['sekolah5'];
        $kota5 = $_POST['kota5'];
        $negara5 = $_POST['negara5'];
        $jurusan5 = $_POST['jurusan5'];
        $thnmasuk5 = $_POST['thnmasuk5'];
        $thnlulus5 = $_POST['thnlulus5'];
        $keterangan5 = $_POST['keterangan5'];
        $sekolah6 = $_POST['sekolah6'];
        $kota6 = $_POST['kota6'];
        $negara6 = $_POST['negara6'];
        $jurusan6 = $_POST['jurusan6'];
        $thnmasuk6 = $_POST['thnmasuk6'];
        $thnlulus6 = $_POST['thnlulus6'];
        $keterangan6 = $_POST['keterangan6'];
        $sekolah7 = $_POST['sekolah7'];
        $kota7 = $_POST['kota7'];
        $negara7 = $_POST['negara7'];
        $jurusan7 = $_POST['jurusan7'];
        $thnmasuk7 = $_POST['thnmasuk7'];
        $thnlulus7 = $_POST['thnlulus7'];
        $keterangan7 = $_POST['keterangan7'];
        $sekolah8 = $_POST['sekolah8'];
        $kota8 = $_POST['kota8'];
        $negara8 = $_POST['negara8'];
        $jurusan8 = $_POST['jurusan8'];
        $thnmasuk8 = $_POST['thnmasuk8'];
        $thnlulus8 = $_POST['thnlulus8'];
        $keterangan8 = $_POST['keterangan8'];

    //2. table pelatihan
        $txtid_pel1 = $_POST['txtid_pel1'];
				$txtid_pel2 = $_POST['txtid_pel2'];
				$txtid_pel3 = $_POST['txtid_pel3'];
				$txtid_pel4 = $_POST['txtid_pel4'];
				$txtid_pel5 = $_POST['txtid_pel5'];
				$txtid_pel6 = $_POST['txtid_pel6'];
				$txtid_pel7 = $_POST['txtid_pel7'];
				$txtid_pel8 = $_POST['txtid_pel8'];

        $namapelatihan1 = $_POST['namapelatihan1'];
        $tahun1 = $_POST['tahun1'];
        $penyelenggara1 = $_POST['penyelenggara1'];
        $sertifikat1 = $_POST['sertifikat1'];
        $namapelatihan2 = $_POST['namapelatihan2'];
        $tahun2 = $_POST['tahun2'];
        $penyelenggara2 = $_POST['penyelenggara2'];
        $sertifikat2 = $_POST['sertifikat2'];
        $namapelatihan3 = $_POST['namapelatihan3'];
        $tahun3 = $_POST['tahun3'];
        $penyelenggara3 = $_POST['penyelenggara3'];
        $sertifikat3 = $_POST['sertifikat3'];
        $namapelatihan4 = $_POST['namapelatihan4'];
        $tahun4 = $_POST['tahun4'];
        $penyelenggara4 = $_POST['penyelenggara4'];
        $sertifikat4 = $_POST['sertifikat4'];
        $namapelatihan5 = $_POST['namapelatihan5'];
        $tahun5 = $_POST['tahun5'];
        $penyelenggara5 = $_POST['penyelenggara5'];
        $sertifikat5 = $_POST['sertifikat5'];
        $namapelatihan6 = $_POST['namapelatihan6'];
        $tahun6 = $_POST['tahun6'];
        $penyelenggara6 = $_POST['penyelenggara6'];
        $sertifikat6 = $_POST['sertifikat6'];
        $namapelatihan7 = $_POST['namapelatihan7'];
        $tahun7 = $_POST['tahun7'];
        $penyelenggara7 = $_POST['penyelenggara7'];
        $sertifikat7 = $_POST['sertifikat7'];
        $namapelatihan8 = $_POST['namapelatihan8'];
        $tahun8 = $_POST['tahun8'];
        $penyelenggara8 = $_POST['penyelenggara8'];
        $sertifikat8 = $_POST['sertifikat8'];
    //3. table penguasaan_bahasa
        $txtid_bhs1 = $_POST['txtid_bhs1'];
				$txtid_bhs2 = $_POST['txtid_bhs2'];
				$txtid_bhs3 = $_POST['txtid_bhs3'];

        $jenisbahasa1 = $_POST['jenisbahasa1'];
        $membaca1 = $_POST['membaca1'];
        $menulis1 = $_POST['menulis1'];
        $berbicara1 = $_POST['berbicara1'];
        $jenisbahasa2 = $_POST['jenisbahasa2'];
        $membaca2 = $_POST['membaca2'];
        $menulis2 = $_POST['menulis2'];
        $berbicara2 = $_POST['berbicara2'];
        $jenisbahasa3 = $_POST['jenisbahasa3'];
        $membaca3 = $_POST['membaca3'];
        $menulis3 = $_POST['menulis3'];
        $berbicara3 = $_POST['berbicara3'];

    //untuk table organisasi
    $txtid_org1 = $_POST['txtid_org1'];
		$txtid_org2 = $_POST['txtid_org2'];
		$txtid_org3 = $_POST['txtid_org3'];

    $namaorg1 = $_POST['namaorg1'];
    $masakerja1 = $_POST['masakerja1'];
    $jabatanx1 = $_POST['jabatanx1'];
    $keteranganx1 = $_POST['keteranganx1'];
    $namaorg2 = $_POST['namaorg2'];
    $masakerja2 = $_POST['masakerja2'];
    $jabatanx2 = $_POST['jabatanx2'];
    $keteranganx2 = $_POST['keteranganx2'];
    $namaorg3 = $_POST['namaorg3'];
    $masakerja3 = $_POST['masakerja3'];
    $jabatanx3 = $_POST['jabatanx3'];
    $keteranganx3 = $_POST['keteranganx3'];

    //untuk table riwayat_kerja
    $txtid_rkerja1 = $_POST['txtid_rkerja1'];
		$txtid_rkerja2 = $_POST['txtid_rkerja2'];
		$txtid_rkerja3 = $_POST['txtid_rkerja3'];

    $np1 = $_POST['np1'];
    $jabatan1 = $_POST['jabatan1'];
    $posisi1 = $_POST['posisi1'];
    $awalk1 = $_POST['awalk1'];
    $akhirk1 = $_POST['akhirk1'];
    $gaji1 = $_POST['gaji1'];
    $berhenti1 = $_POST['berhenti1'];
    $np2 = $_POST['np2'];
    $jabatan2 = $_POST['jabatan2'];
    $posisi2 = $_POST['posisi2'];
    $awalk2 = $_POST['awalk2'];
    $akhirk2 = $_POST['akhirk2'];
    $gaji2 = $_POST['gaji2'];
    $berhenti2 = $_POST['berhenti2'];
    $np3 = $_POST['np3'];
    $jabatan3 = $_POST['jabatan3'];
    $posisi3 = $_POST['posisi3'];
    $awalk3 = $_POST['awalk3'];
    $akhirk3 = $_POST['akhirk3'];
    $gaji3 = $_POST['gaji3'];
    $berhenti3 = $_POST['berhenti3'];

    //untuk table dp_lainnya
    $txtid_lain1 = $_POST['txtid_lain1'];
		$txtid_lain2 = $_POST['txtid_lain2'];
		$txtid_lain3 = $_POST['txtid_lain3'];
		$txtid_lain4 = $_POST['txtid_lain4'];
		$txtid_lain5 = $_POST['txtid_lain5'];

    $namak1 = $_POST['namak1'];
    $alamatk1 = $_POST['alamatk1'];
    $notelpk1 = $_POST['notelpk1'];
    $hubungank1 = $_POST['hubungank1'];
    $namak2 = $_POST['namak2'];
    $alamatk2 = $_POST['alamatk2'];
    $notelpk2 = $_POST['notelpk2'];
    $hubungank2 = $_POST['hubungank2'];
    $namak3 = $_POST['namak3'];
    $alamatk3 = $_POST['alamatk3'];
    $notelpk3 = $_POST['notelpk3'];
    $hubungank3 = $_POST['hubungank3'];
    $namak4 = $_POST['namak4'];
    $alamatk4 = $_POST['alamatk4'];
    $notelpk4 = $_POST['notelpk4'];
    $hubungank4 = $_POST['hubungank4'];
    $namak5 = $_POST['namak5'];
    $alamatk5 = $_POST['alamatk5'];
    $notelpk5 = $_POST['notelpk5'];
    $hubungank5 = $_POST['hubungank5'];

    if ($id=="")
    { $iddok=flookup("max(id_dok)","data_pribadi","id_dok<>0");
      $iddok=$iddok+1;
      $sql1 = "INSERT INTO data_pribadi(
          id_dp, id_dok, posisi_lamaran,
          work_information, ref_kerja, imagess,
          nama_lengkap, nama_panggilan, jenis_kelamin,
          ttl, kewarganegaraan, alamat_tetap, alamat_sementara, ktp, agama, jenis_sim,
          no_sim, goldar, status_pernikahan, no_hp, email,
          ukuran_baju,dateinput,username)
          VALUES(null, '$iddok', '$posisi', '$info',
              '$ref_kerja','','$fullname','$nickname','$gender',
              '$ttl', '$warganegara', '$alamattetap', '$alamatsementara',
              '$ktp', '$agama', '$jenissim', '$nosim', '$goldar', '$marital', '$nohp', '$email'
              ,'$ukuranbaju','$dateinput','$username'
              );";
      insert_log($sql1,$username);
      
      $id_dp=flookup("id_dp","data_pribadi","id_dok='$iddok'");
      $sql2 = "INSERT INTO sk_nama_keluarga(
          id_sk,id_dp, 
          ayah,ibu,saudara1,saudara2,saudara3,saudara4,suami_istri,anak1,anak2,anak3,anak4,
          jk_ayah,jk_ibu,jk_s1,jk_s2,jk_s3,jk_s4,jk_s_i,jk_ank1,jk_ank2,jk_ank3,jk_ank4,
          ttl_ayah,ttl_ibu,ttl_s1,ttl_s2,ttl_s3,ttl_s4,ttl_s_i,ttl_ank1,ttl_ank2,ttl_ank3,ttl_ank4,
          pekerjaan_ayah,pekerjaan_ibu,pekerjaan_s1,pekerjaan_s2,pekerjaan_s3,pekerjaan_s4,pekerjaan_s_i,pekerjaan_ank1,pekerjaan_ank2,pekerjaan_ank3,pekerjaan_ank4) 
          VALUES(null,'$id_dp', 
          '$namaayah','$namaibu','$namas1','$namas2','$namas3','$namas4','$namas_i','$namaank1','$namaank2','$namaank3','$namaank4',
          '$jk_ayah','$jk_ibu','$jk_s1','$jk_s2','$jk_s3','$jk_s4','$jk_s_i','$jk_ank1','$jk_ank2','$jk_ank3','$jk_ank4',
          '$ttlayah','$ttlibu','$ttls1','$ttls2','$ttls3','$ttls4','$ttls_i','$ttlank1','$ttlank2','$ttlank3','$ttlank4',
          '$kerjaanayah','$kerjaanibu','$kerjaans1','$kerjaans2','$kerjaans3','$kerjaans4','$kerjaans_i','$kerjaanank1','$kerjaanank2','$kerjaanank3','$kerjaanank4');";
      insert_log($sql2,$username);
      
      $txtedu_arr=array($txtid_edu1,$txtid_edu2,$txtid_edu3,$txtid_edu4,$txtid_edu5,$txtid_edu6,$txtid_edu7,$txtid_edu8);
      $sklh_arr=array($sekolah1,$sekolah2,$sekolah3,$sekolah4,$sekolah5,$sekolah6,$sekolah7,$sekolah8);
      $kota_arr=array($kota1,$kota2,$kota3,$kota4,$kota5,$kota6,$kota7,$kota8);
      $nega_arr=array($negara1,$negara2,$negara3,$negara4,$negara5,$negara6,$negara7,$negara8);
      $juru_arr=array($jurusan1,$jurusan2,$jurusan3,$jurusan4,$jurusan5,$jurusan6,$jurusan7,$jurusan8);
      $tmsk_arr=array($thnmasuk1,$thnmasuk2,$thnmasuk3,$thnmasuk4,$thnmasuk5,$thnmasuk6,$thnmasuk7,$thnmasuk8);
      $tlul_arr=array($thnlulus1,$thnlulus2,$thnlulus3,$thnlulus4,$thnlulus5,$thnlulus6,$thnlulus7,$thnlulus8);
      $keta_arr=array($keterangan1,$keterangan2,$keterangan3,$keterangan4,$keterangan5,$keterangan6,$keterangan7,$keterangan8);
      foreach ($sklh_arr as $key => $value)
      { if($sklh_arr[$key]!="" and $txtedu_arr[$key]=="")
        { $sql3 ="INSERT INTO edu_skill(id_dp, nama_sekolah, kota, negara, jurusan, tahun_masuk, tahun_lulus, keterangan)
            VALUES ('$id_dp','$sklh_arr[$key]','$kota_arr[$key]',
            '$nega_arr[$key]','$juru_arr[$key]','$tmsk_arr[$key]','$tlul_arr[$key]',
            '$keta_arr[$key]');";
          insert_log($sql3,$username);
        }
      }
      
      $txtpel_arr = array($txtid_pel1,$txtid_pel2,$txtid_pel3,$txtid_pel4,$txtid_pel5,$txtid_pel6,$txtid_pel7,$txtid_pel8);
      $pela_arr=array($namapelatihan1,$namapelatihan2,$namapelatihan3,$namapelatihan4,$namapelatihan5,$namapelatihan6,$namapelatihan7,$namapelatihan8);
      $thun_arr=array($tahun1,$tahun2,$tahun3,$tahun4,$tahun5,$tahun6,$tahun7,$tahun8);
      $peny_arr=array($penyelenggara1,$penyelenggara2,$penyelenggara3,$penyelenggara4,$penyelenggara5,$penyelenggara6,$penyelenggara7,$penyelenggara8);
      $sert_arr=array($sertifikat1,$sertifikat2,$sertifikat3,$sertifikat4,$sertifikat5,$sertifikat6,$sertifikat7,$sertifikat8);
      foreach ($pela_arr as $key => $value)
      { if($pela_arr[$key]!="")
        {	$sql4 = "INSERT INTO pelatihan(id_pelatihan,id_dp, nama_pelatihan, tahun, penyelenggara, keterangan_sertifikat)
          	VALUES(null,'$id_dp','$pela_arr[$key]','$thun_arr[$key]',
          	'$peny_arr[$key]','$sert_arr[$key]');";
      		insert_log($sql4,$username);
      	}
     	}

     	$txtbhs_arr=array($txtid_bhs1,$txtid_bhs2,$txtid_bhs3);
     	$bhsa_arr=array($jenisbahasa1,$jenisbahasa2,$jenisbahasa3);
     	$baca_arr=array($membaca1,$membaca2,$membaca3);
     	$nlis_arr=array($menulis1,$menulis2,$menulis3);
     	$bica_arr=array($berbicara1,$berbicara2,$berbicara3);
     	foreach ($bhsa_arr as $key => $value)
      { if($bhsa_arr[$key]!="")
        {	$sql5 = "INSERT INTO penguasaan_bahasa(id_pngbahasa,id_dp,jenis_bahasa, membaca, menulis, berbicara)
          	VALUES(null,'$id_dp','$bhsa_arr[$key]','$baca_arr[$key]',
          	'$nlis_arr[$key]','$bica_arr[$key]');";
      		insert_log($sql5,$username);
      	}
      }

      $txtorg_arr=array($txtid_org1,$txtid_org2,$txtid_org3);
      $orga_arr=array($namaorg1,$namaorg2,$namaorg3);
      $masa_arr=array($masakerja1,$masakerja2,$masakerja3);
      $jaba_arr=array($jabatanx1,$jabatanx2,$jabatanx3);
      $keto_arr=array($keteranganx1,$keteranganx2,$keteranganx3);
      foreach ($orga_arr as $key => $value)
      { if($orga_arr[$key]!="")
        {	$sql6 = "INSERT INTO organisasi(id_org,id_dp, nama_org, masa_kerja, jabatan, keterangan)
          	VALUES(null,'$id_dp','$orga_arr[$key]','$masa_arr[$key]',
          	'$jaba_arr[$key]','$keto_arr[$key]');";
      		insert_log($sql6,$username);
      	}
      }
      
      $txtrke_arr=array($txtid_rkerja1,$txtid_rkerja2,$txtid_rkerja3);
      $peru_arr=array($np1,$np2,$np3);
      $jabp_arr=array($jabatan1,$jabatan2,$jabatan3);
      $posi_arr=array($posisi1,$posisi2,$posisi3);
      $awth_arr=array($awalk1,$awalk2,$awalk3);
      $akth_arr=array($akhirk1,$akhirk2,$akhirk3);
      $gaji_arr=array($gaji1,$gaji2,$gaji3);
      $alas_arr=array($berhenti1,$berhenti2,$berhenti3);
      foreach ($peru_arr as $key => $value)
      { if($peru_arr[$key]!="")
        {	$sql7 ="INSERT INTO riwayat_kerja(id_rkerja,id_dp, nama_perusahaan, jabatan, posisi, awal_kerja, akhir_kerja, gaji, alasan_berhenti)
          	VALUES(null,'$id_dp','$peru_arr[$key]','$jabp_arr[$key]','$posi_arr[$key]',
          	'$awth_arr[$key]','$akth_arr[$key]','$gaji_arr[$key]',
          	'$alas_arr[$key]');";
      		insert_log($sql7,$username);
      	}
      }
      
      $txtlain_arr=array($txtid_lain1,$txtid_lain2,$txtid_lain3,$txtid_lain4,$txtid_lain5);
      $lain_arr=array($namak1,$namak2,$namak3,$namak4,$namak5);
      $lala_arr=array($alamatk1,$alamatk2,$alamatk3,$alamatk4,$alamatk5);
      $ltel_arr=array($notelpk1,$notelpk2,$notelpk3,$notelpk4,$notelpk5);
      $lhub_arr=array($hubungank1,$hubungank2,$hubungank3,$hubungank4,$hubungank5);
      foreach ($lain_arr as $key => $value)
      { if($lain_arr[$key]!="")
        {	$sql8 ="INSERT INTO dp_lainnya(id_lainnya,id_dp, nama, alamat, no_telp, hubungan)
          	VALUES(null,'$id_dp', '$lain_arr[$key]','$lala_arr[$key]','$ltel_arr[$key]','$lhub_arr[$key]')";
      		insert_log($sql8,$username);
      	}
      }
    }
    else
    { $iddok=flookup("id_dok","data_pribadi","id_dp='$id'");
      $sql1 = "update data_pribadi set posisi_lamaran='$posisi',
        work_information='$info',ref_kerja='$ref_kerja',nama_lengkap='$fullname',
        nama_panggilan='$nickname',jenis_kelamin='$gender',ttl='$ttl',kewarganegaraan='$warganegara',
        alamat_tetap='$alamattetap',alamat_sementara='$alamatsementara',ktp='$ktp',
        agama='$agama',jenis_sim='$jenissim',no_sim='$nosim',goldar='$goldar',status_pernikahan='$marital',
        no_hp='$nohp',email='$email',ukuran_baju='$ukuranbaju'
        where id_dp='$id'";
      insert_log($sql1,$username);
      
      $id_dp=flookup("id_dp","data_pribadi","id_dok='$iddok'");
      $sql2 = "update sk_nama_keluarga set ayah='$namaayah',ibu='$namaibu',
        saudara1='$namas1',saudara2='$namas2',saudara3='$namas3',saudara4='$namas4',
        suami_istri='$namas_i',
        anak1='$namaank1',anak2='$namaank2',anak3='$namaank3',anak4='$namaank4',
        jk_ayah='$jk_ayah',jk_ibu='$jk_ibu',
        jk_s1='$jk_s1',jk_s2='$jk_s2',jk_s3='$jk_s3',jk_s4='$jk_s4',
        jk_s_i='$jk_s_i',
        jk_ank1='$jk_ank1',jk_ank2='$jk_ank2',jk_ank3='$jk_ank3',jk_ank4='$jk_ank4',
        ttl_ayah='$ttlayah',ttl_ibu='$ttlibu',
        ttl_s1='$ttls1',ttl_s2='$ttls2',ttl_s3='$ttls3',ttl_s4='$ttls4',
        ttl_s_i='$ttls_i',
        ttl_ank1='$ttlank1',ttl_ank2='$ttlank2',ttl_ank3='$ttlank3',ttl_ank4='$ttlank4',
        pekerjaan_ayah='$kerjaanayah',pekerjaan_ibu='$kerjaanibu',
        pekerjaan_s1='$kerjaans1',pekerjaan_s2='$kerjaans2',pekerjaan_s3='$kerjaans3',pekerjaan_s4='$kerjaans4',
        pekerjaan_s_i='$kerjaans_i',
        pekerjaan_ank1='$kerjaanank1',pekerjaan_ank2='$kerjaanank2',pekerjaan_ank3='$kerjaanank3',pekerjaan_ank4='$kerjaanank4' 
        where id_dp='$id_dp'";
      insert_log($sql2,$username);
			
			$txtedu_arr=array($txtid_edu1,$txtid_edu2,$txtid_edu3,$txtid_edu4,$txtid_edu5,$txtid_edu6,$txtid_edu7,$txtid_edu8);
      $sklh_arr=array($sekolah1,$sekolah2,$sekolah3,$sekolah4,$sekolah5,$sekolah6,$sekolah7,$sekolah8);
      $kota_arr=array($kota1,$kota2,$kota3,$kota4,$kota5,$kota6,$kota7,$kota8);
      $nega_arr=array($negara1,$negara2,$negara3,$negara4,$negara5,$negara6,$negara7,$negara8);
      $juru_arr=array($jurusan1,$jurusan2,$jurusan3,$jurusan4,$jurusan5,$jurusan6,$jurusan7,$jurusan8);
      $tmsk_arr=array($thnmasuk1,$thnmasuk2,$thnmasuk3,$thnmasuk4,$thnmasuk5,$thnmasuk6,$thnmasuk7,$thnmasuk8);
      $tlul_arr=array($thnlulus1,$thnlulus2,$thnlulus3,$thnlulus4,$thnlulus5,$thnlulus6,$thnlulus7,$thnlulus8);
      $keta_arr=array($keterangan1,$keterangan2,$keterangan3,$keterangan4,$keterangan5,$keterangan6,$keterangan7,$keterangan8);
      foreach ($sklh_arr as $key => $value)
      { if($sklh_arr[$key]!="" and $txtedu_arr[$key]=="")
        { $sql3 ="INSERT INTO edu_skill(id_dp, nama_sekolah, kota, negara, jurusan, tahun_masuk, tahun_lulus, keterangan)
            VALUES ('$id_dp','$sklh_arr[$key]','$kota_arr[$key]',
            '$nega_arr[$key]','$juru_arr[$key]','$tmsk_arr[$key]','$tlul_arr[$key]',
            '$keta_arr[$key]');";
          insert_log($sql3,$username);
        }
        else if($sklh_arr[$key]!="" and $txtedu_arr[$key]!="")
        { $sql3 ="update edu_skill set nama_sekolah='$sklh_arr[$key]',kota='$kota_arr[$key]',
		        negara='$nega_arr[$key]',jurusan='$juru_arr[$key]',tahun_masuk='$tmsk_arr[$key]',
		        tahun_lulus='$tlul_arr[$key]',keterangan='$keta_arr[$key]' 
		        where id_dp='$id_dp' and id_edu_skill='$txtedu_arr[$key]'";
		      insert_log($sql3,$username);
        }
      }

      $txtpel_arr = array($txtid_pel1,$txtid_pel2,$txtid_pel3,$txtid_pel4,$txtid_pel5,$txtid_pel6,$txtid_pel7,$txtid_pel8);
      $pela_arr=array($namapelatihan1,$namapelatihan2,$namapelatihan3,$namapelatihan4,$namapelatihan5,$namapelatihan6,$namapelatihan7,$namapelatihan8);
      $thun_arr=array($tahun1,$tahun2,$tahun3,$tahun4,$tahun5,$tahun6,$tahun7,$tahun8);
      $peny_arr=array($penyelenggara1,$penyelenggara2,$penyelenggara3,$penyelenggara4,$penyelenggara5,$penyelenggara6,$penyelenggara7,$penyelenggara8);
      $sert_arr=array($sertifikat1,$sertifikat2,$sertifikat3,$sertifikat4,$sertifikat5,$sertifikat6,$sertifikat7,$sertifikat8);
      foreach ($pela_arr as $key => $value)
      { if($pela_arr[$key]!="" and $txtpel_arr[$key]=="")
        {	$sql4 = "INSERT INTO pelatihan(id_pelatihan,id_dp, nama_pelatihan, tahun, penyelenggara, keterangan_sertifikat)
          	VALUES(null,'$id_dp','$pela_arr[$key]','$thun_arr[$key]',
          	'$peny_arr[$key]','$sert_arr[$key]');";
      		insert_log($sql4,$username);
      	}
      	else if($pela_arr[$key]!="" and $txtpel_arr[$key]!="")
      	{	$sql4 = "update pelatihan set nama_pelatihan='$pela_arr[$key]', 
		        tahun='$thun_arr[$key]',penyelenggara='$peny_arr[$key]',
		        keterangan_sertifikat='$sert_arr[$key]'
		        where id_dp='$id_dp' and id_pelatihan='$txtpel_arr[$key]'";
		      insert_log($sql4,$username);
		    }
     	}
      
      $txtbhs_arr=array($txtid_bhs1,$txtid_bhs2,$txtid_bhs3);
     	$bhsa_arr=array($jenisbahasa1,$jenisbahasa2,$jenisbahasa3);
     	$baca_arr=array($membaca1,$membaca2,$membaca3);
     	$nlis_arr=array($menulis1,$menulis2,$menulis3);
     	$bica_arr=array($berbicara1,$berbicara2,$berbicara3);
     	foreach ($bhsa_arr as $key => $value)
      { if($bhsa_arr[$key]!="" and $txtbhs_arr[$key]=="")
        {	$sql5 = "INSERT INTO penguasaan_bahasa(id_pngbahasa,id_dp,jenis_bahasa, membaca, menulis, berbicara)
          	VALUES(null,'$id_dp','$bhsa_arr[$key]','$baca_arr[$key]',
          	'$nlis_arr[$key]','$bica_arr[$key]');";
      		insert_log($sql5,$username);
      	}
      	else if($bhsa_arr[$key]!="" and $txtbhs_arr[$key]!="")
      	{	$sql5 = "update penguasaan_bahasa set jenis_bahasa='$bhsa_arr[$key]',
		        membaca='$baca_arr[$key]',menulis='$nlis_arr[$key]',berbicara='$bica_arr[$key]' 
		        where id_dp='$id_dp' and id_pngbahasa='$txtbhs_arr[$key]'";
		      insert_log($sql5,$username);
		    }
      }

      $txtorg_arr=array($txtid_org1,$txtid_org2,$txtid_org3);
      $orga_arr=array($namaorg1,$namaorg2,$namaorg3);
      $masa_arr=array($masakerja1,$masakerja2,$masakerja3);
      $jaba_arr=array($jabatanx1,$jabatanx2,$jabatanx3);
      $keto_arr=array($keteranganx1,$keteranganx2,$keteranganx3);
      foreach ($orga_arr as $key => $value)
      { if($orga_arr[$key]!="" and $txtorg_arr[$key]=="")
        {	$sql6 = "INSERT INTO organisasi(id_org,id_dp, nama_org, masa_kerja, jabatan, keterangan)
          	VALUES(null,'$id_dp','$orga_arr[$key]','$masa_arr[$key]',
          	'$jaba_arr[$key]','$keto_arr[$key]');";
      		insert_log($sql6,$username);
      	}
      	else if($orga_arr[$key]!="" and $txtorg_arr[$key]!="")
      	{	$sql6 = "update organisasi set nama_org='$orga_arr[$key]',masa_kerja='$masa_arr[$key]',
		        jabatan='$jaba_arr[$key]',keterangan='$keto_arr[$key]' 
		        where id_dp='$id_dp' and id_org='$txtorg_arr[$key]'";
		      insert_log($sql6,$username);
		    }
      }
      
      $txtrke_arr=array($txtid_rkerja1,$txtid_rkerja2,$txtid_rkerja3);
      $peru_arr=array($np1,$np2,$np3);
      $jabp_arr=array($jabatan1,$jabatan2,$jabatan3);
      $posi_arr=array($posisi1,$posisi2,$posisi3);
      $awth_arr=array($awalk1,$awalk2,$awalk3);
      $akth_arr=array($akhirk1,$akhirk2,$akhirk3);
      $gaji_arr=array($gaji1,$gaji2,$gaji3);
      $alas_arr=array($berhenti1,$berhenti2,$berhenti3);
      foreach ($peru_arr as $key => $value)
      { if($peru_arr[$key]!="" and $txtrke_arr[$key]=="")
        {	$sql7 ="INSERT INTO riwayat_kerja(id_rkerja,id_dp, nama_perusahaan, jabatan, posisi, awal_kerja, akhir_kerja, gaji, alasan_berhenti)
          	VALUES(null,'$id_dp','$peru_arr[$key]','$jabp_arr[$key]','$posi_arr[$key]',
          	'$awth_arr[$key]','$akth_arr[$key]','$gaji_arr[$key]',
          	'$alas_arr[$key]');";
      		insert_log($sql7,$username);
      	}
      	else if($peru_arr[$key]!="" and $txtrke_arr[$key]!="")
      	{	$sql7 ="update riwayat_kerja set nama_perusahaan='$peru_arr[$key]',
      			jabatan='$jabp_arr[$key]',
		        posisi='$posi_arr[$key]',awal_kerja='$awth_arr[$key]',
		        akhir_kerja='$akth_arr[$key]',
		        gaji='$gaji_arr[$key]',alasan_berhenti='$alas_arr[$key]' 
		        where id_dp='$id_dp' and id_rkerja='$txtrke_arr[$key]'";
		      insert_log($sql7,$username);
		    }
      }
      
      $txtlain_arr=array($txtid_lain1,$txtid_lain2,$txtid_lain3,$txtid_lain4,$txtid_lain5);
      $lain_arr=array($namak1,$namak2,$namak3,$namak4,$namak5);
      $lala_arr=array($alamatk1,$alamatk2,$alamatk3,$alamatk4,$alamatk5);
      $ltel_arr=array($notelpk1,$notelpk2,$notelpk3,$notelpk4,$notelpk5);
      $lhub_arr=array($hubungank1,$hubungank2,$hubungank3,$hubungank4,$hubungank5);
      foreach ($lain_arr as $key => $value)
      { if($lain_arr[$key]!="" and $txtlain_arr[$key]=="")
        {	$sql8 ="INSERT INTO dp_lainnya(id_lainnya,id_dp, nama, alamat, no_telp, hubungan)
          	VALUES(null,'$id_dp','$lain_arr[$key]','$lala_arr[$key]','$ltel_arr[$key]','$lhub_arr[$key]')";
      		insert_log($sql8,$username);
      	}
      	else if($lain_arr[$key]!="" and $txtlain_arr[$key]!="")
      	{	$sql8 ="update dp_lainnya set nama='$lain_arr[$key]',
      			alamat='$lala_arr[$key]',
		        no_telp='$ltel_arr[$key]',hubungan='$lhub_arr[$key]' 
		        where id_dp='$id_dp' and id_lainnya='$txtlain_arr[$key]' ";
		      insert_log($sql8,$username);
		    }
      }
    }
    $_SESSION['msg']="Data Berhasil Disimpan";
    echo '<script>location.replace("../hr/?mod=29")</script>';

mysqli_close($con_new);
?>
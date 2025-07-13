<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
 
if (isset($_GET['id'])) {$id_dp=$_GET['id'];} else {$id_dp="";}
if ($id_dp!="")
{ $sql="select a.*,s.* 
  from data_pribadi a inner join sk_nama_keluarga s on a.id_dp=s.id_dp 
  where a.id_dp='$id_dp'";
  $rs=mysql_fetch_array(mysql_query($sql));
  $nama_lengkap=$rs['nama_lengkap'];
  $posisi_lamaran=$rs['posisi_lamaran'];
  $work_information=$rs['work_information'];
  $ref_kerja=$rs['ref_kerja'];
  $imagess=$rs['imagess'];
  $nama_panggilan=$rs['nama_panggilan'];
  $jenis_kelamin=$rs['jenis_kelamin'];
  $ttl=$rs['ttl'];
  $kewarganegaraan=$rs['kewarganegaraan'];
  $alamat_tetap=$rs['alamat_tetap'];
  $alamat_sementara=$rs['alamat_sementara'];
  $ktp=$rs['ktp'];
  $agama=$rs['agama'];
  $jenis_sim=$rs['jenis_sim'];
  $no_sim=$rs['no_sim'];
  $goldar=$rs['goldar'];
  $status_pernikahan=$rs['status_pernikahan'];
  $no_hp=$rs['no_hp'];
  $email=$rs['email'];
  $ukuran_baju=$rs['ukuran_baju'];
  $skayah=$rs['ayah'];
  $skibu=$rs['ibu'];
  $sksaudara1=$rs['saudara1'];
  $sksaudara2=$rs['saudara2'];
  $sksaudara3=$rs['saudara3'];
  $sksaudara4=$rs['saudara4'];
  $sksuami_istri=$rs['suami_istri'];
  $skanak1=$rs['anak1'];
  $skanak2=$rs['anak2'];
  $skanak3=$rs['anak3'];
  $skanak4=$rs['anak4'];
  $skjk_ayah=$rs['jk_ayah'];
  $skjk_ibu=$rs['jk_ibu'];
  $skjk_s1=$rs['jk_s1'];
  $skjk_s2=$rs['jk_s2'];
  $skjk_s3=$rs['jk_s3'];
  $skjk_s4=$rs['jk_s4'];
  $skjk_s_i=$rs['jk_s_i'];
  $skjk_ank1=$rs['jk_ank1'];
  $skjk_ank2=$rs['jk_ank2'];
  $skjk_ank3=$rs['jk_ank3'];
  $skjk_ank4=$rs['jk_ank4'];
  $skttl_ayah=$rs['ttl_ayah'];
  $skttl_ibu=$rs['ttl_ibu'];
  $skttl_s1=$rs['ttl_s1'];
  $skttl_s2=$rs['ttl_s2'];
  $skttl_s3=$rs['ttl_s3'];
  $skttl_s4=$rs['ttl_s4'];
  $skttl_s_i=$rs['ttl_s_i'];
  $skttl_ank1=$rs['ttl_ank1'];
  $skttl_ank2=$rs['ttl_ank2'];
  $skttl_ank3=$rs['ttl_ank3'];
  $skttl_ank4=$rs['ttl_ank4'];
  $skpekerjaan_ayah=$rs['pekerjaan_ayah'];
  $skpekerjaan_ibu=$rs['pekerjaan_ibu'];
  $skpekerjaan_s1=$rs['pekerjaan_s1'];
  $skpekerjaan_s2=$rs['pekerjaan_s2'];
  $skpekerjaan_s3=$rs['pekerjaan_s3'];
  $skpekerjaan_s4=$rs['pekerjaan_s4'];
  $skpekerjaan_s_i=$rs['pekerjaan_s_i'];
  $skpekerjaan_ank1=$rs['pekerjaan_ank1'];
  $skpekerjaan_ank2=$rs['pekerjaan_ank2'];
  $skpekerjaan_ank3=$rs['pekerjaan_ank3'];
  $skpekerjaan_ank4=$rs['pekerjaan_ank4'];

  $sqledu=mysql_query("select * from edu_skill 
    where id_dp='$id_dp' order by id_edu_skill");
  $id_edu_arr="";
  $edunama_sekolah_arr="";
  $edukota_arr="";
  $edunegara_arr="";
  $edujurusan_arr="";
  $edutahun_masuk_arr="";
  $edutahun_lulus_arr="";
  $eduketerangan_arr="";
  $jm_edu=0;
  while($rsedu=mysql_fetch_array($sqledu))
  { if ($id_edu_arr=="")
    { $id_edu_arr=$rsedu['id_edu_skill']; 
      $edunama_sekolah_arr=$rsedu['nama_sekolah'];
      $edukota_arr=$rsedu['kota'];
      $edunegara_arr=$rsedu['negara'];
      $edujurusan_arr=$rsedu['jurusan'];
      $edutahun_masuk_arr=$rsedu['tahun_masuk'];
      $edutahun_lulus_arr=$rsedu['tahun_lulus'];
      $eduketerangan_arr=$rsedu['keterangan'];
    }
    else
    { $id_edu_arr=$id_edu_arr.",".$rsedu['id_edu_skill']; 
      $edunama_sekolah_arr=$edunama_sekolah_arr.",".$rsedu['nama_sekolah'];
      $edukota_arr=$edukota_arr.",".$rsedu['kota'];
      $edunegara_arr=$edunegara_arr.",".$rsedu['negara'];
      $edujurusan_arr=$edujurusan_arr.",".$rsedu['jurusan'];
      $edutahun_masuk_arr=$edutahun_masuk_arr.",".$rsedu['tahun_masuk'];
      $edutahun_lulus_arr=$edutahun_lulus_arr.",".$rsedu['tahun_lulus'];
      $eduketerangan_arr=$eduketerangan_arr.",".$rsedu['keterangan'];
    }
    $jm_edu++;
  }
  $id_edu=explode(",",$id_edu_arr);
  $edunama_sekolah=explode(",",$edunama_sekolah_arr);
  $edukota=explode(",",$edukota_arr);
  $edunegara=explode(",",$edunegara_arr);
  $edujurusan=explode(",",$edujurusan_arr);
  $edutahun_masuk=explode(",",$edutahun_masuk_arr);
  $edutahun_lulus=explode(",",$edutahun_lulus_arr);
  $eduketerangan=explode(",",$eduketerangan_arr);

  $sqlpel=mysql_query("select * from pelatihan 
    where id_dp='$id_dp' order by id_pelatihan");
  $id_pel_arr="";
  $pelnama_sekolah_arr="";
  $pelkota_arr="";
  $pelnegara_arr="";
  $peljurusan_arr="";
  $jm_pel=0;
  while($rspel=mysql_fetch_array($sqlpel))
  { if ($id_pel_arr=="")
    { $id_pel_arr=$rspel['id_pelatihan']; 
      $pelnama_sekolah_arr=$rspel['nama_pelatihan'];
      $pelkota_arr=$rspel['tahun'];
      $pelnegara_arr=$rspel['penyelenggara'];
      $peljurusan_arr=$rspel['keterangan_sertifikat'];
    }
    else
    { $id_pel_arr=$id_pel_arr.",".$rspel['id_pelatihan']; 
      $pelnama_sekolah_arr=$pelnama_sekolah_arr.",".$rspel['nama_pelatihan'];
      $pelkota_arr=$pelkota_arr.",".$rspel['tahun'];
      $pelnegara_arr=$pelnegara_arr.",".$rspel['penyelenggara'];
      $peljurusan_arr=$peljurusan_arr.",".$rspel['keterangan_sertifikat'];
    }
    $jm_pel++;
  }
  $id_pel=explode(",",$id_pel_arr);
  $pelnama_sekolah=explode(",",$pelnama_sekolah_arr);
  $pelkota=explode(",",$pelkota_arr);
  $pelnegara=explode(",",$pelnegara_arr);
  $peljurusan=explode(",",$peljurusan_arr);

  $sqlbhs=mysql_query("select * from penguasaan_bahasa 
    where id_dp='$id_dp' order by id_pngbahasa");
  $id_pngbahasa_arr="";
  $jenis_bahasa_arr="";
  $membaca_arr="";
  $menulis_arr="";
  $berbicara_arr="";
  $jm_bhs=0;
  while($rsbhs=mysql_fetch_array($sqlbhs))
  { if ($id_pngbahasa_arr=="")
    { $id_pngbahasa_arr=$rsbhs['id_pngbahasa'];
      $jenis_bahasa_arr=$rsbhs['jenis_bahasa'];
      $membaca_arr=$rsbhs['membaca'];
      $menulis_arr=$rsbhs['menulis'];
      $berbicara_arr=$rsbhs['berbicara'];
    }
    else
    { $id_pngbahasa_arr=$id_pngbahasa_arr.",".$rsbhs['id_pngbahasa'];
      $jenis_bahasa_arr=$jenis_bahasa_arr.",".$rsbhs['jenis_bahasa'];
      $membaca_arr=$membaca_arr.",".$rsbhs['membaca'];
      $menulis_arr=$menulis_arr.",".$rsbhs['menulis'];
      $berbicara_arr=$berbicara_arr.",".$rsbhs['berbicara'];
    }
    $jm_bhs++;
  }
  $id_pngbahasa=explode(",",$id_pngbahasa_arr);
  $jenis_bahasa=explode(",",$jenis_bahasa_arr);
  $membaca=explode(",",$membaca_arr);
  $menulis=explode(",",$menulis_arr);
  $berbicara=explode(",",$berbicara_arr);

  $sqlorg=mysql_query("select * from organisasi 
    where id_dp='$id_dp' order by id_org");
  $id_org_arr="";
  $nama_org_arr="";
  $masa_kerja_arr="";
  $jabatan_arr="";
  $keterangan_arr="";
  $jm_org=0;
  while($rsorg=mysql_fetch_array($sqlorg))
  { if ($id_org_arr=="")
    { $id_org_arr=$rsorg['id_org'];
      $nama_org_arr=$rsorg['nama_org'];
      $masa_kerja_arr=$rsorg['masa_kerja'];
      $jabatan_arr=$rsorg['jabatan'];
      $keterangan_arr=$rsorg['keterangan'];
    }
    else
    { $id_org_arr=$id_org_arr.",".$rsorg['id_org'];
      $nama_org_arr=$nama_org_arr.",".$rsorg['nama_org'];
      $masa_kerja_arr=$masa_kerja_arr.",".$rsorg['masa_kerja'];
      $jabatan_arr=$jabatan_arr.",".$rsorg['jabatan'];
      $keterangan_arr=$keterangan_arr.",".$rsorg['keterangan'];
    }
    $jm_org++;
  }
  $id_org=explode(",",$id_org_arr);
  $nama_org=explode(",",$nama_org_arr);
  $masa_kerja=explode(",",$masa_kerja_arr);
  $jabatan=explode(",",$jabatan_arr);
  $keterangan=explode(",",$keterangan_arr);

  $sqlrkerja=mysql_query("select * from riwayat_kerja 
    where id_dp='$id_dp' order by id_rkerja");
  $id_rkerja_arr="";
  $nama_perusahaan_arr="";
  $jabatan_arr="";
  $posisi_arr="";
  $awal_kerja_arr="";
  $akhir_kerja_arr="";
  $gaji_arr="";
  $alasan_berhenti_arr="";
  $jm_rkerja=0;
  while($rsrkerja=mysql_fetch_array($sqlrkerja))
  { if ($id_rkerja_arr=="")
    { $id_rkerja_arr=$rsrkerja['id_rkerja'];
      $nama_perusahaan_arr=$rsrkerja['nama_perusahaan'];
      $jabatan_arr=$rsrkerja['jabatan'];
      $posisi_arr=$rsrkerja['posisi'];
      $awal_kerja_arr=$rsrkerja['awal_kerja'];
      $akhir_kerja_arr=$rsrkerja['akhir_kerja'];
      $gaji_arr=$rsrkerja['gaji'];
      $alasan_berhenti_arr=$rsrkerja['alasan_berhenti'];
    }
    else
    { $id_rkerja_arr=$id_rkerja_arr.",".$rsrkerja['id_rkerja'];
      $nama_perusahaan_arr=$nama_perusahaan_arr.",".$rsrkerja['nama_perusahaan'];
      $jabatan_arr=$jabatan_arr.",".$rsrkerja['jabatan'];
      $posisi_arr=$posisi_arr.",".$rsrkerja['posisi'];
      $awal_kerja_arr=$awal_kerja_arr.",".$rsrkerja['awal_kerja'];
      $akhir_kerja_arr=$akhir_kerja_arr.",".$rsrkerja['akhir_kerja'];
      $gaji_arr=$gaji_arr.",".$rsrkerja['gaji'];
      $alasan_berhenti_arr=$alasan_berhenti_arr.",".$rsrkerja['alasan_berhenti'];
    }
    $jm_rkerja++;
  }
  $id_rkerja=explode(",",$id_rkerja_arr);
  $nama_perusahaan=explode(",",$nama_perusahaan_arr);
  $jabatan=explode(",",$jabatan_arr);
  $posisi=explode(",",$posisi_arr);
  $awal_kerja=explode(",",$awal_kerja_arr);
  $akhir_kerja=explode(",",$akhir_kerja_arr);
  $gaji=explode(",",$gaji_arr);
  $alasan_berhenti=explode(",",$alasan_berhenti_arr);

  $sqllain=mysql_query("select * from dp_lainnya 
    where id_dp='$id_dp' order by id_lainnya");
  $id_lainnya_arr="";
  $lainnama_arr="";
  $lainalamat_arr="";
  $lainno_telp_arr="";
  $lainhubungan_arr="";
  $jm_lain=0;
  while($rslain=mysql_fetch_array($sqllain))
  { if ($id_lainnya_arr=="")
    { $id_lainnya_arr=$rslain['id_lainnya'];
      $lainnama_arr=$rslain['nama'];
      $lainalamat_arr=$rslain['alamat'];
      $lainno_telp_arr=$rslain['no_telp'];
      $lainhubungan_arr=$rslain['hubungan'];
    }
    else
    { $id_lainnya_arr=$id_lainnya_arr.",".$rslain['id_lainnya'];
      $lainnama_arr=$lainnama_arr.",".$rslain['nama'];
      $lainalamat_arr=$lainalamat_arr.",".$rslain['alamat'];
      $lainno_telp_arr=$lainno_telp_arr.",".$rslain['no_telp'];
      $lainhubungan_arr=$lainhubungan_arr.",".$rslain['hubungan'];
    }
    $jm_lain++;
  }
  $id_lainnya=explode(",",$id_lainnya_arr);
  $lainnama=explode(",",$lainnama_arr);
  $lainalamat=explode(",",$lainalamat_arr);
  $lainno_telp=explode(",",$lainno_telp_arr);
  $lainhubungan=explode(",",$lainhubungan_arr);
}
else
{ $nama_lengkap="";
  $posisi_lamaran="";
  $work_information="";
  $ref_kerja="";
  $imagess="";
  $nama_panggilan="";
  $jenis_kelamin="";
  $ttl="";
  $kewarganegaraan="";
  $alamat_tetap="";
  $alamat_sementara="";
  $ktp="";
  $agama="";
  $jenis_sim="";
  $no_sim="";
  $goldar="";
  $status_pernikahan="";
  $no_hp="";
  $email="";
  $ukuran_baju="";

  $skayah="";
  $skibu="";
  $sksaudara1="";
  $sksaudara2="";
  $sksaudara3="";
  $sksaudara4="";
  $sksuami_istri="";
  $skanak1="";
  $skanak2="";
  $skanak3="";
  $skanak4="";
  $skjk_ayah="";
  $skjk_ibu="";
  $skjk_s1="";
  $skjk_s2="";
  $skjk_s3="";
  $skjk_s4="";
  $skjk_s_i="";
  $skjk_ank1="";
  $skjk_ank2="";
  $skjk_ank3="";
  $skjk_ank4="";
  $skttl_ayah="";
  $skttl_ibu="";
  $skttl_s1="";
  $skttl_s2="";
  $skttl_s3="";
  $skttl_s4="";
  $skttl_s_i="";
  $skttl_ank1="";
  $skttl_ank2="";
  $skttl_ank3="";
  $skttl_ank4="";
  $skpekerjaan_ayah="";
  $skpekerjaan_ibu="";
  $skpekerjaan_s1="";
  $skpekerjaan_s2="";
  $skpekerjaan_s3="";
  $skpekerjaan_s4="";
  $skpekerjaan_s_i="";
  $skpekerjaan_ank1="";
  $skpekerjaan_ank2="";
  $skpekerjaan_ank3="";
  $skpekerjaan_ank4="";

  $jm_edu=0;
  $jm_pel=0;
  $jm_org=0;
  $jm_lain=0;
  $jm_rkerja=0;
  $jm_bhs=0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Aplikasi Karyawan</title>
    <style>
        table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 90%;
                margin-left : 5%;
                margin-right: 5%;
            }
        font{
             font-style:italic;
            }
        .tigapuluh{
            width: 30%;
            margin: 10px;
        }
        .tujuhpuluh{
            width: 70%;
            margin: 10px;
        }
        select{
            margin:10px; width:50%; height:40px; font-size:14px;"
        }
    </style>
</head>
<body>
<form id="formapply" method="POST">
   <table>
        <tr>
            <td style="
            border: 2px solid #000000;
            text-align: center;
            padding: 8px;"
            rowspan="4"
            ><img src="../../include/img-01.png" style="width:100px; height:100px"></td>
            <td style="
            border: 2px solid #000000;
            text-align: center;
            padding: 8px;"
            rowspan="4"
            > 
                <p style="font-weight:bold;
                        font-size:40px;
                ">Form Aplikasi Calon Karyawan</p>
            </td>
            <td style="text-align: left;
                    border-top: 2px solid #000000;
                    padding: 8px;">
                Kode Dok</td>
            <td style="text-align: left;
                    border-top: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px;">: <input type="text" readonly value='F.16.P.HR.P-01.F-02.01.02' id="id_dok" name="id_dok" placeholder="wajib diisi"/> </td>
        </tr>
            <td style="text-align: left;
                    padding: 8px;"> Revisi </td>
            <td style="text-align: left;
                    border-right: 2px solid #000000;
                    padding: 8px;">: <input type="text" readonly value='-' name="revisi">  </td>
        </tr>
        <tr>
            <td style="text-align: left;
                    padding: 8px;">Tanggal Revisi </td>
            <td style="text-align: left;
                    border-right: 2px solid #000000;
                    padding: 8px;">: <input type="text" readonly value='-' name="revisi_date" value="<?php echo date('Y-m-d');?>"> </td>
        </tr>
        <tr>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    padding: 8px;">Tanggal Berlaku</td>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px;">: <input type="text" readonly value='30 September 2016' name="berlaku_date"> </td>
        <tr>
   </table> 
   <br/>
   <table style="margin-bottom:20px;">
        <tr >
            <td
            style="text-align:left;width:30%;"> 
            <span style="font-weight:bold;">Posisi Yang Dilamar (<font>Applied Position</font>)</span>
            </td>
            <td
                style="text-align:left; width:70%;">
                : <input type="text" value="<?php echo $posisi_lamaran;?>" style="width:90%; padding:5px; font-size:14px;" placeholder="wajib diisi" id="posisi" name="posisi"> 
            </td>
       </tr>
    </table>
    <table style="margin-bottom:20px;">
        <tr>
            <td style="text-align:left;width:70%;"> 
            <span style="font-weight:bold;">Anda Mengetahui Info Lowongan Pekerjaan Dari (<font>work information</font>):</span> <br>
                <span style="padding-left:5%;">
                <input type="radio" name="info" id="info" 
                  value="internet" 
                  <?php if ($work_information=="internet") {echo "checked";} ?>
                  style="margin-top:8px; margin-left:18px"> Internet
                <input type="radio" name="info" id="info" 
                  value="media" 
                  <?php if ($work_information=="media") {echo "checked";} ?>
                  style="margin-top:8px; margin-left:18px"> Media
                <input type="radio" name="info" id="info" 
                  value="keluarga" 
                  <?php if ($work_information=="keluarga") {echo "checked";} ?>
                  style="margin-top:8px; margin-left:18px"> Keluarga (<font>family</font>)
                <input type="radio" name="info" id="info" 
                  value="iklan" 
                  <?php if ($work_information=="iklan") {echo "checked";} ?>
                  style="margin-top:8px; margin-left:18px"> Iklan (<font>advert</font>)
                <input type="radio" name="info" id="info" 
                  value="lainnya" 
                  <?php if ($work_information=="lainnya") {echo "checked";} ?>
                  style="margin-top:8px; margin-left:18px"> Lainnya <input id="isiinfo" type="text" style="padding:5px;">
            </td>
            <td style="text-align:left; width:30%;">
            <!-- image here -->
            </td>
       </tr>
   </table>
   <p style="margin-left:5%; font-weight:bold;">*Referensi Kerja(sebutkan nama dan hubungan)(<font>work Reference</font>)(<font>Provide name of reference</font> :</p>
   <input type="text" value="<?php echo $ref_kerja;?>" style="width:90%; padding:5px; font-size:14px; margin-left:5%;" placeholder="wajib diisi" id="referensi" name="referensi"> 
   <fieldset style="margin-left:5%; margin-right:5%; margin-top:15px;">
    <legend style="font-weight:bold;"> Data Pribadi ( <font>personal data</font> )</legend>
    <table>
            <tr>
                <td class="tigapuluh">Nama Lengkap (<font>full name</font>) </td>
                <td class="tujuhpuluh"> 
                
                    : <input type="text" value="<?php echo $nama_lengkap;?>" style="width:30%; padding:5px; margin: 5px;  font-size:14px;" id="fullname" placeholder="wajib diisi" name="fullname"> nama panggilan (<font>nick name</fong>) <input name="nickname" placeholder="wajib diisi" id="nickname" type="text" value="<?php echo $nama_panggilan;?>" style="width:30%; padding:5px; margin: 5px; font-size:14px;">
                
                </td>
            </tr>
            <tr>
                <td class="tigapuluh">Jenis Kelamin (<font>Gender</font>) </td>
                <td class="tujuhpuluh"> 
                : 
                  <select name="gender" id="gender">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$jenis_kelamin,'Pilih Jenis Kelamin');
                  ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td class="tigapuluh">Tempat, Tanngal Lahir (<font>Place of birth</font>) </td>
                <td class="tujuhpuluh"> 
                    : <input type="text" value="<?php echo $ttl;?>" style="width:90%; padding:5px; margin: 5px; font-size:14px;" id="ttl" placeholder="wajib diisi" name="ttl">
                </td>
            </tr>
            <tr>
                <td class="tigapuluh">Kewarganegaraan (<font>Nationality</font>) </td>
                <td class="tujuhpuluh"> 
                    : <input type="text" value="<?php echo $kewarganegaraan;?>" style="width:90%; padding:5px; margin: 5px; font-size:14px;" id="warganegara" placeholder="wajib diisi" name="warganegara">
                </td>
            </tr>
            <tr valign="top">
                <td class="tigapuluh">Alamat Tetap (<font>Permanent Address</font>) </td>
                <td class="tujuhpuluh"> 
                  <textarea  style="width:90%; height:150px; padding:5px; 
                    margin-left: 15px; font-size:14px; resize:none;" 
                    id="alamattetap" placeholder="wajib diisi" 
                    name="alamattetap">
                  <?php echo $alamat_tetap; ?>
                  </textarea>
                </td>
            </tr>
            <tr valign="top">
                <td class="tigapuluh">Alamat Sementara (<font>Temporary Address</font>) </td>
                <td class="tujuhpuluh"> 
                  <textarea  style="width:90%; height:150px; padding:5px; 
                    margin-left: 15px; font-size:14px; resize:none;" 
                    id="alamatsementara" placeholder="wajib diisi" 
                    name="alamatsementara">
                  <?php echo $alamat_sementara; ?>
                  </textarea>
                </td>
            </tr>
            <tr>
                <td class="tigapuluh">Agama (<font>Religion</font>) </td>
                <td class="tujuhpuluh"> 
                    :
                    <select name="agama" id="agama">
                        <?php
                        $sql="select nama_pilihan isi,nama_pilihan tampil from 
                          masterpilihan where kode_pilihan='Agama'";
                        IsiCombo($sql,$agama,'Pilih Agama');
                        ?>
                    </select>
                    <!-- : <input type="text" style="width:90%; padding:5px; margin: 5px; font-size:14px;" name="agama"> -->
                </td>
            </tr>
            <tr>
                <td class="tigapuluh">No. KTP (<font>Identification Card Number</font>) </td>
                <td class="tujuhpuluh"> 
                    : <input type="text" value="<?php echo $ktp;?>" style="width:90%; padding:5px; margin: 5px; font-size:14px;" placeholder="wajib diisi" id="ktp" name="ktp">
                </td>
            </tr>
            <tr>
                <td class="tigapuluh">Jenis SIM (<font>Type of Driving License</font>) </td>
                <td class="tujuhpuluh"> 
                
                    : <input type="text" value="<?php echo $jenis_sim;?>" style="width:15%; padding:5px; margin: 5px;  font-size:14px;" name="jenissim"> No. SIM (<font>Driving License number</fong>) <input type="text" value="<?php echo $no_sim;?>" style="width:35%; padding:5px; margin: 5px; font-size:14px;" name="nosim">
                
                </td>
            </tr>
            <tr>
                <td class="tigapuluh">Golongan Darah (<font>Blood Type</font>) </td>
                <td class="tujuhpuluh"> 
                    :
                    <select name="goldar" id="goldar">
                        <?php
                        $sql="select nama_pilihan isi,nama_pilihan tampil from 
                          masterpilihan where kode_pilihan='Gol_Dar'";
                        IsiCombo($sql,$goldar,'Pilih Golongan Darah');
                        ?>
                    </select>

                </td>
            </tr>
            <tr>
                <td class="tigapuluh">Status Pernikahan (<font>Marital Status</font>) </td>
                <td class="tujuhpuluh"> 
                   : <select name="marital" id="marital">
                        <?php
                        $sql="select ptkpcode isi,ptkpdesc tampil from hr_masterptkp";
                        IsiCombo($sql,$status_pernikahan,'Pilih Status Pernikahan');
                        ?>
                   </select>
                </td>
            </tr>
            <tr>
                <td class="tigapuluh">No. Handphone/ Telephone (<font>Phone Number</font>) </td>
                <td class="tujuhpuluh"> 
                    : <input type="text" value="<?php echo $no_hp;?>" style="width:90%; padding:5px; margin: 5px; font-size:14px;" placeholder="wajib diisi" id="nohp" name="nohp">
                </td>
            </tr>
            <tr>
                <td class="tigapuluh">Email</td>
                <td class="tujuhpuluh"> 
                    : <input type="text" value="<?php echo $email;?>" style="width:90%; padding:5px; margin: 5px; font-size:14px;" placeholder="wajib diisi" id="email" name="email">
                </td>
            </tr>
            <tr>
                <td class="tigapuluh">Ukuran Baju (<font>Shirt Size</font>) </td>
                <td class="tujuhpuluh"> 
                    : <input type="text" value="<?php echo $ukuran_baju;?>" style="width:90%; padding:5px; margin: 5px; font-size:14px;" placeholder="wajib diisi" id="ukuranbaju" name="ukuranbaju">
                </td>
            </tr>
    </table>
   </fieldset>
   <fieldset style="margin-left:5%; margin-right:5%; margin-top:20px;">
   <legend style="font-weight:bold;">Susunan Keluarga(<font>family structure</font>)</legend>
   <table>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><p style="font-weight:bold;">Hubungan</p>(<font>relationship</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><p style="font-weight:bold;">Nama</p>(<font>name</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><p style="font-weight:bold;">Jenis Kelamin</p>(<font>gender</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><p style="font-weight:bold;">Tempat Tanggal Lahir</p>(<font>place of birth</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"> <p style="font-weight:bold;">Pekerjaan</p>(<font>occupation</font>)</td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Ayah (<font>father</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;">
            <input type="text" value="<?php echo $skayah;?>" style="width:90%; padding:5px;" 
              name="namaayah"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_ayah">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_ayah,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttlayah" value="<?php echo $skttl_ayah; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaanayah" value="<?php echo $skpekerjaan_ayah; ?>"></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Ibu (<font>mother</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="width:90%; padding:5px;" 
              name="namaibu" value="<?php echo $skibu;?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_ibu">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_ibu,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttlibu" value="<?php echo $skttl_ibu; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaanibu" value="<?php echo $skpekerjaan_ibu; ?>"></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Saudara 1 (<font>Brother/Sister</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="width:90%; padding:5px;" 
              name="namas1" value="<?php echo $sksaudara1;?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_s1">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_s1,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttls1" value="<?php echo $skttl_s1; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaans1" value="<?php echo $skpekerjaan_s1; ?>"></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Saudara 2 (<font>Brother/Sister</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="width:90%; padding:5px;" 
              name="namas2" value="<?php echo $sksaudara2;?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_s2">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_s2,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttls2" value="<?php echo $skttl_s2; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaans2" value="<?php echo $skpekerjaan_s2; ?>"></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Saudara 3 (<font>Brother/Sister</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="width:90%; padding:5px;" name="namas3"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_s3">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_s3,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttls3" value="<?php echo $skttl_s3; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaans3" value="<?php echo $skpekerjaan_s3; ?>"></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Saudara 4 (<font>Brother/Sister</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="width:90%; padding:5px;" name="namas4"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_s4">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_s4,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttls4" value="<?php echo $skttl_s4; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaans4" value="<?php echo $skpekerjaan_s4; ?>"></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Suami/Istri (<font>Husband/Wife</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="width:90%; padding:5px;" name="namas_i"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_s_i">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_s_i,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttls_i" value="<?php echo $skttl_s_i; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaans_i" value="<?php echo $skpekerjaan_s_i; ?>"></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Anak Ke 1 (<font>Son / Daughter 1</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="width:90%; padding:5px;" name="namaank1"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_ank1">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_ank1,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttlank1" value="<?php echo $skttl_ank1; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaanank1" value="<?php echo $skpekerjaan_ank1; ?>"></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Anak Ke 2 (<font>Son / Daughter 2</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="width:90%; padding:5px;" name="namaank2"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_ank2">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_ank2,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttlank2" value="<?php echo $skttl_ank2; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaanank2" value="<?php echo $skpekerjaan_ank2; ?>"></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Anak Ke 3 (<font>Son / Daughter 3</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="width:90%; padding:5px;" name="namaank3"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_ank3">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_ank3,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttlank3" value="<?php echo $skttl_ank3; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaanank3" value="<?php echo $skpekerjaan_ank3; ?>"></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;">Anak Ke 4 (<font>Son / Daughter 4</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="width:90%; padding:5px;" name="namaank4"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;">
                <select name="jk_ank4">
                  <?php 
                  $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Sex'";
                  IsiCombo($sql,$skjk_ank4,'Pilih Jenis Kelamin');
                  ?>
                </select>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:90%; padding:5px;" 
              name="ttlank4" value="<?php echo $skttl_ank4; ?>"></td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:35%;"><input type="text" style="width:90%; padding:5px;" 
              name="kerjaanank4" value="<?php echo $skpekerjaan_ank4; ?>"></td>
        </tr>
   </table>
   </fieldset>
   <fieldset style="margin-left:5%; margin-right:5%; margin-top:20px;">
    <legend style="font-weight:bold;">Pendidikan dan Skill (<font>education and skill</font>)</legend>
        <table>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><p style="font-weight:bold;">Nama Sekolah</p>(<font>name of school</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><p style="font-weight:bold;">Kota</p>(<font>City</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><p style="font-weight:bold;">Negara</p>(<font>Qountry</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><p style="font-weight:bold;">Jurusan</p>(<font>Major</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><p style="font-weight:bold;">Tahun Masuk</p>(<font>Starting Year</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><p style="font-weight:bold;">Tahun Lulus</p>(<font>Graduate Year</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><p style="font-weight:bold;">Keterangan</p>(<font>Remarks</font>)<p style="font-weight:bold;">Lulus/Tidak/Belum</p><font>Pass/Not/Not yet</font></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="sekolah1" value="<?php if($jm_edu>=1) {echo $edunama_sekolah[0];}?>">
                  <input type="hidden" name="txtid_edu1" value="<?php if($jm_edu>=1) {echo $id_edu[0];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="kota1" value="<?php if($jm_edu>=1) {echo $edukota[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="negara1" value="<?php if($jm_edu>=1) {echo $edunegara[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="jurusan1" value="<?php if($jm_edu>=1) {echo $edujurusan[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnmasuk1" value="<?php if($jm_edu>=1) {echo $edutahun_masuk[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnlulus1" value="<?php if($jm_edu>=1) {echo $edutahun_lulus[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="keterangan1" value="<?php if($jm_edu>=1) {echo $eduketerangan[0];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="sekolah2" value="<?php if($jm_edu>=2) {echo $edunama_sekolah[1];}?>">
                  <input type="hidden" name="txtid_edu2" value="<?php if($jm_edu>=2) {echo $id_edu[1];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="kota2" value="<?php if($jm_edu>=2) {echo $edukota[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="negara2" value="<?php if($jm_edu>=2) {echo $edunegara[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="jurusan2" value="<?php if($jm_edu>=2) {echo $edujurusan[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnmasuk2" value="<?php if($jm_edu>=2) {echo $edutahun_masuk[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnlulus2" value="<?php if($jm_edu>=2) {echo $edutahun_lulus[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="keterangan2" value="<?php if($jm_edu>=2) {echo $eduketerangan[1];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="sekolah3" value="<?php if($jm_edu>=3) {echo $edunama_sekolah[2];}?>">
                  <input type="hidden" name="txtid_edu3" value="<?php if($jm_edu>=3) {echo $id_edu[2];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="kota3" value="<?php if($jm_edu>=3) {echo $edukota[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="negara3" value="<?php if($jm_edu>=3) {echo $edunegara[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="jurusan3" value="<?php if($jm_edu>=3) {echo $edujurusan[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnmasuk3" value="<?php if($jm_edu>=3) {echo $edutahun_masuk[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnlulus3" value="<?php if($jm_edu>=3) {echo $edutahun_lulus[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="keterangan3" value="<?php if($jm_edu>=3) {echo $eduketerangan[2];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="sekolah4" value="<?php if($jm_edu>=4) {echo $edunama_sekolah[3];}?>">
                  <input type="hidden" name="txtid_edu4" value="<?php if($jm_edu>=4) {echo $id_edu[3];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="kota4" value="<?php if($jm_edu>=4) {echo $edukota[3];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="negara4" value="<?php if($jm_edu>=4) {echo $edunegara[3];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="jurusan4" value="<?php if($jm_edu>=4) {echo $edujurusan[3];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnmasuk4" value="<?php if($jm_edu>=4) {echo $edutahun_masuk[3];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnlulus4" value="<?php if($jm_edu>=4) {echo $edutahun_lulus[3];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="keterangan4" value="<?php if($jm_edu>=4) {echo $eduketerangan[3];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="sekolah5" value="<?php if($jm_edu>=5) {echo $edunama_sekolah[4];}?>">
                  <input type="hidden" name="txtid_edu5" value="<?php if($jm_edu>=5) {echo $id_edu[4];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="kota5" value="<?php if($jm_edu>=5) {echo $edukota[4];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="negara5" value="<?php if($jm_edu>=5) {echo $edunegara[4];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="jurusan5" value="<?php if($jm_edu>=5) {echo $edujurusan[4];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnmasuk5" value="<?php if($jm_edu>=5) {echo $edutahun_masuk[4];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnlulus5" value="<?php if($jm_edu>=5) {echo $edutahun_lulus[4];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="keterangan5" value="<?php if($jm_edu>=5) {echo $eduketerangan[4];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="sekolah6" value="<?php if($jm_edu>=6) {echo $edunama_sekolah[5];}?>">
                  <input type="hidden" name="txtid_edu6" value="<?php if($jm_edu>=6) {echo $id_edu[5];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="kota6" value="<?php if($jm_edu>=6) {echo $edukota[5];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="negara6" value="<?php if($jm_edu>=6) {echo $edunegara[5];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="jurusan6" value="<?php if($jm_edu>=6) {echo $edujurusan[5];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnmasuk6" value="<?php if($jm_edu>=6) {echo $edutahun_masuk[5];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnlulus6" value="<?php if($jm_edu>=6) {echo $edutahun_lulus[5];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="keterangan6" value="<?php if($jm_edu>=6) {echo $eduketerangan[5];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="sekolah7" value="<?php if($jm_edu>=7) {echo $edunama_sekolah[6];}?>">
                  <input type="hidden" name="txtid_edu7" value="<?php if($jm_edu>=7) {echo $id_edu[6];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="kota7" value="<?php if($jm_edu>=7) {echo $edukota[6];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="negara7" value="<?php if($jm_edu>=7) {echo $edunegara[6];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="jurusan7" value="<?php if($jm_edu>=7) {echo $edujurusan[6];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnmasuk7" value="<?php if($jm_edu>=7) {echo $edutahun_masuk[6];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnlulus7" value="<?php if($jm_edu>=7) {echo $edutahun_lulus[6];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="keterangan7" value="<?php if($jm_edu>=7) {echo $eduketerangan[6];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="sekolah8" value="<?php if($jm_edu>=8) {echo $edunama_sekolah[7];}?>">
                  <input type="hidden" name="txtid_edu8" value="<?php if($jm_edu>=8) {echo $id_edu[7];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="kota8" value="<?php if($jm_edu>=8) {echo $edukota[7];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="negara8" value="<?php if($jm_edu>=8) {echo $edunegara[7];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="jurusan8" value="<?php if($jm_edu>=8) {echo $edujurusan[7];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnmasuk8" value="<?php if($jm_edu>=8) {echo $edutahun_masuk[7];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:10%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="thnlulus8" value="<?php if($jm_edu>=8) {echo $edutahun_lulus[7];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="keterangan8" value="<?php if($jm_edu>=8) {echo $eduketerangan[7];}?>"></td>
            </tr>        
        </table>
        <table style="margin-top:20px;">
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><p style="font-weight:bold;">Nama Pelatihan</p>(<font>name of training/course</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><p style="font-weight:bold;">Tahun</p>(<font>year</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Penyelenggara</p>(<font>organizer</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Keterangan Sertifikat</p>(<font>name of training/course</font>)<p style="font-weight:bold;">Bersertifikat/Tidak</p>(<font>certified/not certified</font>)</td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namapelatihan1" value="<?php if($jm_pel>=1) {echo $pelnama_sekolah[0];}?>">
                  <input type="hidden" name="txtid_pel1" value="<?php if($jm_pel>=1) {echo $id_pel[0];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="tahun1" value="<?php if($jm_pel>=1) {echo $pelkota[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="penylenggara1" value="<?php if($jm_pel>=1) {echo $pelnegara[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="sertifikat1" value="<?php if($jm_pel>=1) {echo $peljurusan[0];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namapelatihan2" value="<?php if($jm_pel>=2) {echo $pelnama_sekolah[1];}?>">
                  <input type="hidden" name="txtid_pel2" value="<?php if($jm_pel>=2) {echo $id_pel[1];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="tahun2" value="<?php if($jm_pel>=2) {echo $pelkota[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="penylenggara2" value="<?php if($jm_pel>=2) {echo $pelnegara[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="sertifikat2" value="<?php if($jm_pel>=2) {echo $peljurusan[1];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namapelatihan3" value="<?php if($jm_pel>=3) {echo $pelnama_sekolah[2];}?>">
                  <input type="hidden" name="txtid_pel3" value="<?php if($jm_pel>=3) {echo $id_pel[2];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="tahun3" value="<?php if($jm_pel>=3) {echo $pelkota[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="penylenggara3" value="<?php if($jm_pel>=3) {echo $pelnegara[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="sertifikat3" value="<?php if($jm_pel>=3) {echo $peljurusan[2];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namapelatihan4" value="<?php if($jm_pel>=4) {echo $pelnama_sekolah[3];}?>">
                  <input type="hidden" name="txtid_pel4" value="<?php if($jm_pel>=4) {echo $id_pel[3];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="tahun4" value="<?php if($jm_pel>=4) {echo $pelkota[3];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="penylenggara4" value="<?php if($jm_pel>=4) {echo $pelnegara[3];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="sertifikat4" value="<?php if($jm_pel>=4) {echo $peljurusan[3];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namapelatihan5" value="<?php if($jm_pel>=5) {echo $pelnama_sekolah[4];}?>">
                  <input type="hidden" name="txtid_pel5" value="<?php if($jm_pel>=5) {echo $id_pel[4];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="tahun5" value="<?php if($jm_pel>=5) {echo $pelkota[4];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="penylenggara5" value="<?php if($jm_pel>=5) {echo $pelnegara[4];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="sertifikat5" value="<?php if($jm_pel>=5) {echo $peljurusan[4];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namapelatihan6" value="<?php if($jm_pel>=6) {echo $pelnama_sekolah[5];}?>">
                  <input type="hidden" name="txtid_pel6" value="<?php if($jm_pel>=6) {echo $id_pel[5];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="tahun6" value="<?php if($jm_pel>=6) {echo $pelkota[5];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="penylenggara6" value="<?php if($jm_pel>=6) {echo $pelnegara[5];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="sertifikat6" value="<?php if($jm_pel>=6) {echo $peljurusan[5];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namapelatihan7" value="<?php if($jm_pel>=7) {echo $pelnama_sekolah[6];}?>">
                  <input type="hidden" name="txtid_pel7" value="<?php if($jm_pel>=7) {echo $id_pel[6];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="tahun7" value="<?php if($jm_pel>=7) {echo $pelkota[6];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="penylenggara7" value="<?php if($jm_pel>=7) {echo $pelnegara[6];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="sertifikat7" value="<?php if($jm_pel>=7) {echo $peljurusan[6];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:30%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namapelatihan8" >
                  <input type="hidden" name="txtid_pel8" value="<?php if($jm_pel>=8) {echo $id_pel[7];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="tahun8" value="<?php if($jm_pel>=8) {echo $pelkota[7];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="penylenggara8" value="<?php if($jm_pel>=8) {echo $pelnegara[7];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="sertifikat8" value="<?php if($jm_pel>=8) {echo $peljurusan[7];}?>"></td>
            </tr>
        </table>
        <p style="margin-top:20px; margin-left:5%; font-weight:bold">Penguasaan Bahasa (<font>language skill</font>)</p>
        <table style="width:50%;">
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Jenis Bahasa</p>(<font>language type</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Membaca</p>(<font>reading</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Menulis</p>(<font>writing</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Berbicara</p>(<font>speaking</font>)</td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="jenisbahasa1" value="<?php if($jm_bhs>=1) {echo $jenis_bahasa[0];}?>">
                  <input type="hidden" name="txtid_bhs1" value="<?php if($jm_bhs>=1) {echo $id_pngbahasa[0];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="membaca1" value="<?php if($jm_bhs>=1) {echo $membaca[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="menulis1" value="<?php if($jm_bhs>=1) {echo $menulis[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="berbicara1" value="<?php if($jm_bhs>=1) {echo $berbicara[0];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="jenisbahasa2" value="<?php if($jm_bhs>=2) {echo $jenis_bahasa[1];}?>">
                  <input type="hidden" name="txtid_bhs2" value="<?php if($jm_bhs>=2) {echo $id_pngbahasa[1];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="membaca2" value="<?php if($jm_bhs>=2) {echo $membaca[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="menulis2" value="<?php if($jm_bhs>=2) {echo $menulis[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="berbicara2" value="<?php if($jm_bhs>=2) {echo $berbicara[1];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="jenisbahasa3" value="<?php if($jm_bhs>=3) {echo $jenis_bahasa[2];}?>">
                  <input type="hidden" name="txtid_bhs3" value="<?php if($jm_bhs>=3) {echo $id_pngbahasa[2];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="membaca3" value="<?php if($jm_bhs>=3) {echo $membaca[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="menulis3" value="<?php if($jm_bhs>=3) {echo $menulis[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="berbicara3" value="<?php if($jm_bhs>=3) {echo $berbicara[2];}?>"></td>
            </tr>
        </table>
   </fieldset>
   <fieldset style="margin-left:5%; margin-right:5%; margin-top:20px;">
        <legend style="font-weight:bold;">Pengalaman Berorganisasi(<font>Experiences in Organization</font>)</legend>
        <table>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Nama Organisai</p>(<font>name of organization</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Masa kerja</p>(<font>length of service</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Jabatan</p>(<font>position</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Keterangan</p>(<font>remarks</font>)</td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="namaorg1" value="<?php if($jm_org>=1) {echo $nama_org[0];}?>" style="font-size:14px; width:90%; padding:5px;">
                  <input type="hidden" name="txtid_org1" value="<?php if($jm_org>=1) {echo $id_org[0];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="masakerja1" value="<?php if($jm_org>=1) {echo $masa_kerja[0];}?>" style="font-size:14px; width:90%; padding:5px;"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="jabatanx1" value="<?php if($jm_org>=1) {echo $jabatan[0];}?>" style="font-size:14px; width:90%; padding:5px;"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="keteranganx1" value="<?php if($jm_org>=1) {echo $keterangan[0];}?>" style="font-size:14px; width:90%; padding:5px;"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="namaorg2" value="<?php if($jm_org>=2) {echo $nama_org[1];}?>" style="font-size:14px; width:90%; padding:5px;">
                  <input type="hidden" name="txtid_org2" value="<?php if($jm_org>=2) {echo $id_org[1];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="masakerja2" value="<?php if($jm_org>=2) {echo $masa_kerja[1];}?>"style="font-size:14px; width:90%; padding:5px;"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="jabatanx2" value="<?php if($jm_org>=2) {echo $jabatan[1];}?>" style="font-size:14px; width:90%; padding:5px;"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="keteranganx2" value="<?php if($jm_org>=2) {echo $keterangan[1];}?>" style="font-size:14px; width:90%; padding:5px;"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="namaorg3" value="<?php if($jm_org>=3) {echo $nama_org[2];}?>" style="font-size:14px; width:90%; padding:5px;">
                  <input type="hidden" name="txtid_org3" value="<?php if($jm_org>=3) {echo $id_org[2];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="masakerja3" value="<?php if($jm_org>=3) {echo $masa_kerja[2];}?>" style="font-size:14px; width:90%; padding:5px;"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="jabatanx3" value="<?php if($jm_org>=3) {echo $jabatan[2];}?>" style="font-size:14px; width:90%; padding:5px;"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" 
                  name="keteranganx3" value="<?php if($jm_org>=3) {echo $keterangan[2];}?>" style="font-size:14px; width:90%; padding:5px;"></td>
            </tr>
        </table>
   </fieldset>
   <fieldset style="margin-left:5%; margin-right:5%; margin-top:20px;">
        <legend style="font-weight:bold;">Riwayat Pekerjaan(<font>Job Experiences</font>)</legend>
        <table>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><p style="font-weight:bold;">Nama Perusahaan</p>(<font>company name</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><p style="font-weight:bold;">Jabatan</p>(<font>title</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><p style="font-weight:bold;">Posisi/bagian</p>(<font>position</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:9%;"><p style="font-weight:bold;">Awal Bekerja</p>(<font>starting work</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:9%;"><p style="font-weight:bold;">Akhir Bekerja</p>(<font>finished work</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:12%;"><p style="font-weight:bold;">Gaji</p>(<font>salary</font>)</td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><p style="font-weight:bold;">Alasan Berhenti</p>(<font>reason of resignation</font>)</td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="np1" value="<?php if($jm_rkerja>=1) {echo $nama_perusahaan[0];}?>">
                  <input type="hidden" name="txtid_rkerja1" value="<?php if($jm_rkerja>=1) {echo $id_rkerja[0];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="font-size:14px; width:85%; padding:5px;" 
                  name="jabatan1" value="<?php if($jm_rkerja>=1) {echo $jabatan[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="posisi1" value="<?php if($jm_rkerja>=1) {echo $posisi[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:9%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="awalk1" value="<?php if($jm_rkerja>=1) {echo $awal_kerja[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:9%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="akhirk1" value="<?php if($jm_rkerja>=1) {echo $akhir_kerja[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:12%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="gaji1" value="<?php if($jm_rkerja>=1) {echo $gaji[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="berhenti1" value="<?php if($jm_rkerja>=1) {echo $alasan_berhenti[0];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="np2" value="<?php if($jm_rkerja>=2) {echo $nama_perusahaan[1];}?>">
                  <input type="hidden" name="txtid_rkerja2" value="<?php if($jm_rkerja>=2) {echo $id_rkerja[1];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="jabatan2" value="<?php if($jm_rkerja>=2) {echo $jabatan[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="posisi2" value="<?php if($jm_rkerja>=2) {echo $posisi[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:9%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="awalk2" value="<?php if($jm_rkerja>=2) {echo $awal_kerja[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:9%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="akhirk2" value="<?php if($jm_rkerja>=2) {echo $akhir_kerja[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:12%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="gaji2" value="<?php if($jm_rkerja>=2) {echo $gaji[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="berhenti2" value="<?php if($jm_rkerja>=2) {echo $alasan_berhenti[1];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="np3" value="<?php if($jm_rkerja>=3) {echo $nama_perusahaan[2];}?>">
                  <input type="hidden" name="txtid_rkerja3" value="<?php if($jm_rkerja>=3) {echo $id_rkerja[2];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="jabatan3" value="<?php if($jm_rkerja>=3) {echo $jabatan[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="posisi3" value="<?php if($jm_rkerja>=3) {echo $posisi[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:9%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="awalk3" value="<?php if($jm_rkerja>=3) {echo $awal_kerja[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:9%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="akhirk3" value="<?php if($jm_rkerja>=3) {echo $akhir_kerja[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:12%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="gaji3" value="<?php if($jm_rkerja>=3) {echo $gaji[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:20%;"><input type="text" style="font-size:14px; width:85%; padding:5px;"
                  name="berhenti3" value="<?php if($jm_rkerja>=3) {echo $alasan_berhenti[2];}?>"></td>
            </tr>
        </table>
   </fieldset>
   <fieldset style="margin-left:5%; margin-right:5%; margin-top:20px;">
        <legend style="font-weight:bold;">Lainnya(<font>other</font>)</legend>
        <p style="margin-left:5%; font-weight:bold">Nama/ Keluarga yang bisa dihubungi saat kondisi darurat (<font>name / family can be contacted in emergency situation</font>)</p>
        <table>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Nama(<font>name</font>)</p></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Alamat<font>address</font>)</p></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">No. Telp(<font>phone number</font>)</p></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><p style="font-weight:bold;">Hubungan(<font>relationship</font>)</p></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namak1" value="<?php if($jm_lain>=1) {echo $lainnama[0];}?>">
                  <input type="hidden" name="txtid_lain1" value="<?php if($jm_lain>=1) {echo $id_lainnya[0];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="alamatk1" value="<?php if($jm_lain>=1) {echo $lainalamat[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="notelpk1" value="<?php if($jm_lain>=1) {echo $lainno_telp[0];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="hubungank1" value="<?php if($jm_lain>=1) {echo $lainhubungan[0];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namak2" value="<?php if($jm_lain>=2) {echo $lainnama[1];}?>">
                  <input type="hidden" name="txtid_lain2" value="<?php if($jm_lain>=2) {echo $id_lainnya[1];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="alamatk2" value="<?php if($jm_lain>=2) {echo $lainalamat[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="notelpk2" value="<?php if($jm_lain>=2) {echo $lainno_telp[1];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="hubungank2" value="<?php if($jm_lain>=2) {echo $lainhubungan[1];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namak3" value="<?php if($jm_lain>=3) {echo $lainnama[2];}?>">
                  <input type="hidden" name="txtid_lain3" value="<?php if($jm_lain>=3) {echo $id_lainnya[2];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="alamatk3" value="<?php if($jm_lain>=3) {echo $lainalamat[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="notelpk3" value="<?php if($jm_lain>=3) {echo $lainno_telp[2];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="hubungank3" value="<?php if($jm_lain>=3) {echo $lainhubungan[2];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namak4" value="<?php if($jm_lain>=4) {echo $lainnama[3];}?>">
                  <input type="hidden" name="txtid_lain4" value="<?php if($jm_lain>=4) {echo $id_lainnya[3];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="alamatk4" value="<?php if($jm_lain>=4) {echo $lainalamat[3];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="notelpk4" value="<?php if($jm_lain>=4) {echo $lainno_telp[3];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="hubungank4" value="<?php if($jm_lain>=4) {echo $lainhubungan[3];}?>"></td>
            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="namak5" value="<?php if($jm_lain>=5) {echo $lainnama[4];}?>">
                  <input type="hidden" name="txtid_lain5" value="<?php if($jm_lain>=5) {echo $id_lainnya[4];}?>">
                </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="alamatk5" value="<?php if($jm_lain>=5) {echo $lainalamat[4];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="notelpk5" value="<?php if($jm_lain>=5) {echo $lainno_telp[4];}?>"></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:25%;"><input type="text" style="font-size:14px; width:90%; padding:5px;" 
                  name="hubungank5" value="<?php if($jm_lain>=5) {echo $lainhubungan[4];}?>"></td>
            </tr>
        </table>
   </fieldset>
   <input type="checkbox" id="checkcheck" style="margin-left:5%; margin-top:15px;">Dengan ini menyatakan bahwa data yg diatas adalah data yg sesuai<p style="margin-left:5%; line-height: 0.01em;"> (<font>I Hereby statement above data are true</font>)</p>
   <input type="submit" onclick="return isReadytoApply()" value="kirim" style="width:90%; margin-left:5%; margin-right:5%; background-color:#3588c4;
    font-size:18px; font-weight:bold; color:#ffffff; margin-top:10px; padding:8px;
   ">
   </form> 
</body>
<script type="text/javascript">
  function isReadytoApply(){
    var iddok = document.getElementById("id_dok").value;
    var posisi = document.getElementById("posisi").value;
    var referensi = document.getElementById("referensi").value;
    var fullname = document.getElementById("fullname").value;
    var nickname = document.getElementById("nickname").value;
    var info = document.getElementById("info").value;
    var isiinfo = document.getElementById("isiinfo").value;
    var gender = document.getElementById("gender").value;
    var ttl = document.getElementById("ttl").value;
    var warganegara = document.getElementById("warganegara").value;
    var alamattetap = document.getElementById("alamattetap").value;
    var alamatsementara = document.getElementById("alamatsementara").value;
    var agama = document.getElementById("agama").value;
    var ktp = document.getElementById("ktp").value;
    var goldar = document.getElementById("goldar").value;
    var marital = document.getElementById("marital").value;
    var nohp = document.getElementById("nohp").value;
    var email = document.getElementById("email").value;
    var ukuranbaju = document.getElementById("ukuranbaju").value;
    var checkcheck = document.getElementById("checkcheck");

    if(info == "lainnya"){
        info.value = "lainnya - "+isiinfo;
    }

    if (iddok == "")
    { alert("ID Dokumen Kosong"); 
      document.getElementById("id_dok").focus(); valid = false;
    }
    else if (posisi == "")
    { alert("Posisi Kosong"); 
      document.getElementById("posisi").focus(); valid = false; 
    }
    else if (fullname == "")
    { alert("Nama Lengkap Kosong"); 
      document.getElementById("fullname").focus(); valid = false;
    }
    else if (nickname == "")
    { alert("Nama Singkat Kosong"); 
      document.getElementById("nickname").focus(); valid = false;
    }
    else if (ttl == "")
    { alert("Tpt Tgl Lahir Kosong"); 
      document.getElementById("ttl").focus(); valid = false;
    }
    else if (warganegara == "")
    { alert("Warga Negara Kosong"); 
      document.getElementById("warganegara").focus(); valid = false;
    }
    else if (alamattetap == "")
    { alert("Alamat Tetap Kosong"); 
      document.getElementById("alamattetap").focus(); valid = false;  
    }
    else if (alamatsementara == "")
    { alert("Alamat Sementara Kosong"); 
      document.getElementById("alamatsementara").focus(); valid = false;
    }
    else if (ktp == "")
    { alert("No. KTP Kosong"); 
      document.getElementById("ktp").focus(); valid = false;
    }
    else if (nohp == "")
    { alert("No. HP Kosong"); 
      document.getElementById("nohp").focus(); valid = false;
    }
    else if (email == "")
    { alert("Email Kosong"); 
      document.getElementById("email").focus(); valid = false;
    }
    else if (ukuranbaju == "")
    { alert("Ukuran Baju Kosong"); 
      document.getElementById("ukuranbaju").focus(); valid = false;
    }
    else if (referensi == "")
    { alert("Referensi Kosong"); 
      document.getElementById("referensi").focus(); valid = false;
    }
    else if(!checkcheck.checked)
    { alert("check the checkbox if you fill the form with correct answer"); 
      document.getElementById("checkcheck").focus(); valid = false;
    }
    else if(gender == 0)
    { alert("choose your gender"); 
      document.getElementById("gender").focus(); valid = false;
    }
    else if(agama == "")
    { alert("please choose your religion"); 
      document.getElementById("agama").focus(); valid = false;
    }
    else if(goldar == "")
    { alert("choose your blood type");
      document.getElementById("goldar").focus(); valid = false;
    }
    else if(marital == "")
    { alert("choose your marital status");
      document.getElementById("marital").focus(); valid = false;
    }
    else
    { valid = true;
      document.getElementById("formapply").action = "apply_kon.php?id=<?php echo $id_dp;?>";
    }
    return valid;
    exit;
}   
</script>>
</html>
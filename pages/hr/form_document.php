<?php
//include 'koneksi.php';
include '../../include/conn.php';
include '../forms/fungsi.php';
if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}
if ($id=="")
{ $nm_lengkap="";
  $dept="";
  $fddepartment="";
  $fdnik="";
  $fdbagian="";
  $fdtanggalmasuk="";
  $fdfc_ktp_sim="";
  $fdpas_foto="";
  $fdsurat_lamaran="";
  $fddaftar_riwayat_hidup="";
  $fdijazah_terakhir="";
  $fdfc_kk="";
  $fdskck="";
  $fdsurat_dokter="";
  $fdpengalaman_kerja="";
  $fdizin_suami_or_ortu="";
  $fdform_permintaan_tk="";
  $fdperjanjian_kerja="";
  $fdsurat_perbedaan_dokument="";
  $fdform_aplikasi_karyawan="";
  $fdform_ringkasan_psikotest_skill="";
  $fdform_pernyataan_orientasi="";
}
else
{ $sql="select a.nama_lengkap,s.* 
    from data_pribadi a inner join form_dokumen s on 
    a.id_dp=s.id_dp 
    where s.id_form_dk='$id'";
  $rs=mysql_fetch_array(mysql_query($sql));
  $id_dp=$rs['id_dp'];
  $nm_lengkap=$rs['nama_lengkap'];
  $dept=$rs['department'];
  $fddepartment=$rs['department'];
  $fdnik=$rs['nik'];
  $fdbagian=$rs['bagian'];
  $fdtanggalmasuk=$rs['tanggalmasuk'];
  $fdfc_ktp_sim=$rs['fc_ktp_sim'];
  $fdpas_foto=$rs['pas_foto'];
  $fdsurat_lamaran=$rs['surat_lamaran'];
  $fddaftar_riwayat_hidup=$rs['daftar_riwayat_hidup'];
  $fdijazah_terakhir=$rs['ijazah_terakhir'];
  $fdfc_kk=$rs['fc_kk'];
  $fdskck=$rs['skck'];
  $fdsurat_dokter=$rs['surat_dokter'];
  $fdpengalaman_kerja=$rs['pengalaman_kerja'];
  $fdizin_suami_or_ortu=$rs['izin_suami_or_ortu'];
  $fdform_permintaan_tk=$rs['form_permintaan_tk'];
  $fdperjanjian_kerja=$rs['perjanjian_kerja'];
  $fdsurat_perbedaan_dokument=$rs['surat_perbedaan_dokument'];
  $fdform_aplikasi_karyawan=$rs['form_aplikasi_karyawan'];
  $fdform_ringkasan_psikotest_skill=$rs['form_ringkasan_psikotest_skill'];
  $fdform_pernyataan_orientasi=$rs['form_pernyataan_orientasi'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Document</title>
    <style >
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
        select{
            margin:10px; width:90%; height:40px; font-size:14px;"
        }
    </style>
</head>
<body>
<form method="POST" id="formdocument">
<table>
        <tr>
            <td style="
            border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:20%"
            rowspan="4"
            ><img src="../../include/img-01.png" style="width:100px; height:100px"></td>
            <td style="
            border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:50%"
            rowspan="4"
            > 
                <p style="font-weight:bold;
                        font-size:40px;
                ">Formulir Checklist Kelengkapan Dokumen</p>
            </td>
            <td style="text-align: left;
                    border-top: 2px solid #000000;
                    padding: 8px; width:15%;">
                Kode Dok</td>
            <td style="text-align: left;
                    border-top: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px; width:15%;">: <input type="text" readonly value='F.16.P.HR.P-01.F-02' id="id_dok" name="id_dok" placeholder="wajib diisi" style="width:85%; padding:3px;"></td>
        </tr>
            <td style="text-align: left;
                    padding: 8px; width:15%;"> Revisi </td>
            <td style="text-align: left;
                    border-right: 2px solid #000000;
                    padding: 8px; width:15%;">: <input type="text" readonly value='-' name="revisi" style="width:85%; padding:3px;">  </td>
        </tr>
        <tr>
            <td style="text-align: left;
                    padding: 8px; width:15%;">Tanggal Revisi </td>
            <td style="text-align: left;
                    border-right: 2px solid #000000;
                    padding: 8px; width:15%;">: <input type="text" readonly value='30 September 2016' name="revisi_date" style="width:85%; padding:3px;" value="<?php echo date('Y-m-d');?>"></td>
        </tr>
        <tr>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    padding: 8px; width:15%;">Tanggal Berlaku</td>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px; width:15%;">: <input type="date" name="berlaku_date" style="width:85%; padding:3px;"></td>
        <tr>
   </table>
   <table style="margin-top:20px;">
        <tr>
            <td style="text-align: left;
                    padding: 8px; width:20%;">Nama Karyawan</td>
            <td style="text-align: left;
                    padding: 8px; width:30%;">:
                    <select name="karyawan">
                    <?php
                    $sql = "SELECT id_dp isi,nama_lengkap tampil FROM data_pribadi order by id_dp desc";
                    IsiCombo($sql,$id_dp,'Pilih Nama');
                    ?>
                    </select>
            <td style="text-align: left;
                    padding: 8px; width:20%;">Tanggal Masuk</td>
            <td style="text-align: left;
                    padding: 8px; width:30%;">: <input type="date" value="<?php echo $fdtanggalmasuk;?>" id="tanggalmasuk" name="tanggalmasuk" style="width:90%; padding:3px;"></td>
        </tr>
        <tr>
            <td style="text-align: left;
                    padding: 8px; width:20%;">Departemen</td>
            <td style="text-align: left;
                    padding: 8px; width:30%;">:
                <select name="departemen">
                    <?php
                    $sql2 = 'SELECT nama_pilihan isi,nama_pilihan tampil FROM 
                      masterpilihan WHERE kode_pilihan="Dept"';
                    IsiCombo($sql2,$dept,"Pilih Dept");
                    ?>
                </select>
                    
            <td style="text-align: left;
                    padding: 8px; width:20%;"></td>
            <td style="text-align: left;
                    padding: 8px; width:30%;"></td>
        </tr>
        <tr>
            <td style="text-align: left;
                    padding: 8px; width:20%;">NIK</td>
            <td style="text-align: left;
                    padding: 8px; width:30%;">: <input type="text" value="<?php echo $fdnik;?>" id="nik" name="nik" placeholder="wajib diisi" style="width:90%; padding:3px;"></td>
            <td style="text-align: left;
                    padding: 8px; width:20%;"></td>
            <td style="text-align: left;
                    padding: 8px; width:30%;"></td>
        </tr>
        <tr>
            <td style="text-align: left;
                    padding: 8px; width:20%;">Bagian</td>
            <td style="text-align: left;
                    padding: 8px; width:30%;">: 
                <select name="bagian">
                    <?php
                    $sql3 = 'SELECT nama_pilihan isi,nama_pilihan tampil FROM 
                      masterpilihan WHERE kode_pilihan="Bagian"';
                    IsiCombo($sql3,$fdbagian,"Pilih Bagian");
                    ?>
                </select>
                    </td>
            <td style="text-align: left;
                    padding: 8px; width:20%;"></td>
            <td style="text-align: left;
                    padding: 8px; width:30%;"></td>
        </tr>
   </table>
   <table style="margin-top:20px;">
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">No.</th>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:65%">Nama dokumen</th>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%">Yes</th>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%">No</th>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%">N/A</th>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">1</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Fotocopy KTP dan atau SIM</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose1" 
            <?php if ($fdfc_ktp_sim=="yes") {echo "checked";} ?> id="choose1"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose1" 
            <?php if ($fdfc_ktp_sim=="no") {echo "checked";} ?>id="choose1"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose1" 
            <?php if ($fdfc_ktp_sim=="n/a") {echo "checked";} ?>id="choose1"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">2</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Pas Photo</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose2" <?php if ($fdpas_foto=="yes") {echo "checked";} ?> id="choose2"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose2" <?php if ($fdpas_foto=="no") {echo "checked";} ?> id="choose2"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose2" <?php if ($fdpas_foto=="n/a") {echo "checked";} ?> id="choose2"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">3</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Surat Lamaran</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose3" <?php if ($fdsurat_lamaran=="yes") {echo "checked";} ?> id="choose3"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose3" <?php if ($fdsurat_lamaran=="no") {echo "checked";} ?> id="choose3"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose3" <?php if ($fdsurat_lamaran=="n/a") {echo "checked";} ?> id="choose3"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">4</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Daftar Riwayat Hidup</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose4" <?php if ($fddaftar_riwayat_hidup=="yes") {echo "checked";} ?> id="choose4"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose4" <?php if ($fddaftar_riwayat_hidup=="no") {echo "checked";} ?> id="choose4"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose4" <?php if ($fddaftar_riwayat_hidup=="n/a") {echo "checked";} ?> id="choose4"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">5</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Fotocopy ijazah pendidikan terakhir</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose5" <?php if ($fdijazah_terakhir=="yes") {echo "checked";} ?> id="choose5"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose5" <?php if ($fdijazah_terakhir=="no") {echo "checked";} ?> id="choose5"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose5" <?php if ($fdijazah_terakhir=="n/a") {echo "checked";} ?> id="choose5"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">6</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Fotocopy kartu keluarga</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose6" <?php if($fdfc_kk=="yes") {echo "checked";}?> id="choose6"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose6" <?php if($fdfc_kk=="no") {echo "checked";}?> id="choose6"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose6" <?php if($fdfc_kk=="n/a") {echo "checked";}?> id="choose6"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">7</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Surak keterangan catatan kepolisian</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose7" <?php if($fdskck=="yes") {echo "checked";}?> id="choose7"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose7" <?php if($fdskck=="no") {echo "checked";}?> id="choose7"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose7" <?php if($fdskck=="n/a") {echo "checked";}?> id="choose7"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">8</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Surat Keterangan Dokter</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose8" <?php if($fdsurat_dokter=="yes") {echo "checked";}?> id="choose8"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose8" <?php if($fdsurat_dokter=="no") {echo "checked";}?> id="choose8"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose8" <?php if($fdsurat_dokter=="n/a") {echo "checked";}?> id="choose8"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">9</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Surat pengalaman kerja(jika pernah bekerja)</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose9" <?php if($fdpengalaman_kerja=="yes") {echo "checked";}?> id="choose9"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose9" <?php if($fdpengalaman_kerja=="no") {echo "checked";}?> id="choose9"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose9" <?php if($fdpengalaman_kerja=="n/a") {echo "checked";}?> id="choose9"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">10</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Surat ijin suami/ orang tua(untuk pekerja shift)</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose10" <?php if($fdizin_suami_or_ortu=="yes") {echo "checked";}?> id="choose10"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose10" <?php if($fdizin_suami_or_ortu=="no") {echo "checked";}?> id="choose10"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose10" <?php if($fdizin_suami_or_ortu=="n/a") {echo "checked";}?> id="choose10"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">11</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Formulir permintaan tenaga kerja</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose11" <?php if($fdform_permintaan_tk=="yes") {echo "checked";}?> id="choose11"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose11" <?php if($fdform_permintaan_tk=="no") {echo "checked";}?> id="choose11"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose11" <?php if($fdform_permintaan_tk=="n/a") {echo "checked";}?> id="choose11"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">12</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Surat perjanjian kerja</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose12" <?php if($fdperjanjian_kerja=="yes") {echo "checked";}?> id="choose12"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose12" <?php if($fdperjanjian_kerja=="no") {echo "checked";}?> id="choose12"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose12" <?php if($fdperjanjian_kerja=="n/a") {echo "checked";}?> id="choose12"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">13</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Surat Keterangan perbedaan/ ketidaksesuaian dokumen</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose13" <?php if($fdsurat_perbedaan_dokument=="yes") {echo "checked";}?> id="choose13"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose13" <?php if($fdsurat_perbedaan_dokument=="no") {echo "checked";}?> id="choose13"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose13" <?php if($fdsurat_perbedaan_dokument=="n/a") {echo "checked";}?> id="choose13"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">14</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Form aplikasi karyawan</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose14" <?php if($fdform_aplikasi_karyawan=="yes") {echo "checked";}?> id="choose14"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose14" <?php if($fdform_aplikasi_karyawan=="no") {echo "checked";}?> id="choose14"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose14" <?php if($fdform_aplikasi_karyawan=="n/a") {echo "checked";}?> id="choose14"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">15</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Form ringkasan psikotest dan test skill</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose15" <?php if($fdform_ringkasan_psikotest_skill=="yes") {echo "checked";}?> id="choose15"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose15" <?php if($fdform_ringkasan_psikotest_skill=="no") {echo "checked";}?> id="choose15"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose15" <?php if($fdform_ringkasan_psikotest_skill=="n/a") {echo "checked";}?> id="choose15"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">16</th>
            <td style="border: 2px solid #000000;
            text-align: left;
            padding: 8px; width:65%">Form pernyataan pemberian orientasi</td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="yes" 
            name="choose16" <?php if($fdform_pernyataan_orientasi=="yes") {echo "checked";}?> id="choose16"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="no" 
            name="choose16" <?php if($fdform_pernyataan_orientasi=="no") {echo "checked";}?> id="choose16"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" value="n/a" 
            name="choose16" <?php if($fdform_pernyataan_orientasi=="n/a") {echo "checked";}?> id="choose16"></td>
        </tr>
   </table>
   <input onclick="return isReadytoDocumented()" type="submit" value="kirim" style="width:90%; margin-left:5%; margin-right:5%; background-color:#3588c4;
    font-size:18px; font-weight:bold; color:#ffffff; margin-top:10px; padding:8px;">
</form>
</body>
<script type="text/javascript">
  function isReadytoDocumented()
  { var iddok = document.getElementById("id_dok").value;
    var tanggalmasuk = document.getElementById("tanggalmasuk").value;
    var nik = document.getElementById("nik").value;

    if (iddok == "")
    { alert("Kode Dokumen Kosong"); 
      document.getElementById("id_dok").focus(); valid = false;
    }
    else if (tanggalmasuk == "")
    { alert("Tanggal Masuk Kosong"); 
      document.getElementById("tanggalmasuk").focus(); valid = false;
    }
    else if (nik == "")
    { alert("NIK Kosong"); 
      document.getElementById("nik").focus(); valid = false;
    }
    else
    { valid = true;
      document.getElementById("formdocument").action = "document_kon.php?id=<?php echo $id;?>"; 
    }
    return valid;
    exit;
  }   
</script>>
</html>
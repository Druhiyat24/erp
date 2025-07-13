<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
 
if (isset($_GET['id'])) {$id_tk=$_GET['id'];} else {$id_tk="";}
if ($id_tk!="")
{ $sql="select * from form_tenaga_kerja where id_tk='$id_tk'";
  $rs=mysql_fetch_array(mysql_query($sql));
  $status_permintaan=$rs['status_permintaan'];
  $do_tglaju=$rs['do_tglaju'];
  $do_nama=$rs['do_nama'];
  $do_nik=$rs['do_nik'];
  $do_department=$rs['do_department'];
  $do_bagian=$rs['do_bagian'];
  $rk_department=$rs['rk_department'];
  $rk_bagian=$rs['rk_bagian'];
  $rk_tanggal=$rs['rk_tanggal'];
  $rk_jumlah=$rs['rk_jumlah'];
  $rencana_jabatan=$rs['rencana_jabatan'];
  $pendidikanakhir=$rs['pendidikanakhir'];
  $pengalamankerja=$rs['pengalamankerja'];
  $lamakerja_dlmthn=$rs['lamakerja_dlmthn'];
  $jurusan=$rs['jurusan'];
  $uraian_tugas=explode(",",$rs['uraian_tugas']);
  $jml_urai=count($uraian_tugas);  
  $gaji=$rs['gaji'];
  $fasilitas=$rs['fasilitas'];
  $waktu_kontrak=$rs['waktu_kontrak'];
  $keteranganlain=$rs['keteranganlain'];
  $setuju1=$rs['setuju1'];
  $setuju2=$rs['setuju2'];
  $setuju3=$rs['setuju3'];
  $ketahui=$rs['ketahui'];
}
else
{ $status_permintaan="";
  $do_tglaju="";
  $do_nama="";
  $do_nik="";
  $do_department="";
  $do_bagian="";
  $rk_department="";
  $rk_bagian="";
  $rk_tanggal="";
  $rk_jumlah="";
  $rencana_jabatan="";
  $pendidikanakhir="";
  $pengalamankerja="";
  $lamakerja_dlmthn="";
  $jurusan="";
  $uraian_tugas="";
  $gaji="";
  $fasilitas="";
  $waktu_kontrak="";
  $keteranganlain="";
  $setuju1="";
  $setuju2="";
  $setuju3="";
  $ketahui="";
  $jml_urai=0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Permintaan Tenaga Kerja</title>
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
    </style>
</head>
<body>
<form  method="post" id="formpermintaantk">
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
                ">Formulir Permintaan Tenaga Kerja</p>
            </td>
            <td style="text-align: left;
                    border-top: 2px solid #000000;
                    padding: 8px; width:15%;">
                Kode Dok</td>
            <td style="text-align: left;
                    border-top: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px; width:15%;">: <input type="text" value="F.16.HR.NAG.P-01.F-01.01" readonly name="id_dok" id="id_dok" style="width:85%; padding:3px;"></td>
        </tr>
            <td style="text-align: left;
                    padding: 8px; width:15%;"> Revisi </td>
            <td style="text-align: left;
                    border-right: 2px solid #000000;
                    padding: 8px; width:15%;">: <input type="text" name="revisi" value="01" readonly style="width:85%; padding:3px;">  </td>
        </tr>
        <tr>
            <td style="text-align: left;
                    padding: 8px; width:15%;">Tanggal Revisi </td>
            <td style="text-align: left;
                    border-right: 2px solid #000000;
                    padding: 8px; width:15%;">: <input type="text" readonly value="22 Dec 2017" name="revisi_date" style="width:85%; padding:3px;"></td>
        </tr>
        <tr>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    padding: 8px; width:15%;">Tanggal Berlaku</td>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px; width:15%;">: <input type="text" value="31 Jan 2018" readonly name="berlaku_date" style="width:85%; padding:3px;"></td>
        <tr>
   </table>
   <table style="margin-top:20px;">
        <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;">1</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" colspan="2">Status Permintaan</th>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">
                        : 
                    <input type="radio" name="inputpermintaan1" 
                      value="permintaan_baru" 
                      <?php if ($status_permintaan=="permintaan_baru") {echo "checked";} ?>
                      style="margin-left: 20px"> Permintaan Baru 
                      <input type="radio" value="pergantian" 
                      <?php if ($status_permintaan=="pergantian") {echo "checked";} ?>
                      name="inputpermintaan1" style="margin-left: 20px"> Penggantian
                    </td>
        </tr>
        <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;">2</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" colspan="2">Tanggal Pengajuan</th>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">
                        : <input type="date" name="tanggalaju" 
                        value='<?php echo $do_tglaju; ?>' style="width:70%;"></td>
        </tr>
        <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;" rowspan="5">3</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" colspan="6">Diajukan Oleh:</th>
        </tr>
        <tr>
            <td rowspan="4" style="width:5%; border: 2px solid #000000;
                    padding: 8px;">
            </td>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Nama</td>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">: 
                    <input type="text" name="namapengaju" id="namapengaju" 
                    value='<?php echo $do_nama;?>' style="width:85%; padding:8px"></td>
          </tr>
          <tr>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">NIK</td>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">: 
                    <input type="text"  name="nikpengaju" id="nikpengaju" 
                    value='<?php echo $do_nik;?>' style="width:85%; padding:8px"></td>
          </tr>
          <tr>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Department</td>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">: 
                    <input type="text" name="departmentpengaju" id="departmentpengaju" 
                    value='<?php echo $do_department;?>'style="width:85%; padding:8px"></td>
          </tr>
          <tr>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Bagian</td>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">: 
                    <input type="text" name="bagianpengaju" id="bagianpengaju" 
                    value='<?php echo $do_bagian;?>'style="width:85%; padding:8px"></td>
          </tr>
          <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;" rowspan="5">4</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" colspan="6">Rencana Kebutuhan:</th>
        </tr>
        <tr>
            <td rowspan="4" style="width:5%; border: 2px solid #000000;
                    padding: 8px;">
            </td>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Department</td>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">: 
                    <input type="text" name="rk_department" id="rk_department" 
                    value='<?php echo $rk_department;?>'style="width:85%; padding:8px"></td>
          </tr>
          <tr>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Bagian</td>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">: 
                    <input type="text" name="rk_bagian" id="rk_bagian" 
                    value='<?php echo $rk_bagian;?>' style="width:85%; padding:8px"></td>
          </tr>
          <tr>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Tanggal</td>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">: 
                    <input type="date" name="rk_tanggal" id="rk_tanggal" 
                    value='<?php echo $rk_tanggal;?>' style="width:85%; padding:8px"></td>
          </tr>
          <tr>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Jumlah</td>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">: 
                    <input type="text"  name="rk_jumlah" id="rk_jumlah" 
                    value='<?php echo $rk_jumlah;?>' style="width:85%; padding:8px"></td>
          </tr>
          <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;">5</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" colspan="2">Rencana Jabatan</th>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">
                        : 
                        <input type="radio" value="manager" name="inputpermintaan2" 
                          <?php if ($rencana_jabatan=="manager") {echo "checked";} ?>
                          style="margin-left: 20px"> Manager
                        <input type="radio" value="chief" name="inputpermintaan2" 
                          <?php if ($rencana_jabatan=="chief") {echo "checked";} ?>
                          style="margin-left: 20px"> Chief
                        <input type="radio" value="spv" name="inputpermintaan2" 
                          <?php if ($rencana_jabatan=="spv") {echo "checked";} ?>
                          style="margin-left: 20px"> SPV
                        <input type="radio" value="leader" name="inputpermintaan2" 
                          <?php if ($rencana_jabatan=="leader") {echo "checked";} ?>
                          style="margin-left: 20px"> Leader
                        <input type="radio" value="operator"name="inputpermintaan2" 
                          <?php if ($rencana_jabatan=="operator") {echo "checked";} ?>
                          style="margin-left: 20px"> Operator
            </td>
        </tr>
        <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;" rowspan="4">6</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" rowspan="4" colspan="2">Pendidikan Minimal dan Jurusan</th>
            <td style="text-align: left;
                    padding: 8px; width:25%;">
                    <input type="radio" value="sd" 
                    <?php if ($pendidikanakhir=="sd") {echo "checked";} ?>
                    name="sekolah"> Sekolah Dasar
            </td>
            <td style="text-align: left;
                    padding: 8px; width:19%;">
                    <input type="radio" value="d1" 
                    <?php if ($pendidikanakhir=="d1") {echo "checked";} ?>
                    name="sekolah"> Diploma 1
            </td>
            <td style="text-align: left;
                    padding: 8px; width:19%;">
                    <input type="radio" value="s1" 
                    <?php if ($pendidikanakhir=="s1") {echo "checked";} ?>
                    name="sekolah"> Strata 1
            </td>
            <td style="text-align: left;
                    border-right: 2px solid #000000;
                    padding: 8px; width:17%;">
                    <input type="radio" value="dll"  
                    <?php if ($pendidikanakhir=="dll") {echo "checked";} ?>
                    name="sekolah"> dll
            </td>
        </tr>
        <tr>
        <td style="text-align: left;
                    padding: 8px; width:25%;">
                    <input type="radio" value="smp" 
                    <?php if ($pendidikanakhir=="smp") {echo "checked";} ?>
                    name="sekolah"> Sekolah Lanjutan tingkat Pertama
            </td>
            <td style="text-align: left;
                    padding: 8px; width:19%;">
                    <input type="radio" value="d2" 
                    <?php if ($pendidikanakhir=="d2") {echo "checked";} ?>
                    name="sekolah"> Diploma 2
            </td>
            <td style="text-align: left;
                    padding: 8px; width:19%;">
                    <input type="radio" value="s2" 
                    <?php if ($pendidikanakhir=="s2") {echo "checked";} ?>
                    name="sekolah"> Strata 2
            </td>
            <td style="text-align: left;
                    border-right: 2px solid #000000;
                    padding: 8px; width:17%;"></td>
        </tr>
        <tr>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    padding: 8px; width:25%;">
                    <input type="radio" value="sma" 
                    <?php if ($pendidikanakhir=="sma") {echo "checked";} ?>
                    name="sekolah"> Sekolah Menengah Atas
            </td>
            <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    padding: 8px; width:19%;">
                    <input type="radio" value="d3" 
                    <?php if ($pendidikanakhir=="d3") {echo "checked";} ?>
                    name="sekolah"> Diploma 3
            </td>
            <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    padding: 8px; width:19%;">
                    <input type="radio" value="s3" 
                    <?php if ($pendidikanakhir=="s3") {echo "checked";} ?>
                    name="sekolah"> Strata 3
            </td>
            <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px; width:17%;"></td>
        </tr>
        <tr>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">
                    Jurusan : 
                    <input type="text" name="jurusan" id="jurusan" 
                    value='<?php echo $jurusan;?>' style="width:60%; padding:8px; margin-left:20px;">
                    </td>
        </tr>
        <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;">7</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" colspan="2">Pengalaman Kerja</th>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">
                        : <input type="radio" id="pengalamankerja" 
                        name="pengalamankerja" style="margin-left: 20px" 
                        <?php if ($pengalamankerja=="ya") {echo "checked";} ?>
                        value="ya">Ya, Waktu Pengalaman 
                        <input type="text" name="lamakerja" id="lamakerja" 
                        value='<?php echo $lamakerja_dlmthn;?>' style="width:30px; padding: 8px;"> Tahun. 
                        <input type="radio" name="pengalamankerja" 
                        style="margin-left: 20px" 
                        <?php if ($pengalamankerja=="tidak") {echo "checked";} ?>
                        value="tidak"> Tidak
                    </td>
        </tr>
        <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;" rowspan="5" valign="top">8</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" colspan="2" rowspan="5" valign="top">Uraian Tugas Secara Umum</th>
            <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px;" colspan="2" valign="top">
                        1. 
                        <textarea name="keterangan1" style="width:80%; height:150px; padding:5px; resize:none;">
                        <?php if($jml_urai>=1) {echo $uraian_tugas[0];}?>
                        </textarea>
                    </td>
            <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px;" colspan="2" valign="top">
                        6. 
                        <textarea name="keterangan6" style="width:80%; height:150px; padding:5px; resize:none;">
                        <?php if($jml_urai>=6) {echo $uraian_tugas[5];}?>
                        </textarea>
            </td>
        </tr>
        <tr>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; ;" colspan="2" valign="top">
                        2. 
                        <textarea name="keterangan2" style="width:80%; height:150px; padding:5px; resize:none;">
                        <?php if($jml_urai>=2) {echo $uraian_tugas[1];}?>
                        </textarea>
                    </td>
            <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; ;" colspan="2" valign="top">
                        7. 
                        <textarea name="keterangan7" style="width:80%; height:150px; padding:5px; resize:none;">
                        <?php if($jml_urai>=7) {echo $uraian_tugas[6];}?>
                        </textarea>
                   </td>
        </tr>
        <tr>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; " colspan="2" valign="top">
                        3. 
                        <textarea name="keterangan3" style="width:80%; height:150px; padding:5px; resize:none;">
                        <?php if($jml_urai>=3) {echo $uraian_tugas[2];}?>
                        </textarea>
                    </td>
            <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px;" colspan="2" valign="top">
                        8. 
                        <textarea name="keterangan8" style="width:80%; height:150px; padding:5px; resize:none;">
                        <?php if($jml_urai>=8) {echo $uraian_tugas[7];}?>
                        </textarea>
                    </td>
        </tr>
        <tr>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px;" colspan="2" valign="top">
                        4. 
                        <textarea name="keterangan4" style="width:80%; height:150px; padding:5px; resize:none;">
                        <?php if($jml_urai>=4) {echo $uraian_tugas[3];}?>
                        </textarea>
                    </td>
            <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px;" colspan="2" valign="top">
                        9. 
                        <textarea name="keterangan9" style="width:80%; height:150px; padding:5px; resize:none;">
                        <?php if($jml_urai>=9) {echo $uraian_tugas[8];}?>
                        </textarea>
                    </td>
        </tr>
        <tr>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px;" colspan="2" valign="top">
                        5. 
                        <textarea name="keterangan5" style="width:80%; height:150px; padding:5px; resize:none;">
                        <?php if($jml_urai>=5) {echo $uraian_tugas[4];}?>
                        </textarea>
                    </td>
            <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px;" colspan="2" valign="top">
                        10. 
                        <textarea name="keterangan10" style="width:80%; height:150px; padding:5px; resize:none;">
                        <?php if($jml_urai>=10) {echo $uraian_tugas[9];}?>
                        </textarea>
                    </td>
        </tr>
        <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;">9</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" colspan="2">Rencana Gaji dan Fasilitas</th>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">
                        Golongan Gaji/ Besaran Gaji : <input type="text" name="gaji" 
                          id="gaji" value="<?php echo $gaji;?>" style="margin-left: 10px; padding:8px; width:60%;"> <br/><br/>
                        Fasilitas : <input type="text" name="fasilitas" 
                          id="fasilitas" value="<?php echo $fasilitas;?>" style="margin-left: 10px; padding:8px; width:80%;">
                    </td>
        </tr>
        <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;">10</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" colspan="2">Jangka Waktu Kontrak</th>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">: <input type="text" name="kontrak"  
                      id="kontrak"  value="<?php echo $waktu_kontrak;?>" style="margin-left: 10px; padding:8px; width:90%;">
                    </td>
        </tr>
        <tr>
            <th style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:5%;">11</th>
            <th style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:25%;" colspan="2">Keterangan Lainnya(Jumlah, Keterampilan tambahan, dll)</th>
            <td style="text-align: left;
                    border: 2px solid #000000;
                    padding: 8px; width:70%;" colspan="4">: <input type="text" name="lainnya" 
                      id="lainnya" value="<?php echo $keteranganlain;?>" style="margin-left: 10px; padding:8px; width:90%;">
                    </td>
        </tr>
   </table>
   <table style="margin-top:20px">
        <tr>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Diajukan</td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Disetujui</td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Diketahui</td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Disetujui</td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Disetujui</td>
        </tr>
        <tr>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%; height:100px;"></td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%; height:100px;"></td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%; height:100px;"></td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%; height:100px;"></td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%; height:100px;"></td>
        </tr>
        <tr>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;"></td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">Chief/Dept.Head/Manager</td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">HR-GA</td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">General Mgr Product</td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%;">General Mgr Factory</td>
        </tr>
        <tr>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%; "></td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%; ">
                    <select name="chief" id="chief" style="width:90%; padding:8px">
                    <?php 
                    $sql="select username isi,fullname tampil from 
                      userpassword where approval='1' order by fullname";
                    IsiCombo($sql,$setuju1,'Pilih Approval');
                    ?>
                    </select>
        </td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%; ">
                    <select name="hr" id="hr" style="width:90%; padding:8px">
                    <?php 
                    $sql="select username isi,fullname tampil from 
                      userpassword where approval='1' order by fullname";
                    IsiCombo($sql,$setuju2,'Pilih Approval');
                    ?>
                    </select>
                    </td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%; ">
                    <select name="gmp" id="gmp" style="width:90%; padding:8px">
                    <?php 
                    $sql="select username isi,fullname tampil from 
                      userpassword where approval='1' order by fullname";
                    IsiCombo($sql,$setuju3,'Pilih Approval');
                    ?>
                    </select>
                    </td>
        <td style="text-align: center;
                    border: 2px solid #000000;
                    padding: 8px; width:20%; ">
                    <select name="gmf" id="gmf" style="width:90%; padding:8px">
                    <?php 
                    $sql="select username isi,fullname tampil from 
                      userpassword where approval='1' order by fullname";
                    IsiCombo($sql,$ketahui,'Pilih Approval');
                    ?>
                    </select>
                    </td>
        </tr>
   </table>
   <input onclick="return isReadyNewTK()" type="submit" value="kirim" style="width:90%; margin-left:5%; margin-right:5%; background-color:#3588c4;
    font-size:18px; font-weight:bold; color:#ffffff; margin-top:10px; padding:8px;">
</form>
</body>
<script type="text/javascript">
function isReadyNewTK()
{ var iddok = document.getElementById("id_dok").value;
  var namapengaju = document.getElementById("namapengaju").value;
  var nikpengaju = document.getElementById("nikpengaju").value;
  var departmentpengaju = document.getElementById("departmentpengaju").value;
  var bagianpengaju = document.getElementById("bagianpengaju").value;
  var rk_department = document.getElementById("rk_department").value;
  var rk_bagian = document.getElementById("rk_bagian").value;
  var rk_tanggal = document.getElementById("rk_tanggal").value;
  var rk_jumlah = document.getElementById("rk_jumlah").value;
  var jurusan = document.getElementById("jurusan").value;
  var pengalamankerja = document.getElementById("pengalamankerja");
  var lamakerja = document.getElementById("lamakerja").value;
  var gaji = document.getElementById("gaji").value;
  var fasilitas = document.getElementById("fasilitas").value;
  var kontrak = document.getElementById("kontrak").value;
  var lainnya = document.getElementById("lainnya").value;
  var chief = document.getElementById("chief").value;
  var hr = document.getElementById("hr").value;
  var gmp = document.getElementById("gmp").value;
  var gmf = document.getElementById("gmf").value;

  //if(pengalamankerja.checked)
  //{ pengalamankerja.value = "lama kerja selama "+lamakerja+" tahun"; }
  
  if(iddok == "")
  { alert("Kode Dokumen Kosong"); 
    document.getElementById("id_dok").focus();valid=false;
  }
  else if(namapengaju == "")
  { alert("Nama Pengaju Kosong"); 
    document.getElementById("namapengaju").focus();valid=false;
  }
  else if(nikpengaju == "")
  { alert("NIK Pengaju Kosong"); 
    document.getElementById("nikpengaju").focus();valid=false;
  }
  else if(departmentpengaju == "")
  { alert("Dept Pengaju Kosong"); 
    document.getElementById("departmentpengaju").focus();valid=false;
  }
  else if(bagianpengaju == "")
  { alert("Bagian Pengaju Kosong"); 
    document.getElementById("bagianpengaju").focus();valid=false;
  }
  else if(rk_department == "")
  { alert("RK Department Kosong"); 
    document.getElementById("rk_department").focus();valid=false;
  }
  else if(rk_bagian == "")
  { alert("RK Bagian Kosong"); 
    document.getElementById("rk_bagian").focus();valid=false;
  }
  else if(rk_tanggal == "")
  { alert("RK Tanggal Kosong"); 
    document.getElementById("rk_tanggal").focus();valid=false;
  }
  else if(rk_jumlah == "")
  { alert("RK Jumlah Kosong"); 
    document.getElementById("rk_jumlah").focus();valid=false;
  }
  else if(jurusan == "")
  { alert("Jurusan Kosong"); 
    document.getElementById("jurusan").focus();valid=false;
  }
  else if(gaji == "")
  { alert("Gaji Kosong"); 
    document.getElementById("gaji").focus();valid=false;
  }
  else if(fasilitas == "")
  { alert("Fasilitas Kosong"); 
    document.getElementById("fasilitas").focus();valid=false;
  }
  else if(kontrak == "")
  { alert("Kontrak Kosong"); 
    document.getElementById("kontrak").focus();valid=false;
  }
  else if(lainnya == "")
  { alert("Lainnya Kosong"); 
    document.getElementById("lainnya").focus();valid=false;
  }
  else if(chief == "")
  { alert("Chief Kosong"); 
    document.getElementById("chief").focus();valid=false;
  }
  else if(hr == "")
  { alert("HR Kosong"); 
    document.getElementById("hr").focus();valid=false;
  }
  else if(gmf == "")
  { alert("GM F Kosong"); 
    document.getElementById("gmf").focus();valid=false;
  }
  else if(gmp == "")
  { alert("GM P Kosong"); 
    document.getElementById("gmp").focus();valid=false;
  }
  else
  { valid=true;
    document.getElementById("formpermintaantk").action = "permintaantk_kon.php?id=<?php echo $id_tk;?>"; 
  }
  return valid;
  exit;
}     
</script>>
</html>
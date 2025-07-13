<?php
include '../../include/conn.php';

if (isset($_GET['id'])) {$id_fi=$_GET['id'];} else {$id_fi="";}
if ($id_fi=="")
{ $intnama_kandidat="";
  $intinterview_by="";
  $inttanggal="";
  $intpenguasaan_kerja="";
  $intket1="";
  $intkomunikasi="";
  $intket2="";
  $intproblem_solving="";
  $intket3="";
  $intkerjasama="";
  $intket4="";
  $intmotivasi="";
  $intket5="";
  $intintegritas="";
  $intket6="";
  $intinisiatif="";
  $intket7="";
  $intcara_pandang="";
  $intket8="";
  $intperencanaan="";
  $intket9="";
  $intkepemimpinan="";
  $intket10="";
  $intputusan="";
  $intpewawancara="";
  $intdateinput="";
  $intusername="";
}
else
{ $sql="select * from form_interview where id_fi='$id_fi'";
  $rs=mysql_fetch_array(mysql_query($sql));
  $intnama_kandidat=$rs['nama_kandidat'];
  $intinterview_by=$rs['interview_by'];
  $inttanggal=$rs['tanggal'];
  $intpenguasaan_kerja=$rs['penguasaan_kerja'];
  $intket1=$rs['ket1'];
  $intkomunikasi=$rs['komunikasi'];
  $intket2=$rs['ket2'];
  $intproblem_solving=$rs['problem_solving'];
  $intket3=$rs['ket3'];
  $intkerjasama=$rs['kerjasama'];
  $intket4=$rs['ket4'];
  $intmotivasi=$rs['motivasi'];
  $intket5=$rs['ket5'];
  $intintegritas=$rs['integritas'];
  $intket6=$rs['ket6'];
  $intinisiatif=$rs['inisiatif'];
  $intket7=$rs['ket7'];
  $intcara_pandang=$rs['cara_pandang'];
  $intket8=$rs['ket8'];
  $intperencanaan=$rs['perencanaan'];
  $intket9=$rs['ket9'];
  $intkepemimpinan=$rs['kepemimpinan'];
  $intket10=$rs['ket10'];
  $intputusan=$rs['putusan'];
  $intpewawancara=$rs['pewawancara'];
  $intdateinput=$rs['dateinput'];
  $intusername=$rs['username'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Interview</title>
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
<form method="post" id="forminterview">
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
                ">Form Interview Karyawan</p>
            </td>
            <td style="text-align: left;
                    border-top: 2px solid #000000;
                    padding: 8px; width:15%;">
                Kode Dok</td>
            <td style="text-align: left;
                    border-top: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px; width:15%;">: <input type="text" readonly value='F.16.HR.NAG.P-01.F-05.01' name="id_dok" id="id_dok" style="width:85%; padding:3px;"></td>
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
                    padding: 8px; width:15%;">: <input type="text" readonly value='-' name="revisi_date" style="width:85%; padding:3px;"></td>
        </tr>
        <tr>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    padding: 8px; width:15%;">Tanggal Berlaku</td>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px; width:15%;">: <input type="text" readonly value='10 Oct 2016' name="berlaku_date" style="width:85%; padding:3px;"></td>
        <tr>
   </table>

   <table style="margin-top:20px;">
        <tr>
            <td style="text-align: left;
                    padding: 8px; width:20%;">Nama Kandidat</td>
            <td style="text-align: left;
                    padding: 8px; width:30%;">: <input type="text" 
                    name="namakandidat" value="<?php echo $intnama_kandidat; ?>" 
                    id="namakandidat" style="width:90%; padding:3px;"></td>
            <td style="text-align: left;
                    padding: 8px; width:20%;">Tanggal</td>
            <td style="text-align: left;
                    padding: 8px; width:30%;">: <input type="date"  
                      name="tanggalin" value="<?php echo $inttanggal; ?>" style="width:90%; padding:3px;"></td>
        </tr>
        <tr>
            <td style="text-align: left;
                    padding: 8px; width:20%;">Di Interview Oleh</td>
            <td style="text-align: left;
                    padding: 8px; width:30%;">: <input type="text" 
                    name="interviewby" value="<?php echo $intinterview_by; ?>" id="interviewby" style="width:90%; padding:3px;"></td>
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
            padding: 8px; width:40%">Aspek Penilaian</th>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%">Sangat Baik</th>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%">Baik</th>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%">Cukup</th>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%">Kurang</th>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%">Keterangan</th>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:100%; background-color:#c6c6c6" colspan="7">Bidang Teknis Pekerjaan</th>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">1</th>
            <td style="border: 2px solid #000000;
                text-align: left;
                padding: 8px; width:40%">
                <p style="line-height:0.1; font-weight:bold; padding:8px; ">Penguasaan Pekerjaan</p>
                <p style="line-height:1; padding:8px;">
                Kesesuaian pengetahuan dengan jabatan yang akan diisi, kemampuan mengenali masalah dan ketepatan memberi jawaban yang logis (tergantung kompetensi fungsional masing-masing)
                </p>
            </td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="sangatbaik" 
            <?php if ($intpenguasaan_kerja=="sangatbaik") {echo "checked";} ?> 
            name="nilai1"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="baik" 
            <?php if ($intpenguasaan_kerja=="baik") {echo "checked";} ?>
            name="nilai1"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="cukup" 
            <?php if ($intpenguasaan_kerja=="cukup") {echo "checked";} ?>
            name="nilai1"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="kurang" 
            <?php if ($intpenguasaan_kerja=="kurang") {echo "checked";} ?>
            name="nilai1"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%"><textarea name="keterangan1" id="keterangan1" style="width:90%; height:150px; padding:5px; resize:none;"></textarea></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">2</th>
            <td style="border: 2px solid #000000;
                text-align: left;
                padding: 8px; width:40%">
                <p style="line-height:0.1; font-weight:bold; padding:8px; ">Komunikasi</p>
                <p style="line-height:1; padding:8px;">
                Pengungkapan ide/pikiran yang teratur dan dapat dimengerti orang lain. Dinilai dari cara berbicara atau penyampaian pendapat.
                </p>
            </td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="sangatbaik" 
            <?php if ($intkomunikasi=="sangatbaik") {echo "checked";} ?>
            name="nilai2"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="baik" 
            <?php if ($intkomunikasi=="baik") {echo "checked";} ?>
            name="nilai2"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="cukup" 
            <?php if ($intkomunikasi=="cukup") {echo "checked";} ?>
            name="nilai2"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="kurang" 
            <?php if ($intkomunikasi=="kurang") {echo "checked";} ?>
            name="nilai2"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%"><textarea name="keterangan2" id="keterangan2" style="width:90%; height:150px; padding:5px; resize:none;"></textarea></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">3</th>
            <td style="border: 2px solid #000000;
                text-align: left;
                padding: 8px; width:40%">
                <p style="line-height:0.1; font-weight:bold; padding:8px; ">Problem Solving</p>
                <p style="line-height:1; padding:8px;">
                Kemampuan mengidentifikasi masalah yang ada serta dapat mencari/ memberikan solusi yang dapat diterima orang lain.<br/>
                *minta calon untuk menceritakan pengalamannya dalam menyelesaikan masalah, apa saja yang dilakukan dalam menyelesaikan masalah itu (terutama masalah yang berhubungan dengan pekerjaan) dan bagaimana hasilnya?
                </p>
            </td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="sangatbaik" 
            <?php if ($intproblem_solving=="sangatbaik") {echo "checked";} ?>
            name="nilai3"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="baik" 
            <?php if ($intproblem_solving=="baik") {echo "checked";} ?>
            name="nilai3"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="cukup" 
            <?php if ($intproblem_solving=="cukup") {echo "checked";} ?>
            name="nilai3"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="kurang" 
            <?php if ($intproblem_solving=="kurang") {echo "checked";} ?>
            name="nilai3"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%"><textarea name="keterangan3" id="keterangan3" style="width:90%; height:150px; padding:5px; resize:none;"></textarea></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">4</th>
            <td style="border: 2px solid #000000;
                text-align: left;
                padding: 8px; width:40%">
                <p style="line-height:0.1; font-weight:bold; padding:8px; ">Kerjasama</p>
                <p style="line-height:1; padding:8px;">
                Penyesuaian dengan lingkungan baru & rekan kerja.<br/>
                *ajukan pertanyaan seputar pengalaman pribadi dalam mengerjakan suatu proyek atau tugas tertentu
                </p>
            </td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="sangatbaik" 
            <?php if ($intkerjasama=="sangatbaik") {echo "checked";} ?>
            name="nilai4"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="baik" 
            <?php if ($intkerjasama=="baik") {echo "checked";} ?>
            name="nilai4"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="cukup" 
            <?php if ($intkerjasama=="cukup") {echo "checked";} ?>
            name="nilai4"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="kurang" 
            <?php if ($intkerjasama=="kurang") {echo "checked";} ?>
            name="nilai4"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%"><textarea name="keterangan4" id="keterangan4" style="width:90%; height:150px; padding:5px; resize:none;"></textarea></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:100%; background-color:#c6c6c6" colspan="7">Kepribadian</th>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">5</th>
            <td style="border: 2px solid #000000;
                text-align: left;
                padding: 8px; width:40%">
                <p style="line-height:0.1; font-weight:bold; padding:8px; ">Motivasi dan Kemauan Berprestasi</p>
                <p style="line-height:1; padding:8px;">
                perhatian untuk bekerja dengan baik dan melampaui standar prestasi<br/>
                *minta ybs untuk menceritakan kegiatan yang pernah dilakukan yg dianggap sebagai hal yang sukses, waktunya, mengapa dikatakan sukses dan gambaran situasinya saat itu yang telah ditetapkan.<br/>
                *bisa tanyakan juga motivasinya untuk bekerja, apa targetnya, dan bagaimana rencananya untuk mencapai tujuan tersebut.
                </p>
            </td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="sangatbaik" 
            <?php if ($intmotivasi=="sangatbaik") {echo "checked";} ?>
            name="nilai5"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="baik" 
            <?php if ($intmotivasi=="baik") {echo "checked";} ?>
            name="nilai5"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="cukup" 
            <?php if ($intmotivasi=="cukup") {echo "checked";} ?>
            name="nilai5"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="kurang" 
            <?php if ($intmotivasi=="kurang") {echo "checked";} ?>
            name="nilai5"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%"><textarea name="keterangan5" id="keterangan5" style="width:90%; height:150px; padding:5px; resize:none;"></textarea></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">6</th>
            <td style="border: 2px solid #000000;
                text-align: left;
                padding: 8px; width:40%">
                <p style="line-height:0.1; font-weight:bold; padding:8px; ">Integritas</p>
                <p style="line-height:1; padding:8px;">
                Kemampuan untuk bertindak sesuai norma-norma atau nilai-nilai yang berlaku baik secara umum maupun di perusahaan<br/>
                *pertanyakan seputar pengalaman tentang kejadian dimana teman atau rekan kerja yang melakukan perbuatan yang berlawanan dengan prinsip.
                </p>
            </td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="sangatbaik" 
            <?php if ($intintegritas=="sangatbaik") {echo "checked";} ?>
            name="nilai6"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="baik" 
            <?php if ($intintegritas=="baik") {echo "checked";} ?>
            name="nilai6"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="cukup" 
            <?php if ($intintegritas=="cukup") {echo "checked";} ?>
            name="nilai6"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="kurang" 
            <?php if ($intintegritas=="kurang") {echo "checked";} ?>
            name="nilai6"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%"><textarea name="keterangan6" id="keterangan6" style="width:90%; height:150px; padding:5px; resize:none;"></textarea></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">7</th>
            <td style="border: 2px solid #000000;
                text-align: left;
                padding: 8px; width:40%">
                <p style="line-height:0.1; font-weight:bold; padding:8px; ">Inisiatif/ Kreativitas</p>
                <p style="line-height:1; padding:8px;">
                Tindakan untuk memulai sesuatu<br/>
                *Pertanyakan tentang pengerjaan atau menciptakan suatu kesempatan yang baru atau berbeda dalam pekerjaan? minta ybs untuk cerita tentang hal tersebut, dan bagaimana hasilnya.</p>
            </td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="sangatbaik" 
            <?php if ($intinisiatif=="sangatbaik") {echo "checked";} ?>
            name="nilai7"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="baik" 
            <?php if ($intinisiatif=="baik") {echo "checked";} ?>
            name="nilai7"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="cukup" 
            <?php if ($intinisiatif=="cukup") {echo "checked";} ?>
            name="nilai7"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="kurang" 
            <?php if ($intinisiatif=="kurang") {echo "checked";} ?>
            name="nilai7"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%"><textarea name="keterangan7" id="keterangan7" style="width:90%; height:150px; padding:5px; resize:none;"></textarea></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">8</th>
            <td style="border: 2px solid #000000;
                text-align: left;
                padding: 8px; width:40%">
                <p style="line-height:0.1; font-weight:bold; padding:8px; ">Cara Pandang/ Konsep Berfikir</p>
                <p style="line-height:1; padding:8px;">
                Kemampuan berfikir secara sistematis dan mampu menganalisa masalah yang dihadapi<br/>
                *minta calon menceritakan tentang suatu kejadian atau peristiwa dimana ybs harus mengenali apa yang sedang terjadi dan apa yang difikirkan saat tersebut.</p>
            </td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="sangatbaik" 
            <?php if ($intcara_pandang=="sangatbaik") {echo "checked";} ?>
            name="nilai8"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="baik" 
            <?php if ($intcara_pandang=="baik") {echo "checked";} ?>
            name="nilai8"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="cukup" 
            <?php if ($intcara_pandang=="cukup") {echo "checked";} ?>
            name="nilai8"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="kurang" 
            <?php if ($intcara_pandang=="kurang") {echo "checked";} ?>
            name="nilai8"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%"><textarea name="keterangan8" id="keterangan8" style="width:90%; height:150px; padding:5px; resize:none;"></textarea></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:100%; background-color:#c6c6c6" colspan="7">Managerial</th>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">9</th>
            <td style="border: 2px solid #000000;
                text-align: left;
                padding: 8px; width:40%">
                <p style="line-height:0.1; font-weight:bold; padding:8px; ">Perencanaan</p>
                <p style="line-height:1; padding:8px;">
                Mampu merencakan tindakan bagi diri sendiri dan orang lain dengan efektif dan efisien<br/>
                *tanyakan misalnya tentang bagaimana dia membuat rencana untuk masa depan.</p>
            </td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="sangatbaik" 
            <?php if ($intperencanaan=="sangatbaik") {echo "checked";} ?>
            name="nilai9"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="baik" 
            <?php if ($intperencanaan=="baik") {echo "checked";} ?>
            name="nilai9"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="cukup" 
            <?php if ($intperencanaan=="cukup") {echo "checked";} ?>
            name="nilai9"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="kurang" 
            <?php if ($intperencanaan=="kurang") {echo "checked";} ?>
            name="nilai9"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%"><textarea name="keterangan9" id="keterangan9" style="width:90%; height:150px; padding:5px; resize:none;"></textarea></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:5%">10</th>
            <td style="border: 2px solid #000000;
                text-align: left;
                padding: 8px; width:40%">
                <p style="line-height:0.1; font-weight:bold; padding:8px; ">Kepemimpinan</p>
                <p style="line-height:1; padding:8px;">
                Pengalaman sebagai pemimpin, kemampuan mengkordinasi atau mengorganisir suatu kegiatan, mengontrol dan mengambil keputusan.</p>
            </td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="sangatbaik" 
            <?php if ($intkepemimpinan=="sangatbaik") {echo "checked";} ?>
            name="nilai10"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="baik" 
            <?php if ($intkepemimpinan=="baik") {echo "checked";} ?>
            name="nilai10"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="cukup" 
            <?php if ($intkepemimpinan=="cukup") {echo "checked";} ?>
            name="nilai10"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:10%"><input type="radio" 
            value="kurang" 
            <?php if ($intkepemimpinan=="kurang") {echo "checked";} ?>
            name="nilai10"></td>
            <td style="border: 2px solid #000000;
            text-align: center;
            padding: 8px; width:15%"><textarea name="keterangan10" id="keterangan10" style="width:90%; height:150px; padding:5px; resize:none;"></textarea></td>
        </tr>
    </table>
    <table style="margin-top:20px;">
            <tr>
                <th style="width:50%">Kesimpulan Rekomendasi</th>
                <th style="width:50%">Pewawancara</th>
            </tr>
            <tr>
                <td style="width:50%">
                    <input type="radio" id="penerimaan" 
                    value="diterima" 
                    <?php if ($intputusan=="diterima") {echo "checked";} ?>
                    name="penerimaan">Diterima<br/>
                    <input type="radio" id="penerimaan" 
                    value="dipertimbangkan" 
                    <?php if ($intputusan=="dipertimbangkan") {echo "checked";} ?>
                    name="penerimaan">Perlu Dipertimbangkan dengan hasil test
                </td>
                
                <td style="width:50%; text-align:center;"><br/><input type="text" id="pewawancara" 
                name="pewawancara" value="<?php echo $intpewawancara;?>" style="width:70%; padding:8px;"></td>
            </tr>
    </table>
    <input onclick="return isReadyInterviewed()" type="submit" value="kirim" style="width:90%; margin-left:5%; margin-right:5%; background-color:#3588c4;
    font-size:18px; font-weight:bold; color:#ffffff; margin-top:10px; padding:8px;">
</form>
</body>
<script type="text/javascript">
function isReadyInterviewed()
{ var iddok = document.getElementById("id_dok").value;
  var namakandidat = document.getElementById("namakandidat").value;
  var interviewby = document.getElementById("interviewby").value;
  var keterangan1 = document.getElementById("keterangan1").value;
  var keterangan2 = document.getElementById("keterangan2").value;
  var keterangan3 = document.getElementById("keterangan3").value;
  var keterangan4 = document.getElementById("keterangan4").value;
  var keterangan5 = document.getElementById("keterangan5").value;
  var keterangan6 = document.getElementById("keterangan6").value;
  var keterangan7 = document.getElementById("keterangan7").value;
  var keterangan8 = document.getElementById("keterangan8").value;
  var keterangan9 = document.getElementById("keterangan9").value;
  var keterangan10 = document.getElementById("keterangan10").value;
  var penerimaan = document.getElementById("penerimaan").value;
  var pewawancara = document.getElementById("pewawancara").value;

  if(iddok == "")
  { alert("Kode Dokumen Kosong"); 
    document.getElementById("id_dok").focus(); valid = false;
  }
  else if(namakandidat == "")
  { alert("Nama Kandidat Kosong"); 
    document.getElementById("namakandidat").focus(); valid = false;
  }
  else if(interviewby == "")
  { alert("Interview By Kosong"); 
    document.getElementById("interviewby").focus(); valid = false;
  }
  /*
  else if(keterangan1 == "")
  { alert("Keterangan 1 Kosong"); 
    document.getElementById("keterangan1").focus(); valid = false;
  }
  else if(keterangan2 == "")
  { alert("Keterangan 2 Kosong"); 
    document.getElementById("keterangan2").focus(); valid = false;
  }
  else if(keterangan3 == "")
  { alert("Keterangan 3 Kosong"); 
    document.getElementById("keterangan3").focus(); valid = false;
  }
  else if(keterangan4 == "")
  { alert("Keterangan 4 Kosong"); 
    document.getElementById("keterangan4").focus(); valid = false;
  }
  else if(keterangan5 == "")
  { alert("Keterangan 5 Kosong"); 
    document.getElementById("keterangan5").focus(); valid = false;
  }
  else if(keterangan6 == "")
  { alert("Keterangan 6 Kosong"); 
    document.getElementById("keterangan6").focus(); valid = false;
  }
  else if(keterangan7 == "")
  { alert("Keterangan 7 Kosong"); 
    document.getElementById("keterangan7").focus(); valid = false;
  }
  else if(keterangan8 == "")
  { alert("Keterangan 8 Kosong"); 
    document.getElementById("keterangan8").focus(); valid = false;
  }
  else if(keterangan9 == "")
  { alert("Keterangan 9 Kosong"); 
    document.getElementById("keterangan9").focus(); valid = false;
  }
  else if(keterangan10 == "")
  { alert("Keterangan 10 Kosong"); 
    document.getElementById("keterangan10").focus(); valid = false;
  }
  */
  else if(pewawancara == "")
  { alert("Pewawancara Kosong"); 
    document.getElementById("pewawancara").focus(); valid = false;
  }
  else
  { valid = true;
    document.getElementById("forminterview").action = "interview_kon.php?id=<?php echo $id_fi;?>"; 
  }
  return valid;
  exit;
}     
</script>>
</html>
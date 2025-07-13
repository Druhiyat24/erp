<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';

$images = '../../include/img-01.png';

if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}
$sql="select a.nama_lengkap,s.* 
  from data_pribadi a inner join form_dokumen s on 
  a.id_dp=s.id_dp 
  where s.id_form_dk='$id'";
$rs=mysql_fetch_array(mysql_query($sql));
$nama=$rs['nama_lengkap'];
$tgl_msk=$rs['tanggalmasuk'];
$dept=$rs['department'];
$nik=$rs['nik'];
$bagian=$rs['bagian'];
if ($rs['fc_ktp_sim']=="yes") {$ktp="x";} else {$ktp="";}
if ($rs['fc_ktp_sim']=="no") {$ktp2="x";} else {$ktp2="";}
if ($rs['pas_foto']=="yes") {$pasfoto="x";} else {$pasfoto="";}
if ($rs['pas_foto']=="no") {$pasfoto2="x";} else {$pasfoto2="";}
if ($rs['pas_foto']=="no") {$pasfoto2="x";} else {$pasfoto2="";}
if ($rs['surat_lamaran']=="yes") {$surat_lamaran="x";} else {$surat_lamaran="";}
if ($rs['daftar_riwayat_hidup']=="yes") {$daftar_riwayat_hidup="x";} else {$daftar_riwayat_hidup="";}
if ($rs['ijazah_terakhir']=="yes") {$ijazah_terakhir="x";} else {$ijazah_terakhir="";}
if ($rs['fc_kk']=="yes") {$fc_kk="x";} else {$fc_kk="";}
if ($rs['skck']=="yes") {$skck="x";} else {$skck="";}
if ($rs['surat_dokter']=="yes") {$surat_dokter="x";} else {$surat_dokter="";}
if ($rs['pengalaman_kerja']=="yes") {$pengalaman_kerja="x";} else {$pengalaman_kerja="";}
if ($rs['izin_suami_or_ortu']=="yes") {$izin_suami_or_ortu="x";} else {$izin_suami_or_ortu="";}
if ($rs['form_permintaan_tk']=="yes") {$form_permintaan_tk="x";} else {$form_permintaan_tk="";}
if ($rs['perjanjian_kerja']=="yes") {$perjanjian_kerja="x";} else {$perjanjian_kerja="";}
if ($rs['surat_perbedaan_dokument']=="yes") {$surat_perbedaan_dokument="x";} else {$surat_perbedaan_dokument="";}
if ($rs['form_aplikasi_karyawan']=="yes") {$form_aplikasi_karyawan="x";} else {$form_aplikasi_karyawan="";}
if ($rs['form_ringkasan_psikotest_skill']=="yes") {$form_ringkasan_psikotest_skill="x";} else {$form_ringkasan_psikotest_skill="";}
if ($rs['form_pernyataan_orientasi']=="yes") {$form_pernyataan_orientasi="x";} else {$form_pernyataan_orientasi="";}
if ($rs['surat_lamaran']=="no") {$surat_lamaran2="x";} else {$surat_lamaran2="";}
if ($rs['daftar_riwayat_hidup']=="no") {$daftar_riwayat_hidup2="x";} else {$daftar_riwayat_hidup2="";}
if ($rs['ijazah_terakhir']=="no") {$ijazah_terakhir2="x";} else {$ijazah_terakhir2="";}
if ($rs['fc_kk']=="no") {$fc_kk2="x";} else {$fc_kk2="";}
if ($rs['skck']=="no") {$skck2="x";} else {$skck2="";}
if ($rs['surat_dokter']=="no") {$surat_dokter2="x";} else {$surat_dokter2="";}
if ($rs['pengalaman_kerja']=="no") {$pengalaman_kerja2="x";} else {$pengalaman_kerja2="";}
if ($rs['izin_suami_or_ortu']=="no") {$izin_suami_or_ortu2="x";} else {$izin_suami_or_ortu2="";}
if ($rs['form_permintaan_tk']=="no") {$form_permintaan_tk2="x";} else {$form_permintaan_tk2="";}
if ($rs['perjanjian_kerja']=="no") {$perjanjian_kerja2="x";} else {$perjanjian_kerja2="";}
if ($rs['surat_perbedaan_dokument']=="no") {$surat_perbedaan_dokument2="x";} else {$surat_perbedaan_dokument2="";}
if ($rs['form_aplikasi_karyawan']=="no") {$form_aplikasi_karyawan2="x";} else {$form_aplikasi_karyawan2="";}
if ($rs['form_ringkasan_psikotest_skill']=="no") {$form_ringkasan_psikotest_skill2="x";} else {$form_ringkasan_psikotest_skill2="";}
if ($rs['form_pernyataan_orientasi']=="no") {$form_pernyataan_orientasi2="x";} else {$form_pernyataan_orientasi2="";}

$style1='style="width:70%;"';
$style2='style="width:1%;"';
$style3='style="width:29%;"';
$style0='style="width:23%;"';
$styleborder="border: 1px solid black;";
$style4='style="text-align:center;'.$styleborder.'"';
$style5='style="'.$styleborder.'"';
$tbl_header='
  <table style="width:100%;border-collapse: collapse;">
    <tr>
      <td style="width:20%;text-align:center;'.$styleborder.'"><img src='.$images.' style="heigh:70px; width:80px;"></td>
      <td style="width:50%;'.$styleborder.'text-align:center;">Formulir Checklist <br>Kelengkapan Dokumen</td>
      <td style="width:30%;'.$styleborder.'">
        <table style="width:100%;font-size:10px;">
          <tr>
            <td>Kode Dok</td>
            <td>F.16.P.HR.P-01.F-02</td>
          </tr>
          <tr>
            <td>Revisi</td>
            <td>-</td>
          </tr>
          <tr>
            <td>Tanggal Revisi</td>
            <td>-</td>
          </tr>
          <tr>
            <td>Tanggal Berlaku</td>
            <td>30 Sept 2016</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>';
$tbl_detail='
  <table style="width:100%;font-size:10px;">
    <tr>
      <td '.$style2.'>Nama Karyawan </td>
      <td '.$style2.'>:</td>
      <td '.$style0.'>'.$nama.'</td>
    </tr>
    <tr>
      <td '.$style2.'>Tanggal Masuk </td>
      <td '.$style2.'>:</td>
      <td '.$style0.'>'.fd_view($tgl_msk).'</td>
    </tr>
    <tr>
      <td '.$style2.'>Departemen </td>
      <td '.$style2.'>:</td>
      <td '.$style0.'>'.$dept.'</td>
    </tr>
    <tr>
      <td '.$style2.'>NIK </td>
      <td '.$style2.'>:</td>
      <td '.$style0.'>'.$nik.'</td>
    </tr>
    <tr>
      <td '.$style2.'>Bagian </td>
      <td '.$style2.'>:</td>
      <td '.$style0.'>'.$bagian.'</td>
    </tr>
  </table>';
  $tbl_yesno='
    <table style="width:100%;font-size:10px;">
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <table style="width:100%;font-size:10px;border-collapse: collapse;">
      <tr>
        <td style="width:4%;text-align:center;'.$styleborder.'">No</td>
        <td style="width:39%;text-align:center;'.$styleborder.'">Nama Dokumen</td>
        <td style="width:5%;text-align:center;'.$styleborder.'">Yes</td>
        <td style="width:5%;text-align:center;'.$styleborder.'">No</td>
        <td style="width:15%;text-align:center;'.$styleborder.'">N/A</td>
      </tr>
      <tr>
        <td '.$style4.'>1</td>
        <td '.$style5.'>Fotocopy Dokumen</td>
        <td '.$style4.'>'.$ktp.'</td>
        <td '.$style4.'>'.$ktp2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>2</td>
        <td '.$style5.'>Pas Photo</td>
        <td '.$style4.'>'.$pasfoto.'</td>
        <td '.$style4.'>'.$pasfoto2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>3</td>
        <td '.$style5.'>Surat Lamaran</td>
        <td '.$style4.'>'.$surat_lamaran.'</td>
        <td '.$style4.'>'.$surat_lamaran2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>4</td>
        <td '.$style5.'>Riwayat Hidup</td>
        <td '.$style4.'>'.$daftar_riwayat_hidup.'</td>
        <td '.$style4.'>'.$daftar_riwayat_hidup2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>5</td>
        <td '.$style5.'>Fotocopy Ijazah Pendidikan Terakhir</td>
        <td '.$style4.'>'.$ijazah_terakhir.'</td>
        <td '.$style4.'>'.$ijazah_terakhir2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>6</td>
        <td '.$style5.'>Fotocopy Kartu Keluarga</td>
        <td '.$style4.'>'.$fc_kk.'</td>
        <td '.$style4.'>'.$fc_kk2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>7</td>
        <td '.$style5.'>Surat Keterangan Catatan Kepolisian</td>
        <td '.$style4.'>'.$skck.'</td>
        <td '.$style4.'>'.$skck2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>8</td>
        <td '.$style5.'>Surat Keterangan Dokter</td>
        <td '.$style4.'>'.$surat_dokter.'</td>
        <td '.$style4.'>'.$surat_dokter2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>9</td>
        <td '.$style5.'>Surat Pengalaman Kerja (Jika pernah bekerja)</td>
        <td '.$style4.'>'.$pengalaman_kerja.'</td>
        <td '.$style4.'>'.$pengalaman_kerja2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>10</td>
        <td '.$style5.'>Surat Ijin Suami/Orang Tua (Untuk Pekerja Shift)</td>
        <td '.$style4.'>'.$izin_suami_or_ortu.'</td>
        <td '.$style4.'>'.$izin_suami_or_ortu2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>11</td>
        <td '.$style5.'>Formulir Permintaan Tenaga Kerja</td>
        <td '.$style4.'>'.$form_permintaan_tk.'</td>
        <td '.$style4.'>'.$form_permintaan_tk2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>12</td>
        <td '.$style5.'>Surat Perjanjian Kerja</td>
        <td '.$style4.'>'.$perjanjian_kerja.'</td>
        <td '.$style4.'>'.$perjanjian_kerja2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>13</td>
        <td '.$style5.'>Surat Keterangan Perbedaan / Ketidaksesuaian Dokumen</td>
        <td '.$style4.'>'.$surat_perbedaan_dokument.'</td>
        <td '.$style4.'>'.$surat_perbedaan_dokument2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>14</td>
        <td '.$style5.'>Form Aplikasi Karyawan</td>
        <td '.$style4.'>'.$form_aplikasi_karyawan.'</td>
        <td '.$style4.'>'.$form_aplikasi_karyawan2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>15</td>
        <td '.$style5.'>Form Ringkasan Psikotest dan Test Skill</td>
        <td '.$style4.'>'.$form_ringkasan_psikotest_skill.'</td>
        <td '.$style4.'>'.$form_ringkasan_psikotest_skill2.'</td>
        <td '.$style4.'></td>
      </tr>
      <tr>
        <td '.$style4.'>16</td>
        <td '.$style5.'>Form Pernyataan Pemberian Orientasi</td>
        <td '.$style4.'>'.$form_pernyataan_orientasi.'</td>
        <td '.$style4.'>'.$form_pernyataan_orientasi2.'</td>
        <td '.$style4.'></td>
      </tr>
    </table>';
$html = '
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Form Check List</title>
</head>
<body style=" padding-left:5%; padding-right:5%;">
  '.$tbl_header.'
  '.$tbl_detail.'
  <br>
  '.$tbl_yesno.'
</body>
</html>'
?>

<?php
include("../../mpdf57/mpdf.php");

$mpdf=new mPDF();

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>

<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';

$images = '../../include/img-01.png';

if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}

$style1='style="width:70%;"';
$style2='style="width:1%;"';
$style3='style="width:29%;"';
$styleborder="border: 1px solid black;";
$style4='style="'.$styleborder.'"';

$sql="select a.*,s.* 
  from data_pribadi a inner join sk_nama_keluarga s on a.id_dp=s.id_dp 
  where a.id_dp='$id'";
$rs=mysql_fetch_array(mysql_query($sql));
$kode_dok="F.16.P.HR.P-01.F-02.01.02";
$revisi="-";
$tanggal_revisi="-";
$tanggal_berlaku="30 September 2016";

$posisi_lamaran=$rs['posisi_lamaran'];
$work_information=$rs['work_information'];
$ref_kerja=$rs['ref_kerja'];
$imagess=$rs['imagess'];
$nama_lengkap=$rs['nama_lengkap'];
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

$fayah=$rs['ayah'];
$fibu=$rs['ibu'];
$fsaudara1=$rs['saudara1'];
$fsaudara2=$rs['saudara2'];
$fsaudara3=$rs['saudara3'];
$fsaudara4=$rs['saudara4'];
$fsuami_istri=$rs['suami_istri'];
$fanak1=$rs['anak1'];
$fanak2=$rs['anak2'];
$fanak3=$rs['anak3'];
$fanak4=$rs['anak4'];
$fjk_ayah=$rs['jk_ayah'];
$fjk_ibu=$rs['jk_ibu'];
$fjk_s1=$rs['jk_s1'];
$fjk_s2=$rs['jk_s2'];
$fjk_s3=$rs['jk_s3'];
$fjk_s4=$rs['jk_s4'];
$fjk_s_i=$rs['jk_s_i'];
$fjk_ank1=$rs['jk_ank1'];
$fjk_ank2=$rs['jk_ank2'];
$fjk_ank3=$rs['jk_ank3'];
$fjk_ank4=$rs['jk_ank4'];
$fttl_ayah=$rs['ttl_ayah'];
$fttl_ibu=$rs['ttl_ibu'];
$fttl_s1=$rs['ttl_s1'];
$fttl_s2=$rs['ttl_s2'];
$fttl_s3=$rs['ttl_s3'];
$fttl_s4=$rs['ttl_s4'];
$fttl_s_i=$rs['ttl_s_i'];
$fttl_ank1=$rs['ttl_ank1'];
$fttl_ank2=$rs['ttl_ank2'];
$fttl_ank3=$rs['ttl_ank3'];
$fttl_ank4=$rs['ttl_ank4'];
$fpekerjaan_ayah=$rs['pekerjaan_ayah'];
$fpekerjaan_ibu=$rs['pekerjaan_ibu'];
$fpekerjaan_s1=$rs['pekerjaan_s1'];
$fpekerjaan_s2=$rs['pekerjaan_s2'];
$fpekerjaan_s3=$rs['pekerjaan_s3'];
$fpekerjaan_s4=$rs['pekerjaan_s4'];
$fpekerjaan_s_i=$rs['pekerjaan_s_i'];
$fpekerjaan_ank1=$rs['pekerjaan_ank1'];
$fpekerjaan_ank2=$rs['pekerjaan_ank2'];
$fpekerjaan_ank3=$rs['pekerjaan_ank3'];
$fpekerjaan_ank4=$rs['pekerjaan_ank4'];

$sqledu=mysql_query("select * from edu_skill 
  where id_dp='$id' order by id_edu_skill");
while($rsedu=mysql_fetch_array($sqledu))
{ $fnama_sekolah=$rsedu['nama_sekolah'];
  $fkota=$rsedu['kota'];
  $fnegara=$rsedu['negara'];
  $fjurusan=$rsedu['jurusan'];
  $ftahun_masuk=$rsedu['tahun_masuk'];
  $ftahun_lulus=$rsedu['tahun_lulus'];
  $fketerangan=$rsedu['keterangan'];
  $edu_data = $edu_data.
  '<tr>
    <td '.$style4.'>'.$fnama_sekolah.'</td>
    <td '.$style4.'>'.$fkota.'</td>
    <td '.$style4.'>'.$fnegara.'</td>
    <td '.$style4.'>'.$fjurusan.'</td>
    <td '.$style4.'>'.$ftahun_masuk.'</td>
    <td '.$style4.'>'.$ftahun_lulus.'</td>
    <td '.$style4.'>'.$fketerangan.'</td>
  </tr>'; 
}

$sqlpel=mysql_query("select * from pelatihan 
  where id_dp='$id' order by id_pelatihan");
while($rspel=mysql_fetch_array($sqlpel))
{ $fnama_pelatihan=$rspel['nama_pelatihan'];
  $ftahun=$rspel['tahun'];
  $fpenyelenggara=$rspel['penyelenggara'];
  $fketerangan_sertifikat=$rspel['keterangan_sertifikat'];
  $pel_data = $pel_data.
  '<tr>
    <td '.$style4.'>'.$fnama_pelatihan.'</td>
    <td '.$style4.'>'.$ftahun.'</td>
    <td '.$style4.'>'.$fpenyelenggara.'</td>
    <td '.$style4.'>'.$fketerangan_sertifikat.'</td>
  </tr>'; 
}

$sqlbhs=mysql_query("select * from penguasaan_bahasa 
  where id_dp='$id' order by id_pngbahasa");
while($rsbhs=mysql_fetch_array($sqlbhs))
{ $fjenis_bahasa=$rsbhs['jenis_bahasa'];
  $fmembaca=$rsbhs['membaca'];
  $fmenulis=$rsbhs['menulis'];
  $fberbicara=$rsbhs['berbicara'];
  $bhs_data = $bhs_data.
  '<tr>
    <td '.$style4.'>'.$fjenis_bahasa.'</td>
    <td '.$style4.'>'.$fmembaca.'</td>
    <td '.$style4.'>'.$fmenulis.'</td>
    <td '.$style4.'>'.$fberbicara.'</td>
  </tr>'; 
}

$sqlorg=mysql_query("select * from organisasi 
  where id_dp='$id' order by id_org");
while($rsorg=mysql_fetch_array($sqlorg))
{ $fnama_org=$rsorg['nama_org'];
  $fmasa_kerja=$rsorg['masa_kerja'];
  $fjabatan=$rsorg['jabatan'];
  $foketerangan=$rsorg['keterangan'];
  $org_data = $org_data.
  '<tr>
    <td '.$style4.'>'.$fnama_org.'</td>
    <td '.$style4.'>'.$fmasa_kerja.'</td>
    <td '.$style4.'>'.$fjabatan.'</td>
    <td '.$style4.'>'.$foketerangan.'</td>
  </tr>'; 
}

$sqlrk=mysql_query("select * from riwayat_kerja 
  where id_dp='$id' order by id_rkerja");
while($rsrk=mysql_fetch_array($sqlrk))
{ $rknama_perusahaan=$rsrk['nama_perusahaan'];
  $rkjabatan=$rsrk['jabatan'];
  $rkposisi=$rsrk['posisi'];
  $rkawal_kerja=$rsrk['awal_kerja'];
  $rkakhir_kerja=$rsrk['akhir_kerja'];
  $rkgaji=$rsrk['gaji'];
  $rkalasan_berhenti=$rsrk['alasan_berhenti'];
  $rke_data = $rke_data.
  '<tr>
    <td '.$style4.'>'.$rknama_perusahaan.'</td>
    <td '.$style4.'>'.$rkjabatan.'</td>
    <td '.$style4.'>'.$rkposisi.'</td>
    <td '.$style4.'>'.$rkawal_kerja.'</td>
    <td '.$style4.'>'.$rkakhir_kerja.'</td>
    <td '.$style4.'>'.$rkgaji.'</td>
    <td '.$style4.'>'.$rkalasan_berhenti.'</td>
  </tr>'; 
}

$sqllain=mysql_query("select * from dp_lainnya 
  where id_dp='$id' order by id_lainnya");
while($rslain=mysql_fetch_array($sqllain))
{ $dplnama=$rslain['nama'];
  $dplalamat=$rslain['alamat'];
  $dplno_telp=$rslain['no_telp'];
  $dplhubungan=$rslain['hubungan'];
  $lain_data = $lain_data.
  '<tr>
    <td '.$style4.'>'.$dplnama.'</td>
    <td '.$style4.'>'.$dplalamat.'</td>
    <td '.$style4.'>'.$dplno_telp.'</td>
    <td '.$style4.'>'.$dplhubungan.'</td>
  </tr>'; 
}

$tbl_header='
  <table style="width:100%;border-collapse: collapse;">
    <tr>
      <td style="width:20%;text-align:center;'.$styleborder.'"><img src='.$images.' style="heigh:70px; width:80px;"></td>
      <td style="width:50%;'.$styleborder.'text-align:center;">Form Apply Calon Karyawan</td>
      <td style="width:30%;'.$styleborder.'">
        <table style="width:100%;font-size:10px;">
          <tr>
            <td>Kode Dok</td>
            <td>'.$kode_dok.'</td>
          </tr>
          <tr>
            <td>Revisi</td>
            <td>'.$revisi.'</td>
          </tr>
          <tr>
            <td>Tanggal Revisi</td>
            <td>'.$tanggal_revisi.'</td>
          </tr>
          <tr>
            <td>Tanggal Berlaku</td>
            <td>'.$tanggal_berlaku.'</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>';
$tbl_detail='
  <table style="width:100%;font-size:10px;">
    <tr>
      <td '.$style1.'>Posisi Yang Dilamar (<i>Applied Position</i>)</td>
      <td '.$style2.'>:</td>
      <td '.$style3.'>'.$posisi_lamaran.'</td>
    </tr>
    <tr>
      <td>Anda Mengetahui Info Lowongan Pekerjaan Dari (<i>work information</i>)</td>
      <td>:</td>
      <td>'.$work_information.'</td>
    </tr>
    <tr>
      <td>*Referensi Kerja (sebutkan nama dan hubungan) (<i>Work Reference</i>) (<i>Provide name of reference</i>)</td>
      <td>:</td>
      <td>'.$ref_kerja.'</td>
    </tr>
  </table>';
$tbl_personal='
  <table style="width:100%;font-size:10px;">
    <tr>
      <td>Data Pribadi (<i>personal data</i>)</td>
    </tr>
  </table>
  <table style="width:100%;font-size:10px;border-collapse: collapse;'.$styleborder.'">
    <tr>
      <td style="width:50%;">Nama Lengkap (<i>full name</i>)</td>
      <td style="width:1%;">: </td>
      <td style="width:49%;">'.$nama_lengkap.'</td>
    </tr>
    <tr>
      <td>Nama Panggilan (<i>nick name</i>)</td>
      <td>: </td>
      <td>'.$nama_panggilan.'</td>
    </tr>
    <tr>
      <td>Jenis Kelamin (<i>gender</i>)</td>
      <td>: </td>
      <td>'.$jenis_kelamin.'</td>
    </tr>
    <tr>
      <td>Tempat, Tanggal Lahir (<i>place of birth</i>)</td>
      <td>: </td>
      <td>'.$ttl.'</td>
    </tr>
    <tr>
      <td>Kewarganegaraan (<i>nationality</i>)</td>
      <td>: </td>
      <td>'.$kewarganegaraan.'</td>
    </tr>
    <tr>
      <td>Alamat Tetap (<i>permanent address</i>)</td>
      <td>: </td>
      <td>'.$alamat_tetap.'</td>
    </tr>
    <tr>
      <td>Alamat Sementara (<i>temporary address</i>)</td>
      <td>: </td>
      <td>'.$alamat_sementara.'</td>
    </tr>
    <tr>
      <td>Agama (<i>religion</i>)</td>
      <td>: </td>
      <td>'.$agama.'</td>
    </tr>
    <tr>
      <td>No. KTP (<i>identification card number</i>)</td>
      <td>: </td>
      <td>'.$ktp.'</td>
    </tr>
    <tr>
      <td>Jenis SIM (<i>type of driving license</i>)</td>
      <td>: </td>
      <td>'.$jenis_sim.'</td>
    </tr>
    <tr>
      <td>No. SIM (<i>driving license number</i>)</td>
      <td>: </td>
      <td>'.$no_sim.'</td>
    </tr>
    <tr>
      <td>Golongan Darah (<i>blood type</i>)</td>
      <td>: </td>
      <td>'.$goldar.'</td>
    </tr>
    <tr>
      <td>Status Pernikahan (<i>marital status</i>)</td>
      <td>: </td>
      <td>'.$status_pernikahan.'</td>
    </tr>
    <tr>
      <td>No. Handphone / Telephone (<i>phone number</i>)</td>
      <td>: </td>
      <td>'.$no_hp.'</td>
    </tr>
    <tr>
      <td>Email (<i>email</i>)</td>
      <td>: </td>
      <td>'.$email.'</td>
    </tr>
    <tr>
      <td>Ukuran Baju (<i>shirt size</i>)</td>
      <td>: </td>
      <td>'.$ukuran_baju.'</td>
    </tr>
  </table>';
$tbl_susunan='
  <table style="width:100%;font-size:10px;">
    <tr>
      <td>Susunan Keluarga (<i>family structure</i>)</td>
    </tr>
  </table>
  <table style="width:100%;font-size:10px;border-collapse: collapse;">
    <tr>
      <td style="width:15%;'.$styleborder.'">Hubungan <br>(<i>relationship</i>)</td>
      <td style="width:30%;'.$styleborder.'">Nama <br>(<i>name</i>)</td>
      <td style="width:10%;'.$styleborder.'">Jenis Kelamin <br>(<i>gender</i>)</td>
      <td style="width:20%;'.$styleborder.'">Tempat Tanggal Lahir <br>(<i>place of birth</i>)</td>
      <td style="width:25%;'.$styleborder.'">Pekerjaan <br>(<i>occupation</i>)</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Ayah (<i>father</i>)</td>
      <td '.$style4.'>'.$fayah.'</td>
      <td '.$style4.'>'.$fjk_ayah.'</td>
      <td '.$style4.'>'.$fttl_ayah.'</td>
      <td '.$style4.'>'.$fpekerjaan_ayah.'</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Ibu (<i>mother</i>)</td>
      <td '.$style4.'>'.$fibu.'</td>
      <td '.$style4.'>'.$fjk_ibu.'</td>
      <td '.$style4.'>'.$fttl_ibu.'</td>
      <td '.$style4.'>'.$fpekerjaan_ibu.'</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Saudara 1 (<i>brother/sister</i>)</td>
      <td '.$style4.'>'.$fsaudara1.'</td>
      <td '.$style4.'>'.$fjk_s1.'</td>
      <td '.$style4.'>'.$fttl_s1.'</td>
      <td '.$style4.'>'.$fpekerjaan_s1.'</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Saudara 2 (<i>brother/sister</i>)</td>
      <td '.$style4.'>'.$fsaudara2.'</td>
      <td '.$style4.'>'.$fjk_s2.'</td>
      <td '.$style4.'>'.$fttl_s2.'</td>
      <td '.$style4.'>'.$fpekerjaan_s2.'</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Saudara 3 (<i>brother/sister</i>)</td>
      <td '.$style4.'>'.$fsaudara3.'</td>
      <td '.$style4.'>'.$fjk_s3.'</td>
      <td '.$style4.'>'.$fttl_s3.'</td>
      <td '.$style4.'>'.$fpekerjaan_s3.'</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Saudara 4 (<i>brother/sister</i>)</td>
      <td '.$style4.'>'.$fsaudara4.'</td>
      <td '.$style4.'>'.$fjk_s4.'</td>
      <td '.$style4.'>'.$fttl_s4.'</td>
      <td '.$style4.'>'.$fpekerjaan_s4.'</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Suami/Istri (<i>husband/wife</i>)</td>
      <td '.$style4.'>'.$fsuami_istri.'</td>
      <td '.$style4.'>'.$fjk_s_i.'</td>
      <td '.$style4.'>'.$fttl_s_i.'</td>
      <td '.$style4.'>'.$fpekerjaan_s_i.'</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Anak Ke 1 <br>(<i>son / daughter 1</i>)</td>
      <td '.$style4.'>'.$fanak1.'</td>
      <td '.$style4.'>'.$fjk_ank1.'</td>
      <td '.$style4.'>'.$fttl_ank1.'</td>
      <td '.$style4.'>'.$fpekerjaan_ank1.'</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Anak Ke 2 <br>(<i>son / daughter 2</i>)</td>
      <td '.$style4.'>'.$fanak2.'</td>
      <td '.$style4.'>'.$fjk_ank2.'</td>
      <td '.$style4.'>'.$fttl_ank2.'</td>
      <td '.$style4.'>'.$fpekerjaan_ank2.'</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Anak Ke 3 <br>(<i>son / daughter 3</i>)</td>
      <td '.$style4.'>'.$fanak3.'</td>
      <td '.$style4.'>'.$fjk_ank3.'</td>
      <td '.$style4.'>'.$fttl_ank3.'</td>
      <td '.$style4.'>'.$fpekerjaan_ank3.'</td>
    </tr>
    <tr>
      <td height="30" '.$style4.'>Anak Ke 4 <br>(<i>son / daughter 4</i>)</td>
      <td '.$style4.'>'.$fanak4.'</td>
      <td '.$style4.'>'.$fjk_ank4.'</td>
      <td '.$style4.'>'.$fttl_ank4.'</td>
      <td '.$style4.'>'.$fpekerjaan_ank4.'</td>
    </tr>
  </table>';
$tbl_pendidikan='
  <table style="width:100%;font-size:10px;">
    <tr>
      <td>Pendidikan dan Skill (<i>education and skill</i>)</td>
    </tr>
  </table>
  <table style="width:100%;font-size:10px;border-collapse: collapse;">
    <tr>
      <td style="width:10%;'.$styleborder.'">Nama Sekolah <br>(<i>name of school</i>)</td>
      <td style="width:10%;'.$styleborder.'">Kota <br>(<i>city</i>)</td>
      <td style="width:10%;'.$styleborder.'">Negara <br>(<i>country</i>)</td>
      <td style="width:10%;'.$styleborder.'">Jurusan <br>(<i>major</i>)</td>
      <td style="width:10%;'.$styleborder.'">Tahun Masuk <br>(<i>starting year</i>)</td>
      <td style="width:10%;'.$styleborder.'">Tahun Lulus <br>(<i>graduate year</i>)</td>
      <td style="width:10%;'.$styleborder.'">
        Keterangan <br>(<i>remark</i>)
        <br>Lulus/Tidak/Belum <br>(<i>pass/not/not yet</i>)
      </td>
    </tr>
    '.$edu_data.'
  </table>';
$tbl_kursus='
  <table style="width:100%;font-size:10px;border-collapse: collapse;">
    <tr>
      <td style="width:25%;'.$styleborder.'">Nama Pelatihan <br>(<i>name of training/course</i>)</td>
      <td style="width:25%;'.$styleborder.'">Tahun <br>(<i>year</i>)</td>
      <td style="width:25%;'.$styleborder.'">Penyelenggara <br>(<i>organizer</i>)</td>
      <td style="width:25%;'.$styleborder.'">
        Keterangan <br>(<i>name of training/course</i>)
        <br>Sertifikat/Tidak <br>(<i>certified/not certified</i>)
      </td>
    </tr>
    '.$pel_data.'
  </table>';
$tbl_bahasa='
  <table style="width:100%;font-size:10px;">
    <tr>
      <td>Penguasaan Bahasa  (<i>Langauge and skill</i>)</td>
    </tr>
  </table>
  <table style="width:100%;font-size:10px;border-collapse: collapse;">
    <tr>
      <td style="width:10%;'.$styleborder.'">Jenis Bahasa <br>(<i>Language Type</i>)</td>
      <td style="width:10%;'.$styleborder.'">Membaca  <br>(<i>Reading</i>)</td>
      <td style="width:10%;'.$styleborder.'">Menulis <br>(<i>Writing</i>)</td>
      <td style="width:10%;'.$styleborder.'">Berbicara <br>(<i>Speaking</i>)</td>
    </tr>
    '.$bhs_data.'
  </table>';
$tbl_pengalaman='
<table style="width:100%;font-size:10px;">
  <tr>
    <td>IV Pengalaman Berorganisasi (<i>Experiences In Organization</i>)</td>
  </tr>
</table>
<table style="width:100%;font-size:10px;border-collapse: collapse;">
  <tr>
    <td style="width:15%;'.$styleborder.'">Nama Organisasi <br>(<i>Name Of organization</i>)</td>
    <td style="width:30%;'.$styleborder.'">Masa Kerja <br>(<i>Length of Service</i>)</td>
    <td style="width:10%;'.$styleborder.'">Jabatan <br>(<i>Position</i>)</td>
    <td style="width:20%;'.$styleborder.'">Keterangan <br>(<i>Remarks</i>)</td>
  </tr>
  '.$org_data.'
</table>';
$tbl_riwayat='
<table style="width:100%;font-size:10px;">
  <tr>
    <td>VI Riwayat Pekerjaan (<i>Job Experiences </i>)</td>
  </tr>
</table>
<table style="width:100%;font-size:10px;border-collapse: collapse;">
  <tr>
    <td style="width:15%;'.$styleborder.'">Nama Perusahaan <br>(<i>Company Name</i>)</td>
    <td style="width:15%;'.$styleborder.'">Jabatan <br>(<i>Title</i>)</td>
    <td style="width:10%;'.$styleborder.'">Posisi / bagian <br>(<i>Position</i>)</td>
    <td style="width:10%;'.$styleborder.'">Awal Bekerja <br>(<i>Starting work</i>)</td>
    <td style="width:10%;'.$styleborder.'">Akhir Bekerja <br>(<i>Finished work</i>)</td>
    <td style="width:10%;'.$styleborder.'">Gaji <br>(<i>Sallary</i>)</td>
    <td style="width:10%;'.$styleborder.'">Alasan Berhenti <br>(<i>Reason of resigtnation</i>)</td>
  </tr>
  '.$rke_data.'
</table>';
$tbl_lainnya='
<table style="width:100%;font-size:10px;">
  <tr>
    <td>VII Lainnya (<i>Others </i>)</td>
  </tr>
  <tr>
  <td>Nama / keluarga yang bisa dihubungi saat kondisi darurat (<i>Name/Family that can be contacted in emergency situation</i>)</td>
  </tr>
</table>
<table style="width:100%;font-size:10px;border-collapse: collapse;">
  <tr>
    <td style="width:15%;'.$styleborder.'">Nama (<i> Name</i>)</td>
    <td style="width:30%;'.$styleborder.'">Alamat (<i>Address</i>)</td>
    <td style="width:10%;'.$styleborder.'">No Tlp (<i>Phone Number</i>)</td>
    <td style="width:20%;'.$styleborder.'">Hubungan (<i>Relationship</i>)</td>
  </tr>
  '.$lain_data.'
</table>';
$tbl_bawah='
<table style="width:100%;font-size:10px;">
  <tr>
    <td>Dengan ini menyatakan bahwa data yang tercantum diatas adalah data yang sesuai.<br>
     (<i>I Hereby statement above data are true </i>)</td>
  </tr>
</table>
<table style="width:100%;font-size:10px;">
  <tr>
    <td style="text-align:right;">Rancaekek,</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="text-align:right;">__________________</td>
  </tr>
</table>';
$html = '
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Apply Calon Karyawan</title>
</head>
<body style=" padding-left:5%; padding-right:5%;">
  '.$tbl_header.'
  '.$tbl_detail.'
  <br>
  '.$tbl_personal.'
  <br>
  '.$tbl_susunan.'
  <br>
  '.$tbl_pendidikan.'
  <br>
  '.$tbl_kursus.'
  <br>
  '.$tbl_bahasa.'
  <br>
  '.$tbl_pengalaman.'
  <br>
  '.$tbl_riwayat.'
  <br>
  '.$tbl_lainnya.'
  <br>
  '.$tbl_bawah.'
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

<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';

$images = '../../include/img-01.png';

if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}
$sql="select * from form_interview where id_fi='$id'";
$rs=mysql_fetch_array(mysql_query($sql));
$nama_kandidat=$rs['nama_kandidat'];
$interview_by=$rs['interview_by'];
$tanggal=$rs['tanggal'];
$penguasaan_kerja=$rs['penguasaan_kerja'];
$ket1=$rs['ket1'];
$komunikasi=$rs['komunikasi'];
$ket2=$rs['ket2'];
$problem_solving=$rs['problem_solving'];
$ket3=$rs['ket3'];
$kerjasama=$rs['kerjasama'];
$ket4=$rs['ket4'];
$motivasi=$rs['motivasi'];
$ket5=$rs['ket5'];
$integritas=$rs['integritas'];
$ket6=$rs['ket6'];
$inisiatif=$rs['inisiatif'];
$ket7=$rs['ket7'];
$cara_pandang=$rs['cara_pandang'];
$ket8=$rs['ket8'];
$perencanaan=$rs['perencanaan'];
$ket9=$rs['ket9'];
$kepemimpinan=$rs['kepemimpinan'];
$ket10=$rs['ket10'];
$putusan=$rs['putusan'];
if ($putusan=="dipertimban") {$putusan2="x";} else {$putusan2="";}
if ($putusan=="diterima") {$putusan1="x";} else {$putusan1="";}

$pewawancara=$rs['pewawancara'];
if ($penguasaan_kerja=="sangatbaik") {$penguasaan_kerja_sb='x';} else {$penguasaan_kerja_sb='';}
if ($komunikasi=="sangatbaik") {$komunikasi_sb='x';} else {$komunikasi_sb='';}
if ($problem_solving=="sangatbaik") {$problem_solving_sb='x';} else {$problem_solving_sb='';}
if ($kerjasama=="sangatbaik") {$kerjasama_sb='x';} else {$kerjasama_sb='';}
if ($motivasi=="sangatbaik") {$motivasi_sb='x';} else {$motivasi_sb='';}
if ($integritas=="sangatbaik") {$integritas_sb='x';} else {$integritas_sb='';}
if ($inisiatif=="sangatbaik") {$inisiatif_sb='x';} else {$inisiatif_sb='';}
if ($cara_pandang=="sangatbaik") {$cara_pandang_sb='x';} else {$cara_pandang_sb='';}
if ($perencanaan=="sangatbaik") {$perencanaan_sb='x';} else {$perencanaan_sb='';}
if ($kepemimpinan=="sangatbaik") {$kepemimpinan_sb='x';} else {$kepemimpinan_sb='';}
if ($penguasaan_kerja=="baik") {$penguasaan_kerja_b='x';} else {$penguasaan_kerja_b='';}
if ($komunikasi=="baik") {$komunikasi_b='x';} else {$komunikasi_b='';}
if ($problem_solving=="baik") {$problem_solving_b='x';} else {$problem_solving_b='';}
if ($kerjasama=="baik") {$kerjasama_b='x';} else {$kerjasama_b='';}
if ($motivasi=="baik") {$motivasi_b='x';} else {$motivasi_b='';}
if ($integritas=="baik") {$integritas_b='x';} else {$integritas_b='';}
if ($inisiatif=="baik") {$inisiatif_b='x';} else {$inisiatif_b='';}
if ($cara_pandang=="baik") {$cara_pandang_b='x';} else {$cara_pandang_b='';}
if ($perencanaan=="baik") {$perencanaan_b='x';} else {$perencanaan_b='';}
if ($kepemimpinan=="baik") {$kepemimpinan_b='x';} else {$kepemimpinan_b='';}
if ($penguasaan_kerja=="cukup") {$penguasaan_kerja_c='x';} else {$penguasaan_kerja_c='';}
if ($komunikasi=="cukup") {$komunikasi_c='x';} else {$komunikasi_c='';}
if ($problem_solving=="cukup") {$problem_solving_c='x';} else {$problem_solving_c='';}
if ($kerjasama=="cukup") {$kerjasama_c='x';} else {$kerjasama_c='';}
if ($motivasi=="cukup") {$motivasi_c='x';} else {$motivasi_c='';}
if ($integritas=="cukup") {$integritas_c='x';} else {$integritas_c='';}
if ($inisiatif=="cukup") {$inisiatif_c='x';} else {$inisiatif_c='';}
if ($cara_pandang=="cukup") {$cara_pandang_c='x';} else {$cara_pandang_c='';}
if ($perencanaan=="cukup") {$perencanaan_c='x';} else {$perencanaan_c='';}
if ($kepemimpinan=="cukup") {$kepemimpinan_c='x';} else {$kepemimpinan_c='';}
if ($penguasaan_kerja=="kurang") {$penguasaan_kerja_k='x';} else {$penguasaan_kerja_k='';}
if ($komunikasi=="kurang") {$komunikasi_k='x';} else {$komunikasi_k='';}
if ($problem_solving=="kurang") {$problem_solving_k='x';} else {$problem_solving_k='';}
if ($kerjasama=="kurang") {$kerjasama_k='x';} else {$kerjasama_k='';}
if ($motivasi=="kurang") {$motivasi_k='x';} else {$motivasi_k='';}
if ($integritas=="kurang") {$integritas_k='x';} else {$integritas_k='';}
if ($inisiatif=="kurang") {$inisiatif_k='x';} else {$inisiatif_k='';}
if ($cara_pandang=="kurang") {$cara_pandang_k='x';} else {$cara_pandang_k='';}
if ($perencanaan=="kurang") {$perencanaan_k='x';} else {$perencanaan_k='';}
if ($kepemimpinan=="kurang") {$kepemimpinan_k='x';} else {$kepemimpinan_k='';}

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
      <td style="width:50%;'.$styleborder.'text-align:center;">FORM INTERVIEW KARYAWAN</td>
      <td style="width:30%;'.$styleborder.'">
        <table style="width:100%;font-size:10px;">
          <tr>
            <td>Kode Dok</td>
            <td>:</td>
            <td>F.16.HR.NAG.P-01.F-05.01</td>
          </tr>
          <tr>
            <td>Revisi</td>
            <td>:</td>
            <td>-</td>
          </tr>
          <tr>
            <td>Tanggal Revisi</td>
            <td>:</td>
            <td>-</td>
          </tr>
          <tr>
            <td>Tanggal Berlaku</td>
            <td>:</td>
            <td>10 Oct 2016</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>';
$tbl_detail='
  <table style="width:100%;font-size:10px;">
    <tr>
      <td '.$style2.'>Nama Kandidat </td>
      <td '.$style2.'>:</td>
      <td '.$style0.'>'.$nama_kandidat.'</td>
      <td '.$style2.'>Tanggal</td>
      <td '.$style2.'>:</td>
      <td '.$style0.'>'.fd_view($tanggal).'</td>
    </tr>
    <tr>
      <td '.$style2.'>Di Interview Oleh </td>
      <td '.$style2.'>:</td>
      <td '.$style0.'>'.$interview_by.'</td>
    </tr>
    </table>';
  $tbl_aspek='
    <table style="width:100%;font-size:10px;">
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <table style="width:100%;font-size:10px;border-collapse: collapse;">
      <tr>
        <td style="width:4%;text-align:center;'.$styleborder.'">No</td>
        <td style="width:39%;text-align:center;'.$styleborder.'">Aspek Penilaian</td>
        <td style="width:5%;text-align:center;'.$styleborder.'">Sangat<br>Baik</td>
        <td style="width:5%;text-align:center;'.$styleborder.'">Baik</td>
        <td style="width:5%;text-align:center;'.$styleborder.'">Cukup</td>
        <td style="width:5%;text-align:center;'.$styleborder.'">Kurang</td>
        <td style="width:5%;text-align:center;'.$styleborder.'">Keterangan</td>
      </tr>
      <tr>
        <td bgcolor="#6666ff" colspan="7" '.$style4.'>Bidang Teknis Pekerjaan</td>
      </tr>
      <tr>
        <td '.$style4.'>1</td>
        <td '.$style5.'><b>Penguasaan Pekerjaan</b><br>Kesesuaian pengetahuan dengan jabatan yg
        akan diisi, kemampuan mengenali masalah & ketepatan memberi jawaban yg logis
        ( Tergantung kompetensi fungsional masing-masing bagian )</td>
        <td '.$style4.'>'.$penguasaan_kerja_sb.'</td>
        <td '.$style4.'>'.$penguasaan_kerja_b.'</td>
        <td '.$style4.'>'.$penguasaan_kerja_c.'</td>
        <td '.$style4.'>'.$penguasaan_kerja_k.'</td>
        <td '.$style4.'>'.$ket1.'</td>
      </tr>
      <tr>
        <td '.$style4.'>2</td>
        <td '.$style5.'><b>Komunikasi</b><br>Pengungkapan ide/pikiran yang teratur & dapat dimengerti orang lain.
        Dinilai dari cara berbicara atau penyampaian pendapat.</td>
        <td '.$style4.'>'.$komunikasi_sb.'</td>
        <td '.$style4.'>'.$komunikasi_b.'</td>
        <td '.$style4.'>'.$komunikasi_c.'</td>
        <td '.$style4.'>'.$komunikasi_k.'</td>
        <td '.$style4.'>'.$ket2.'</td>
      </tr>
      <tr>
        <td '.$style4.'>3</td>
        <td '.$style5.'><b>Problem Solving</b><br>Kemampuan mengidentifikasi masalah yang ada
        serta dapat mencari / memberikan solusi yang dapat diterima orang lain <br>* Minta Calon utk
        menceritakan pengalamannya dalam menyelesaikan masalah, apa saja yang dilakukan dalam
        menyelesaikan masalah itu (terutama masalah yang berhubungan dengan pekerjaan) dan bagaimana hasilnya?</td>
        <td '.$style4.'>'.$problem_solving_sb.'</td>
        <td '.$style4.'>'.$problem_solving_b.'</td>
        <td '.$style4.'>'.$problem_solving_c.'</td>
        <td '.$style4.'>'.$problem_solving_k.'</td>
        <td '.$style4.'>'.$ket3.'</td>
      </tr>
      <tr>
        <td '.$style4.'>4</td>
        <td '.$style5.'><b>Kerjasama</b><br>Penyesuaian dengan lingkungan baru & rekan kerja,
        <br>* Ajukan pertanyaan seputar Pengalaman pribadi dalam mengerjakan suatu proyek atau tugas tertentu</td>
        <td '.$style4.'>'.$kerjasama_sb.'</td>
        <td '.$style4.'>'.$kerjasama_b.'</td>
        <td '.$style4.'>'.$kerjasama_c.'</td>
        <td '.$style4.'>'.$kerjasama_k.'</td>
        <td '.$style4.'>'.$ket4.'</td>
      </tr>
      <tr>
        <td bgcolor="#6666ff" colspan="7" '.$style4.'>Kepribadian</td>
      </tr>
      <tr>
        <td '.$style4.'>5</td>
        <td '.$style5.'><b>Motivasi & Kemauan Berprestasi</b><br>Perhatian untuk bekerja dengan baik & Melampaui standar prestasi<br>
        * Minta ybs utk menceritakan kegiatan yg pernah dilakukan yg dianggap sebagai hal yang sukses,
        waktunya, mengapa dikatakan sukses dan gambaran situasinya saat itu yang telah ditetapkan.<br>
        *Bisa tanyakan juga motivasinya utk bekerja, apa targetnya, dan bagaimana rencananya utk mencapai tujuan tsb</td>
        <td '.$style4.'>'.$motivasi_sb.'</td>
        <td '.$style4.'>'.$motivasi_b.'</td>
        <td '.$style4.'>'.$motivasi_c.'</td>
        <td '.$style4.'>'.$motivasi_k.'</td>
        <td '.$style4.'>'.$ket5.'</td>
      </tr>
      <tr>
        <td '.$style4.'>6</td>
        <td '.$style5.'><b>Integritas</b><br>Kemampuan untuk bertindak sesuai norma-norma atau nilai-nilai yang berlaku baik secara
        umum maupun di perusahaan<br>* Pertanyakan seputar pengalaman ttg kejadian dimana teman atau rekan kerja yg melakukan perbuatan
        yang berlawanan dengan prinsip</td>
        <td '.$style4.'>'.$integritas_sb.'</td>
        <td '.$style4.'>'.$integritas_b.'</td>
        <td '.$style4.'>'.$integritas_c.'</td>
        <td '.$style4.'>'.$integritas_k.'</td>
        <td '.$style4.'>'.$ket6.'</td>
      </tr>
      <tr>
        <td '.$style4.'>7</td>
        <td '.$style5.'><b>Inisiatif / Kreativitas</b><br>Tindakan untuk memulai sesuatu<br>
        *Pertanyakan ttg pengerjaan atau menciptakan suatu kesempatan yang baru atau berbeda dalam pekerjaan ? Minta
        <br>ybs utk cerita ttg hal tsb. dan bagaimana hasilnya</td>
        <td '.$style4.'>'.$inisiatif_sb.'</td>
        <td '.$style4.'>'.$inisiatif_b.'</td>
        <td '.$style4.'>'.$inisiatif_c.'</td>
        <td '.$style4.'>'.$inisiatif_k.'</td>
        <td '.$style4.'>'.$ket7.'</td>
      </tr>
      <tr>
        <td '.$style4.'>8</td>
        <td '.$style5.'><b>Cara Pandang / Konsep Berfikir</b><br>Kemampuan berfikir secara sistematis dan
        mampu menganalisa masalah yg dihadapi<br>* Minta Calon menceritakan tentang suatu kejadian atau peristiwa dimana ybs
        harus mengenali apa yang sedang terjadi dan apa yg dia pikirkan saat tsb.</td>
        <td '.$style4.'>'.$cara_pandang_sb.'</td>
        <td '.$style4.'>'.$cara_pandang_b.'</td>
        <td '.$style4.'>'.$cara_pandang_c.'</td>
        <td '.$style4.'>'.$cara_pandang_k.'</td>
        <td '.$style4.'>'.$ket8.'</td>
      </tr>
      <tr>
        <td bgcolor="#6666ff" colspan="7" '.$style4.'>Managerial</td>
      </tr>
      <tr>
        <td '.$style4.'>9</td>
        <td '.$style5.'><b>Perencanaan</b><br>Mampu merencanakan tindakan bagi diri sendiri dan orang-orang
        lain dengan efektif dan efisien.<br>* Tanyakan misalnya ttg bagaimana dia membuat rencana untuk masa depan.</td>
        <td '.$style4.'>'.$perencanaan_sb.'</td>
        <td '.$style4.'>'.$perencanaan_b.'</td>
        <td '.$style4.'>'.$perencanaan_c.'</td>
        <td '.$style4.'>'.$perencanaan_k.'</td>
        <td '.$style4.'>'.$ket9.'</td>
      </tr>
      <tr>
        <td '.$style4.'>10</td>
        <td '.$style5.'><b>Kepemimpinan</b><br>Pengalaman sebagai pemimpin, kemampuan mengkoordinasi atau mengorganisir suatu
        kegiatan, mengontrol & mengambil keputusan.</td>
        <td '.$style4.'>'.$kepemimpinan_sb.'</td>
        <td '.$style4.'>'.$kepemimpinan_b.'</td>
        <td '.$style4.'>'.$kepemimpinan_c.'</td>
        <td '.$style4.'>'.$kepemimpinan_k.'</td>
        <td '.$style4.'>'.$ket10.'</td>
      </tr>
      </table>';
      $tbl_bawah='
      <table style="width:50%;font-size:10px;">
      <tr>
        <td colspan=3>Kesimpulan Rekomendasi</td>
      </tr>
      <tr>
        <td>'.$putusan1.'</td>
        <td>Diterima</td>
        <td></td>
      </tr>
      <tr>
        <td>'.$putusan2.'</td>
        <td>Perlu Dipertimbangkan dengan hasil test</td>
        <td></td>
      </tr>
      </table>
      Pewawancara<br>
      '.$pewawancara.'';

$html = '
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Form Interview</title>
</head>
<body style=" padding-left:5%; padding-right:5%;">
  '.$tbl_header.'
  '.$tbl_detail.'
  <br>
  '.$tbl_aspek.'
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

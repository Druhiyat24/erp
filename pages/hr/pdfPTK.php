<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';

$images = '../../include/img-01.png';

if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}

$style1='style="width:70%;"';
$style2='style="width:1%;"';
$style3='style="width:29%;"';
$style0='style="width:23%;"';
$styleborder="border: 2px solid black;";
$style4='style="text-align:center;'.$styleborder.'"';
$style4L='style="text-align:left;'.$styleborder.'"';
$style5='style="'.$styleborder.'"';

$sql="select * from form_tenaga_kerja where id_tk='$id'";
$rs=mysql_fetch_array(mysql_query($sql));
$status_permintaan=$rs['status_permintaan'];
if ($status_permintaan=="permintaan_baru") 
{ $status1="X ";
  $status2="";
} 
else 
{ $status2="X ";
  $status1="";
} 
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
if ($rencana_jabatan=="manager")
{ $rjm="X ";
  $rjc="";
  $rjs="";
  $rjl="";
  $rjo="";
}
else if ($rencana_jabatan=="chief")
{ $rjm="";
  $rjc="X ";
  $rjs="";
  $rjl="";
  $rjo="";
}
else if ($rencana_jabatan=="spv")
{ $rjm="";
  $rjc="";
  $rjs="X ";
  $rjl="";
  $rjo="";
}
else if ($rencana_jabatan=="leader")
{ $rjm="";
  $rjc="";
  $rjs="";
  $rjl="X ";
  $rjo="";
}
else if ($rencana_jabatan=="operator")
{ $rjm="";
  $rjc="";
  $rjs="";
  $rjl="";
  $rjo="X ";
}
else
{	$rjm="";
  $rjc="";
  $rjs="";
  $rjl="";
  $rjo="";
}
$pendidikanakhir=$rs['pendidikanakhir'];
$arr_x=array("&nbsp;&nbsp;","X ");
if ($pendidikanakhir=="sd")
{ $pter_sd=$arr_x[1];
  $pter_sp=$arr_x[0];
  $pter_sa=$arr_x[0];
  $pter_d1=$arr_x[0];
  $pter_d2=$arr_x[0];
  $pter_d3=$arr_x[0];
  $pter_s1=$arr_x[0];
  $pter_s2=$arr_x[0];
  $pter_la=$arr_x[0];
}
else if ($pendidikanakhir=="smp")
{ $pter_sd=$arr_x[0];
  $pter_sp=$arr_x[1];
  $pter_sa=$arr_x[0];
  $pter_d1=$arr_x[0];
  $pter_d2=$arr_x[0];
  $pter_d3=$arr_x[0];
  $pter_s1=$arr_x[0];
  $pter_s2=$arr_x[0];
  $pter_la=$arr_x[0];
}
else if ($pendidikanakhir=="sma")
{ $pter_sd=$arr_x[0];
  $pter_sp=$arr_x[0];
  $pter_sa=$arr_x[1];
  $pter_d1=$arr_x[0];
  $pter_d2=$arr_x[0];
  $pter_d3=$arr_x[0];
  $pter_s1=$arr_x[0];
  $pter_s2=$arr_x[0];
  $pter_la=$arr_x[0];
}
else if ($pendidikanakhir=="d1")
{ $pter_sd=$arr_x[0];
  $pter_sp=$arr_x[0];
  $pter_sa=$arr_x[0];
  $pter_d1=$arr_x[1];
  $pter_d2=$arr_x[0];
  $pter_d3=$arr_x[0];
  $pter_s1=$arr_x[0];
  $pter_s2=$arr_x[0];
  $pter_la=$arr_x[0];
}
else if ($pendidikanakhir=="d2")
{ $pter_sd=$arr_x[0];
  $pter_sp=$arr_x[0];
  $pter_sa=$arr_x[0];
  $pter_d1=$arr_x[0];
  $pter_d2=$arr_x[1];
  $pter_d3=$arr_x[0];
  $pter_s1=$arr_x[0];
  $pter_s2=$arr_x[0];
  $pter_la=$arr_x[0];
}
else if ($pendidikanakhir=="d3")
{ $pter_sd=$arr_x[0];
  $pter_sp=$arr_x[0];
  $pter_sa=$arr_x[0];
  $pter_d1=$arr_x[0];
  $pter_d2=$arr_x[0];
  $pter_d3=$arr_x[1];
  $pter_s1=$arr_x[0];
  $pter_s2=$arr_x[0];
  $pter_la=$arr_x[0];
}
else if ($pendidikanakhir=="s1")
{ $pter_sd=$arr_x[0];
  $pter_sp=$arr_x[0];
  $pter_sa=$arr_x[0];
  $pter_d1=$arr_x[0];
  $pter_d2=$arr_x[0];
  $pter_d3=$arr_x[0];
  $pter_s1=$arr_x[1];
  $pter_s2=$arr_x[0];
  $pter_la=$arr_x[0];
}
else if ($pendidikanakhir=="s2")
{ $pter_sd=$arr_x[0];
  $pter_sp=$arr_x[0];
  $pter_sa=$arr_x[0];
  $pter_d1=$arr_x[0];
  $pter_d2=$arr_x[0];
  $pter_d3=$arr_x[0];
  $pter_s1=$arr_x[0];
  $pter_s2=$arr_x[1];
  $pter_la=$arr_x[0];
}
else
{ $pter_sd=$arr_x[0];
  $pter_sp=$arr_x[0];
  $pter_sa=$arr_x[0];
  $pter_d1=$arr_x[0];
  $pter_d2=$arr_x[0];
  $pter_d3=$arr_x[0];
  $pter_s1=$arr_x[0];
  $pter_s2=$arr_x[0];
  $pter_la=$arr_x[0];
}
$pengalamankerja=$rs['pengalamankerja'];
$lamakerja_dlmthn=$rs['lamakerja_dlmthn'];
if ($pengalamankerja=="ya")
{ $pke1=$arr_x[1]; $pke2=$arr_x[0];}
else
{ $pke1=$arr_x[0]; $pke2=$arr_x[1];}
$jurusan=$rs['jurusan'];
$uraian_tugas=explode(",",$rs['uraian_tugas']);
$jml_urai=count($uraian_tugas);
if($jml_urai>=1) {$txturai1=$uraian_tugas[0];} else {$txturai1="";}
if($jml_urai>=2) {$txturai2=$uraian_tugas[1];} else {$txturai2="";}
if($jml_urai>=3) {$txturai3=$uraian_tugas[2];} else {$txturai3="";}
if($jml_urai>=4) {$txturai4=$uraian_tugas[3];} else {$txturai4="";}
if($jml_urai>=5) {$txturai5=$uraian_tugas[4];} else {$txturai5="";}
if($jml_urai>=6) {$txturai6=$uraian_tugas[5];} else {$txturai6="";}
if($jml_urai>=7) {$txturai7=$uraian_tugas[6];} else {$txturai7="";}
if($jml_urai>=8) {$txturai8=$uraian_tugas[7];} else {$txturai8="";}
if($jml_urai>=9) {$txturai9=$uraian_tugas[8];} else {$txturai9="";}
if($jml_urai>=10) {$txturai10=$uraian_tugas[9];} else {$txturai10="";}
$gaji=$rs['gaji'];
$fasilitas=$rs['fasilitas'];
$waktu_kontrak=$rs['waktu_kontrak'];
$keteranganlain=$rs['keteranganlain'];
$setuju1=$rs['setuju1'];
if ($rs['setuju1_app']=="W")
{ $setuju1_app="Waiting"; }
else
{ $setuju1_app="Approved ".fd_view_dt($rs['setuju1_date']);}
$setuju2=$rs['setuju2'];
if ($rs['setuju2_app']=="W")
{ $setuju2_app="Waiting"; }
else
{ $setuju2_app="Approved ".fd_view_dt($rs['setuju2_date']);}
$setuju3=$rs['setuju3'];
if ($rs['setuju3_app']=="W")
{ $setuju3_app="Waiting"; }
else
{ $setuju3_app="Approved ".fd_view_dt($rs['setuju3_date']);}
$ketahui=$rs['ketahui'];
if ($rs['ketahui_app']=="W")
{ $ketahui_app="Waiting"; }
else
{ $ketahui_app="Approved ".fd_view_dt($rs['ketahui_date']);}

$tbl_header='
  <table style="width:100%;border-collapse: collapse;">
    <tr>
      <td style="width:20%;'.$styleborder.'"><img src='.$images.' style="heigh:70px; width:80px;"></td>
      <td style="width:50%;'.$styleborder.'text-align:center;">Form Permintaan <br>Tenaga Kerja</td>
      <td style="width:30%;'.$styleborder.'">
        <table style="width:100%;font-size:10px;">
          <tr>
            <td>Kode Dok</td>
            <td>F.16.HR.NAG.P-01.F-01.01</td>
          </tr>
          <tr>
            <td>Revisi</td>
            <td>01</td>
          </tr>
          <tr>
            <td>Tanggal Revisi</td>
            <td>22 Dec 2017</td>
          </tr>
          <tr>
            <td>Tanggal Berlaku</td>
            <td>31 Jan 2018</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>';
  $tbl_yesno='
    <table style="width:100%;font-size:10px;border-collapse: collapse;">
    <tr>
      <td width="30px" '.$style4.'>1</td>
      <td colspan="2" width="100px" '.$style5.'>Status Permintaan</td>
      <td colspan="4" height="30px" '.$style5.'>
      '.$status1.'Permintaan Baru&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      '.$status2.'Penggantian</td>
    </tr>
    <tr>
      <td '.$style4.'>2</td>
      <td colspan="2" '.$style5.'>Tanggal Pengajuan</td>
      <td colspan="4" height="30px" '.$style5.'>:'.fd_view($do_tglaju).'</td>
    </tr>
      <tr>
        <td rowspan="5" '.$style4.'>3</td>
        <td colspan="6" height="30px" '.$style5.'>Diajukan Oleh</td>
        <td></td>
      </tr>
      <tr>
        <td width="60px" rowspan="4" '.$style4.'>&nbsp;</td>
        <td width="200px" '.$style5.'>Nama</td>
        <td colspan="4" height="30px" '.$style5.'>:'.$do_nama.'</td>
      </tr>
      <tr>
        <td '.$style5.'>NIK</td>
        <td colspan="4"  height="30px" '.$style5.'>:'.$do_nik.'</td>
      </tr>
      <tr>
        <td '.$style5.'>Departement</td>
        <td colspan="4" height="30px" '.$style5.'>:'.$do_department.'</td>
      </tr>
      <tr>
        <td '.$style5.'>Bagian</td>
        <td colspan="4" height="30px" '.$style5.'>:'.$do_bagian.'</td>
      </tr>
      <tr>
        <td rowspan="5" '.$style4.'>4</td>
        <td colspan="6" height="30px" '.$style5.'>Rencana Kebutuhan</td>
      </tr>
      <tr>
        <td rowspan="4" '.$style4.'>&nbsp;</td>
        <td '.$style5.'>Departement</td>
        <td colspan="4" height="30px" '.$style5.'>:'.$rk_department.'</td>
      </tr>
      <tr>
        <td '.$style5.'>Bagian</td>
        <td colspan="4" height="30px" '.$style5.'>:'.$rk_bagian.'</td>
      </tr>
      <tr>
        <td '.$style5.'>Tanggal</td>
        <td colspan="4" height="30px" '.$style5.'>:'.fd_view($rk_tanggal).'</td>
      </tr>
      <tr>
        <td '.$style5.'>Jumlah</td>
        <td colspan="4" height="30px" '.$style5.'>:'.$rk_jumlah.'</td>
      </tr>
      <tr>
        <td '.$style4.'>5</td>
        <td colspan="2" '.$style5.'>Rencana Jabatan</td>
        <td colspan="4" height="30px" '.$style4L.'>
          '.$rjm.'Manager &nbsp;&nbsp;&nbsp;&nbsp;
          '.$rjc.'Chief &nbsp;&nbsp;&nbsp;&nbsp;
          '.$rjs.'SPV &nbsp;&nbsp;&nbsp;&nbsp;
          '.$rjl.'Leader &nbsp;&nbsp;&nbsp;&nbsp;
          '.$rjo.'Operator &nbsp;&nbsp;&nbsp;&nbsp;
        </td>
      </tr>
      <tr>
        <td '.$style4.'>6</td>
        <td colspan="2" '.$style5.'>Pendidikan Minimal &<br>Jurusan</td>
        <td colspan="4" height="70px" '.$style5.'>
          <table>
            <tr>
              <td width="180px">'.$pter_sd.' Sekolah Dasar</td>
              <td width="100px">'.$pter_d1.' Diploma 1</td>
              <td>'.$pter_s1.' Strata 1</td>
            </tr>
            <tr>
              <td>'.$pter_sp.' Sekolah Lanjutan Tingkat Pertama</td>
              <td>'.$pter_d2.' Diploma 2</td>
              <td>'.$pter_s2.' Strata 2</td>
            </tr>
            <tr>
              <td>'.$pter_sa.' Sekolah Menengah Atas</td>
              <td>'.$pter_d3.' Diploma 3</td>
              <td>'.$pter_la.' Lain</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td '.$style4.'>7</td>
        <td colspan="2" '.$style5.'>Pengalaman Kerja</td>
        <td colspan="4" height="40px" '.$style5.'>
          '.$pke1.'Ya
          Waktu Pengalaman&nbsp;'.$lamakerja_dlmthn.'&nbsp;Tahun&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          '.$pke2.'Tidak
        </td>
      </tr>
      <tr>
        <td '.$style4.'>8</td>
        <td colspan="2" '.$style5.'>Uraian Tugas Secara<br>Umum</td>
        <td height="140px" width="400px" colspan="4" '.$style4L.'>
        1  '.$txturai1.'<br>
        2  '.$txturai2.'<br>
        3  '.$txturai3.'<br>
        4  '.$txturai4.'<br>
        5  '.$txturai5.'<br>
        6  '.$txturai6.'<br>
        7  '.$txturai7.'<br>
        8  '.$txturai8.'<br>
        9  '.$txturai9.'<br>
        10 '.$txturai10.'<br>
        </td>
      </tr>
      <tr>
        <td '.$style4.'>9</td>
        <td colspan="2" '.$style5.'>Rencana Gaji & Fasilitas</td>
        <td colspan="4" height="30px" '.$style5.'>
        <br>Golongan / Besaran Gaji : '.$gaji.'<br><br>
        Fasilitas               : '.$fasilitas.'<br><br><br></td>
      </tr>
      <tr>
        <td '.$style4.'>10</td>
        <td colspan="2" '.$style5.'>Jangka Waktu Kontrak</td>
        <td colspan="4" height="30px" '.$style4L.'>'.$waktu_kontrak.'</td>
      </tr>
      <tr>
        <td '.$style4.'>11</td>
        <td colspan="2" '.$style5.'>Keterangan Lainnya<br>(Jumlah, Keterampilan<br>Tambahan,dll)</td>
        <td colspan="4" '.$style4L.'>'.$keteranganlain.'</td>
      </tr>
      </table>';
    $tbl_bawah='
    <table style="width:100%;font-size:10px;border-collapse: collapse;">

    <tr>
        <td width="160px" '.$style4.'>Diajukan</td>
        <td width="160px" '.$style4.'>Disetujui</td>
        <td width="160px" '.$style4.'>Diketahui</td>
        <td width="160px" '.$style4.'>Disetujui</td>
        <td width="160px" '.$style4.'>Disetujui</td>
    </tr>
    <tr>
        <td rowspan="2" height="900px" '.$style5.' >----</td>
        <td rowspan="2" width="160px" '.$style5.'>----</td>
        <td rowspan="2" width="160px" '.$style5.'>----</td>
        <td rowspan="2" width="160px" '.$style5.'>----</td>
        <td rowspan="2" width="160px" '.$style5.'>----</td>
    </tr>
    <tr>
        <td '.$style5.'></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td height="30px" width="160px" '.$style5.'>&nbsp;</td>
        <td width="160px" '.$style4.'>Chief/Dept.Head/Manager</td>
        <td width="160px" '.$style4.'>HR-GA</td>
        <td width="160px" '.$style4.'>General Mgr Produksi</td>
        <td width="160px" '.$style4.'>General Mgr Factory</td>
    </tr>
    <tr>
        <td height="20px" width="160px" '.$style5.'>&nbsp;</td>
        <td width="160px" '.$style5.'>'
        .$setuju1.
        '&nbsp;<br>'
        .$setuju1_app.'</td>
        <td width="160px" '.$style5.'>'
        .$setuju2.
        '&nbsp;<br>'
        .$setuju2_app.'</td>
        <td width="160px" '.$style5.'>'
        .$setuju3.
        '&nbsp;<br>'
        .$setuju3_app.'</td>
        <td width="160px" '.$style5.'>'
        .$ketahui.
        '&nbsp;<br>'
        .$ketahui_app.'</td>
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
  <br>
  '.$tbl_yesno.'
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

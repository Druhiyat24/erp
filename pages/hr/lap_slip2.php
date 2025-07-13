<?php
include '../../include/conn.php';
include '../forms/fungsi.php';

ob_start();

$tglcetak="Dicetak : ".date('Y-m-d H:i'); 
$ukuran_kertas="A4"; 
$orientasi_kertas = "P"; # L = Landscape P = Portrait
$paydate=fd($_POST['txtfrom']);
if (isset($_POST['txtdept'])) {$dept=$_POST['txtdept'];} else {$dept="";}
if (isset($_POST['txtline'])) {$line=$_POST['txtline'];} else {$line="";}
#$paydate="2019-01-01";
$sql="select a.*,s.nama,s.department,s.bagian,0 tmk from 
  hr_salaryrecord a inner join hr_masteremployee s on a.nik=s.nik
  where paydate='$paydate' and department='$dept' and line='$line' order by a.nik";
$result=mysql_query($sql);
$trec=1;
while($rs = mysql_fetch_array($result))
{ $periode=fd_view($rs['mulai'])." s/d ".fd_view($rs['selesai']);
  $tot_in=$rs['gaji_pokok'] + $rs['overtime'];
  $tot_pot=$rs['bpjs_tk'] + $rs['bpjs_kes'] + $rs['bpjs_pen'];
  $sub_tot=$tot_in - $tot_pot;
  if ($trec==1) 
  { echo '<table>'; 
      echo '<tr>'; 
  }
  echo '
  <td width="30%">
    <table border="1" cellspacing="0" width="100%" 
      style="border-collapse: collapse; width:100%; font-size:10px;">
      <tr>
        <td>Periode</td>
        <td>'.$periode.'</td>
      </tr>
      <tr>
        <td>Nama</td>
        <td>'.$rs['nama'].'</td>
      </tr>
      <tr>
        <td>Bagian</td>
        <td>'.$rs['bagian'].'</td>
      </tr>
      <tr>
        <td>NIK</td>
        <td>'.$rs['nik'].'</td>
      </tr>
      <tr>
        <td>Gaji Pokok</td>
        <td align="right">'.fn($rs['gaji_pokok'],2).'</td>
      </tr>
      <tr>
        <td>Tunj. Masa Kerja</td>
        <td align="right">'.fn($rs['tmk'],2).'</td>
      </tr>
      <tr>
        <td>Tunj. Hasil Kerja</td>
        <td align="right">'.fn($rs['tmk'],2).'</td>
      </tr>
      <tr>
        <td>Insentif Absensi</td>
        <td align="right">'.fn($rs['tmk'],2).'</td>
      </tr>
      <tr>
        <td>Lembur 1</td>
        <td align="right">'.fn($rs['overtime'],2).'</td>
      </tr>
      <tr>
        <td>Lembur 2</td>
        <td align="right">'.fn($rs['tmk'],2).'</td>
      </tr>
      <tr>
        <td>BPJS Tenaga Kerja</td>
        <td align="right">'.fn($rs['bpjs_tk'],2).'</td> 
      </tr>
      <tr>
        <td>BPJS Pensiun</td>
        <td align="right">'.fn($rs['bpjs_pen'],2).'</td> 
      </tr>
      <tr>
        <td>BPJS Kesehatan</td>
        <td align="right">'.fn($rs['bpjs_kes'],2).'</td>
      </tr>
      <tr>
        <td>Absensi Ijin</td>
        <td align="right">'.fn($rs['tmk'],2).'</td>
      </tr>
      <tr>
        <td>Potongan PPh</td>
        <td align="right">'.fn($rs['tmk'],2).'</td>
      </tr>
      <tr>
        <td>Potongan Lain-Lain</td>
        <td align="right">'.fn($rs['deduction'],2).'</td>
      </tr>
      <tr>
        <td>Sub Total</td>
        <td align="right">'.fn($sub_tot,2).'</td>
      </tr>
      <tr>
        <td>Koreksi Upah</td>
        <td align="right">'.fn($rs['tmk'],2).'</td>
      </tr>
      <tr>
        <td>Total</td>
        <td align="right">'.fn($sub_tot,2).'</td>
      </tr>
      <tr>
        <td>Dibuat Oleh</td>
        <td>Diterima Oleh</td>
      </tr>
    </table>
  </td>';
  $trec++;
  if ($trec==4) { echo "</td></tr></table><br>"; $trec=1; }
}
echo "</td></tr></table>";
?>
<?php
include('../../mpdf57/mpdf.php');
$header = '';
$content = ob_get_clean();
$footer =  $footernya;           
try {
    # $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan, Space_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);
    $mpdf=new mPDF('utf-8', $ukuran_kertas, 9 ,'Arial', 5, 5, 5, 5, 5, 1, $orientasi_kertas);
    $mpdf->SetTitle("Laporan");
    $mpdf->setHTMLHeader($header);
    $mpdf->setHTMLFooter($footer);
    $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
    $mpdf->WriteHTML($content);
    $mpdf->Output("laporan.pdf","I");
} catch(Exception $e) {
    echo $e;
    exit;
}
?>
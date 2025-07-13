<?php

include('../../mpdf57/mpdf.php');

include '../../include/conn.php';

require_once '../wh/phpqrcode-master/qrlib.php';

include 'fungsi.php';

ob_start();

$penyimpanan = "temp_rak/";
if (!file_exists($penyimpanan))
  mkdir($penyimpanan);


$id = $_GET['id'];


//paging--------------------------

// if ($jdata>=1 and $jdata<8)

// { $space_head=52; }

// else

// { $space_head=55; }
//end paging------------------------------

$space_head = 0;

$ukuran_kertas = "A4";

$orientasi_kertas = "L"; # L = Landscape P = Portrait

# $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan,  pace_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);
$mpdf = new mPDF('utf-8', 'A4-L', 0, 'Times New Roman', 50, 50, 2, 5, 5, 5, 'L');

?>

    <?php
    $sqlquedet = "select * from master_rak where id = '$id'  ";

    $qdet = mysql_query($sqlquedet);
    $ix = 1;
    while ($rshder = mysql_fetch_array($qdet)) {
      $nama_rak = $rshder["nama_rak"];
      $kode_rak = $rshder["kode_rak"];

      QRcode::png("http://10.10.5.62:8080/generateQr/index.php?rak=$kode_rak", $penyimpanan  . $kode_rak . ".png", QR_ECLEVEL_L, 1, 1);
      // QRcode::png($kode, $penyimpanan  . $simpan_ . ".png", QR_ECLEVEL_L, 1, 1);


      $head_data = '
      <table class="head"  border="1" cellspacing="0" 
      style="border-collapse: collapse; width:100%; font-size: 52px;text-align: center;">    
<tr>
      <td><b>' . $nama_rak . '</b></td>
</tr>
<tr>
      <td><b><img src ="' . $penyimpanan  . $kode_rak . '.png" alt = "" height="300" width = "300" ></b></td>
</tr>
<tr>
<td><b>' . $kode_rak . '</b></td>
</tr>

          
</table>';
      // <td>'.$rshder['id_item'].''.$product_code_40.'</td>  
      $header = $head_data;
      $mpdf->setHTMLHeader($header);
      $mpdf->AddPage();
      $ix++;
    }


    ?>

<?php


try {
  $content = ob_get_clean();



  // $mpdf->WriteHTML($content);
  // $mpdf->WriteHTML($footer);


  $mpdf->Output("BarcodeQR.pdf", "I");
} catch (Exception $e) {

  echo $e;
  exit;
}


?>
<?php

include('../../mpdf57/mpdf.php');

include '../../include/conn.php';

require_once '../prod_new/phpqrcode-master/qrlib.php';

include 'fungsi.php';

ob_start();

$id_stocker = $_GET['id_stocker'];

$penyimpanan = "temp_stocker/";
if (!file_exists($penyimpanan))
  mkdir($penyimpanan);

$space_head = 35;

$ukuran_kertas = "A4";

$orientasi_kertas = "P"; # L = Landscape P = Portrait

# $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan,  pace_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);
$mpdf = new mPDF('utf-8', array(70, 100), 5, 'Times New Roman', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);

?>

    <?php

    $sqlquedet = "select a.*, replace(concat(a.no_cut,'_',a.size,'_',a.id),'/','_') simpan, mb.supplier buyer, so.buyerno,
    ac.brand, ac.styleno,ac.deldate,sd.reff_no,ac.main_dest from stocker a
    inner join so_det sd on a.id_so_det =  sd.id 
    inner join so on sd.id_so = so.id
    inner join act_costing ac on so.id_cost = ac.id
    inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
    where a.id = '$id_stocker'";
    $qdet = mysql_query($sqlquedet);
    $ix = 1;
    while ($rshder = mysql_fetch_array($qdet)) {
      $simpan =  $rshder["simpan"];

      QRcode::png($simpan, $penyimpanan  . $simpan . ".png", QR_ECLEVEL_L, 1, 1);
      $qrcode = '<img src ="' . $penyimpanan  . $simpan . '.png" alt = "" height="50" width = "50" >';
      $head_data = ' 
      <table class="main" border="1" width="100%" 
      style="border-collapse: collapse; width:100%;font-weight:bold">
   <tr>
      <td align="center" rowspan = "3">' . $qrcode  . '</td>
      <td align="left" style="border-bottom:0" colspan = "2">Bundle Qty : ' . $rshder['qty_cut'] . ' </td>				
   </tr>  
   <tr>
      <td align="left" style="border-top:0;border-bottom:0" colspan = "2">Size : ' . $rshder['size'] . '</td>
   </tr>
   <tr>
      <td align="left" style="border-top:0" colspan = "2">Range : ' . $rshder['range_awal'] . ' - ' . $rshder['range_akhir'] . ' </td>
   </tr>
   <tr>
      <td height="15" colspan = "3" align="center">Deskripsi Item</td>
   </tr>  
   <tr>
      <td align="left" style="border-right:0;border-bottom:0" >Nomor Stocker</td>
      <td align="left" style="border-left:0;border-right:0;border-bottom:0;width:2%">:</td>
      <td align="left" style="border-left:0;border-bottom:0">' . $rshder['no_cut'] . '</td>
   </tr>      
   <tr>
      <td align="left" style="border-right:0;border-bottom:0">Worksheet</td>
      <td align="left" style="border-left:0;border-right:0;border-bottom:0;width:2%">:</td>
      <td align="left" style="border-left:0;border-bottom:0">' . $rshder['kpno'] . '</td>
   </tr>  
   <tr>
      <td align="left" style="border-right:0;border-bottom:0">Buyer</td>
      <td align="left" style="border-left:0;border-right:0;border-bottom:0;width:2%">:</td>
      <td align="left" style="border-left:0;border-bottom:0">' . $rshder['buyer'] . '</td>
   </tr>
   <tr>
      <td align="left" style="border-right:0;border-bottom:0">Buyer PO</td>
      <td align="left" style="border-left:0;border-right:0;border-bottom:0;width:2%">:</td>
      <td align="left" style="border-left:0;border-bottom:0">' . $rshder['buyerno'] . '</td>
   </tr> 
   <tr>
      <td align="left" style="border-right:0;border-bottom:0">Brand</td>
      <td align="left" style="border-left:0;border-right:0;border-bottom:0;width:2%">:</td>
      <td align="left" style="border-left:0;border-bottom:0">' . $rshder['brand'] . '</td>
   </tr> 
   <tr>
      <td align="left" style="border-right:0;border-bottom:0">Style</td>
      <td align="left" style="border-left:0;border-right:0;border-bottom:0;width:2%">:</td>
      <td align="left" style="border-left:0;border-bottom:0">' . $rshder['styleno'] . '</td>
   </tr>           
   <tr>
      <td align="left" style="border-right:0;border-bottom:0">Color</td>
      <td align="left" style="border-left:0;border-right:0;border-bottom:0;width:2%">:</td>
      <td align="left" style="border-left:0;border-bottom:0">' . $rshder['color'] . '</td>
   </tr> 
   <tr>
      <td align="left" style="border-right:0;border-bottom:0">Shade</td>
      <td align="left" style="border-left:0;border-right:0;border-bottom:0;width:2%">:</td>
      <td align="left" style="border-left:0;border-bottom:0">' . $rshder['shade'] . '</td>
   </tr> 
   <tr>
      <td align="left" style="border-right:0;border-bottom:0">Delivery Date</td>
      <td align="left" style="border-left:0;border-right:0;border-bottom:0;width:2%">:</td>
      <td align="left" style="border-left:0;border-bottom:0">' . $rshder['deldate'] . '</td>
   </tr>  
   <tr>
      <td align="left" style="border-right:0;border-bottom:0">Reff Order</td>
      <td align="left" style="border-left:0;border-right:0;border-bottom:0;width:2%">:</td>
      <td align="left" style="border-left:0;border-bottom:0">' . $rshder['reff_no'] . '</td>
   </tr>   
   <tr>
      <td align="left" style="border-right:0;">Country</td>
      <td align="left" style="border-left:0;border-right:0;width:2%">:</td>
      <td align="left" style="border-left:0;">' . $rshder['main_dest'] . '</td>
   </tr>                                                    
 </table>

          ';
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


  $mpdf->Output("StockerQr.pdf", "I");
} catch (Exception $e) {

  echo $e;
  exit;
}


?>
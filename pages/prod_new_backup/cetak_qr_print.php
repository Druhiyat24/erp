<?php

include('../../mpdf57/mpdf.php');

include '../../include/conn.php';

require_once '../prod_new/phpqrcode-master/qrlib.php';

include 'fungsi.php';

ob_start();

$penyimpanan = "temp/";
if (!file_exists($penyimpanan))
  mkdir($penyimpanan);


$id_ac = $_POST['cbows'];
$cbocolor = $_POST['cbocolor'];
$cbosize = $_POST['cbosize'];
$cutnumber = $_POST['txtcutnumber'];
$cutno = sprintf("%02s", $cutnumber);
$dari = $_POST['txtdari'];
$dari_ = $dari - 1;
$sampai = $_POST['txtsampai'];
$urutan = ($sampai - $dari) + 1;
// $from = sprintf("%04s", $dari);
// $cut_kode = $cbosize."".$cutnumber."".$from;



//paging--------------------------

// if ($jdata>=1 and $jdata<8)

// { $space_head=52; }

// else

// { $space_head=55; }
//end paging------------------------------

$space_head = 0;

$ukuran_kertas = "A4";

$orientasi_kertas = "P"; # L = Landscape P = Portrait

# $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan,  pace_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);
$mpdf = new mPDF('utf-8', array(20, 12), 0, 'Times New Roman', 0, 0, $space_head, 0, 0, 0, $orientasi_kertas);

?>

    <?php
    $sqlquedet = "select sd.id id_so_det,ac.brand,ac.kpno,
    replace(concat(ac.kpno,'_',substr(sd.color,1,3),'_',substr(sd.size,1,3),'_'),'/','_')kpno_fix, 
    sd.color, size, ac.main_dest,ac.styleno 
    from so_det sd 
    inner join so on sd.id_so = so.id
    inner join act_costing ac on so.id_cost = ac.id
    where ac.id = '$id_ac' and sd.color = '$cbocolor' and sd.size = '$cbosize' and sd.cancel = 'N' order by sd.id asc  limit 1 ";

    $qdet = mysql_query($sqlquedet);
    $ix = 1;
    while ($rshder = mysql_fetch_array($qdet)) {
      $from = sprintf("%04s", $dari_);
      $from_ = sprintf("%04s", $dari);
      $dari_++;
      $dari++;
      $kpno_fix = $rshder["kpno_fix"];
      $cut_kode = $cbosize . "" . $cutno . "" . $from;
      $cut_kode_ = $cbosize . "" . $cutno . "" . $from_;
      $simpan = $kpno_fix . "" . $cutno . "" . $from;
      $simpan_ = $kpno_fix . "" . $cutno . "" . $from_;
      $brand = $rshder["brand"];
      $id_so_det =  $rshder["id_so_det"];
      $styleno =  $rshder["styleno"];
      $kpno =  $rshder["kpno"];
      $color =  $rshder["color"];
      $size =  $rshder["size"];
      $main_dest = $rshder["main_dest"];
      if ($main_dest == '') {
        $main_dest_fix = '-';
      } else {
        $main_dest_fix = $main_dest;
      }
      // $kode = $id_so_det . "|" . "\n" . $brand . "\n" . $styleno . "\n" . $kpno . "\n" . $color . "\n" . $size . "\n" . $main_dest_fix;
      $kode = $id_so_det . "|";
      // $kode1 = stripslashes($kode);

      for ($i = $urutan; $i > 0; $i--) {


        QRcode::png($kode, $penyimpanan  . $simpan_ . ".png", QR_ECLEVEL_L, 1, 1);

        $from = sprintf("%04s", $dari_);
        $dari_++;
        $cut_kode = $cbosize . "" . $cutno . "" . $from;
        $simpan = $kpno_fix . "" . $cutno . "" . $from;

        $from_ = sprintf("%04s", $dari);
        $dari++;
        $cut_kode_ = $cbosize . "" . $cutno . "" . $from_;
        $simpan_ = $kpno_fix . "" . $cutno . "" . $from_;

        $head_data = '
      <table class="head"  border="0" cellspacing="0" 
      style="border-collapse: collapse; width:100%; font-size: 8px;">    
<tr>
      <td><b>' . $cut_kode . '</b></td>
      <td rowspan="5">
      <img src ="' . $penyimpanan  . $simpan . '.png" alt = "" height="40" width = "40" ></td>
</tr>
<tr>
      <td><b>' . $rshder['kpno'] . '</b></td>
</tr>
<tr>
      <td><b>' . $rshder['color'] . '</b></td>
</tr>  
<tr>
      <td><b>' . $rshder['size'] . '</b></td>
</tr>
<tr>
      <td><b>' . $main_dest_fix . '</b></td>
</tr>
</tr>            
</table>';
        // <td>'.$rshder['id_item'].''.$product_code_40.'</td>  
        $header = $head_data;
        $mpdf->setHTMLHeader($header);
        $mpdf->AddPage();
        $ix++;
      }
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
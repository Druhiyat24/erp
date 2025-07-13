<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';

//$images = '../../include/LogoNAG.png';
$images = '../../include/img-01.png';
if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}
$sql="select * from tbl_debit where kd_debit='$id'";
$rs=mysql_fetch_array(mysql_query($sql));
$nama_kandidat=$rs['nama_kandidat'];
$tanggal=$rs['tanggal'];
  $no=1;
  $kd_debit =$rs['kd_debit'];
  $no_invoice =$rs['no_invoice'];
  $attn =$rs['attn'];
  $tanggal =$rs['tanggal'];
  $SKU =$rs['SKU'];
  $price =$rs['price'];
  $qty =$rs['qty'];
  $total_amount =$rs['total_amount'];
  $nopo =$rs['nopo'];
  $notes =$rs['notes'];
  $nm_tempat =$rs['nm_tempat'];
  $nm_kantor =$rs['nm_kantor'];
  $nm_bank =$rs['nm_bank'];
  $bank_alamat =$rs['bank_alamat'];
  $country =$rs['country'];
  $city =$rs['city'];
  $act_usd =$rs['act_usd'];
  $swift_code =$rs['swift_code'];
  $nm_buyer =$rs['nm_buyer'];

$style1='style="width:70%;"';
$style2='style="width:1%;"';
$style3='style="width:29%;"';
$style0='style="width:23%;"';
$styleborder="border: 2px solid black;";
$style4='style="text-align:center;'.$styleborder.'"';
$style4L='style="text-align:left;'.$styleborder.'"';
$style5='style="'.$styleborder.'"';

$tbl_header='
<table style="width:100%;border-collapse:collapse;">
    <tr>
      <td style="width:20%;"><img src='.$images.' style="heigh:70px; width:80px;"></td>
      <td style="width:50%;font-size:17px;text-align:center;">PT.NIRWANA ALABARE GARMENT<br><p style="font-size:9px;">JALAN RAYA RANCAEKEK-MAJALAYA NO.289,<br>SOLOKAN JERUK, BANDUNG, INDONESIA</p></td>
      <td style="width:30%;">
        <table style="width:100%;font-size:10px;">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>';
  $tbl_judul='
    <table style="width:100%;font-size:10px;border-collapse: collapse;">
    <tr>
      <td style="text-align:center;text-decoration:underline;font-size:15px;width=30px;">DEBIT NOTE</td>
    </tr>
    </table>';

  $tbl_detail='
    <table style="width:100%;font-size:10px;">
        <tr>
          <td rowspan="2" style="vertical-align:top;text-align:right;text-decoration:underline;font-size:10px;width=30px;">CONSIGNEE :</td>
          <td rowspan="2" style="text-align:left;text-decoration:underline;font-size:10px;width=30px;"><b>'.$nm_buyer.'</b><br>'.$nm_tempat.'</td>

          <td rowspan="2" style="width:80px;">&nbsp;</td>

          <td rowspan="2" style="vertical-align:top;text-decoration:underline;font-size:10px;width=30px;">CONSIGNEE :</td>
          <td style="vertical-align:top;text-align:left;e;font-size:10px;width=30px;">'.$no_invoice.'</td>
        </tr>
        <tr>
          <td style="vertical-align:top;">'.$tanggal.'</td>
        </tr>
         <tr>
          <td style="text-align:right;">ATTN :</td>
          <td style="">'.$attn.'</td>
        </tr>
    </table>';

    $tbl_bawah='
    <table style="width:100%;font-size:10px;border-collapse: collapse;">

    <tr>
        <td style="'.$styleborder.'">NO</td>
        <td style="'.$styleborder.'">DESCRIPTION</td>
        <td style="'.$styleborder.'">PO</td>
        <td style="'.$styleborder.'">SKU</td>
        <td style="'.$styleborder.'">QTY IN PCS</td>
        <td style="'.$styleborder.'">PRICE</td>
        <td style="'.$styleborder.'">TOTAL AMOUNT</td>
    </tr>
    <tr>
    </tr>
    <tr>
      <td style="'.$styleborder.'">'.$no.'</td>
      <td style="'.$styleborder.'">'.$notes.'</td>
      <td style="'.$styleborder.'">'.$nopo.'</td>
      <td style="'.$styleborder.'">'.$SKU.'</td>
      <td style="'.$styleborder.'">'.$qty.'</td>
      <td style="'.$styleborder.'">$ '.$price.'</td>
      <td style="'.$styleborder.'">$ '.$total_amount.'</td>
    </tr>
    <tr>
      <td colspan="6" style="'.$styleborder.'">&nbsp;</td>
      <td style="'.$styleborder.'">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" style="text-align:center;'.$styleborder.'">TOTAL</td>
      <td style="'.$styleborder.'">$ '.$total_amount.'</td>
    </tr>
    <tr>
      <td colspan="6" style="text-align:center;'.$styleborder.'">GRAND TOTAL</td>
      <td style="'.$styleborder.'">$ '.$total_amount.'</td>
    </tr>
    </table>';

    $tbl_ttd='
    <p style="font-size:10px;">Please TT FULL Amount To:</p>
    <table style="width:100%;font-size:10px;">
    <tr>
        <td style="">Name</td>
        <td style="">:</td>
        <td style="">'.$nm_kantor.'</td>
    </tr>
     <tr>
        <td style="">Bank Name</td>
        <td style="">:</td>
        <td style="">'.$nm_bank.'</td>
    </tr>
    <tr>
        <td style="">Bank Street Address</td>
        <td style="">:</td>
        <td style="">'.$bank_alamat.'</td>
    </tr>
    <tr>
        <td style="">Bank City</td>
        <td style="">:</td>
        <td style="">'.$city.'</td>
    </tr>
    <tr>
        <td style="">Bank Country</td>
        <td style="">:</td>
        <td style="">'.$country.'</td>
    </tr>
    <tr>
        <td style="">Bank Account USD</td>
        <td style="">:</td>
        <td style="">'.$act_usd.'</td>
    </tr>
    <tr>
        <td style="">SWIFT code</td>
        <td style="">:</td>
        <td style="">'.$swift_code.'</td>
    </tr>
    </table>
     <table style="width:100%;font-size:10px;">
    <tr>
        <td style="">Please Pay at Full Net Amount</td>
        <td style="width:247px;">&nbsp;</td>
        <td style="">'.$nm_kantor.'</td>
    </tr>
    </table>';
$html = '
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>FORM  DEBIT</title>
</head>
<body style=" padding-left:5%; padding-right:5%;">
  
  '.$tbl_header.'
  <br>
  '.$tbl_judul.'
  <br>
  '.$tbl_detail.'
  <br>
  '.$tbl_bawah.'
  <br>
  <br>

  '.$tbl_ttd.'

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

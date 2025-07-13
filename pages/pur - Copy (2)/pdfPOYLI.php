<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';

$images = '../../include/img-01.png';

if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}
$sql="select a.pono,a.podate,s.fullname nm_user,d.supplier,d.phone telp,d.attn   
  from po_header a inner join userpassword s on a.username=s.username 
  inner join mastersupplier d on a.id_supplier=d.id_supplier  
  where a.id='$id'";
$rsh=mysql_fetch_array(mysql_query($sql));
  $PO_no=$rsh['pono'];
  $podate=fd_view($rsh['podate']);
  $purchas=$rsh['nm_user'];
  $supp=$rsh['supplier'];
  $telp=$rsh['telp'];
  $atn=$rsh['attn'];
$style1='style="width:70%;"';
$style2='style="width:1%;"';
$style3='style="width:29%;"';
$styleborder="border: 1px solid black;";
$style4='style="'.$styleborder.'"';
$tbl_header='
  <table style="width:100%;border-collapse: collapse;">
    <tr>
      <td style="width:20%;text-align:center;'.$styleborder.'"><img src='.$images.' style="heigh:70px; width:80px;"></td>
      <td style="width:50%;'.$styleborder.'text-align:center;">
        <table style="width:100%;font-size:10px;">
          <tr>
            <td style="font-size:13px;text-align:center;">PT YOUNGIL LEATHER INDONESIA</td>
          </tr>
          <tr>
            <td style="font-size:9px;text-align:center;">Jl.Milenium Raya Kawasan Industrial estate<br>Blok E1 No.1 Kecamatan Panongan - Tangerang Banten</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>';
$tbl_detail='
  <table style="width:100%;border-collapse: collapse;">
    <tr>
      <td style="width:40%;'.$styleborder.'">
        <table style="width:100%;font-size:10px;">
          <tr>
            <td>PO No</td>
            <td>: '.$PO_no.'</td>
          </tr>
          <tr>
            <td>Date</td>
            <td>: '.$podate.'</td>
          </tr>
          <tr>
            <td>Purchaser</td>
            <td>: '.$purchas.'</td>
          </tr>
          <tr>
            <td>Supplier Name</td>
            <td>: '.$supp.'</td>
          </tr>
        </table>
      </td>
      <td style="width:30%;'.$styleborder.'">
        <table style="width:100%;font-size:10px;">
          <tr>
            <td>Telp/Fax</td>
            <td>: '.$telp.'</td>
          </tr>
          <tr>
            <td>Attn</td>
            <td>: '.$atn.'</td>
          </tr>
          <tr>
            <td>Payment Term</td>
            <td>: $payment</td>
          </tr>
          <tr>
            <td>ETD</td>
            <td>: $etd</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>';

  $tbl_judul='
  <table style="width:100%;border-collapse: collapse;">
    <tr>
      <td style="width:50%;'.$styleborder.'font-size:12px;text-align:center;">PURCHASE ORDER</td>
    </tr>
  </table>';

$tbl_susunan='
  <table style="width:100%;font-size:10px;">
    <tr>
      <td>Here by we order to you for the following conditions:</td>
    </tr>
  </table>
  <table style="width:100%;font-size:10px;border-collapse: collapse;">
    <tr>
      <td style="width:9%;'.$styleborder.'text-align:center;">Line Id</td>
      <td height="13" style="width:9%;'.$styleborder.'text-align:center;">NO</td>
      <td style="width:20%;'.$styleborder.'text-align:center;">Category</td>
      <td style="width:25%;'.$styleborder.'text-align:center;">Description</td>
      <td style="width:13%;'.$styleborder.'text-align:center;">Quantity</td>
      <td style="width:10%;'.$styleborder.'text-align:center;">UOM</td>
      <td style="width:13%;'.$styleborder.'text-align:center;">Price/Unit</td>
      <td style="width:13%;'.$styleborder.'text-align:center;">Currency</td>
      <td style="width:25%;'.$styleborder.'text-align:center;">Total Amount</td>
      <td style="width:25%;'.$styleborder.'text-align:center;">&nbsp;</td>
      <td style="width:25%;'.$styleborder.'text-align:center;">Remark</td>
    </tr>
    <tr>
      <td align=center '.$style4.' >$Line</td>  
      <td align=center height="30" '.$style4.'>$NO</td>
      <td align=center '.$style4.'>$Catego</td>
      <td align=center '.$style4.'>$Description</td>
      <td align=center '.$style4.'>$Quantity</td>
      <td align=center '.$style4.'>$UOM</td>
      <td align=center '.$style4.'>$Price</td>
      <td align=center '.$style4.'>$Currency</td>
      <td align=center '.$style4.'>$total_amount</td>
      <td align=center '.$style4.'>&nbsp;</td>
      <td align=center '.$style4.'>$remark</td>
    </tr>
    
  </table>';
$tbl_curent='
  <table style="width:60%;font-size:10px;border-collapse: collapse;">
    <tr>
      <td style="width:50%;'.$styleborder.'">
        <table style="width:100%;font-size:10px;">
          <tr>
            <td>The latest purchase date</td>
            <td>: $purchasedate</td>
          </tr>
          <tr>
            <td>Current stock date on today</td>
            <td>: $stockdate</td>
          </tr>
          <tr>
            <td style="width:9%;'.$styleborder.'text-align:center;">1</td>
            <td style="width:40%;'.$styleborder.'text-align:center;">D-ACE 800E</td>
            <td style="width:50%;'.$styleborder.'text-align:center;">$hari</td>
            <td style="width:50%;'.$styleborder.'text-align:center;">HARI</td>
          </tr>
          <tr>
            <td>Consumtion/Day =</td>
          </tr>
          <tr>
            <td style="width:9%;'.$styleborder.'text-align:center;">1</td>
            <td style="width:40%;'.$styleborder.'text-align:center;">D-ACE 800E</td>
            <td style="width:50%;'.$styleborder.'text-align:center;">$hari</td>
            <td style="width:50%;'.$styleborder.'text-align:center;">/HARI</td>
          </tr>
          <tr>
            <td>Order quantity =</td>
          </tr>
          <tr>
            <td style="width:9%;'.$styleborder.'text-align:center;">1</td>
            <td style="width:40%;'.$styleborder.'text-align:center;">D-ACE 800E</td>
            <td style="width:50%;'.$styleborder.'text-align:center;">$kg</td>
            <td style="width:50%;'.$styleborder.'text-align:center;">KG</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>';

$tbl_bahasa='
  <table style="width:100%;font-size:10px;">
    <tr>
      <td>PT. YOUNGIL LEATHER INDONESIA <br>Acknowledge By :</td>
    </tr>
  </table>
  <table style="width:100%;font-size:10px;border-collapse: collapse;">
    <tr>
      <td colspan="4" style="width:10%;'.$styleborder.'text-align:center;">INDONESIA</td>
      <td colspan="4" style="width:10%;'.$styleborder.'text-align:center;">KOREAN</td>
    </tr>
    <tr>
      <td '.$style4.'>P</td>
      <td rowspan="2" align=center '.$style4.'>PIC</td>
      <td rowspan="2" align=center '.$style4.'>Chief</td>
      <td rowspan="2" align=center '.$style4.'>Mgr</td>
      <td rowspan="2" align=center '.$style4.'>Mgr</td>
      <td colspan="2" align=center '.$style4.'>Director</td>
      <td rowspan="2" align=center '.$style4.'>M.Dir</td>
    </tr>
    <tr>
      <td '.$style4.'>P</td>
      <td align=center '.$style4.'>MKT</td>
      <td align=center '.$style4.'>OPR</td>
    </tr>
    <tr>
      <td '.$style4.'>R</td>
      <td rowspan="4" '.$style4.'></td>
      <td rowspan="4" '.$style4.'></td>
      <td rowspan="4" '.$style4.'></td>
      <td rowspan="4" '.$style4.'></td>
      <td rowspan="4" '.$style4.'></td>
      <td rowspan="4" '.$style4.'></td>
      <td rowspan="4" '.$style4.'></td>
    </tr>
    <tr>
      <td '.$style4.'>O</td>
    </tr>
    <tr>
      <td '.$style4.'>V</td>
    </tr>
  </table>';
$tbl_perkata='
<table style="width:100%;font-size:10px;">
  <tr>
    <td>Notes/Catatan :</td>
  </tr>
  <tr>
    <td>1. PLEASE WRITE PURCHASE ORDER NUMBER ON SHIPPING DOCS OF GOODS/SURAT JALAN<br>
    <b>(Mohon di cantumkan No Order di Surat Jalan)</b></td>
  </tr>
  <tr>
    <td>2. P/l MUST BE SENT TO US WITHIN 2 WORKING DAYS AFTER RECEIVING THIS ORDER<br>
    <b>(Performa Invoice harus di kirim selambat-lambatnya dua hari setelah menerima order ini)</b></td>
  </tr>
  <tr>
    <td>2. P/l MUST BE SENT TO US WITHIN 2 WORKING DAYS AFTER RECEIVING THIS ORDER<br>
    <b>(Performa Invoice harus di kirim selambat-lambatnya dua hari setelah menerima order ini)</b></td>
  </tr>
  <tr>
    <td>3. INVOICES SHOULD BE SENT WITH THREE SHEETS<br>
    <b>(Invoice Asli harus di kirim tiga lembar)</b></td>
  </tr>
  <tr>
    <td>4. FAKTUR PAJAK SHOULD BE SENT TOGETHER WITH INVOICES<br>
    <b>(Faktur Pajak Harus di kirim bersamaan dengan Invoice)</b></td>
  </tr>
  <tr>
    <td>5. PER/ROLL MAKSIMAL 35KG TIDAK BOLEH LEBIH</td>
  </tr>
</table>';

$html = '
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PO</title>
</head>
<body style=" padding-left:5%; padding-right:5%;">
  '.$tbl_header.'
  <br>
  '.$tbl_detail.'
  <br>
  '.$tbl_judul.'
  <br>
  '.$tbl_susunan.'
  <br>
  '.$tbl_curent.'
  <br>
  '.$tbl_bahasa.'
  <br>
  '.$tbl_perkata.' 
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

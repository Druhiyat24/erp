<!DOCTYPE html>
<?php
session_start();
include '../../conn/conn.php';
$user = $_SESSION['username'];
$images = '../../images/img-01.png';
$id_inv =$_GET['id_inv'];
?>

<?php
$sql= "select * from (select styleno, product_group, product_item, color, size, qty, format(round(unit_price,3),3) unit_price, disc, FORMAT(total_price, 2) AS total_price, uom, curr, id_bppb
                                   FROM sb_invoice_detail
                                   WHERE id_book_invoice = '$id_inv' ORDER BY id_bppb asc ) a
                                   UNION
                                   select * from (select styleno, product_group, product_item, color, size, qty, format(round(unit_price,3),3) unit_price, disc, FORMAT(total_price, 2) AS total_price, uom, curr, id_bppb
                                   FROM tbl_invoice_detail
                                   WHERE id_book_invoice = '$id_inv' ORDER BY id_bppb asc ) a";

 $sql2= "select * from (select distinct a.no_invoice, LEFT(b.Supplier, 30) AS customer,
                                          LEFT(IFNULL(b.alamat, '-'), 27) AS alamat, IFNULL(b.Phone, '-') AS phone, IFNULL(b.Email, '-') AS email,
                                          DATE_FORMAT(f.sj_date,'%d-%m-%Y') AS tgl_inv, UPPER(c.type) AS type, a.shipp, d.type AS type_top, d.top,
                                          e.no_rek, e.nama_bank, e.v_bankaddress, e.curr
                                   FROM sb_book_invoice AS a INNER JOIN 
                                          mastersupplier AS b ON a.id_customer = b.Id_Supplier INNER JOIN
                                          tbl_type AS c ON a.id_type = c.id_type INNER JOIN 
                                          tbl_master_top AS d ON a.id_top = d.id  INNER JOIN 
                                          masterbank AS e ON a.id_bank = e.id  INNER join
                                          sb_invoice_detail as f on a.id=f.id_book_invoice
                                   WHERE a.id = '$id_inv') a
                                   UNION
                                   select * from (select distinct a.no_invoice, LEFT(b.Supplier, 30) AS customer,
                                          LEFT(IFNULL(b.alamat, '-'), 27) AS alamat, IFNULL(b.Phone, '-') AS phone, IFNULL(b.Email, '-') AS email,
                                          DATE_FORMAT(f.sj_date,'%d-%m-%Y') AS tgl_inv, UPPER(c.type) AS type, a.shipp, d.type AS type_top, d.top,
                                          e.no_rek, e.nama_bank, e.v_bankaddress, e.curr
                                   FROM tbl_book_invoice AS a INNER JOIN 
                                          mastersupplier AS b ON a.id_customer = b.Id_Supplier INNER JOIN
                                          tbl_type AS c ON a.id_type = c.id_type INNER JOIN 
                                          tbl_master_top AS d ON a.id_top = d.id  INNER JOIN 
                                          masterbank AS e ON a.id_bank = e.id  INNER join
                                          tbl_invoice_detail as f on a.id=f.id_book_invoice
                                   WHERE a.id = '$id_inv') a";

$rs=mysqli_fetch_array(mysqli_query($conn2,$sql2));

$sql_bppb= "select * from (select DISTINCT shipp_number as bppb_number
                                   FROM sb_invoice_detail
                                   WHERE id_book_invoice = '$id_inv') a
                                   UNION
                                   select * from (select DISTINCT shipp_number as bppb_number
                                   FROM tbl_invoice_detail
                                   WHERE id_book_invoice = '$id_inv') a";

$sql_so= "select * from (select DISTINCT so_number
                                   FROM sb_invoice_detail
                                   WHERE id_book_invoice = '$id_inv') a
                                   UNION
                                   select * from (select DISTINCT so_number
                                   FROM tbl_invoice_detail
                                   WHERE id_book_invoice = '$id_inv') a";

$sql3= "select * from (select id, id_book_invoice, FORMAT(total, 2) AS total, FORMAT(discount, 2) AS discount, FORMAT(dp, 2) AS dp, 
                                          FORMAT(retur, 2) AS retur, FORMAT(twot, 2) AS twot, FORMAT(vat, 2) AS vat, FORMAT(grand_total, 2) AS grand_total 
                                   FROM sb_invoice_pot 
                                   WHERE id_book_invoice = '$id_inv') a
                                   UNION
                                   select * from (select id, id_book_invoice, FORMAT(total, 2) AS total, FORMAT(discount, 2) AS discount, FORMAT(dp, 2) AS dp, 
                                          FORMAT(retur, 2) AS retur, FORMAT(twot, 2) AS twot, FORMAT(vat, 2) AS vat, FORMAT(grand_total, 2) AS grand_total 
                                   FROM tbl_invoice_pot 
                                   WHERE id_book_invoice = '$id_inv') a";

$data_invoice_pot=mysqli_fetch_array(mysqli_query($conn2,$sql3));

$sql4= "select * from (Select concat(UCASE(left(a.nama,1)),LCASE(SUBSTRING(a.nama,2))) as nama from sb_log a inner join sb_book_invoice b on b.no_invoice = a.doc_number where a.activity = 'Create invoice' and b.id = '$id_inv') a
UNION
select * from (Select concat(UCASE(left(a.nama,1)),LCASE(SUBSTRING(a.nama,2))) as nama from tbl_log a inner join tbl_book_invoice b on b.no_invoice = a.doc_number where a.activity = 'Create invoice' and b.id = '$id_inv') a";

$group_user=mysqli_fetch_array(mysqli_query($conn2,$sql4));

$sql5= "Select DISTINCT curr FROM sb_invoice_detail WHERE id_book_invoice = '$id_inv' UNION Select DISTINCT curr FROM tbl_invoice_detail WHERE id_book_invoice = '$id_inv'";

$group_curr=mysqli_fetch_array(mysqli_query($conn2,$sql5));
ob_start();
?>




<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

<style>
        /* @page {
            margin-top: 1.54cm;
            margin-bottom: 1.54cm;
            margin-left: 3.175cm;
            margin-right: 3.175cm;
        } */

        table {
            margin: auto;
        }

        td,
        th {
            padding: 1px;
            text-align: left
        }

        h1 {
            text-align: center
        }

        th {
            text-align: center;
            padding: 10px;
        }

        .footer {
            width: 100%;
            height: 30px;
            margin-top: 50px;
            text-align: right;
        }

        /*
CSS HEADER
*/
        .header {
            width: 100%;
            height: 20px;
            padding-top: 0;
            margin-bottom: 10px;
        }

        .title {
            font-size: 30px;
            font-weight: bold;
            text-align: center;
            margin-top: -90px;
        }


        .horizontal {
            height: 0;
            width: 100%;
            border: 3px solid #000000;
        }

        .position_top {
            vertical-align: top;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .td1 {
            border: 1px solid black;
            border-top: none;
            border-bottom: none;
        }

        .header_title {
            width: 100%;
            height: auto;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
    
  <title>INVOICE</title>
</head>
<body >
   <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <img src="../../images/nag_logo3.jpg" width="15%" style="padding-top:-18px;">
                </td>
                <td class="title">
                    PT.NIRWANA ALABARE GARMENT
                    <div style="font-size:12px;line-height:9">
                        Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, <br />Kabupaten Bandung 40382 <br />Telp. 022-85962081
                    </div>
                </td>
            </tr>
        </table>
        <div class="horizontal">

        </div>
    </div>
<div class="header_title">
        <?= $rs['type']; ?> INVOICE <br />
        <?= $rs['no_invoice']; ?>
    </div>
<br />
    <table style="width:100%;font-size:10px;">
        <tr>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
            <td class="position_top">

            </td>
            <td class="position_top">

            </td>
            <td colspan="3" class="position_top">

            </td>
        </tr>

        <tr>
            <td class="position_top">

            </td>

            <td class="position_top">

            </td>

            <td class="position_top">

            </td>

            <td class="position_top">

            </td>

            <td class="position_top">

            </td>

            <td colspan="3" class="position_top">

            </td>

            <td class="position_top">

            </td>

            <td class="position_top">

            </td>

            <td colspan="3" class="position_top">

            </td>
        </tr>

        <tr>
            <td>
                Date
            </td>
            <td>
                :
            </td>
            <td colspan="3">
                <?= $rs['tgl_inv']; ?>
            </td>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
        </tr>

        <tr>
            <td>
                To
            </td>
            <td>
                :
            </td>
            <td colspan="3">
                <?= $rs['customer']; ?>
            </td>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
        </tr>

        <tr>
            <td>
                Address
            </td>
            <td class="position_top">
                :
            </td>
            <td class="position_top" colspan="3">
                <?= $rs['alamat']; ?>
            </td>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
        </tr>

        <tr>
            <td>
                Telp.
            </td>
            <td>
                :
            </td>
            <td colspan="3">
                <?= $rs['phone']; ?>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td colspan="7">
            </td>
        </tr>

        <tr>
            <td>
                Terms Of Payment
            </td>
            <td>
                :
            </td>
            <td colspan="3">
                <?= $rs['top']; ?> Days
            </td>
            <td>
            </td>
            <td>
            </td>
            <td colspan="7">
            </td>
        </tr>

        <tr>
            <td>
                BPPB#
            </td>
            <td>
                :
            </td>

            <td>
                <?php
                $query_bppb = mysqli_query($conn2,$sql_bppb)or die(mysqli_error());
                while($data_bppb=mysqli_fetch_array($query_bppb)){
                    echo $data_bppb['bppb_number']; echo ',';
                } ?>
            </td>
        </tr>

        <tr>
            <td>
                SALES ORDER#
            </td>
            <td>
                :
            </td>
            <td>
               <?php
                $query_so = mysqli_query($conn2,$sql_so)or die(mysqli_error());
                while($data_so=mysqli_fetch_array($query_so)){
                    echo $data_so['so_number']; echo ',';
                } ?>
            </td>
        </tr>
    </table>

    <br />

<table  border="1" cellspacing="0" style="width:100%;font-size:10px;border-spacing:2px;">
    <tr> 
        <th>Style</th>
        <th>Color</th>
        <th>Product Item</th>
        <th colspan="2" style="width: 100px">Quantity</th>
        <th>Unit Price</th>
        <th colspan="2" style="width: 100px">Total</th>
    </tr>
<tbody>

     <?php
$query = mysqli_query($conn2,$sql)or die(mysqli_error());
$my_tot_unit = 0;
$mata_uang = '';
while($data=mysqli_fetch_array($query)){
    $my_tot_unit += $data['qty'];
    $mata_uang = $data['curr'];

   echo '<tr>
      <td style="text-align:left;">'.$data['styleno'].'</td>
      <td style="text-align:left;">'.$data['color'].'</td>
      <td style="text-align:left;">'.$data['product_item'].' ('.$data['size'].')</td>    
      <td style="text-align:right;">'.$data['qty'].'</td>
      <td style="text-align:center;">'.$data['uom'].'</td>
      <td style="text-align:right;">'.$data['curr'].' '.$data['unit_price'].'</td>
      <td style="text-align:center;">'.$data['curr'].'</td>
      <td style="text-align:right;">'.$data['total_price'].'</td>              
    </tr>'; 
}  



?>
<tr>
            <td colspan='3'></td>
            <td align='right'><?= $my_tot_unit; ?></td>
            <td colspan='4'></td>
        </tr>

        <tr>
            <td colspan='6'>Total</td>
            <td align='center'><?= $mata_uang ?></td>
            <td align='right'><?= $data_invoice_pot['total']; ?></td>
        </tr>

        <tr>
            <td colspan='6'>Discount</td>
            <td align='center'><?= $mata_uang ?></td>
            <td align='right'><?= $data_invoice_pot['discount']; ?></td>
        </tr>

        <tr>
            <td colspan='6'>Down Payment</td>
            <td align='center'><?= $mata_uang ?></td>
            <td align='right'><?= $data_invoice_pot['dp']; ?></td>
        </tr>

        <tr>
            <td colspan='6'>Return</td>
            <td align='center'><?= $mata_uang ?></td>
            <td align='right'><?= $data_invoice_pot['retur']; ?></td>
        </tr>

        <tr>
            <td colspan='6'>Total Before Value Added Tax</td>
            <td align='center'><?= $mata_uang ?></td>
            <td align='right'><?= $data_invoice_pot['twot']; ?></td>
        </tr>

        <tr>
            <td colspan='6'>Value Added Tax</td>
            <td align='center'><?= $mata_uang ?></td>
            <td align='right'><?= $data_invoice_pot['vat']; ?></td>
        </tr>

        <tr>
            <td colspan='6'>Grand Total</td>
            <td align='center'><?= $mata_uang ?></td>
            <td align='right'><?= $data_invoice_pot['grand_total']; ?></td>
        </tr>

  </tbody>
</table> 
<br>

<table style="font-size:10px;" border="0">
        <tr>
            <td style="text-align:right">Bank</td>
            <td>:</td>
            <td>No Rek : <?= $rs['no_rek']; ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?= $rs['nama_bank']; ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?= $rs['v_bankaddress']; ?></td>
            <!--  -->
            <!-- <td>JL.ASIA AFRIKA NO.122-124, PALEDANG,KEC.LENGKONG,KOTA BANDUNG,JAWA
                BARAT 40261</td> -->
        </tr>
    </table>

    <br />
    <br />
    <br />
    <br />
    <br />
    <br />

    <!-- <div style="margin-bottom: 2.54cm;"> -->
    <div style="margin-bottom: 2.54cm; page-break-inside: avoid;">
        <table style="page-break-inside: avoid;" cellpadding="0" cellspacing="0" border="1" width="600px">
            <tr>
                <th style="font-size: 11px; width: 200px">Created By : </th>
                <th style="font-size: 11px; width: 200px">Checked By : </th>
                <th style="font-size: 11px; width: 200px">Approved By : </th>
            </tr>
            <tr>
                <td class="td1">&nbsp;</td>
                <td class="td1">&nbsp;</td>
                <td class="td1">&nbsp;</td>
            </tr>
            <tr>
                <td class="td1">&nbsp;</td>
                <td class="td1">&nbsp;</td>
                <td class="td1">&nbsp; </td>
            </tr>
            <tr>
                <td class="td1">&nbsp;</td>
                <td class="td1">&nbsp;</td>
                <td class="td1">&nbsp; </td>
            </tr>
            <tr>
                <td class="td1">&nbsp;</td>
                <td class="td1">&nbsp;</td>
                <td class="td1">&nbsp; </td>
            </tr>
            <tr style="border-bottom: none;">
                <td class="td1">&nbsp;</td>
                <td class="td1">&nbsp;</td>
                <td class="td1">&nbsp; </td>
            </tr>
            <tr style="border-collapse: collapse; border-top: none;">
                <!-- <td style="font-size:12px;text-align:center;text-decoration:underline">(<?= $username ?>) </td>            
            <td style="font-size:12px;text-align:center">(________________________) </td>
            <td style="font-size:12px;text-align:center">(________________________) </td> -->
                <!--  -->
                <td style="font-size:12px;text-align:center;text-decoration:underline">(<?= $group_user['nama']; ?>)</td>
                <td style="font-size:12px;text-align:center;text-decoration:underline">(<?= "Willy Fernandez" ?>)</td>
                <td style="font-size:12px;text-align:center;text-decoration:underline">(<?= "Syenni Santosa" ?>)</td>
            </tr>
            <tr>
                <td style="text-align:center;font-size:12px">AR Staff</td>
                <td style="text-align:center;font-size:12px">Finance Accounting Manager</td>
                <td style="text-align:center;font-size:12px">Director</td>
            </tr>
        </table>

        <br />

        <table style="page-break-inside: avoid; font-size:10px;" border="0">
            <tr>
                <td style="font-weight: bold">NOTE :</td>
            </tr>
            <tr>
                <td>INVOICE NUMBER : <?= $rs['no_invoice']; ?></td>
            </tr>
            <tr>
                <td>GRAND TOTAL : <?= $group_curr['curr']; ?> <?= $data_invoice_pot['grand_total']; ?></td>
            </tr>
        </table>

    </div>
</body>


</html>  

<?php
$html = ob_get_clean();
// require_once __DIR__ . '/../../mpdf8/vendor/autoload.php';
// include("../../mpdf8/vendor/mpdf/mpdf/src/mpdf.php");
include("../../mpdf57/mpdf.php");

// $mpdf=new \mPDF\mPDF();
$mpdf=new mPDF();

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>



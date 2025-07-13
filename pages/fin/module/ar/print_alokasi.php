<!DOCTYPE html>
<?php
session_start();
include '../../conn/conn.php';
$user = $_SESSION['username'];
$images = '../../images/img-01.png';
$id_alokasi =$_GET['id_alokasi'];
?>

<?php
$sql= "select * from (select a.no_alk, a.coa,IFNULL(c.nama_coa,'-') as coa_name, a.cost_center, IFNULL(d.cost_name,'-') as cost_name, a.no_ref, a.ref_date, a.due_date, b.curr, if(b.curr = 'IDR',format(a.total, 2), format(a.total, 2)) as total, format(a.eqp_idr,2) as eqp_idr,format(a.amount, 2) as amount,format(round(a.total,2) - round(a.amount, 2),2) as ost, a.keterangan,e.supplier,if(b.rate = '0','-',b.rate) as rate from sb_alokasi_detail a left join 
                 mastercoa_v2 c on a.coa=c.no_coa LEFT JOIN 
                 sb_alokasi b on a.no_alk = b.no_alk left join
                 mastersupplier AS e ON b.customer = e.id_supplier left join
                 tbl_cost_center d on a.cost_center = d.code_cost where b.id = '$id_alokasi' GROUP BY a.id ) a
                 UNION
                 select * from (select a.no_alk, a.coa,IFNULL(c.nama_coa,'-') as coa_name, a.cost_center, IFNULL(d.cost_name,'-') as cost_name, a.no_ref, a.ref_date, a.due_date, b.curr, if(b.curr = 'IDR',format(a.total, 2), format(a.total, 2)) as total, format(a.eqp_idr,2) as eqp_idr,format(a.amount, 2) as amount,format(round(a.total,2) - round(a.amount, 2),2) as ost, a.keterangan,e.supplier,if(b.rate = '0','-',b.rate) as rate from tbl_alokasi_detail a left join 
                 mastercoa_v2 c on a.coa=c.no_coa LEFT JOIN 
                 tbl_alokasi b on a.no_alk = b.no_alk left join
                 mastersupplier AS e ON b.customer = e.id_supplier left join
                 tbl_cost_center d on a.cost_center = d.code_cost where b.id = '$id_alokasi' GROUP BY a.id ) a";

 $sql2= "select * from (select a.id, a.no_alk, DATE_FORMAT(a.tgl_alk,'%d-%m-%Y') as tgl_alk,a.customer,b.supplier,a.doc_number,d.nama_bank as bank,a.account,a.curr,if(rate = '0', '-',format(a.rate,2)) as rate,if(a.curr = 'IDR',format(a.amount, 2), format(a.amount, 2)) as amount,format(a.eqp_idr, 2) as eqp_idr,a.status from sb_alokasi AS a INNER JOIN mastersupplier AS b ON a.customer = b.id_supplier inner join masterbank d on d.id = a.bank where a.id = '$id_alokasi') a
 UNION
 select * from (select a.id, a.no_alk, DATE_FORMAT(a.tgl_alk,'%d-%m-%Y') as tgl_alk,a.customer,b.supplier,a.doc_number,d.nama_bank as bank,a.account,a.curr,if(rate = '0', '-',format(a.rate,2)) as rate,if(a.curr = 'IDR',format(a.amount, 2), format(a.amount, 2)) as amount,format(a.eqp_idr, 2) as eqp_idr,a.status from tbl_alokasi AS a INNER JOIN mastersupplier AS b ON a.customer = b.id_supplier inner join masterbank d on d.id = a.bank where a.id = '$id_alokasi') a";

$rs=mysqli_fetch_array(mysqli_query($conn2,$sql2));

 $sql3= "select * from (select DISTINCT b.curr from sb_alokasi_detail a left join 
                 mastercoa_v2 c on a.coa=c.no_coa LEFT JOIN 
                 sb_alokasi b on a.no_alk = b.no_alk left join
                 mastersupplier AS e ON b.customer = e.id_supplier left join
                 tbl_cost_center d on a.cost_center = d.code_cost where b.id = '$id_alokasi' GROUP BY a.id) a
                 UNION
                 select * from (select DISTINCT b.curr from sb_alokasi_detail a left join 
                 mastercoa_v2 c on a.coa=c.no_coa LEFT JOIN 
                 tbl_alokasi b on a.no_alk = b.no_alk left join
                 mastersupplier AS e ON b.customer = e.id_supplier left join
                 tbl_cost_center d on a.cost_center = d.code_cost where b.id = '$id_alokasi' GROUP BY a.id) a";

$rs2=mysqli_fetch_array(mysqli_query($conn2,$sql3));

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
    
  <title>PAYMENT</title>
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
        ALOKASI <br />
        <?= $rs['no_alk']; ?>
    </div>
<br />
    <table style="width:100%;font-size:10px;" >
        <tr>
            <td class="position_top" colspan="8">
                &nbsp;
            </td>
            <td class="position_top">

            </td>
            <td class="position_top">

            </td>
            <td colspan="3" class="position_top" style="width: 60%;">

            </td>
        </tr>

        <!-- <tr>
            <td class="position_top" colspan="8">
                &nbsp;
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
            <td class="position_top" style="text-align:right">
                Date
            </td>
            <td class="position_top">
                :
            </td>
            <td colspan="3" class="position_top" style="text-align:right">
                    </td>
        </tr> -->

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
            <td style="width: 20%">
                Alokasi Date
            </td>
            <td style="width: 2%">
                :
            </td>
            <td colspan="3">
                <?= $rs['tgl_alk']; ?>
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
                Customer
            </td>
            <td>
                :
            </td>
            <td colspan="3">
                <?= $rs['supplier']; ?>
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

        <!-- <tr>
            <td>
                Address
            </td>
            <td class="position_top">
                :
            </td>
            <td class="position_top" colspan="3">
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
        </tr> -->

        <!-- <tr>
            <td>
                Telp.
            </td>
            <td>
                :
            </td>
            <td colspan="3">            </td>
            <td>
            </td>
            <td>
            </td>
            <td colspan="7">
            </td>
        </tr> -->

        <tr>
            <td>
                Document Number
            </td>
            <td>
                :
            </td>
            <td colspan="3">
                <?= $rs['doc_number']; ?>
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
                Bank
            </td>
            <td>
                :
            </td>

            <td>
                <?= $rs['bank']; ?>
            </td>
        </tr>

        <tr>
            <td>
                Account
            </td>
            <td>
                :
            </td>
            <td>
                <?= $rs['account']; ?>
            </td>
        </tr>
    </table>

    <br />

<table style="width:100%;font-size:10px;" border="1">
        <tr align="center">
            <th>COA</th>
            <th>Cost Center</th>
            <th>Ref Number</th>
            <th>Ref Date</th>
            <th>Due Date</th>
            <th>Total</th>
            <th>Outstand</th>
            <th>Amount</th>
        </tr>
<tbody>

     <?php
$query = mysqli_query($conn2,$sql)or die(mysqli_error());
$my_tot_unit = 0;
$mata_uang = '';
while($data=mysqli_fetch_array($query)){
    ?>
<tr>
    <td style="width: 15%; text-align: center;"><?= $data['coa_name']; ?></td>
    <td style="width: 15%; text-align: center;"><?= $data['cost_name']; ?></td>
    <td style="width: 15%; text-align: center;"><?= $data['no_ref']; ?></td>
    <td style="width: 10%; text-align: center;"><?= $data['ref_date']; ?></td>
    <td style="width: 10%; text-align: center;"><?= $data['due_date']; ?></td>
    <td style="width: 10%; text-align: right;"><?= $data['total']; ?></td>
    <td style="width: 10%; text-align: right;"><?= $data['ost']; ?></td>
    <td style="width: 15%; text-align: right;"><?= $data['amount']; ?></td>
</tr>
<?php
}  



?>
<tr>
            <td colspan='7'>Total Amount</td>
            <td align='right'><?= $rs2['curr']; ?> <?= $rs['amount']; ?></td>
        </tr>

        <tr>
            <td colspan='7'>Rate</td>
            <td align='right'><?= $rs['rate']; ?></td>
        </tr>

        <tr>
            <td colspan='7'>Equivalent IDR</td>
            <td align='right'>IDR <?= $rs['eqp_idr']; ?></td>
        </tr>

  </tbody>
</table> 
<br>

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
                <td style="font-size:12px;text-align:center;text-decoration:underline">(<?= "Frisca" ?>)</td>
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
                <td>ALOKASI NUMBER : <?= $rs['no_alk']; ?></td>
            </tr>
            <tr>
                <td>GRAND TOTAL : <?= $rs2['curr']; ?> <?= $rs['amount']; ?></td>
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



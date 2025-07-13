<?php
error_reporting(E_ERROR);
require_once "../forms/journal_interface.php";



ob_start();


class PdfInvoiceComercial {
	public function getListData($id){
		include __DIR__ .'/../../include/conn.php';
		$q = "SELECT 
		IAC.n_id
		,IAC.n_amount
		,IAC.n_so_id
		,IAC.n_id_invoice
		,IAC.v_from
		,IAC.v_to
		,IAC.d_insert
		,IAC.v_pono
		,IH.invno 
		,IH.shipped_by
		,IH.etd
		,IH.eta
		,IH.measurement
		,IH.id_buyer
       /* ,max(BPB.j_bersih) j_bersih
        ,max(BPB.j_kotor) j_kotor
		,BPB.jqty j
		,BPB.j_price jp
		,BPB.id_supplier
		,BPB.nomor_mobil
		,BPB.styleno
		,MSM.shipdesc
		*/
		,MSS.Supplier
		,MSS.terms_of_pay
		,MSS.alamat
		/*,MSST.itemname
		,MSST.color
		,MSST.goods_code
		
		*/,SOD.qty jqty
		,SOD.price jprice
		,SOD.color
		,ACT.styleno
		,ID.j_carton
		FROM shp_estimatepackinglist IAC
			LEFT JOIN(
				SELECT id_buyer,invno,id,shipped_by,measurement,etd,eta FROM shp_pro_invoice_header
			)IH ON IAC.n_id_invoice =  IH.id

			LEFT JOIN(
				SELECT id,shipdesc FROM mastershipmode
			) MSM ON IH.shipped_by = MSM.id


			LEFT JOIN(
				SELECT SUM(carton) j_carton,id_inv FROM shp_pro_invoice_detail GROUP BY id_inv 
			)ID ON IAC.n_id_invoice = ID.id_inv
			LEFT JOIN(
				SELECT color,SUM(qty) qty,sum(price) price,id_so FROM so_det GROUP BY id_so
			)SOD ON IAC.n_so_id = SOD.id_so
			LEFT JOIN
				mastersupplier MSS
			 ON MSS.Id_Supplier = IH.id_buyer	
			LEFT JOIN
				so SO
			 ON SO.id = IAC.n_so_id
			LEFT JOIN
				act_costing ACT
			 ON SO.id_cost = ACT.id
			 
			WHERE IAC.n_id = '$id';
			";
			$stmt = mysql_query($q);
			return $stmt;
}
}
	$number         = "";
	$date           = "";
	$supplier       = "";
	$alamatsupplier = "";
	$shipped_by     = "";
	$nomorpengangkut= "";
	$from           = "";
	$to             = "";
	$termsofpayment = "";
	$etd            = "";
	$etalax         = "";
	$stylename      = "";
	$styleno        = "";
	$po             = "";
	$color          = "";
	$qty            = "";
	$price          = "";
	$amount         = "";
	$beratbersih    = "";
	$beratkotor     = "";
	$carton         = "";
	$measurement    = "";
$PdfInvoiceComercial = new PdfInvoiceComercial();
$list = $PdfInvoiceComercial->getListData($_GET['id']);
while($row = mysql_fetch_array($list)){
	$number         = $row['invno'];
	$date           = $row['d_insert'];
	$supplier       = $row['Supplier'];
	$alamatsupplier = $row['alamat'];
	$shipped_by     = $row['shipdesc'];
	$nomorpengangkut= $row['nomor_mobil'];
	$from           = $row['v_from'];
	$to             = $row['v_to'];
	$termsofpayment = $row['terms_of_pay'];
	$etd            = $row['etd'];
	$etalax         = $row['eta'];
	$stylename      = $row['itemname'];
	$styleno        = $row['styleno'];
	$po             = $row['v_pono'];
	$color          = $row['color'];
	$qty            = $row['jqty'];
	$price          = $row['j_price'];
	$amount         = $row['n_amount'];
	$beratbersih    = $row['j_bersih'];
	$beratkotor     = $row['j_kotor'];
	$carton         = $row['j_carton'];
	$measurement    = $row['measurement'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Proforma Invoice</title>

<style>

@page *{
    margin-top: 1.54cm;
    margin-bottom: 1.54cm;
    margin-left: 3.175cm;
    margin-right: 3.175cm;
}

 	table{margin: auto;}
 	td,th{padding: 1px;text-align: left}
 	h1{text-align: center}
 	th{text-align:center; padding: 10px;}
	
.footer{
	width:100%;
	height:30px;
	margin-top:50px;
	text-align:right;
	
}
/*
CSS HEADER
*/

.header{
	width:100%;
	height:20px;
	padding-top:0;
	margin-bottom:10px;
}
.title{
	font-size:30px;
	font-weight:bold;
	text-align:center;
	margin-top:-90px;
}

.horizontal{
	height:0;
	width:100%;
	border:3px solid #000000;
}
.position_top {
	vertical-align: top;
	
}

table {
  border-collapse: collapse;
  width: 100%;
}
.header_title{
	width:100%;
	height:auto;
	text-align:center;
	font-weight:bold;
	text-decoration:underline;
	font-size:20px;
	
}

</style>
	
	
</head>
<body>

	<div class="header" >
		<table width="100%">
			<tr>
			<td >
				<img src="../../include/img-01.png" width="15%">
			</td>
			<td class="title" >
				PT.NIRWANA ALABARE GARMENT
				<div style="font-size:12px;line-height:9">
					Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, <br/>Kabupaten Bandung 40382 <br/>Telp. 022-85962081				
				</div>
			</td>	
			</tr>
		</table>
		<div class="horizontal">
		
		</div>
	</div>
	<div class="header_title">
		PROFORMA INVOICE 
	</div>
	<br/>
	<table style="width:100%;font-size:10px;" >
		<tr>
			<td class="position_top" colspan="8">
				&nbsp;
			</td>

			<td class="position_top">
				No
			</td>
			<td class="position_top">
				:
			</td>				
			<td colspan="3" class="position_top">
			<!--	065/EXP/EXIM-NAG/2019 --> <?=$number    ?>
			</td>			
			
		</tr>	
		<tr>
			<td class="position_top" colspan="8">
				&nbsp;
			</td>

			<td class="position_top">
				Date
			</td>
			<td class="position_top">
				:
			</td>				
			<td colspan="3" class="position_top">
			<!--	22-Jan-2019 --> <?=date('d-M-Y', strtotime($date))    ?>
			</td>			
			
		</tr>		
		<tr >
			<td class="position_top">
				Shipper
			</td>
			<td class="position_top">
				:
			</td>
			<td class="position_top">
				PT. NIRWANA ALABARE GARMENT <br/>
				JL. RAYA RANCAEKEK-MAJALAYA NO.289, RT.002 / RW.007 
				DESA SOLOKANJERUK, KECAMATAN SOLOKANJERUK 
				KABUPATEN BANDUNG 40382 JAWA BARAT, INDONESIA 
				
			</td>			
			<td class="position_top">
				For Account & Risk of
			</td>
			<td class="position_top">
				:
			</td>		
			<td colspan="3" class="position_top">
				<!--HYBRID  PROMOTIONS LLC
				10711 WALKER STREET
				CYPRESS CA 90630, USA
				-->
				<?=$supplier."<br/>".$alamatsupplier ?>
			</td>	
			<td class="position_top">
				Ship To
			</td>
			<td class="position_top">
				:
			</td>				
			<td colspan="3" class="position_top">
				HYBRID OTAY WHSE
				6060 BUSSINESS CENTER CT
				SUITE 100, SAN DIEGO CA 92154
				USA
			</td>			
			
		</tr>
		<tr>
			<td>
				SHIPPED BY
			</td>

			<td>
				:
			</td>

			<td colspan="11">
				<!--AIR COLLECT	 --> <?=$shipped_by     ?>
			</td>	
			
		</tr>
	
		<tr>
			<td>
				SHIPPED PER  S/S
			</td>
			<td class="position_top">
				:
			</td>

			<td class="position_top" colspan="3">
				<!--CX776 --> <?=$nomorpengangkut ?>
			</td>	
			<td class="position_top">
				ETD
			</td>
			<td class="position_top">
				:
			</td>

			<td class="position_top" colspan="3">
				<!--24-Jan-2019--> <?=date('d-M-Y', strtotime($etd)) ?>
			</td>			
			<td class="position_top">
				ETA LAX
			</td>
			<td class="position_top">
				:
			</td>

			<td class="position_top">
				<!--26-Jan-2019--> <?=date('d-M-Y', strtotime($etalax)) ?>
			</td>				
		</tr>
		<tr>
			<td> 
				FROM
			</td>
			<td>
				:
			</td>			
			<td colspan="3"> 
				<!--SOEAKRNO HATTA JAKARTA, INDONESIA--> <?=$from ?>
			</td>
			<td> 
				TO
			</td>
			<td>
				:
			</td>			
			<td colspan="7"> 
				<!--LOS ANGELES, CA USA--> <?=$to ?>
			</td>			
		</tr>
		<tr>
			<td class="position_top">
				TERM PAYMENT
			</td>

			<td class="position_top">
				:
			</td>

			<td class="position_top">
				<!--T/T PAYMENT--> <?=$termsofpayment  ?>
			</td>	
			
		</tr>		
	</table>
<br/>
	<table style="width:100%;font-size:10px;" border="1">
		<tr align="center">
			<th colspan="3" >
				MARK & NOS.
			</th>
			<th colspan="3">
				DESCRIPTION OF GOODS
			</th>	
			<th>
				QUANTITY
			</th>	
			<th>
				UNIT PRICE
			</th>	
			<th>
				AMOUNT
			</th>				
		</tr>
		<tr align="center">
			<td colspan="3" class="position_top" style="border-bottom:1px solid #FFFFFF">
				AS PER COMMERCIAL 
			</td>
			<td colspan="3" rowspan="2" class="position_top">
				BRAND:
				
			

			</td>	
			<td class="position_top" rowspan="2">
				PCS
			</td>	
			<td class="position_top" rowspan="2">
				PER PCS<br/>IDR
			</td>	
			<td class="position_top" rowspan="2">
				FOB<br/>Jakarta,Indonesia
			</td>				
		</tr>		 
		<tr align="center">
			<td colspan="3" class="position_top" style="border-bottom:1px solid #FFFFFF;border-top:1px solid #FFFFFF">
				&nbsp;
			</td>







		</tr>			
		<tr >
			<td colspan="3" class="position_top" style="border-bottom:1px solid #FFFFFF;border-top:1px solid #FFFFFF">
				&nbsp;
			</td>
			<td colspan="3" class="position_top" style="border:1px solid #FFFFFF">
			<!--	S/S CREW TOP $5 TEE 60% COTTON 40% POLYESTER -->
			<?=$stylename ?>
				
			

			</td>	
			<td class="position_top" rowspan="4">
				<!--5.914--> <?=$qty ?>
			</td>	
			<td class="position_top" rowspan="4">
				<!--$ 1,058  -->Rp.  <?=$price ?>
			</td>	
			<td class="position_top" rowspan="4">
				<!--$ 6.257,012 -->Rp. <?=$amount ?>
			</td>				
		</tr>
		<tr>
			<td colspan="3" style="border-bottom:1px solid #FFFFFF;border-top:1px solid #FFFFFF">
				&nbsp; 
			</td>
			<td style="border:1px solid #FFFFFF">
				STYLE NO.
			</td >
			<td style="border:1px solid #FFFFFF">
				: 
			</td>
			<td style="border:1px solid #FFFFFF">
				<!--LT29901E--> <?=$styleno   ?>
			</td>
			
		</tr>
		<tr>
			<td colspan="3" style="border-bottom:1px solid #FFFFFF;border-top:1px solid #FFFFFF">
				&nbsp; 
			</td>
			<td style="border:1px solid #FFFFFF">
				PO NO. 
			</td>
			<td style="border:1px solid #FFFFFF">
				: 
			</td>
			<td style="border:1px solid #FFFFFF">
				<!--115686 --> <?=$po   ?>
			</td>
		

		</tr>		
		<tr>
			<td colspan="3" style="border-top:1px solid #FFFFFF">
				&nbsp; 
			</td>
		<td style="border:1px solid #FFFFFF;border-bottom:1px solid #000000">
				COLOR 
			</td>
			<td style="border:1px solid #FFFFFF;border-bottom:1px solid #000000">
				: 
			</td>
			<td style="border:1px solid #FFFFFF;border-bottom:1px solid #000000">
				<!--BAY LEAF--> <?=$color ?>
			</td>

		</tr>	
		<tr>
			<td>TOTAL </td>
			<td colspan="5"> : </td>
			<td><!--5.914--><?=$qty ?>  </td>
			<td>&nbsp; </td>
			<td><!--$ 6.257,012-->Rp. <?=$amount ?> </td>
		</tr>
		<tr>
			<td>NET WEIGHT </td>
			<td > : </td>
			<td align="right"><!--616,92	--> <?=$beratbersih  ?> </td>
			<td colspan="6">KGS </td>
		</tr>
		<tr>
			<td>GROSS WEIGHT </td>
			<td > : </td>
			<td align="right"><!--671,26--> <?=$beratkotor  ?>		 </td>
			<td colspan="6">KGS </td>
		</tr>	
		<tr>
			<td>TOTAL </td>
			<td > : </td>
			<td align="right"><!--55--> <?=$carton  ?></td>
			<td colspan="6">CTNS </td>
		</tr>	
		<tr>
			<td>MEASUREMENT </td>
			<td > : </td>
			<td align="right"><!--3,64	--><?=$measurement ?>	 </td>
			<td colspan="6">M3 </td>
		</tr>	
		<tr>
			<td colspan="3">
				&nbsp; 
			</td>
			<td colspan="3" align="center">
				COUNTRY OF ORIGIN  INDONESIA
			</td>

			<td >
				&nbsp; 
			</td>
			<td >
				&nbsp; 
			</td>
			<td >
				&nbsp; 
			</td>			

		</tr>	

		<tr align="left">
			<td colspan="6">
				MANUFACTURER  :

			</td>
				<td colspan="3">
				SIGNED BY  :

			</td>		
		</tr>	
		<tr align="left">
			<td colspan="3">
				PT. NIRWANA ALABARE GARMENT
JL. RAYA RANCAEKEK-MAJALAYA NO.289, RT.002 / RW.007
DESA SOLOKANJERUK, KECAMATAN SOLOKANJERUK
KABUPATEN BANDUNG 40382 JAWA BARAT, INDONESIA


			</td>
	
				<td colspan="6">
				&nbsp;

			</td>				
		</tr>		
	</table>
	
	
</body>


</html> 
<?php
// Store output into vars
$html = ob_get_clean();
//exit($html);
// Convert output into pdf
include("../../mpdf57/mpdf.php");

$mpdf=new mPDF('utf-8', 'A4');
$mpdf->setFooter('{PAGENO}');
//$stylesheet = file_get_contents(__DIR__ .'/../../bootstrap/css/bootstrap.min.css');
//$mpdf->WriteHTML($stylesheet, 1); // CSS Script goes here.
$mpdf->WriteHTML($html);
$mpdf->Output();
?>
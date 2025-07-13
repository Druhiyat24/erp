<?php
session_start();
error_reporting(E_ERROR);

require_once "../forms/journal_interface.php";
ob_start();
class PdfInvoiceComercial {
public function get_populate_bppb($id){

		include __DIR__ .'/../../include/conn.php';

		$q = "
						SELECT (E.bppbno_int)bkb
		FROM shp_invoice_scrap_detail A
			LEFT JOIN masteritem B ON A.id_item = B.id_item
			INNER JOIN shp_invoice_scrap_header C ON A.id_inv_sc = C.id
			INNER JOIN masterpterms D ON C.id_pterms = D.id
			INNER JOIN bppb E ON A.id_bppb = E.id
			INNER JOIN mastersupplier F ON C.id_buyer = F.Id_Supplier
			LEFT JOIN(SELECT id_jo,MAX(id_so)id_so FROM jo_det  GROUP BY id_jo)JOD ON JOD.id_jo = A.id_jo
			LEFT JOIN so SO ON SO.id = JOD.id_so
			WHERE A.id_inv_sc = '{$id}'
			";
			$stmt = mysql_query($q);
			return $stmt;	
}

	public function getListData($id){
		include __DIR__ .'/../../include/conn.php';
		$q = "
			SELECT A.id
		,C.invno
		,A.id_jo
		,C.id id_header
		,A.id_inv_sc
		,A.id_bppb
		,A.id_item
		,A.qty
		,A.price
		,B.itemdesc
		,C.id_coa
		,C.id_buyer
		,C.user_insert
		,C.user_post
		,C.date_invoice
		,C.id_pterms
		,C.fg_ppn
		,if(C.fg_ppn = '1',10,0)percent_tax
		,D.kode_pterms
		,GROUP_CONCAT(E.bppbno_int)bkb
		,E.curr
		,E.berat_bersih nw
		,E.berat_kotor gw
		,A.id_jo
		,SO.so_no
		,ifnull(F.Supplier,0) nama_buyer
		,ifnull(F.Phone,'N/A')kontak
		,ifnull(F.alamat,'N/A')alamat
		,MB.no_rek
		,MB.v_companyaddress
		,MB.nama_bank
		FROM shp_invoice_scrap_detail A
			LEFT JOIN masteritem B ON A.id_item = B.id_item
			INNER JOIN shp_invoice_scrap_header C ON A.id_inv_sc = C.id
			INNER JOIN masterpterms D ON C.id_pterms = D.id
			INNER JOIN bppb E ON A.id_bppb = E.id
			INNER JOIN mastersupplier F ON C.id_buyer = F.Id_Supplier
			LEFT JOIN(SELECT id_jo,MAX(id_so)id_so FROM jo_det  GROUP BY id_jo)JOD ON JOD.id_jo = A.id_jo
			LEFT JOIN so SO ON SO.id = JOD.id_so
			LEFT JOIN masterbank MB ON MB.id_coa = C.id_coa
			WHERE A.id_inv_sc = '{$id}'
			GROUP BY E.bppbno_int	
			";
/* echo $q;
die(); */
			$stmt = mysql_query($q);

			return $stmt;

}

public function getListProduct($id){
		include __DIR__ .'/../../include/conn.php';
		$q = "
		 SELECT A.id
		,A.id_inv_sc
		,A.id_bppb
		,A.id_item
		,A.qty
		,A.discount
		,A.unit
		,A.price
		,B.itemdesc
		,C.fg_ppn
		,A.curr matauang
		,A.curr
		,( ( (IF(fg_ppn ='1',ifnull(A.discount,0)/100,0) * (A.price * A.qty)  )  ) )val_discount
		,( ( (IF(fg_ppn ='1',10/100,0) * (A.price * A.qty)  ) + (A.price * A.qty) ) )net_amt
		,(A.price * A.qty) amt
		FROM shp_invoice_scrap_detail A
			LEFT JOIN masteritem B ON A.id_item = B.id_item
			INNER JOIN shp_invoice_scrap_header C ON A.id_inv_sc = C.id
			INNER JOIN bppb E ON A.id_bppb = E.id
			WHERE A.id_inv_sc = '$id'
			";

			$stmt = mysql_query($q);

			return $stmt;

}





}

	$number         		= "";

	$phone         			= "";

	$id_invoice     		= "";
		
	$date           		= "";
		
	$supplier       		= "";
		
	$alamatsupplier 		= "";
		
	$shipped_by     		= "";
		
	$ship_to				= "";
		
	$po_no					= "";
		
	$shipper				= "";
		
	$nomorpengangkut		= "";
		
	$from           		= "";
		
	$to             		= "";
		
	$termsofpayment 		= "";
		
	$etd            		= "";
		
	$etalax         		= "";
		
	$stylename      		= "";
		
	$styleno        		= "";
		
	$po             		= "";
		
	$color          		= "";
		
	$qty            		= "";
		
	$price          		= "";
		
	$amount         		= "";
		
	$beratbersih    		= "";
		
	$beratkotor     		= "";
		
	$carton         		= "";
		
	$measurement    		= "";
	$bppbno					= "";
	$manufacture_address 	= "";
	$pterms					= "";
	$bank					= "";
	$addressbank			= "";
	$username 				= "";
	$no_rek 				= "";
	$curr 					= "";
	$fg_discount 			= "";
	$discount 				= 0;
$PdfInvoiceComercial = new PdfInvoiceComercial();


$populate_bppb = $PdfInvoiceComercial->get_populate_bppb($_GET['id']);
$bppb_nya = '';
$tmp_bkb = '';
$nn = 0;
 while($pop = mysql_fetch_array($populate_bppb)){
	 if($tmp_bkb == $pop['bkb']){
		$X = "";
	 }else{
		if ($bppb_nya != "") {$bppb_nya .= ",";}
		$bppb_nya .= $pop['bkb'];
		
	 }
	 $nn = 0;
	 $tmp_bkb = $pop['bkb'];
}
	 if($nn > 1){
		$bppb_nya = substr($bppb_nya, 0, -1); 
	 }

$list = $PdfInvoiceComercial->getListData($_GET['id']);

while($row = mysql_fetch_array($list)){

	$number         		= $row['invno'];
	$phone          		= $row['kontak'];
	$id_invoice				= $row['id_header'];
	$date           		= $row['date_invoice'];
	$supplier       		= $row['nama_buyer'];
	$alamatsupplier 		= $row['alamat'];
	$shipped_by     		= '';
	$po_no					= '';
	$shipper				= '';
	$ship_to				= '';
	$nomorpengangkut		= '';
	$from           		= '';
	$to             		= $row['nama_buyer'];
	$termsofpayment 		= '';
	$etd            		= '';
	$etalax         		= '';
	$stylename      	 	= '';
	$styleno        	 	= '';
	$po             	 	= '';
	$color          	 	= '';
	$qty            	 	= '';
	$price          	 	= '';
	$amount         	 	= '';
	$beratbersih    	 	= $row['nw'];
	$beratkotor     	 	= $row['gw'];
	$carton         	 	= '';
	$measurement    	 	= '';
	$bppbno				 	= $row['bkb'];
	$sono				 	= $row['so_no'];
	$manufacture_address 	= $row['alamat'];
	$pterms					= $row['kode_pterms'];
	$bank					= $row['nama_bank'];
	$addressbank			= $row['v_companyaddress'];
	$username 	    		= $row['user_insert'];
	$tax					= $row['percent_tax'];
	$no_rek					= $row['no_rek'];
	$curr					= $row['curr'];;
	$fg_discount			= '';
	$discount				= '';
}

$product = $PdfInvoiceComercial->getListProduct($id_invoice);
/* echo "<pre>";
print_r($product);
die(); */
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    

    <title>Invoice Commercial</title>



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

.td1{
    border:1px solid black;
    border-top: none;
    border-bottom: none;
}

.header_title{

	width:100%;

	height:auto;

	text-align:center;

	font-weight:bold;

	font-size:12px;

	

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

		COMMERCIAL INVOICE <br/>

		<?=$number ?>

	</div>

	<br/>

	<table style="width:100%;font-size:10px;" >

		<tr>

			<td class="position_top" colspan="8">

				&nbsp;

			</td>



			<td class="position_top">

				

			</td>

			<td class="position_top">

				

			</td>				

			<td colspan="3" class="position_top">

			<!--	065/EXP/EXIM-NAG/2019 -->   

			</td>			

			

		</tr>	

		<tr>

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

			<!--	22-Jan-2019 --> <?=date('d-M-Y', strtotime($date))    ?>

			</td>			

			

		</tr>		

		<tr >

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

				<!--HYBRID  PROMOTIONS LLC

				10711 WALKER STREET

				CYPRESS CA 90630, USA

				-->



			</td>	

			<td class="position_top">

				

			</td>

			<td class="position_top">

			

			</td>				

			<td colspan="3" class="position_top">

			<!--	HYBRID OTAY WHSE

				6060 BUSSINESS CENTER CT

				SUITE 100, SAN DIEGO CA 92154

				USA

			-->

			

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

				<!--AIR COLLECT	 --> <?=$supplier     ?>

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

				<!--CX776 -->  <?=$manufacture_address     ?>

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
				<!--SOEAKRNO HATTA JAKARTA, INDONESIA--> <?=$phone ?>

			</td>

			<td> 

			

			</td>

			<td>

				

			</td>			

			<td colspan="7"> 

				<!--LOS ANGELES, CA USA--> 

			</td>			

		</tr>

		<tr>

			<td> 

				Payment Terms

			</td>

			<td>

				:

			</td>			

			<td colspan="3"> 
				<?=$pterms    ?>
				<!--SOEAKRNO HATTA JAKARTA, INDONESIA--> <?=$phone ?>

			</td>

			<td> 

			

			</td>

			<td>

				

			</td>			

			<td colspan="7"> 

				<!--LOS ANGELES, CA USA--> 

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

				<?=$bppb_nya    ?>

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

				<?=$sono    ?>

			</td>		
		</tr>
	</table>

<br/>

	<table style="width:100% !important;font-size:10px;" border="1">

		<tr align="center">


				

			<th colspan='3'>
				Nama Item
			</th>		

			<th>

				Qty

			</th>	

			<th>

				Unit

			</th>		

			<th>

				Unit Price

			</th>			

			<th>

				Currency

			</th>	

			<th>

				Total

			</th>				

		</tr>

<?php 

$my_tot_unit = 0;

$my_tot_price = 0;
$discount_value = 0;
$value_tax = 0;
while($baris = mysql_fetch_array($product)){

	$discount_value = $discount_value +$baris['val_discount'];
	$my_tot_unit = $my_tot_unit + $baris['qty'];

	$my_tot_price = $my_tot_price + ($baris['amt']);

	$mata_uang = $baris['matauang'];

	echo "<tr>";




		echo "<td colspan='3'>".$baris['itemdesc']."</td>";

		echo "<td align='right'>".number_format((float)$baris['qty'], 2, '.', ',')."</td>";

		echo "<td>".$baris['unit']."</td>";

		echo "<td style='text-align:right'>".$baris['curr']." ".number_format((float)$baris['price'], 2, '.', ',')."</td>";

		echo "<td>".$baris['curr']."</td>";

		echo "<td align='right'>".number_format((float)$baris['amt'], 2, '.', ',')."</td>";

	
	echo "</tr>";

	

}
//$discount_value = 0;
$total_awal = $my_tot_price;
if($fg_discount == '0'){
	$discount = 0;
}
else{
	//$discount_value = ($discount/100)*$my_tot_price;
	$my_tot_price   = $my_tot_price - $discount_value;
}
$value_tax = ($tax/100)*$my_tot_price;
$after_tax = $value_tax+$my_tot_price;

	echo "<tr>";

		echo "<td colspan=3></td>";

		echo "<td align='right'>$my_tot_unit</td>";

		echo "<td colspan='4'></td>";

	echo "</tr>";

	echo "<tr>";

		echo "<td colspan='2'></td>";

		echo "<td colspan='4'>Total Without Discount</td>";

		echo "<td>".$mata_uang."</td>";

		echo "<td align='right'>".number_format((float)$total_awal, 2, '.', ',')."</td>";

	echo "</tr>";



	echo "<tr>";

		echo "<td colspan='2'></td>";

		echo "<td colspan='4'>Discount</td>";

		echo "<td>".$mata_uang."</td>";

		echo "<td align='right'>".number_format((float)$discount_value, 2, '.', ',')."</td>";

	echo "</tr>";

	echo "<tr>";

		echo "<td colspan='2'></td>";

		echo "<td colspan='4'>Total Without Taxes + Discount</td>";

		echo "<td>".$mata_uang."</td>";

		echo "<td align='right'>".number_format((float)$my_tot_price, 2, '.', ',')."</td>";

	echo "</tr>";	

	echo "<tr>";

		echo "<td colspan='2'></td>";

		echo "<td colspan='4'>Taxes</td>";

		echo "<td>".$mata_uang."</td>";

		echo "<td align='right'>".number_format((float)$value_tax, 2, '.', ',')."</td>";

	echo "</tr>";

	echo "<tr>";

		echo "<td colspan='2'></td>";

		echo "<td colspan='4'>Grand Total</td>";

		echo "<td>".$mata_uang."</td>";

		echo "<td align='right'>".number_format((float)$after_tax, 2, '.', ',')."</td>";

	echo "</tr>";	

	echo "<tr>";

		echo "<td colspan='6'></td>";

		echo "<td>NW :</td>";

		echo "<td align='right'>$beratbersih KG</td>";

	echo "</tr>";

	echo "<tr>";

		echo "<td colspan='6'></td>";

		echo "<td>GW :</td>";

		echo "<td align='right'>$beratkotor KG</td>";

	echo "</tr>";	

?>







	</table>

	
	<table style="font-size:10px;" border="0">

		<tr>
			<td style="text-align:right">Bank</td>
			<td>:</td>
			<td >No Rek <?=$no_rek    ?></td>
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
			<td ><?=$bank     ?></td>
			
		</tr>	
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td ><?=$addressbank    ?></td>
			
		</tr>
	</table>
	
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>	
<div style=" margin-bottom: 2.54cm;">	
	<!-- <table style="font-size:10px;" border="1"> -->
	<table cellpadding="0" cellspacing="0" border="1" width="600px">

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
			<td style="font-size:12px;text-align:center;text-decoration:underline">(<?=$username ?>) </td>
			<td style="font-size:12px;text-align:center">(________________________) </td>
			<td style="font-size:12px;text-align:center">(________________________) </td>
	
	
		</tr>				
		<tr>	
			<td style="text-align:center;font-size:12px">AR </td>
			<td style="text-align:center;font-size:12px">Kabag </td>
			<td style="text-align:center;font-size:12px">Direktur </td>
	
	
		</tr>		
		</table>
</body>

</div>



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
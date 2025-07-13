<?php
session_start();
error_reporting(E_ERROR);

require_once "../forms/journal_interface.php";







ob_start();





class PdfInvoiceComercial {


public function get_populate_bppb($id){

		include __DIR__ .'/../../include/conn.php';

		$q = "
		SELECT A.bppbno FROM
		(
SELECT a.bppbno,a.id_inv FROM invoice_detail a
 LEFT JOIN invoice_commercial B ON a.id_inv = B.n_idinvoiceheader
WHERE B.n_id='$id' GROUP BY A.bppbno)A
UNION ALL(
	SELECT bpbno FROM invoice_commercial WHERE n_id = '$id'
)
			";

			$stmt = mysql_query($q);

			return $stmt;	
	
	
}

	public function getListData($id){

		include __DIR__ .'/../../include/conn.php';

		$q = "
SELECT IAC.v_noinvoicecommercial
		,SUBSTRING(IAC.bpbno,3,1)tring
		,if(SUBSTRING(IAC.bpbno,3,1) = '-',BPB.bppbno_int,IAC.bpbno) bpbno__
		,IAC.n_id
		,IAC.n_amount
		,IAC.bpbno bpb
		,IAC.n_idinvoiceheader
		,IAC.v_from
		,IAC.v_to
		,IAC.d_insert
		,IAC.v_pono
		,IAC.v_userpost
		,IH.invno 
		,IH.invdate
		,IH.n_idcoa 
		,IH.shipped_by
		,IH.ship_to
		,IH.etd
		,IH.eta
		,IH.id_invoice
		,IH.measurement
        ,IH.nw j_bersih
		,IH.manufacture_address
        ,IH.gw j_kotor
		,IH.shipper
		,IH.id_pterms
		,IH.id_buyer
		,IH.fg_discount
		,IH.n_discount
		,BPB.jqty qtyBPB
		,BPB.j_price
		,BPB.id_supplier
		,BPB.nomor_mobil
		,ACT.styleno
		,ACT.id_buyer
		,MSM.shipdesc
		,MSS.Supplier
		,MSS.terms_of_pay
		,MSS.alamat
		,MSS.Phone
		,MSST.itemname
		,MSST.color
		,MSST.goods_code
		,ID.j_carton
		
		,ID.qty_inv jqty
		,SO.buyerno
		,SO.fob
		,SO.so_no
		,SO.curr
		,SO.tax
		,PTERMS.nama_pterms
		,MSBANK.v_bankaddress
		,MSBANK.nama_bank
		,MSBANK.no_rek
		FROM invoice_commercial IAC
			LEFT JOIN(
				SELECT SUM(carton) j_carton,id_inv,max(id_so_det)id_so_det,sum(qty) qty_inv FROM invoice_detail GROUP BY id_inv 
			)ID ON IAC.n_idinvoiceheader = ID.id_inv		
			LEFT JOIN(
				SELECT id,id_so FROM so_det
			)SOD ON ID.id_so_det = SOD.id
			LEFT JOIN(
				SELECT id,id_cost,ifnull(qty*fob,0) amount,fob,buyerno,so_no,curr,tax FROM so
			)SO ON SOD.id_so = SO.id
			LEFT JOIN(
				SELECT id,kpno styleno,id_buyer,id_product FROM act_costing
			)ACT ON SO.id_cost = ACT.id 
			LEFT JOIN(
				SELECT invdate,invno,id id_invoice,shipped_by,ship_to,measurement,etd,eta,nw,gw,shipper,n_idcoa,manufacture_address,id_pterms,id_buyer,fg_discount,n_discount FROM invoice_header
			)IH ON IAC.n_idinvoiceheader =  IH.id_invoice
			LEFT JOIN(
				SELECT SUM(price) j_price,SUM(qty) jqty,SUM(berat_bersih) j_bersih,SUM(berat_kotor) j_kotor,invno,max(id_supplier) id_supplier,nomor_mobil,styleno,bppbno,bppbno_int,id_item FROM bppb GROUP BY bppbno_int,bppbno
			)BPB ON IAC.bpbno = BPB.bppbno
			LEFT JOIN(
				SELECT id,shipdesc FROM mastershipmode
			) MSM ON IH.shipped_by = MSM.id
			LEFT JOIN(
				SELECT Supplier,Id_supplier,alamat,terms_of_pay,Phone FROM mastersupplier
			)MSS ON MSS.Id_supplier = IH.id_buyer
			LEFT JOIN(
				SELECT id_item,itemname,Color,goods_code FROM masterstyle 
			)MSST ON MSST.id_item = BPB.id_item
			LEFT JOIN(
				SELECT id,nama_pterms FROM masterpterms 
			)PTERMS ON PTERMS.id= IH.id_pterms	
			LEFT JOIN(
				SELECT id_coa,v_bankaddress,nama_bank,no_rek FROM masterbank 
			)MSBANK ON MSBANK.id_coa= IH.n_idcoa	
			
			WHERE IAC.n_id = '$id'
			";

			$stmt = mysql_query($q);

			return $stmt;

}

public function getListProduct($id){
		include __DIR__ .'/../../include/conn.php';
		$q = "SELECT 
SO.curr,SO.unit,

if(BPB.curr = 'USD','$','Rp')matauang,

ID.id,ID.id_inv,ID.id_so_det,SOD.id_so,ID.lot,ID.carton carton,ID.qty

		,SOD.qty qty_so
		
		,SOD.size,ID.price,SOD.color,ID.

price unit_price			,if(SOD.size = 'XS', SOD.qty ,0)  XS

			,if(SOD.size = 'S',  SOD.qty ,0)   S

			,if(SOD.size = 'M',  SOD.qty ,0)   M

			,if(SOD.size = 'L',  SOD.qty ,0)   L

			,if(SOD.size = 'XL', SOD.qty ,0)  XL

			,if(SOD.size = 'XXL',SOD.qty ,0) XXL

			,if(SOD.size = '3XL',SOD.qty ,0) 3XL

		,SO.id_cost

		,JCST.product_item,JCST.styleno,JCST.product_group

		,IH.invno,IH.invdate,IH.nw,IH.gw,IH.country_of_origin		

		,(SELECT kode_color FROM mastercolor WHERE nama_color = SOD.color limit 1) kode_color

		FROM invoice_detail ID

	LEFT JOIN(

		SELECT id,id_so,dest,color,size,qty,unit,price FROM so_det

	)SOD ON ID.id_so_det = SOD.id

	LEFT JOIN bpb BPB ON BPB.id_so_det = SOD.id


	LEFT JOIN

		so SO

		ON SOD.id_so = SO.id

			LEFT JOIN (

				SELECT CST.id idcst,CST.id_product,MP.product_item,MP.product_group,CST.styleno FROM act_costing CST

					LEFT JOIN(

						SELECT id,product_item,product_group FROM masterproduct

					)MP ON CST.id_product = MP.id

			)JCST ON SO.id_cost = JCST.idcst

	LEFT JOIN

		invoice_header IH

		ON ID.id_inv = IH.id	

	WHERE ID.id_inv = '$id' GROUP BY ID.id

			";

			$stmt = mysql_query($q);

			return $stmt;

}





}

	$number         = "";

	$phone         = "";

	$id_invoice     = "";

	$date           = "";

	$supplier       = "";

	$alamatsupplier = "";

	$shipped_by     = "";

	$ship_to		= "";

	$po_no			= "";

	$shipper		= "";

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
	$bppbno			= "";
	$manufacture_address = "";
	$pterms		= "";
	$bank		= "";
	$addressbank		= "";
	$username = "";
	$no_rek = "";
	$curr = "";
	$fg_discount = "";
	$discount = 0;
$PdfInvoiceComercial = new PdfInvoiceComercial();


$populate_bppb = $PdfInvoiceComercial->get_populate_bppb($_GET['id']);
$bppb_nya = '';
while($pop = mysql_fetch_array($populate_bppb)){
	if ($bppb_nya != "") {$bppb_nya .= ",";}
	$bppb_nya .= $pop['bppbno'];
}
	
//$bppb_nya = substr($bppb_nya, 0, -1);

$list = $PdfInvoiceComercial->getListData($_GET['id']);

while($row = mysql_fetch_array($list)){

	$number         = $row['v_noinvoicecommercial'];
	$phone           = $row['Phone'];
	$id_invoice		= $row['id_invoice'];
	$date           = $row['invdate'];
	$supplier       = $row['Supplier'];
	$alamatsupplier = $row['alamat'];
	$shipped_by     = $row['shipdesc'];
	$po_no			= $row['buyerno'];
	$shipper		= $row['shipper'];
	$ship_to		= $row['ship_to'];
	$nomorpengangkut= $row['nomor_mobil'];
	$from           = $row['v_from'];
	$to             = $row['v_to'];
	$termsofpayment = $row['terms_of_pay'];
	$etd            = $row['etd'];

	$etalax         = $row['eta'];

	$stylename      	 = $row['itemname'];
	$styleno        	 = $row['styleno'];
	$po             	 = $row['v_pono'];
	$color          	 = $row['Color'];
	$qty            	 = $row['jqty'];
	$price          	 = $row['fob'];
	$amount         	 = $row['n_amount'];
	$beratbersih    	 = $row['j_bersih'];
	$beratkotor     	 = $row['j_kotor'];
	$carton         	 = $row['j_carton'];
	$measurement    	 = $row['measurement'];
	$bppbno				 = $bppb_nya;
	$sono				 = $row['so_no'];
	$manufacture_address =  $row['manufacture_address'];
	$pterms			= $row['nama_pterms'];
	$bank			= $row['nama_bank'];
	$addressbank	= $row['v_bankaddress'];
	$addressbank	= $row['v_bankaddress'];
	$username 	    = $row['v_userpost'];
	$tax			= $row['tax'];
	$no_rek			= $row['no_rek'];;
	$curr			= $row['curr'];
	$fg_discount			= $row['fg_discount'];
	$discount			= $row['n_discount'];
}

$product = $PdfInvoiceComercial->getListProduct($id_invoice)

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

				<?=$bppbno    ?>

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

	<table style="width:100%;font-size:10px;" border="1">

		<tr align="center">

			<th  >

				Style

			</th>

			<th >

				&nbsp;

			</th>	

			<th>

				&nbsp;

			</th>	

			<th>

				Qty

			</th>	

			<th>

				&nbsp;

			</th>		

			<th>

				Unit Price

			</th>			

			<th>

				&nbsp;

			</th>	

			<th>

				Total

			</th>				

		</tr>

<?php 

$my_tot_unit = 0;

$my_tot_price = 0;

$value_tax = 0;
while($baris = mysql_fetch_array($product)){

	$my_tot_unit = $my_tot_unit + $baris['qty'];

	$my_tot_price = $my_tot_price + ($baris['unit_price']*$baris['qty']);

	$mata_uang = $baris['matauang'];

	echo "<tr>";

		echo "<td>".$baris['styleno']."</td>";

		echo "<td>".$baris['color']."</td>";

		echo "<td>".$baris['product_item']."(".$baris['size'].")"."</td>";

		echo "<td align='right'>".$baris['qty']."</td>";

		echo "<td>".$baris['unit']."</td>";

		echo "<td align='right'>".$curr." ".number_format((float)$baris['unit_price'], 2, '.', ',')."</td>";

		echo "<td>".$baris['matauang']."</td>";

		echo "<td align='right'>".number_format((float)$baris['unit_price']*$baris['qty'], 2, '.', ',')."</td>";

	
	echo "</tr>";

	

}
$discount_value = 0;
$total_awal = $my_tot_price;
if($fg_discount == '0'){
	$discount = 0;
}
else{
	$discount_value = ($discount/100)*$my_tot_price;
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
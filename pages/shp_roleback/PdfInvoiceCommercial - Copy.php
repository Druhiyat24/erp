<?php
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
		,if(SUBSTRING(IAC.bpbno,3,1) = '-',BPB.bppbno_int,IAC.bpbno) bpbno
		,IAC.n_id
		,IAC.n_amount
		,IAC.bpbno bpbiac
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
		,SOD.colorso
		,ID.qty_inv jqty
		,SO.buyerno
		,SO.fob
		,SO.so_no
		,SO.curr
		,SO.tax
		,PTERMS.kode_pterms nama_pterms
		,MSBANK.v_bankaddress
		,MSBANK.nama_bank
		,MSBANK.no_rek
		FROM invoice_commercial IAC
			LEFT JOIN(
				SELECT SUM(carton) j_carton,id_inv,max(id_so_det)id_so_det,sum(qty) qty_inv FROM invoice_detail GROUP BY id_inv 
			)ID ON IAC.n_idinvoiceheader = ID.id_inv		
			LEFT JOIN(
				SELECT id,id_so,color colorso FROM so_det
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
				SELECT id,nama_pterms,kode_pterms FROM masterpterms 
			)PTERMS ON PTERMS.id= IH.id_pterms	
			LEFT JOIN(
				SELECT id_coa,v_bankaddress,nama_bank,no_rek FROM masterbank 
			)MSBANK ON MSBANK.id_coa= IH.n_idcoa	
			
			WHERE IAC.n_id = '$id'
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
	$curr    		= "";
	$fg_discount = "";
	$discount = 0;	
	
$PdfInvoiceComercial = new PdfInvoiceComercial();



$populate_bppb = $PdfInvoiceComercial->get_populate_bppb($_GET['id']);
$bppb_nya = '';
while($pop = mysql_fetch_array($populate_bppb)){
	if ($bppb_nya != "") {$bppb_nya .= ",";}
	$bppb_nya .= $pop['bppbno'];
}
	

$list = $PdfInvoiceComercial->getListData($_GET['id']);
while($row = mysql_fetch_array($list)){
	$number         = $row['v_noinvoicecommercial'];
	$date           = $row['d_insert'];
	$supplier       = $row['Supplier'];
	$alamatsupplier = $row['alamat'];
	$shipped_by     = $row['shipdesc'];
	$po_no			= $row['buyerno'];
	$shipper		= $row['shipper'];
	$ship_to		= $row['ship_to'];
	$nomorpengangkut= $row['nomor_mobil'];
	$from           = $row['v_from'];
	$to             = $row['v_to'];
	$termsofpayment = $row['nama_pterms'];
	$etd            = $row['etd'];
	$etalax         = $row['eta'];
	$stylename      = $row['itemname'];
	$styleno        = $row['styleno'];
	$po             = $row['v_pono'];
	$color          = $row['colorso'];
	$qty            = $row['jqty'];
	$price          = number_format((float)$row['fob'], 2, '.', ',');
	$amount         = $qty * $row['fob'];
	$beratbersih    = $row['j_bersih'];
	$beratkotor     = $row['j_kotor'];
	$carton         = $row['j_carton'];
	$measurement    = $row['measurement'];
$fg_discount			= $row['fg_discount'];
	$discount			= $row['n_discount'];	
	if($row['curr'] == 'IDR' ){
		$curr = "Rp";
	}else{
		$curr = "USD";
	}
	
}
$discount_value = 0;
$total_awal = $amount;
if($fg_discount == '0'){
	$discount = 0;
}
else{
	$discount_value = ($discount/100)*$amount;
	$amount   = $amount - $discount_value;
}
$value_tax = ($tax/100)*$amount;
$after_tax = $value_tax+$amount;
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
		INVOICE 
	</div>
	<br/>
	<table style="width:100%;font-size:10px;" >
		<tr>
			<td class="position_top">
			For Account & Risk of
			</td>
			<td class="position_top">
				:
			</td>			
			<td class="position_top" >
				<?=$supplier."<br/>".$alamatsupplier ?>
			</td>
			<td class="position_top" colspan="5">
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
			<td class="position_top" rowspan="3">
				Shipper
			</td>
			<td class="position_top" rowspan="3">
				:
			</td>
			<td class="position_top" rowspan="3">
				PT. NIRWANA ALABARE GARMENT <br/>
				JL. RAYA RANCAEKEK-MAJALAYA NO.289, RT.002 / RW.007 
				DESA SOLOKANJERUK, KECAMATAN SOLOKANJERUK 
				KABUPATEN BANDUNG 40382 JAWA BARAT, INDONESIA 
				
			</td>			
			<td class="position_top" rowspan="3">
				<!--For Account & Risk of -->
			</td>
			<td class="position_top" rowspan="3">
			<!--	:-->
			</td>		
			<td colspan="3" class="position_top" rowspan="3">
				<!--HYBRID  PROMOTIONS LLC
				10711 WALKER STREET
				CYPRESS CA 90630, USA
				-->
			
			</td>
			
			<td class="position_top"  >
				ETD
			</td>
			<td class="position_top">
				:
			</td>				
			<td colspan="3" class="position_top">
			<!--	HYBRID OTAY WHSE
				6060 BUSSINESS CENTER CT
				SUITE 100, SAN DIEGO CA 92154
				USA
			-->
			<?=date('d-M-Y', strtotime($etd)) ?>
			</td>			
			
		</tr>
<tr >

			
			<td class="position_top" >
				ETA LAX
			</td>
			<td class="position_top">
				:
			</td>				
			<td colspan="3" class="position_top">
			<!--	HYBRID OTAY WHSE
				6060 BUSSINESS CENTER CT
				SUITE 100, SAN DIEGO CA 92154
				USA
			-->
			<!--26-Jan-2019--> <?=date('d-M-Y', strtotime($etalax)) ?>
			</td>			
			
		</tr>	
<tr >

			
			<td class="position_top" >
				Ship To
			</td>
			<td class="position_top">
				:
			</td>				
			<td colspan="3" class="position_top">
			<!--	HYBRID OTAY WHSE
				6060 BUSSINESS CENTER CT
				SUITE 100, SAN DIEGO CA 92154
				USA
			-->
			<?=$ship_to ?>
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
				<!--CX776 -->  <?=$shipper     ?>
			</td>	
			<td class="position_top">
				<!--ETD-->
			</td>
			<td class="position_top">
				<!--: -->
			</td>

			<td class="position_top" colspan="3">
			</td>			
			<td class="position_top">
		
			</td>
			<td class="position_top">
				
			</td>

			<td class="position_top">
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
				<!--TO -->
			</td>
			<td>
			<!--	:-->
			</td>			
			<td colspan="7"> 
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
				PER PCS<br/><?=$curr; ?>
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
				<!--$ 1,058  -->  <?=$curr." ".$price ?>
			</td>	
			<td class="position_top" rowspan="4">
				<!--$ 6.257,012 --><?=$curr." ".number_format((float)$total_awal, 2, '.', ',')?>
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
				<!--115686 --> <?=$po_no   ?>
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
			<td>DISCOUNT </td>
			<td colspan="5"> : </td>
			<td><!--5.914-->  </td>
			<td>&nbsp; </td>
			<td><!--$ 6.257,012--><?=$curr." ".number_format((float)$discount_value, 2, '.', ',') ?> </td>
		</tr>
		<tr>
			<td>TOTAL WITH DISCOUNT</td>
			<td colspan="5"> : </td>
			<td><!--5.914--><?=$qty ?>  </td>
			<td>&nbsp; </td>
			<td><!--$ 6.257,012--><?=$curr." ".number_format((float)$amount, 2, '.', ',') ?> </td>
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
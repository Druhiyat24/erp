<?php
error_reporting(E_ERROR);
require_once "../forms/journal_interface.php";

$Get_type=$_GET['type'];

if ($Get_type=='LOCAL'){
	$label_header='Delivery Order';
}else{
	$label_header='Packing List';
}

ob_start();


class PdfInvoice {
	public function myHeaderInvoice($id){
		include __DIR__ .'/../../include/conn.php';

		$q = "	
			SELECT round(SUM(MASTER.total_carton),2)total_carton
			,MASTER.carton_to
			,round(SUM(ifnull(MASTER.ttl_nw,0)),2)nw
			,round(SUM(ifnull(MASTER.ttl_gw,0)),2)gw
			,MASTER.id
			,MASTER.id
			,MASTER.id_inv
			,MASTER.id_so_det
			,MASTER.id_so
			,MASTER.lot
			,MASTER.qty
			,MASTER.carton
			,MASTER.qty_so
			,MASTER.size
			,MASTER.price 
			,MASTER.id_cost
			,MASTER.product_item
			,MASTER.styleno 
			,MASTER.invno
			,MASTER.invdate
			,MASTER.nw_h
			,MASTER.gw_h
			,MASTER.country_of_origin
			,MASTER.id_buyer	
			,MASTER.date_paclist
			,MASTER.pack
			,MASTER.Supplier
			,MASTER.alamat

FROM (
			SELECT 
			    ((ifnull(ID.carton_to,0)) -(ifnull(ID.carton,0)) + 1 )total_carton
				,( ((ifnull(ID.carton_to,0)) -(ifnull(ID.carton,0)) + 1 ) * (ifnull(ID.nw,0)) )ttl_nw
				,( ((ifnull(ID.carton_to,0)) -(ifnull(ID.carton,0)) + 1 ) * (ifnull(ID.gw,0)) )ttl_gw
				 ,(ifnull(ID.carton_to,0))carton_to
				,(ifnull(ID.nw,0))nw__
				,(ifnull(ID.gw,0))gw__
				,ID.id
				,ID.id_inv
				,ID.id_so_det
				,SOD.id_so
				,ID.lot
				,(ID.qty)qty
				,(ID.carton) carton
				,(SOD.qty)qty_so
				,SOD.size
				,(SOD.price)price 
				,SO.id_cost
				,JCST.product_item
				,JCST.styleno 
				,IH.invno
				,IH.invdate
				,IH.nw nw_h,IH.gw gw_h
				,IH.country_of_origin
				,IH.id_buyer	
				,IH.date_paclist
				,IH.v_codepaclist pack
				,MS.Supplier
				,MS.alamat
		FROM invoice_detail ID
	LEFT JOIN(
		SELECT id,id_so,dest,color,size,qty,unit,price FROM so_det
	)SOD ON ID.id_so_det = SOD.id
	LEFT JOIN
		so SO
		ON SOD.id_so = SO.id
			LEFT JOIN (
				SELECT CST.id idcst,CST.id_product,MP.product_item,CST.styleno FROM act_costing CST
					LEFT JOIN(
						SELECT id,product_item FROM masterproduct
					)MP ON CST.id_product = MP.id
			)JCST ON SO.id_cost = JCST.idcst
	LEFT JOIN
		invoice_header IH
		ON ID.id_inv = IH.id	
	LEFT JOIN 
		mastersupplier MS ON MS.Id_Supplier = IH.id_buyer
	WHERE ID.id_inv = '$id' )MASTER GROUP BY MASTER.id_so";
				//echo $q;
		$stmt = mysql_query($q);	
		//$row = mysql_fetch_array($stmt);
			
			
		
		return $stmt;
		
	}
	
	public function myListSizeInvoice($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT  
	round(ifnull(MASTER.carton_to,0),0)carton_to
	,round(ifnull(MASTER.nw,0),2)nw
	,round(ifnull(MASTER.gw,0),2)gw
,MASTER.id
       ,MASTER.id_inv
       ,MASTER.id_so_det
       ,MASTER.id_so
       ,MASTER.lot
       ,MASTER.carton
       ,MASTER.qty
       ,MASTER.size
       ,MASTER.price
       ,MASTER.color
       ,if(MASTER.size = 'XS', MASTER.qty ,0)  XS
       ,if(MASTER.size = 'S',  MASTER.qty ,0)   S
       ,if(MASTER.size = 'M',  MASTER.qty ,0)   M
       ,if(MASTER.size = 'L',  MASTER.qty ,0)   L
       ,if(MASTER.size = 'XL', MASTER.qty ,0)  XL
       ,if(MASTER.size = 'XXL',MASTER.qty ,0) XXL
	   ,if(MASTER.size = '2XL',MASTER.qty ,0) 2XL
       ,if(MASTER.size = '3XL',MASTER.qty ,0) 3XL
       ,if(MASTER.size = '4XL',MASTER.qty ,0) 4XL
       ,if(MASTER.size = '5XL',MASTER.qty ,0) 5XL
       ,MASTER.id_cost
       ,MASTER.product_item
	   ,MASTER.styleno
       ,MASTER.invno
	   ,MASTER.invdate/* 
	   ,MASTER.nw
	   ,MASTER.gw */
	   ,MASTER.country_of_origin		
       ,MASTER.kode_color
FROM(
	SELECT  
	ID.carton_to
	,ID.nw
	,ID.gw	

			,ID.id
			,ID.id_inv
			,ID.id_so_det
			,SOD.id_so
			,ID.lot
			,ID.carton carton
			,SOD.qty qty_so
			,ID.qty
			,SOD.size sod_size
			,IFNULL(MZ.size,SOD.size)size
			,SOD.price
			,SOD.color
			,SO.id_cost
			,JCST.product_item,JCST.styleno
			,IH.invno,IH.invdate,IH.nw nw_h,IH.gw gw_h,IH.country_of_origin		
			,(SELECT kode_color FROM mastercolor WHERE nama_color = SOD.color limit 1) kode_color
			FROM invoice_detail ID
		LEFT JOIN(
			SELECT id,id_so,dest,color,size,qty,unit,price FROM so_det
		)SOD ON ID.id_so_det = SOD.id
		LEFT JOIN
			so SO
			ON SOD.id_so = SO.id
				LEFT JOIN (
					SELECT CST.id idcst,CST.id_product,MP.product_item,CST.styleno FROM act_costing CST
						LEFT JOIN(
							SELECT id,product_item FROM masterproduct
						)MP ON CST.id_product = MP.id
				)JCST ON SO.id_cost = JCST.idcst
		LEFT JOIN
			invoice_header IH
			ON ID.id_inv = IH.id
		LEFT JOIN
			mastersize
		MZ ON SOD.size = MZ.urut
		WHERE ID.id_inv = '$id' GROUP BY ID.id
		)MASTER  GROUP BY MASTER.size";
				//echo $q;
		$stmt = mysql_query($q);	
		//$row = mysql_fetch_array($stmt);
		return $stmt;		
	}	
		
	
	
	
	public function myListInvoice($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT  
	round(ifnull(MASTER.carton_to,0),0)carton_to
	,round(ifnull(MASTER.nw,0),2)nw
	,round(ifnull(MASTER.gw,0),2)gw
,MASTER.id
       ,MASTER.id_inv
       ,MASTER.id_so_det
       ,MASTER.id_so
       ,MASTER.lot
       ,MASTER.carton
       ,MASTER.qty
       ,MASTER.size
       ,MASTER.price
       ,MASTER.color
       ,if(MASTER.size = 'XS', MASTER.qty ,0)  XS
       ,if(MASTER.size = 'S',  MASTER.qty ,0)   S
       ,if(MASTER.size = 'M',  MASTER.qty ,0)   M
       ,if(MASTER.size = 'L',  MASTER.qty ,0)   L
       ,if(MASTER.size = 'XL', MASTER.qty ,0)  XL
       ,if(MASTER.size = 'XXL',MASTER.qty ,0) XXL
	   ,if(MASTER.size = '2XL',MASTER.qty ,0) 2XL
       ,if(MASTER.size = '3XL',MASTER.qty ,0) 3XL
       ,if(MASTER.size = '4XL',MASTER.qty ,0) 4XL
       ,if(MASTER.size = '5XL',MASTER.qty ,0) 5XL
       ,MASTER.id_cost
       ,MASTER.product_item
	   ,MASTER.styleno
       ,MASTER.invno
	   ,MASTER.invdate/* 
	   ,MASTER.nw
	   ,MASTER.gw */
	   ,MASTER.country_of_origin		
       ,MASTER.kode_color
FROM(
	SELECT  
	ID.carton_to
	,ID.nw
	,ID.gw	

			,ID.id
			,ID.id_inv
			,ID.id_so_det
			,SOD.id_so
			,ID.lot
			,ID.carton carton
			,SOD.qty qty_so
			,ID.qty
			,SOD.size sod_size
			,IFNULL(MZ.size,SOD.size)size
			,SOD.price
			,SOD.color
			,SO.id_cost
			,JCST.product_item,JCST.styleno
			,IH.invno,IH.invdate,IH.nw nw_h,IH.gw gw_h,IH.country_of_origin		
			,(SELECT kode_color FROM mastercolor WHERE nama_color = SOD.color limit 1) kode_color
			FROM invoice_detail ID
		LEFT JOIN(
			SELECT id,id_so,dest,color,size,qty,unit,price FROM so_det
		)SOD ON ID.id_so_det = SOD.id
		LEFT JOIN
			so SO
			ON SOD.id_so = SO.id
				LEFT JOIN (
					SELECT CST.id idcst,CST.id_product,MP.product_item,CST.styleno FROM act_costing CST
						LEFT JOIN(
							SELECT id,product_item FROM masterproduct
						)MP ON CST.id_product = MP.id
				)JCST ON SO.id_cost = JCST.idcst
		LEFT JOIN
			invoice_header IH
			ON ID.id_inv = IH.id
		LEFT JOIN
			mastersize
		MZ ON SOD.size = MZ.urut
		WHERE ID.id_inv = '$id' GROUP BY ID.id
		)MASTER ";
				//echo $q;
		$stmt = mysql_query($q);	
		//$row = mysql_fetch_array($stmt);
		return $stmt;		
	}	
	
	public function getTotalCarton($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT carton_to FROM invoice_detail WHERE id_inv = '$id' ORDER BY carton_to DESC LIMIT 1";
		$stmt = mysql_query($q);		
		return $stmt;
	}
	
	public function mySumInvoice(){
		include __DIR__ .'/../../../include/conn.php';
			$q = "
			
SELECT  
	ifnull(MASTER.carton_to,0)carton_to
	,round(ifnull(MASTER.nw,0),2)nw
	,round(ifnull(MASTER.gw,0),2)gw
	,MASTER.id
       ,MASTER.id_inv
       ,MASTER.id_so_det
       ,MASTER.id_so
       ,MASTER.lot
       ,MASTER.carton
       ,MASTER.qty
       ,MASTER.size
       ,MASTER.price
       ,MASTER.color
       ,if(MASTER.size = 'XS', SUM(MASTER).qty ,0)  XS
       ,if(MASTER.size = 'S',  SUM(MASTER).qty ,0)   S
       ,if(MASTER.size = 'M',  SUM(MASTER).qty ,0)   M
       ,if(MASTER.size = 'L',  SUM(MASTER).qty ,0)   L
       ,if(MASTER.size = 'XL', SUM(MASTER).qty ,0)  XL
       ,if(MASTER.size = 'XXL',SUM(MASTER).qty ,0) XXL
	   ,if(MASTER.size = '2XL',SUM(MASTER).qty ,0) 2XL
       ,if(MASTER.size = '3XL',SUM(MASTER).qty ,0) 3XL
       ,if(MASTER.size = '4XL',SUM(MASTER).qty ,0) 4XL
       ,if(MASTER.size = '5XL',SUM(MASTER).qty ,0) 5XL
       ,MASTER.id_cost
       ,MASTER.product_item
	   ,MASTER.styleno
       ,MASTER.invno
	   ,MASTER.invdate
	   ,MASTER.nw
	   ,MASTER.gw
	   ,MASTER.country_of_origin		
       ,MASTER.kode_color
FROM(
	SELECT   
	ID.carton_to
	,ID.nw
	,ID.gw	
	,ID.id
			,ID.id_inv
			,ID.id_so_det
			,SOD.id_so
			,ID.lot
			,ID.carton carton
			,SOD.qty
			,SOD.size sod_size
			,IFNULL(MZ.size,SOD.size)size
			,SOD.price
			,SOD.color
			,SO.id_cost
			,JCST.product_item,JCST.styleno
			,IH.invno,IH.invdate,IH.nw nw_h,IH.gw gw_h,IH.country_of_origin		
			,(SELECT kode_color FROM mastercolor WHERE nama_color = SOD.color limit 1) kode_color
			FROM invoice_detail ID
		LEFT JOIN(
			SELECT id,id_so,dest,color,size,qty,unit,price FROM so_det
		)SOD ON ID.id_so_det = SOD.id
		LEFT JOIN
			so SO
			ON SOD.id_so = SO.id
				LEFT JOIN (
					SELECT CST.id idcst,CST.id_product,MP.product_item,CST.styleno FROM act_costing CST
						LEFT JOIN(
							SELECT id,product_item FROM masterproduct
						)MP ON CST.id_product = MP.id
				)JCST ON SO.id_cost = JCST.idcst
		LEFT JOIN
			invoice_header IH
			ON ID.id_inv = IH.id
		LEFT JOIN
			mastersize
		MZ ON SOD.size = MZ.urut
		WHERE ID.id_inv = '$id' GROUP BY ID.id
		)MASTER GROUP BY MASTER.id_so";
				//echo $q;
		$stmt = mysql_query($q);	
		$row = mysql_fetch_array($stmt);	
	}	
}
$PdfInvoice = new PdfInvoice();
$header = $PdfInvoice->myHeaderInvoice($_GET['id']);
$HeaderIncoiceNo  = '';
$HeaderPacListNo  = '';
$label_header  = $label_header;
$HeaderDate       = '';
$HeaderLot        = '';
$HeaderCountry    = '';
$HeaderQty        = '';
$HeaderNW         = '';
$HeaderGW         = '';
$HeaderCarton     = '';
$HeaderArticleNo  = '';
$HeaderArticleName= '';
$HeaderPackingListDate= '';
$Supplier= '';
$Header_carton_to= 0;
$total_carton_new = $PdfInvoice->getTotalCarton($_GET['id']);
while($row___ = mysql_fetch_array($total_carton_new)){
	$total_carton__ = $row___['carton_to'] ;
	
}
while($row = mysql_fetch_array($header)){
	$HeaderIncoiceNo  = $row['invno'];
	$HeaderPacListNo  = $row['pack'];
	$HeaderDate       = date('d M Y',strtotime($row['date_paclist']));
	$HeaderPackingListDate       = date('d M Y',strtotime($row['paclist']));
	$HeaderCountry    = $row['country_of_origin'];
	$HeaderQty        = $row['jumlahqty'];
	$HeaderNW         = $row['nw'];
	$HeaderGW         = $row['gw'];
	$HeaderCarton     = $total_carton__ ;
	$HeaderLot        = $row['lot'];
	$HeaderArticleNo  = $row['product_item'];
	$HeaderArticleName= $row['styleno'];
	$Supplier		  = $row['Supplier'];
	$Alamat		  	  = $row['alamat'];
}


$list = $PdfInvoice->myListInvoice($_GET['id']);
$array_size = $PdfInvoice->myListSizeInvoice($_GET['id']);
$populasi_size = array();
$exis_size = "";
while($row_size = mysql_fetch_array($array_size)){
		 array_push($populasi_size,trim($row_size['size']));
}


$Slist = $list;

$sum = $PdfInvoice->mySumInvoice($_GET['id']);

print_r($test);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Journal  Voucher</title>

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
 	th{background-color: #95a5a6; padding: 10px;color: #fff}
	
.footer{
	width:100%;
	height:30px;
	margin-top:50px;
	text-align:right;
	
}
.footer_ttd{

	
	
}
#listlist  td  {
	text-align: center !important;
	
}
</style>
	
	
</head>
<body>

<div class="myHeader">

</div>
	
<!--
				Brunotti Europe BV
				Spacelab 10
				3824 MR Amersfoort
				The Netherlands


-->
    <table id="examplefix" class="display responsive" border="none" style="width:100%;font-size:10px; border-collapse: collapse; ">
		<tr>
			<td colspan="6" style="text-align:center;font-size:15px;font-weight:bold;text-decoration:underline;border:0px dot transparent;border-style:hidden">
				<?=$label_header ?>
			</td>
		</tr>		
		<tr>

			<td style="width:70px">
				INVOICE NO :
			</td>
			<td style="width:250px">
				<!--045/EXP/EXIM-NAG/2019  invoice nomor-->
				<?=$HeaderIncoiceNo ?>
			</td>	


			<td style="width:99px">
				PACKING LIST NO :
			</td>
			<td  style="width:400px">
				<!--045/EXP/EXIM-NAG/2019  invoice nomor-->
				<?=$HeaderPacListNo ?>
			</td>	

			
			<td>
				DATE :
			</td>
			<td>
				<!--JAN 10,2019  header date-->
				<?=$HeaderDate ?>
			</td>				
		</tr>
	
	</table> 
    <table id="examplefix" class="display responsive" style="width:100%;font-size:10px; border-collapse: collapse; " border="0">
		<tr > 
			<td style="width:150px;border-left: 1px solid #000000;border-top: 1px solid #000000;font-weight:bold;text-decoration:underline;font-size:11px">
				INVOICE HEADER
			</td>
			<td style="border-top: 1px solid #000000;">
			&nbsp;
			</td>
			<td style="width:150px;border-top: 1px solid #000000;font-weight:bold;text-decoration:underline;font-size:11px">
			DELIVERY ADDRESS </td>
			<td style="border-top: 1px solid #000000;">
			&nbsp;
			</td>			
			<td style="border-left:1px solid #000000;border-top: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr> 
			<td style="border-left: 1px solid #000000;border-bottom: 1px solid #000000;vertical-align: top;;">
				<?php echo $Supplier ?>
				<!--Brunotti Europe BV
				Spacelab 10
				3824 MR Amersfoort
				The Netherlands
				-->
			</td>
			<td style="border-bottom: 1px solid #000000;">
			&nbsp;  
			</td>
			<td style="border-bottom: 1px solid #000000;vertical-align: top;">
			<?php echo $Alamat ?>
<!-- 				Brunotti Europe BV
			    Spacelab 10
			    3824 MR Amersfoort
			    The Netherlands -->
			</td>
			<td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">
			&nbsp;
			</td>			
			<td rowspan="8" style="margin-top:-30px;border-right:1px solid #000000;border-bottom: 1px solid #000000;"><div style="width:280px;position:absolute;right:60px;top:14.5%;font-size:10px">BOX MARKS & NOTS.<br/>
			
			<br/>
			<tr>
				<td>
					Box No
				</td>
				<td>
				:
				</td>
				<td>
					<?=$HeaderArticleNo ?>
				</td>			
			</tr>
			<br/>
			<tr>
				<td>
					Style Name
				</td>
				<td>
				:
				</td>
				<td>
					<?=$HeaderArticleName ?>
				</td>			
			</tr>			
			<br/>
			<tr>
				<td>
					Style No
				</td>
				<td>
				:
				</td>
				<td>
					<?=$HeaderArticleNo ?>
				</td>			
			</tr>				
<br/>	

			<tr>
				<td>
					Size
				</td>
				<td>
				:
				</td>
				<td>
					
				</td>			
			</tr>	

<br/>
			<tr>
				<td>
					Quantity
				</td>
				<td>
				:
				</td>
				<td>
					<?=$HeaderQty?>
				</td>			
			</tr>
<br/>

			<tr>
				<td>
					Lot
				</td>
				<td>
				:
				</td>
				<td>
					<?=$HeaderLot?>
				</td>			
			</tr>
<br/>			
			<tr>
				<td>
					Order No
				</td>
				<td>
				:
				</td>
				<td>
					
				</td>			
			</tr>
			
			</p></td>
		</tr>	

		
		<tr> 
			<td colspan="4" style="border-left: 1px solid #000000;border-right: 1px solid #000000"> 
				Name & address of Manufacturer:
			</td>

		</tr>		

		<tr> 
			<td style="border-left: 1px solid #000000;font-size:10px;"> 
				PT. NIRWANA ALABARE GARMENT 
				JL. RAYA RANCAEKEK-MALAJAYA NO.289 DESA SOLOKAN JERUK 
				KEC. SOLOKAN JERUK 
				KABUPATEN BANDUNG 40382
				JAWA BARAT
				INDONESIA
			</td>
			<td colspan="3" style="border-right: 1px solid #000000;">
			&nbsp;
			</td>	
		
		</tr>	
		<tr> 
			<td colspan="4" style="border-left: 1px solid #000000;border-right: 1px solid #000000;border-bottom: 1px solid #000000;"> 
				Country Of Origin: <!--INDONESIA --> <?=$HeaderCountry ?>
			</td>

			
		</tr>	
		<tr> 
			<td style="border-left: 1px solid #000000;"> 
				Total Qty
			</td>

			<td >
			:
			</td>	
			<td style="text-align:left" >
			<!--77 --> <?=$HeaderQty ?> PCS
			</td>
			<td style="border-right: 1px solid #000000;">
	
			</td>			
	
		</tr>	

			<tr> 
			<td style="border-left: 1px solid #000000;">
				Total Net Weight
			</td>

			<td >
			:
			</td>	
			<td style="text-align:left;">
			<!-- 40.57 --> <?=$HeaderNW  ?> 
			</td>
			<td style="border-right: 1px solid #000000;">
			
			</td>			

		</tr>	
		
			<tr> 
			<td style="border-left: 1px solid #000000;"> 
				Total Gross Weight
			</td>

			<td >
			:
			</td>	
			<td style="text-align:left">
			<!--5.37 --> <?=$HeaderGW  ?> 
 			</td>
			<td style="border-right: 1px solid #000000;">
			
			</td>			

		</tr>	

			<tr> 
			<td style="border-left: 1px solid #000000;border-bottom: 1px solid #000000;"> 
				Total Number Of Cartoon
			</td>

			<td style="border-bottom: 1px solid #000000;">
			:
			</td>	
			<td style="text-align:left;border-bottom: 1px solid #000000;">
			<!-- 3 --> <?=$HeaderCarton  ?> CTNS
			</td>
			<td style="border-bottom: 1px solid #000000;border-right:1px solid #000000">
			
			</td>			

		</tr>			
			<tr> 
			<td colspan="5" style="border-left:1px solid #000000;border-right:1px solid #000000;"> 
				&nbsp;
			</td>

			



			<tr style="border-left:1px solid #000000;border-right:1px solid #000000;">  
			<td > 
				LOT
			</td>

			<td >
			:
			</td>	
			<td style="text-align:left">
			<!-- ZALANDO --> <?=$HeaderLot ?>
			</td>
			<td colspan="2">
			&nbsp;
			</td>			

		</tr>		
			<tr style="border-left:1px solid #000000;border-right:1px solid #000000;"> 
			<td > 
				ARTICLE NO.
			</td>

			<td >
			:
			</td>	
			<td style="text-align:left">		
				<!--99999999999--> <?=$HeaderArticleNo ?>
			</td>
			<td colspan="2">
			&nbsp;
			</td>
		</tr>	
			<tr style="border-left:1px solid #000000;border-right:1px solid #000000;">  
			<td > 
				ARTICLE NAME
			</td>

			<td >
			:
			</td>	
			<td style="text-align:left">
			<!-- DEVANSY MENS KACKET --> <?=$HeaderArticleName ?>
			</td>
			<td colspan="2">
			&nbsp;
			</td>			
		</tr>		
			<tr style="border-left:1px solid #000000;border-right:1px solid #000000;"> 
			<td > 
				COMPOSITION
			</td>

			<td >
			:
			</td>	
			<td style="text-align:left" colspan="2">
			100% POLYESTER MICRO PLAIN SATIN
			</td>
			<td >
			&nbsp;
			</td>			

		</tr>			
    </table>


<table class="display responsive" id="listlist" style="width:100%;font-size:10px;text-align:center; border-collapse: collapse; " border="1">
	<tr style="text-align:center">
		<td colspan="3">
			CARTON NO.
		</td>
		<td>
			ARTICLE NO.
		</td>	
		<td>
			ARTICLE NAME.
		</td>
		<td colspan="2">
			COLOR CODE * COLOR
		</td>	
		<?php
		//SIZE EXTRAK
		for($a=0;$a<count($populasi_size);$a++){
			echo "<td>";
			print_r($populasi_size[$a]); 
			echo "</td>";
			
		}
		
		
		
		?>
<!-- 		<td>
			XS
		</td>
		<td>
			S
		</td>
		<td>
			M
		</td>
		<td>
			L
		</td>
		<td>
			XL
		</td>
		<td>
			XXL
		</td>
		<td>
			2XL
		</td>			
		<td>
			3XL
		</td>
		<td>
			4XL
		</td>
		<td>
			5XL
		</td>	 */	
		<td>
			QTY/CTN
		</td>	
		<td>
			TOTAL/CTN
		</td>
		<td >
			TOTAL QTY PCS
		</td>		
		<td >
			G.W (kg)
		</td>	
		<td >
			N.W (kg)
		</td>	
		</td>	
		<!--<td >
			CTN SIZE
		</td>		-->
	<td>
			QTY/CTN
		</td>	
		<td>
			TTL CTN
		</td>
		<td >
			TTL QTY PCS
		</td>		
		<td >
			G.W (kg)
		</td>	
		<td >
			N.W (kg)
		</td>
		<td >
			TTL G.W (kg)
		</td>	
		<td >
			TTL N.W (kg)
		</td>		
	</tr>
	
<?php 
 		for($a=0;$a<count($populasi_size);$a++){
			${"S_$populasi_size[$a]"} = 0;
		}

		$no         = 0;
/* 		$S_sx       = 0;
		$S_s        = 0;
		$S_m        = 0;
		$S_l        = 0;
		$S_xl       = 0;
		$S_xxl      = 0;
		$S_2xl      = 0;
		$S_3xl      = 0;
		$S_4xl      = 0;
		$S_5xl      = 0; */
		$S_carton   = 0;
		$S_qtpperctn= 0;
		$S_totalqty =0;
		$S_nw       = 0;
		$S_gw       = 0;
		$S_stnsize  = '';
		$slist = [];
		$count_luar = 0;
		while($row = mysql_fetch_array($list)){
			$sumsize = 0;
		for($a=0;$a<count($populasi_size);$a++){
				if($row['size'] == $populasi_size[$a]){
					${"qty_$populasi_size[$a]"} = $row['qty'];
					${"S_$populasi_size[$a]"} = ${"S_$populasi_size[$a]"} + $row['qty'];
					$row['a_qty'][$a] = $row['qty'];
					
				}else{
					${"qty_$populasi_size[$a]"} = 0;
					$row['a_qty'][$a] =0;
				}
				$sumsize = $sumsize + ${"qty_$populasi_size[$a]"};
		}			
			
			
			
			
			$row['sumsize'] = $sumsize;
/* 		    $S_s        = $S_s        +$row['S'];
		    $S_m        = $S_m        +$row['M'];
		    $S_l        = $S_l        +$row['L'];
		    $S_xl       = $S_xl       +$row['XL'];
		    $S_xxl      = $S_xxl      +$row['XXL'];
			$S_2xl      = $S_2xl      +$row['2XL'];
		    $S_3xl      = $S_3xl      +$row['3XL'];
			$S_4xl      = $S_4xl      +$row['4XL'];
			$S_5xl      = $S_5xl      +$row['5XL']; */
		    $S_qtpperctn= $S_qtpperctn+$row['sumsize'];
		    $S_totalqty = $S_totalqty +$row['sumsize'];
		    $S_nw       = $S_nw       +$row['nw'];
		    $S_gw       = $S_gw       +$row['gw'];
			
			$total_carton = $row['carton_to'] - $row['carton'] + 1;
			$row['total_carton']= $row['carton_to'] - $row['carton'] + 1;
			$row['ttl_nw'] = $total_carton *$row['nw'];
			$row['ttl_gw'] = $total_carton *$row['gw'];
			$S_ttl_nw	= $S_ttl_nw + $row['ttl_nw'];
			$S_ttl_gw	= $S_ttl_gw + $row['ttl_gw'];
			$S_carton	= $HeaderCarton;
			$no ++;
			array_push($slist,$row);
?>
	<tr style="text-align:center">
		<td style="text-align:center">
			<?=$row['carton']?>
		</td>
			<td>
			-
		</td>
		<td style="text-align:center">
			<?=$row['carton_to']?>
		</td>	
		<td style="text-align:center">
			<?=$row['product_item'] ?>
		</td style="text-align:center">
		<td>
			<?=$row['styleno'] ?>
		</td>	
		<td style="text-align:center">
			<?=$row['kode_color'] ?>
		</td>		
		<td style="text-align:center">
			<?=$row['color'] ?>
		</td>	
		
<?php
		for($a=0;$a<count($populasi_size);$a++){
			echo "<td>";
			echo ${"qty_$populasi_size[$a]"};
			echo "</td>";
			
		}

?>		
		
		
		
		<td style="text-align:center">
			<?=$row['sumsize']?>
		</td>		
		<td style="text-align:center">
			<?=$total_carton?>
		</td>	
		<td style="text-align:center">
			<?=$row['sumsize']?>
		</td>			
		<td style="text-align:center">
			<?=$row['nw']?>
		</td>
		
		<td style="text-align:center">
			<?=$row['gw']?>
		</td>
		<td style="text-align:center">
			<?=$row['ttl_nw']?>
		</td>
		
		<td style="text-align:center">
			<?=$row['ttl_gw']?>
		</td>		
		<!--< <td style="text-align:center">
			<!-- 60 X 40 X 40 -->
		</td>	-->
	</tr>

<?php
		}

?>
	<tr>
		<td colspan="6" style="text-align:center">
			SUB - TOTAL
		</td>
		<td  style="text-align:center">
			-
		</td>
<?php
		for($a=0;$a<count($populasi_size);$a++){
			echo "<td>";
			echo ${"S_$populasi_size[$a]"};
			echo "</td>";
			
		}

?>			
		
		</td>			
		<td style="text-align:center">
			<?=$S_qtpperctn   ?>
		</td>
		<td style="text-align:center">
		<?=$S_carton   ?>
			
		</td>		
		<td style="text-align:center">
			<?=$S_totalqty   ?>
		</td>		
		<td style="text-align:center">
			<?=$S_nw   ?>
		</td>	
		<td style="text-align:center">
			<?=$S_gw   ?>
		</td>			
		<td style="text-align:center">
			<?=$S_ttl_nw   ?>
		</td>	
		<td style="text-align:center">
			<?=$S_ttl_gw ?>
		</td>	
	
	</tr>	
	<tr>
		<td colspan="6" <td style="text-align:center">>
			GRAND - TOTAL
		</td>
		<td  style="text-align:center">
			-
		</td>
<?php
		for($a=0;$a<count($populasi_size);$a++){
			echo "<td>";
			echo ${"S_$populasi_size[$a]"};
			echo "</td>";
			
		}


?>


		<td style="text-align:center">
			<?=$S_qtpperctn   ?>
		</td>
		<td style="text-align:center">
			<?=$S_carton   ?>
		
		</td>		
		<td style="text-align:center">
			<?=$S_totalqty   ?>
		</td>		
		<td style="text-align:center">
			<?=$S_nw   ?>
		</td>	
		<td style="text-align:center">
			<?=$S_gw   ?>
		</td>			
		
		<td style="text-align:center">
			<?=$S_ttl_nw   ?>
		</td>	
		<td style="text-align:center">
			<?=$S_ttl_gw ?>
		</td>		
	
	</tr>		
</table>
<br/>

<table class="display responsive" style="width:90%;font-size:10px; border-collapse: collapse; " border="1">
		<tr>
		<td colspan="11">SUMMARY</td>
	</tr>	
	<tr>
	
		<td>ARTICLE NO. </td>
		<td>ARTICLE NAME </td>
		<td colspan="2">COLOR CODE & COLOR </td>
<?php
		for($a=0;$a<count($populasi_size);$a++){
			echo "<td>";
			print_r($populasi_size[$a]); 
			echo "</td>";
			
		}


?>
		<td>TOTAL</td>
		<td>CTNS</td>
	</tr>
		<?php for ($x=0;$x<count($slist);$x++){ ?>
	<tr style="text-align:center">
		<td style="text-align:center"><?=$slist[$x]['product_item'] ?></td>
		<td style="text-align:center"><?=$slist[$x]['styleno'] ?></td>
		<td style="text-align:center"><?=$slist[$x]['kode_color'] ?></td>
		<td style="text-align:center"><?=$slist[$x]['color'] ?></td>

<?php
		for($a=0;$a<count($populasi_size);$a++){
			echo "<td>";
			print_r($slist[$x]['a_qty'][$a]); 
			echo "</td>";
			
		}


?>


		<td style="text-align:center"><?=$slist[$x]['sumsize'] ?></td>
		<td style="text-align:center"><?=$slist[$x]['total_carton'] ?></td>
	</tr>	
		<?php } 
		/*$S_sx       
		$S_s        
		$S_m        
		$S_l        
		$S_xl       
		$S_xxl      
		$S_3xl      
		$S_qtpperctn
		$S_totalqty 
		$S_nw       
		$S_gw       
		$S_carton
*/		
		?>
	<tr style="text-align:center">
		<td colspan="4" style="text-align:center">GRAND TOTAL</td>
<?php
		for($a=0;$a<count($populasi_size);$a++){
			echo "<td>";
			print_r(${"S_$populasi_size[$a]"}); 
			echo "</td>";
			
		}


?>
		<td style="text-align:center"><?=$S_totalqty?></td>
		<td style="text-align:center"><?=$S_carton?></td>
	</tr>		
</table>

<div class="footer">
	<div class="footer_ttd">
	______________________________________
	<br/>
	Authorized Signature&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
</div>
</body>
</html>
<?php
// Store output into vars
$html = ob_get_clean();
//exit($html);
// Convert output into pdf
include("../../mpdf57/mpdf.php");

$mpdf=new mPDF('utf-8', 'A4-L');
$mpdf->setFooter('{PAGENO}');
//$stylesheet = file_get_contents(__DIR__ .'/../../bootstrap/css/bootstrap.min.css');
//$mpdf->WriteHTML($stylesheet, 1); // CSS Script goes here.
$mpdf->WriteHTML($html);
$mpdf->Output();
?>
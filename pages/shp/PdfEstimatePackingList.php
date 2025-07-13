<?php
error_reporting(E_ERROR);
require_once "../forms/journal_interface.php";



ob_start();


class PdfInvoice {
	public function myHeaderInvoice($id){
		include __DIR__ .'/../../include/conn.php';
		$q = "SELECT 	
		EPL.n_id
		,EPL.n_so_id
		,date(EPL.d_insert)date
		,EPL.v_from
		,EPL.v_to
		,SO.so_no
		,SOD.qty
		,SOD.price
		,JCST.product_item
		,JCST.styleno
		FROM shp_estimatepackinglist EPL
		LEFT JOIN(
			SELECT id,so_no,id_cost FROM so
		)SO ON EPL.n_so_id = SO.id
		LEFT JOIN(
			SELECT id_so,dest,sum(qty)qty,sum(price)price FROM so_det GROUP BY id_so
		)SOD ON SO.id = SOD.id_so
		
			LEFT JOIN (
				SELECT CST.id idcst,CST.id_product,MP.product_item,CST.styleno FROM act_costing CST
					LEFT JOIN(
						SELECT id,product_item FROM masterproduct
					)MP ON CST.id_product = MP.id
			)JCST ON SO.id_cost = JCST.idcst	
		WHERE EPL.n_id = '$id'";
				//echo $q;
		$stmt = mysql_query($q);	
		//$row = mysql_fetch_array($stmt);
			
			
		
		return $stmt;
		
	}
	public function myListInvoice($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "
			SELECT 
			 PX.id
            ,PX.lot
            ,PX.id_inv
            ,PX.so_no
            ,PX.invno
			,PX.carton
            ,PX.invdate
            ,PX.nw
            ,PX.gw
            ,PX.jumlahqty
            ,PX.id_cost
            ,PX.size
            ,PX.color
            ,PX.product_item
            ,PX.styleno
            ,PX.kode_color
			,PX.XS
			,PX.S
			,PX.M
			,PX.L
			,PX.XL
			,PX.XXL
			,PX.3XL
			,PX.n_so_id
FROM (
SELECT 		EPL.n_id id
			,'' lot
			,'' id_inv
			,'' carton
			,JSO.so_no
			,'' invno
			,'' invdate
			,'' nw
			,'' gw
			,JSO.jumlahqty
			,JSO.id_cost
			,JSO.size
			,if(JSO.size = 'XS', JSO.jumlahqty ,0)  XS
			,if(JSO.size = 'S', JSO.jumlahqty ,0)   S
			,if(JSO.size = 'M', JSO.jumlahqty ,0)   M
			,if(JSO.size = 'L', JSO.jumlahqty ,0)   L
			,if(JSO.size = 'XL', JSO.jumlahqty ,0)  XL
			,if(JSO.size = 'XXL', JSO.jumlahqty ,0) XXL
			,if(JSO.size = '3XL', JSO.jumlahqty ,0) 3XL
			,JSO.color
			,JCST.product_item
			,JCST.styleno
			,(SELECT kode_color FROM mastercolor WHERE nama_color = JSO.color limit 1) kode_color
			,EPL.n_so_id
			FROM shp_estimatepackinglist EPL

			LEFT JOIN(
				SELECT SO.so_no,SO.id, SO.id_cost,SOD.qty jumlahqty,SOD.color,SOD.size FROM so SO 
					LEFT JOIN(
						SELECT id_so,qty,color,size FROM so_det
					)SOD ON SO.id = SOD.id_so 
			)JSO ON EPL.n_so_id = JSO.id
			LEFT JOIN (
				SELECT CST.id idcst,CST.id_product,MP.product_item,CST.styleno FROM act_costing CST
					LEFT JOIN(
						SELECT id,product_item FROM masterproduct
					)MP ON CST.id_product = MP.id
			)JCST ON JSO.id_cost = JCST.idcst

			WHERE EPL.n_id = '$id'
			) PX GROUP BY PX.size";
				//echo $q;
		$stmt = mysql_query($q);	
		//$row = mysql_fetch_array($stmt);
		
			
		
		return $stmt;		
		
	}	
	public function mySumInvoice(){
		include __DIR__ .'/../../../include/conn.php';
			$q = "
			
SELECT 
			 SM.id
            ,SM.lot
            ,SM.id_inv
            ,SM.so_no
            ,SM.invno
			,SM.carton
            ,SM.invdate
            ,SM.nw
            ,SM.gw
            ,SM.jumlahqty
            ,SM.id_cost
            ,SM.size
            ,SM.color
            ,SM.product_item
            ,SM.styleno
            ,SM.kode_color
			,SUM(SM.XS)  XS 
			,SUM(SM.S)S
			,SUM(SM.M)M
			,SUM(SM.L)L
			,SUM(SM.XL) XL
			,SUM(SM.XXL) XXL
			,SUM(SM.3XL) 3XL
			,SM.n_so_id
			FROM (	
SELECT 
			 PX.id
            ,PX.lot
            ,PX.id_inv
            ,PX.so_no
            ,PX.invno
			,PX.carton
            ,PX.invdate
            ,PX.nw
            ,PX.gw
            ,PX.jumlahqty
            ,PX.id_cost
            ,PX.size
            ,PX.color
            ,PX.product_item
            ,PX.styleno
            ,PX.kode_color
			,PX.XS
			,PX.S
			,PX.M
			,PX.L
			,PX.XL
			,PX.XXL
			,PX.3XL
			,PX.n_so_id
FROM (
SELECT 		EPL.n_id id
			,'' lot
			,'' id_inv
			,'' carton
			,JSO.so_no
			,'' invno
			,'' invdate
			,'' nw
			,'' gw
			,JSO.jumlahqty
			,JSO.id_cost
			,JSO.size
			,if(JSO.size = 'XS', JSO.jumlahqty ,0)  XS
			,if(JSO.size = 'S', JSO.jumlahqty ,0)   S
			,if(JSO.size = 'M', JSO.jumlahqty ,0)   M
			,if(JSO.size = 'L', JSO.jumlahqty ,0)   L
			,if(JSO.size = 'XL', JSO.jumlahqty ,0)  XL
			,if(JSO.size = 'XXL', JSO.jumlahqty ,0) XXL
			,if(JSO.size = '3XL', JSO.jumlahqty ,0) 3XL
			,JSO.color
			,JCST.product_item
			,JCST.styleno
			,(SELECT kode_color FROM mastercolor WHERE nama_color = JSO.color limit 1) kode_color
			,EPL.n_so_id
			FROM shp_estimatepackinglist EPL

			LEFT JOIN(
				SELECT SO.so_no,SO.id, SO.id_cost,SOD.qty jumlahqty,SOD.color,SOD.size FROM so SO 
					LEFT JOIN(
						SELECT id_so,qty,color,size FROM so_det
					)SOD ON SO.id = SOD.id_so 
			)JSO ON EPL.n_so_id = JSO.id
			LEFT JOIN (
				SELECT CST.id idcst,CST.id_product,MP.product_item,CST.styleno FROM act_costing CST
					LEFT JOIN(
						SELECT id,product_item FROM masterproduct
					)MP ON CST.id_product = MP.id
			)JCST ON JSO.id_cost = JCST.idcst

			WHERE EPL.n_id = '$id'
			) PX GROUP BY PX.size) SM";
				//echo $q;
		$stmt = mysql_query($q);	
		$row = mysql_fetch_array($stmt);	
	}	
}
$PdfInvoice = new PdfInvoice();
$header = $PdfInvoice->myHeaderInvoice($_GET['id']);
$HeaderIncoiceNo  = '';
$HeaderDate       = '';
$HeaderLot        = '';
$HeaderCountry    = '';
$HeaderQty        = '';
$HeaderNW         = '';
$HeaderGW         = '';
$HeaderCarton     = '';
$HeaderArticleNo  = '';
$HeaderArticleName= '';

while($row = mysql_fetch_array($header)){
	$HeaderIncoiceNo  = $row['invno'];
	$HeaderDate       = date('d M Y',strtotime($row['invdate']));
	$HeaderCountry    = $row['country_of_origin'];
	$HeaderQty        = $row['jumlahqty'];
	$HeaderNW         = $row['nw'];
	$HeaderGW         = $row['gw'];
	$HeaderCarton     = $row['carton'];
	$HeaderLot        = $row['lot'];
	$HeaderArticleNo  = $row['product_item'];
	$HeaderArticleName= $row['styleno'];
}


$list = $PdfInvoice->myListInvoice($_GET['id']);

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
			<td colspan="4" style="text-align:center;font-size:15px;font-weight:bold;text-decoration:underline;border:0px dot transparent;border-style:hidden">
				Estimate Packing List
			</td>
		</tr>		
		<tr>

			<td style="width:150px">
				INVOICE NO :
			</td>
			<td style="width:570px;border:none" >
				<!--045/EXP/EXIM-NAG/2019  invoice nomor-->
				<?=$HeaderIncoiceNo ?>
			</td style="width:150px">	
			
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
			<td style="border-left: 1px solid #000000;border-bottom: 1px solid #000000;">
				Brunotti Europe BV
				Spacelab 10
				3824 MR Amersfoort
				The Netherlands
			</td>
			<td style="border-bottom: 1px solid #000000;">
			&nbsp;  
			</td>
			<td style="border-bottom: 1px solid #000000;">
				Brunotti Europe BV
			    Spacelab 10
			    3824 MR Amersfoort
			    The Netherlands
			</td>
			<td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">
			&nbsp;
			</td>			
			<td rowspan="8" style="margin-top:-30px;border-right:1px solid #000000;border-bottom: 1px solid #000000;"><div style="width:280px;position:absolute;right:60px;top:14.5%;font-size:10px">BOX MARKS & NOTS.<br/>
			
			<br/>
			Box No.<br/>
			Style Name.<br/>
			Style No.<br/>
			Colour No.<br/>
			Colour Name.<br/>
			Size<br/>
			Quantity<br/>
			Lot<br/>
			Order No.<br/>
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
			<td style="text-align:right" >
			<!--77 --> <?=$HeaderQty ?>
			</td>
			<td style="border-right: 1px solid #000000;">
			PCS
			</td>			
	
		</tr>	

			<tr> 
			<td style="border-left: 1px solid #000000;">
				Total Net Weight
			</td>

			<td >
			:
			</td>	
			<td style="text-align:right;">
			<!-- 40.57 --> <?=$HeaderNW  ?>
			</td>
			<td style="border-right: 1px solid #000000;">
			PCS
			</td>			

		</tr>	
		
			<tr> 
			<td style="border-left: 1px solid #000000;"> 
				Total Gross Weight
			</td>

			<td >
			:
			</td>	
			<td style="text-align:right">
			<!--5.37 --> <?=$HeaderGW  ?>
 			</td>
			<td style="border-right: 1px solid #000000;">
			PCS
			</td>			

		</tr>	

			<tr> 
			<td style="border-left: 1px solid #000000;border-bottom: 1px solid #000000;"> 
				Total Number Of Cartoon
			</td>

			<td style="border-bottom: 1px solid #000000;">
			:
			</td>	
			<td style="text-align:right;border-bottom: 1px solid #000000;">
			<!-- 3 --> <?=$HeaderCarton  ?>
			</td>
			<td style="border-bottom: 1px solid #000000;border-right:1px solid #000000">
			CTNS
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
		<td>
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
			3XL
		</td>	
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
		<td >
			CTN SIZE
		</td>		
	</tr>
	
<?php 
		$no         = 0;
		$S_sx       = 0;
		$S_s        = 0;
		$S_m        = 0;
		$S_l        = 0;
		$S_xl       = 0;
		$S_xxl      = 0;
		$S_3xl      = 0;
		$S_carton   = 0;
		$S_qtpperctn= 0;
		$S_totalqty =0;
		$S_nw       = 0;
		$S_gw       = 0;
		$S_stnsize  = 0.29;
		$slist = [];
		while($row = mysql_fetch_array($list)){
			$row['sumsize'] = $row['XS'] + $row['S'] + $row['M'] + $row['L'] + $row['XL'] + $row['XXL'] + $row['3XL']; 
			$S_sx       = $S_sx       +$row['XS'];
		    $S_s        = $S_s        +$row['S'];
		    $S_m        = $S_m        +$row['M'];
		    $S_l        = $S_l        +$row['L'];
		    $S_xl       = $S_xl       +$row['XL'];
		    $S_xxl      = $S_xxl      +$row['XXL'];
		    $S_3xl      = $S_3xl      +$row['3XL'];
		    $S_qtpperctn= $S_qtpperctn+$row['sumsize'];
		    $S_totalqty = $S_totalqty +$row['sumsize'];
		    $S_nw       = $S_nw       +$row['nw'];
		    $S_gw       = $S_gw       +$row['gw'];
			$S_carton	= $S_carton   +$row['carton'];
			
			
			$no ++;
			array_push($slist,$row);
?>
	<tr>
		<td>
			1
		</td>
			<td>
			-
		</td>
		<td>
			<?=$no; ?>
		</td>	
		<td>
			<?=$row['product_item'] ?>
		</td>
		<td>
			<?=$row['styleno'] ?>
		</td>	
		<td>
			<?=$row['kode_color'] ?>
		</td>		
		<td>
			<?=$row['color'] ?>
		</td>	
		<td>
			<?=$row['XS'] ?>
		</td>		
		<td>
			<?=$row['S'] ?>
		</td>	
		<td>
			<?=$row['M'] ?>
		</td>	
		<td>
			<?=$row['L'] ?>
		</td>	
		<td>
			<?=$row['XL'] ?>
		</td>	
		<td>
			<?=$row['XXL'] ?>
		</td>	
		<td>
			<?=$row['3XL'] ?>
		</td>	
		<td>
			<?=$row['sumsize']?>
		</td>		
		<td>
			<?=$row['carton']?>
		</td>	
		<td>
			<?=$row['sumsize']?>
		</td>			
		<td>
			<?=$row['nw']?>
		</td>		
		<td>
			<?=$row['gw']?>
		</td>
		<td>
			60 X 40 X 40
		</td>		
	</tr>

<?php
		}

?>
	<tr>
		<td colspan="7" style="text-align:center">
			SUB - TOTAL
		</td>

		<td style="text-align:center">
			<?=$S_sx   ?>
		</td>
		<td style="text-align:center">
			<?=$S_s   ?>
		</td>
		<td style="text-align:center">
			<?=$S_m   ?>
		</td>
		<td style="text-align:center">
			<?=$S_l   ?>
		</td>
		<td style="text-align:center">
			<?=$S_xl   ?>
		</td>
		<td style="text-align:center">
			<?=$S_xxl   ?>
		</td>	
		<td style="text-align:center">
			<?=$S_3xl   ?>
		</td>	
		
		<td style="text-align:center">
			<?=$S_qtpperctn   ?>
		</td>
		<td >
			<?=$S_totalqty   ?>
		</td>		
		<td style="text-align:center">
			<?=$S_carton   ?>
		</td>		
		<td style="text-align:center">
			<?=$S_nw   ?>
		</td>	
		<td style="text-align:center">
			<?=$S_gw   ?>
		</td>			
	
		<td style="text-align:center">
			<?=$S_stnsize   ?>
		</td>		
	</tr>	
	<tr>
		<td colspan="7" <td style="text-align:center">>
			GRAND - TOTAL
		</td>

		<td style="text-align:center">
			<?=$S_sx   ?>
		</td>
		<td style="text-align:center">
			<?=$S_s   ?>
		</td>
		<td style="text-align:center">
			<?=$S_m   ?>
		</td>
		<td style="text-align:center">
			<?=$S_l   ?>
		</td>
		<td style="text-align:center">
			<?=$S_xl   ?>
		</td>
		<td style="text-align:center">
			<?=$S_xxl   ?>
		</td>	
		<td style="text-align:center">
			<?=$S_3xl   ?>
		</td>	
		
		<td style="text-align:center">
			<?=$S_qtpperctn   ?>
		</td>
		<td style="text-align:center">
			<?=$S_totalqty   ?>
		</td>		
		<td style="text-align:center">
			<?=$S_carton   ?>
		</td>		
		<td style="text-align:center">
			<?=$S_nw   ?>
		</td>	
		<td style="text-align:center">
			<?=$S_gw   ?>
		</td>			
	
		<td style="text-align:center">
			<?=$S_stnsize   ?>
		</td>		
	</tr>		
</table>
<br/>

<table class="display responsive" style="width:90%;font-size:10px; border-collapse: collapse; " border="1">
		<tr>
		<td colspan="13">SUMMARY</td>
	</tr>	
	<tr>
	
		<td>ARTICLE NO. </td>
		<td>ARTICLE NAME </td>
		<td colspan="2">COLOR CODE & COLOR </td>
		<td>XS</td>
		<td>S</td>
		<td>M</td>
		<td>L</td>
		<td>XL</td>
		<td>XXL</td>
		<td>3XL</td>
		<td>TOTAL</td>
		<td>CTNS</td>
	</tr>
		<?php for ($x=0;$x<count($slist);$x++){ ?>
	<tr>
		<td><?=$slist[$x]['product_item'] ?></td>
		<td><?=$slist[$x]['styleno'] ?></td>
		<td><?=$slist[$x]['kode_color'] ?></td>
		<td><?=$slist[$x]['color'] ?></td>
		<td><?=$slist[$x]['XS'] ?></td>
		<td><?=$slist[$x]['S'] ?></td>
		<td><?=$slist[$x]['M'] ?></td>
		<td><?=$slist[$x]['L'] ?></td>
		<td><?=$slist[$x]['XL'] ?></td>
		<td><?=$slist[$x]['XXL'] ?></td>
		<td><?=$slist[$x]['3XL'] //sumsize?></td>
		<td><?=$slist[$x]['sumsize'] ?></td>
		<td><?=$slist[$x]['carton'] ?></td>
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
	<tr>
		<td colspan="4">GRAND TOTAL</td>
		<td><?=$S_sx?></td>
		<td><?=$S_s?></td>
		<td><?=$S_m?></td>
		<td><?=$S_l?></td>
		<td><?=$S_xl?></td>
		<td><?=$S_xxl?></td>
		<td><?=$S_3xl?></td>
		<td><?=$S_totalqty?></td>
		<td><?=$S_carton?></td>
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
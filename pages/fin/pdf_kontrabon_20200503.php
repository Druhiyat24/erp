<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';
include_once '../forms/journal_interface.php';
$images = '../../include/img-01.png';
$id=$_GET['id'];


$sql="
		SELECT       a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,MT_H.percentage percentage_h
					,( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) )value_pph
					,( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )value_ppn
					,a.amount_original
					,a.n_ppn
					, (ifnull(a.amount_original,0)  + ( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )  )nilai_with_ppn
					, (ifnull(a.amount_original,0) - ( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) ) + ( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )  )nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.credit
					,POH.pono
					,POH.podate
					,BPB.bpbdate
					,MS.Supplier
					,ACT.kpno
					,b.inv_supplier invno
				    ,DATE_ADD(b.date_journal, INTERVAL POH.jml_pterms DAY) as jatuh_tempo
					,BPB.unit
					FROM fin_journal_d a
					INNER JOIN (SELECT inv_supplier,date_journal,id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 

					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT_H ON MT_H.idtax = b.n_pph
					LEFT JOIN bpb BPB ON BPB.id = a.id_bpb
					LEFT JOIN po_header POH ON POH.id = a.id_po
					LEFT JOIN po_item  POI ON POI.id = a.id_po_det
					LEFT JOIN mastersupplier MS ON MS.Id_Supplier = a.id_supplier
				/*LEFT JOIN jo JO ON JO.id = BPB.id_jo */
				LEFT JOIN jo_det JOD ON JOD.id_jo = BPB.id_jo
				LEFT JOIN so SO ON SO.id = JOD.id_so
				LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost					
					WHERE 1=1 AND a.id_journal = '$id' AND
					a.credit > 0 AND  a.id_coa NOT IN('15204','15207') AND a.reff_doc IS NOT NULL GROUP BY a.id_journal,a.row_id
					
					
		UNION ALL
/* PAJAK */

			SELECT   a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,'0' value_pph
					,'0' value_ppn
					,'0' percentage_h
					,a.debit amount_original
					,a.n_ppn
					, (-1*(a.debit))nilai_with_ppn
					, (-1*(a.debit))nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.debit
					,'' pono
					,'' podate
					,'' bpbdate
					,'' Supplier
					,'' kpno
					,'' invno
				    ,'' jatuh_tempo	
					,'' unit
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					WHERE 1=1 AND a.id_journal = '$id' AND
					a.debit > 0 AND
					a.id_coa NOT IN('15204','15207') AND c.id_coa IS NOT NULL GROUP BY a.id_journal,a.row_id
 







/* PAJAK */		

UNION ALL


/* LAIN-LAIN */

			SELECT   a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,'0' value_pph
					,'0' value_ppn
					,'0' percentage_h
					,a.debit amount_original
					,a.n_ppn
					, ((a.debit))nilai_with_ppn
					, ((a.debit))nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.debit
					,'' pono
					,'' podate
					,'' bpbdate
					,'' Supplier
					,'' kpno
					,'' invno
				    ,'' jatuh_tempo		
					,'' unit
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					WHERE 1=1 AND a.id_journal = '$id' AND
					a.debit > 0 AND
					a.id_coa NOT IN('15204','15207') AND a.reff_doc IS NULL AND c.id_coa IS NULL GROUP BY a.id_journal,a.row_id
/* LAIN-LAIN */					
		";
if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}
$rs=mysql_fetch_array(mysql_query($sql));
$v_listcode=$_GET['id'];
//$value_pph = get_pph_kontra_bon($id,"KB");
$user = $rs['v_userinsert'];
if($rs['Supplier'] !=''){
	$Supplier=$rs['Supplier'];
}
$tanggal_input=fd_view($rs['date_journal']);
if(ISSET($rs['kpno'])){
	$ws =$rs['kpno'];
}else{
	$ws = "-";
}
#$tanggal_berlaku=fd_view($rs['tanggal_berlaku']);
$style1='style="width:70%;"';
$style2='style="width:1%;"';
$style3='style="width:29%;"';
$styleborder="border: 1px solid black;";
$styleborder2="border-left: 1px solid white;";
$style4='style="'.$styleborder.'"';
$style5='style="'.$styleborder2.'"';
$tbl_header='
  <table style="width:100%;height:500px;border-collapse: collapse;">
    <tr>
      <td style="width:30%;text-align:center;'.$styleborder.'"><img src='.$images.' style="heigh:70px; width:80px;"></td>
       <td style="width:20%;text-align:center;"></td>
      <td style="width:50%;'.$styleborder.'text-align:center;"><font color=black>PT.NIRWANA ALABARE GARMENT</font></td>
    </tr>
  </table>
  <hr />';
$tbl_no='
<table width="100%">
<tr>
	<td ><h5>KONTRA BON : '.$v_listcode.'</h5></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><h5>'.$Supplier.'</h5></td>
</tr>
</table>
<table style="width:100%;height:50%;font-size:10px; border-spacing: 15px;">
    <tr>

      <td style="width:15%;text-align:left;">DATE CREATED  :</td>
		<td style="text-align:left;">#WS :</td>
    </tr>
    <tr>

      <td style="text-align:left;">'.$tanggal_input.'</td>
     <td style="text-align:left;">'.$ws.'</td>
    </tr>
</table>
<hr />';


ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

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



	font-size:12px;

	

}



</style>

	

  <title>KONTRA BON</title>
</head>
<body style=" padding-left:5%; padding-right:5%;">
<?php echo $tbl_header ?>
<br>
<?php echo $tbl_no ?>
<table  border="1" cellspacing="0" style="width:100%;font-size:12px;border-spacing:2px;">
  <tr>

      <th style="width:20%;<?php echo $styleborder?>text-align:center;">No.Po</th>
      <th style="width:20%;<?php echo $styleborder?>text-align:center;">No.Bbp</th>
      <th style="width:auto;<?php echo $styleborder?>text-align:center;">Tanggal Bpb</th>
      <th style="width:auto;<?php echo $styleborder?>text-align:center;">TANGGAL PO</th>
	  <th style="width:15%;<?php echo $styleborder?>text-align:center;">Item Description</th>
      <th colspan="2" style="width:15%;<?php echo $styleborder?>text-align:center;">Quantity</th>
      <th colspan="2" style="width:15%;<?php echo $styleborder?>text-align:center;">Unit Price</th>
      <th colspan="2" style="width:15%;<?php echo $styleborder?>text-align:center;">Amount Bpb</th>
      <th style="width:15%;<?php echo $styleborder?>text-align:center;">Jatuh Tempo</th>
      <th style="width:15%;<?php echo $styleborder?>text-align:center;">No Invoice</th>	  
	  <th style="width:15%;<?php echo $styleborder?>text-align:center;">No.Faktur Pajak</th>
    </tr>
<tbody >
<?php  
$query=mysql_query($sql)or die(mysql_error());
$total = 0;
$total_curr  = 0;
$total_utang = 0;
$mata_uang = "";
$pajak_ppn = 0;
$pajak_pph = 0;
$fg_tax = 0;
$ppn_nya = 0;
$pph_nya = 0;
$total_value_pph = 0;
$total_pph_header = 0;
$invno ='';
$qty_total = 0;
$arr = array();
while ($data=mysql_fetch_array($query)) {
	if(!ISSET($data['pono']) || !EMPTY($data['pono']) ){
		$pono = $data['pono'];
	}
	if(!ISSET($data['bpb_ref']) || !EMPTY($data['bpb_ref']) ){
		if($data['bpb_ref'] !=''){
			$bpb_ref = $data['bpb_ref'];
		}
	}	
	if(!ISSET($data['bpbdate']) || !EMPTY($data['bpbdate']) ){
		if($data['bpbdate'] !=''){
			$bpbdate = fd_view($data['bpbdate']);
		}
	}
	if(!ISSET($data['podate']) || !EMPTY($data['podate']) ){
		if($data['podate'] !=''){
			$podate = fd_view($data['podate']);
		}
	}
	if(!ISSET($data['qty']) || !EMPTY($data['qty']) ){
		if($data['qty'] !=''){
			$qty_v = number_format((float)$data['qty'], 0, '.', ',');
			$qty = $data['qty'];
			$unit = $data['unit'];
		}else{
			$qty_v = number_format((float)0, 0, '.', ',');;
			$qty = 0;
		}
	}	
	if(!ISSET($data['curr']) || !EMPTY($data['curr']) ){
		if($data['curr'] !=''){
			$curr = $data['curr'];
		}
	}
	if(!ISSET($data['price']) || !EMPTY($data['price']) ){
		if($data['price'] !=''){
			$price =  number_format((float)$data['price'], 2, '.', ',');
		}
	}	
	
	if(!ISSET($data['jatuh_tempo']) || !EMPTY($data['jatuh_tempo']) ){
		if($data['jatuh_tempo'] !=''){
			$jatuh_tempo = fd_view($data['jatuh_tempo']);
		}
	}	
		if(!ISSET($data['invno']) || !EMPTY($data['invno']) ){
		if($data['invno'] !=''){
			$invno = $data['invno'];
		}else{
			$invno = '';
		}
	}	
	if(!ISSET($data['v_fakturpajak']) || !EMPTY($data['v_fakturpajak']) ){
		if($data['v_fakturpajak'] !=''){
			$v_fakturpajak = $data['v_fakturpajak'];
		}else{
			$v_fakturpajak = '';
		}
	}		
	
	// perulangna untuk menampilkan data
	?>
   <tr>
      <td style="width:20%;text-align:center;"><?php echo $pono ?></td>
      <td style="width:20%;text-align:center;"><?php echo $bpb_ref ?></td>
      <td style="width:auto;text-align:center;"><?php echo $bpbdate //$data['no_po'] ?></td>
	  <td style="width:auto;text-align:center;"><?php echo  $podate //$data['no_po'] ?></td>
      <td style="width:15%;text-align:center;"><?php echo $data['description'] ?></td>
	  <td style="width:auto;text-align:right;border-right:none"><?php echo $qty_v ?></td> 
	  <td style="width:auto;text-align:left;border-left:none"><?php echo $unit ?></td> 
      <td style="width:3%;text-align:left;border-right:none"><?php  echo $data['curr']  ?></td>
	  <td style="width:auto;text-align:right;border-left:none"><?php echo $price ?></td>
      <td style="width:3%;text-align:left;border-right:none"><?php  echo $data['curr']  ?></td>
	  <td style="width:auto;text-align:right;border-left:none"><?php 	echo number_format((float)$data['nilai_with_ppn'], 2, '.', ',') ?>		</td>
	   <td style="width:15%;text-align:center;"><?php echo $jatuh_tempo ?></td>
	  <td style="width:15%;text-align:center;"><?php echo  $invno ?></td>
      <td style="width:15%;text-align:center;"><?php echo $v_fakturpajak ?></td>
    </tr>
		<?php
/* 		$qty =$qty + $data['qty'];
		if($data['is_utang'] == 'Y') {
			$total_utang_debit= $total_utang + $data['nilai'];	
		}
		if($data['is_other'] == 'Y') {
			$total_other= $total_other + $data['nilai'];	
		}	 */	
		$qty_total =$qty_total + $qty;
		$total_curr = $total_curr + $data['nilai_with_ppn'];
		if($data['curr'] !=''){
			$mata_uang = $data['curr'];
		}
		if($data['unit'] !=''){
			$unit = $data['unit'];
		}		
		$percentage_h = $data['percentage_h'];
	//} 

$total_curr_bef_tax  = $total_curr_bef_tax + $data['amount_original'];
$ppn_nya = $ppn_nya + $data['value_ppn'];
$pph_nya = $pph_nya + $data['value_pph'];


 }
$value_pph_header = (($percentage_h/100) * $total_curr_bef_tax );
$total_value_pph = $pph_nya + $value_pph_header;
 $grand_total = $total_curr_bef_tax + $ppn_nya - $total_value_pph ;
 
 ?>

   <tr>
      <td style="width:20%;text-align:center;"></td>
      <td style="width:20%;text-align:center;"></td>
      <td style="width:auto;text-align:center;"></td>
	  <td style="width:auto;text-align:center;"></td>
      <td style="width:15%;text-align:center;"></td>
	  <td style="width:auto;text-align:right;border-right:none"><?php echo number_format((float)$qty_total, 2, '.', ',') ?></td> 
	  <td style="width:auto;text-align:left;border-left:none"><?php echo $unit ?></td> 
      <td style="width:3%;text-align:left;border-right:none"></td>
	  <td style="width:auto;text-align:right;border-left:none"></td>
      <td style="width:3%;text-align:left;border-right:none"><?php  echo $mata_uang  ?></td>
	  <td style="width:auto;text-align:right;border-left:none"><?php echo number_format((float)$total_curr, 2, '.', ',') ?></td>
	   <td style="width:15%;text-align:center;"></td>
	  <td style="width:15%;text-align:center;"></td>
      <td style="width:15%;text-align:center;"></td>
    </tr>

  </tbody>
</table> 
<br>

<table width="100%" border="0" style="font-size:10px">

	<tr>
		<td width="70%">
			
		</td>
			
		<td>
			Total Before Tax
		</td>
<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang." ".number_format((float)$total_curr_bef_tax, 2, '.', ','); ?>
		</td>		
	</tr>	

<!-- pajak -- -->	

<!--
	<tr>
		<td width="70%">
			
		</td>
			
		<td>
			PPn 10% 
		</td>
<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php 
				// echo $mata_uang." ".number_format((float)$ppn_nya, 2, '.', ','); 
			?>
		</td>		
	</tr>	
-->
	<tr>
		<td width="70%">
			
		</td>
			
		<td>
			Ppn 10% 
		</td>
<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang." ".number_format((float)$ppn_nya, 2, '.', ','); ?>
		</td>		
	</tr>	
	<tr>
		<td width="70%">
			
		</td>
			
		<td>
			Pph 
		</td>
<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang."( ".number_format((float)$total_value_pph, 2, '.', ',')." )"; ?>
		</td>		
	</tr>	

	<tr>
		<td width="70%">
			
		</td>
			
		<td >
			Total 
		</td>
		<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang." ".number_format((float)$grand_total, 2, '.', ',') ?>
		</td>
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
<br/>
<br/>



	<table cellpadding="0" cellspacing="0" border="1" width='500';>

		<tr>	
			<th style="font-size:12px">Made By : </th>
			<th style="font-size:12px">Checked By : </th>
			<th style="font-size:12px">Approved By : </th>
	
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
		<tr>	
			<td class="td1">&nbsp;</td>
			<td class="td1">&nbsp;</td>
			<td class="td1">&nbsp; </td>
	
		</tr>
		<tr>	
			<td style="font-size:12px;text-align:center;">&nbsp;&nbsp;&nbsp; </td>
			<td style="font-size:12px;text-align:center">&nbsp;&nbsp;&nbsp; </td>
			<td style="font-size:12px;text-align:center">&nbsp;&nbsp;&nbsp; </td>
	
	
		</tr>				
	
		</table>

</body>


</html>  

<?php
$html = ob_get_clean();
include("../../mpdf57/mpdf.php");

$mpdf=new mPDF();

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>

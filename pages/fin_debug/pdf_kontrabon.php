<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';
include_once '../forms/journal_interface.php';
$images = '../../include/img-01.png';
$id=$_GET['id'];


$sql="SELECT 
                 bpb.bpbno
				 ,bpb.bpbno_int
				 ,bpb.id id_bpb
				 ,bpb.bpbdate
				,poh.pono
				,poh.podate
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,FD.qty
                ,bpb.unit
                ,FD.curr
                ,FD.price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,FD.amount_original nilai_ori
				,((ifnull(FD.n_ppn,0)/100)*((FD.amount_original)) + FD.amount_original )nilai
				,'Y' is_jasa
				,'N' is_pajak
				,'N' is_utang
				,'N' is_other
				,ACT.kpno
				,FD.row_id
            FROM 
                bpb 
		INNER JOIN (SELECT * FROM fin_journal_d WHERE id_journal LIKE '%-PK-%' AND is_retur_bh ='N' AND debit > 0 AND id_coa NOT IN('15204','15207') AND reff_doc IS NOT NULL) FD ON FD.id_bpb = bpb.id
				
                LEFT JOIN mastersupplier ms ON FD.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON FD.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_item poi ON poi.id_gen = FD.id_item
				LEFT JOIN po_header poh ON poh.id = FD.id_po
				LEFT JOIN jo JO ON JO.id = bpb.id_jo
				LEFT JOIN jo_det JOD ON bpb.id_jo = JOD.id
				LEFT JOIN so SO ON SO.id = JOD.id_so
				LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost

			WHERE 1=1
                 AND poi.cancel = 'N' AND FD.id_journal = '$id' AND poh.id IS NOT NULL
		AND (SUBSTR(bpb.bpbno_int,1,3)='WIP' )
		group by bpb.id


				UNION ALL
            SELECT 
                 bpb.bpbno
				 ,bpb.bpbno_int
				 ,bpb.id id_bpb
				  ,bpb.bpbdate
				,poh.pono
				,poh.podate
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,FD.qty
                ,bpb.unit
                ,FD.curr
                ,FD.price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,FD.amount_original nilai_ori
				,((ifnull(FD.n_ppn,0)/100)*((FD.amount_original)) + FD.amount_original )nilai
				,'N' is_jasa
				,'N' is_pajak
				,'N' is_utang	
				,'N' is_other
				,ACT.kpno
				,FD.row_id
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_header poh ON poh.pono = bpb.pono
				LEFT JOIN po_item poi ON poi.id_po = poh.id
				LEFT JOIN jo JO ON JO.id = poi.id_jo
				LEFT JOIN jo_det JOD ON JOD.id_jo = JOD.id
				LEFT JOIN so SO ON SO.id = JOD.id_so
				LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost
		INNER JOIN (SELECT * FROM fin_journal_d WHERE id_journal LIKE '%-PK-%' AND is_retur_bh ='N' AND credit > 0 AND id_coa NOT IN('15204','15207') AND reff_doc IS NOT NULL  ) FD ON FD.id_bpb = bpb.id
			WHERE 1=1
                 AND poi.cancel = 'N' 	AND FD.id_journal = '$id' AND poh.id IS NOT NULL
				 AND (SUBSTR(bpb.bpbno_int,1,3)!='WIP' )
		
		group by bpb.id
		
		
	UNION ALL
	
/*retur */
SELECT 
				 bpb.bppbno
                ,bpb.bppbno_int 
				 ,bpb.id id_bpb
				,bpb.bppbdate bpdate
				,poh.pono
				,poh.podate
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bppbdate bpdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,FD.qty
                ,bpb.unit
                ,FD.curr
                ,FD.price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,(FD.amount_original * (-1))nilai_ori
				,(-1* ((ifnull(FD.n_ppn,0)/100)*((FD.amount_original)) + FD.amount_original ) )nilai
				,'N' is_jasa
				,'N' is_pajak
				,'N' is_utang
				,'N' is_other
				,ACT.kpno	
				,FD.row_id
            FROM 
                bppb bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				INNER JOIN po_item poi ON poi.id_gen = mi.id_gen AND bpb.id_jo = poi.id_jo
				LEFT JOIN po_header poh ON poh.id = poi.id_po AND poh.id_supplier = bpb.id_supplier
				LEFT JOIN jo JO ON JO.id = poi.id_jo
				LEFT JOIN jo_det JOD ON JOD.id_jo = JOD.id
				LEFT JOIN so SO ON SO.id = JOD.id_so
				LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost								

				
		INNER JOIN (SELECT * FROM fin_journal_d WHERE id_journal LIKE '%-PK-%' AND is_retur_bh = 'Y' AND debit > 0 AND id_coa NOT IN('15204','15207') AND reff_doc IS NOT NULL  ) FD  ON FD.id_bppb = bpb.id 
			WHERE 1=1
                 AND poi.cancel = 'N' 	AND FD.id_journal ='$id'		AND poh.id IS NOT NULL
		
		group by bpb.id,FD.row_id	
		
	UNION ALL
/* retur */	
	
/*pajak */
 SELECT 
                 '0' bpbno
				,'0' bpbno_int
				,'0' id_bpb
				,'0' bpbdate
				,'0' pono
				,'0' podate
				,'0' supplier_po
				,'0' price_po
				,'0' ppn
                ,'0' bpbdate
                ,'0' id_supplier
                ,'0' id_item
                ,'0' qty
                ,'0' unit
                ,FD.curr
                ,'0' price
                ,'0' Supplier
                ,'0' supplier_code
                ,'0' vendor_cat
                ,FD.description
                ,'0'mattype
                ,'0'matclass
				,'0' n_code_category
                ,'0' id_group
                ,'0' qty_po
                ,'0' id_po_det
				,'0' id_gen
				,'0' nilai_ori
				,FD.debit nilai
				,'N' is_jasa
				,'Y' is_pajak
				,'N' is_utang		
				,'N' is_other				
				,'' kpno
				,FD.row_id
            FROM 
                fin_journal_d FD
			WHERE 1=1 AND
                 FD.id_journal = '$id' 
				 AND (FD.id_coa LIKE '%15204%' OR FD.id_coa LIKE '%15207%')
				 AND FD.debit > 0	
/*pajak */					
UNION ALL
/*utang debit */
 SELECT 
                 ' ' bpbno
				,' ' bpbno_int
				,' ' id_bpb
				,' ' bpbdate
				,' ' pono
				,' ' podate
				,' ' supplier_po
				,' ' price_po
				,' ' ppn
                ,' ' bpbdate
                ,' ' id_supplier
                ,' ' id_item
                ,' ' qty
                ,' 'unit
                ,FD.curr
                ,' ' price
                ,' ' Supplier
                ,' ' supplier_code
                ,' ' vendor_cat
                ,FD.description
                ,' 'mattype
                ,' 'matclass
				,' ' n_code_category
                ,' ' id_group 
                ,' ' qty_po
                ,' ' id_po_det
				,' ' id_gen
				,'0' nilai_ori
				,(FD.debit*-1) nilai
				,'N' is_jasa
				,'N' is_pajak
				,'Y' is_utang		
				,'N' is_other				
				,'' kpno
				,FD.row_id
            FROM 
                fin_journal_d FD
				INNER JOIN mapping_utang MU ON FD.id_coa = MU.id_coa
			WHERE 1=1 AND
                  FD.id_journal = '$id'
				AND FD.debit > 0 AND FD.is_retur_bh = 'N'
				 GROUP BY FD.row_id		

UNION ALL

/*LAIN LAIN */
 SELECT 
                 ' ' bpbno
				,' ' bpbno_int
				,' ' id_bpb
				,' ' bpbdate
				,' ' pono
				,' ' podate
				,' ' supplier_po
				,' ' price_po
				,' ' ppn
                ,' ' bpbdate
                ,' ' id_supplier
                ,' ' id_item
                ,' ' qty
                ,' 'unit
                ,FD.curr
                ,' ' price
                ,' ' Supplier
                ,' ' supplier_code
                ,' ' vendor_cat
                ,FD.description
                ,' 'mattype
                ,' 'matclass
				,' ' n_code_category
                ,' ' id_group 
                ,' ' qty_po
                ,' ' id_po_det
				,' ' id_gen
				,'0' nilai_ori
				,(FD.debit) nilai
				,'N' is_jasa
				,'N' is_pajak
				,'N' is_utang			
				,'Y' is_other	
				,'' kpno
				,FD.row_id
            FROM 
                fin_journal_d FD
				LEFT JOIN mapping_utang MU ON FD.id_coa = MU.id_coa
			WHERE 1=1 AND
                  FD.id_journal = '$id'
				 AND FD.debit > 0 AND MU.id_coa IS NULL AND FD.reff_doc IS NULL
				 GROUP BY FD.row_id
				 
		";
if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}
$rs=mysql_fetch_array(mysql_query($sql));
$v_listcode=$_GET['id'];
$value_pph = get_pph_kontra_bon($id,"KB");
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


.paddings {
  padding-top:20px;
  padding-bottom:20px;   
  padding-left:5px; 
  padding-right:5px; 
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

      <th style="width:20%;<?php echo $styleborder?>text-align:center;">No.PO</th>
      <th style="width:20%;<?php echo $styleborder?>text-align:center;">No.BPB</th>
      <th style="width:auto;<?php echo $styleborder?>text-align:center;">Tanggal BPB</th>
      <th style="width:auto;<?php echo $styleborder?>text-align:center;">TANGGAL PO</th>
	  <th style="width:15%;<?php echo $styleborder?>text-align:center;">Item Description</th>
      <th colspan="2" style="width:15%;<?php echo $styleborder?>text-align:center;">Quantity</th>
      <th colspan="2" style="width:15%;<?php echo $styleborder?>text-align:center;">Unit Price</th>
      <th colspan="2" style="width:15%;<?php echo $styleborder?>text-align:center;">Amount BPB</th>
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
$total_other = 0;
$total_amnt_ppn = 0;
$total_utang_debit = 0;
$ppn_nya_____ = 0;
$arr = array();
while ($data=mysql_fetch_array($query)) {// perulangna untuk menampilkan data
if($data['is_pajak'] == 'Y' ){
	$ppn_nya_____ = $data['nilai'];
}else{
	if(ISSET($data['jatuh_tempo'])){
		$jatuh_tempo = fd_view($data['jatuh_tempo']);
	}else{
		$jatuh_tempo = "";
	}
	if(ISSET($data['invno'])){
		$invno = "";
		
	}else{
		$invno = $data['invno'];
		
	}
	if(ISSET($data['v_fakturpajak'])){
		$v_fakturpajak = "";
		
	}else{
		$v_fakturpajak = $data['v_fakturpajak'];
		
	}	
	?>
   <tr>
      <td style="width:20%;text-align:center;"><?php echo $data['pono'] ?></td>
      <td style="width:20%;text-align:center;"><?php echo $data['bpbno_int'] ?></td>
      <td style="padding-left:8px;padding-right:8px;width:15%;text-align:center;"><?php echo fd_view($data['bpbdate']) //$data['no_po'] ?></td>
	  <td style="width:auto;text-align:center;"><?php echo  fd_view($data['podate']) //$data['no_po'] ?></td>
      <td style="width:15%;text-align:center;" class="paddings"><?php echo $data['itemdesc'] ?></td>
	  <td style="width:auto;text-align:right;border-right:none"><?php echo number_format((float)$data['qty'], 2, '.', ',') ?></td> 
	  <td style="width:auto;text-align:left;border-left:none"><?php echo $data['unit'] ?></td> 
      <td style="width:5%;text-align:right;border-right:none"><?php  echo $data['curr']  ?></td>
	  <td style="padding-right:10px;width:9%;text-align:right;border-left:none"><?php echo number_format((float)$data['price'], 2, '.', ',') ?></td>
      <td style="width:5%;text-align:right;border-right:none"><?php  echo $data['curr']  ?></td>
	  <td style="padding-right:10px;width:9%;text-align:right;border-left:none"><?php 	echo number_format((float)$data['nilai'], 2, '.', ',') ?>		</td>
	   <td style="width:15%;text-align:center;"><?php echo $jatuh_tempo ?></td>
	  <td style="width:15%;text-align:center;"><?php echo  $invno ?></td>
      <td style="width:15%;text-align:center;"><?php echo $v_fakturpajak ?></td>
    </tr>
		<?php
		$qty =$qty + $data['qty'];
		if($data['is_utang'] == 'Y') {
			$total_utang_debit= $total_utang + $data['nilai'];	
		}
		if($data['is_other'] == 'Y') {
			$total_other= $total_other + $data['nilai'];	
		}		
		
		$mata_uang = $data['curr'];
		$unit = $data['unit'];
		
	//} 

$total_curr_bef_tax  = $total_curr_bef_tax + $data['nilai_ori'];
$total_curr = $total_curr + $data['nilai'];	
}

 }
 
 $grand_total = $total_curr_bef_tax + $ppn_nya_____ - $value_pph + $total_utang_debit + $total_other;
 
 ?>

   <tr>
      <td style="width:20%;text-align:center;"></td>
      <td style="width:20%;text-align:center;"></td>
      <td style="width:auto;text-align:center;"></td>
	  <td style="width:auto;text-align:center;"></td>
      <td style="width:15%;text-align:center;"></td>
	  <td style="width:auto;text-align:right;border-right:none"><?php echo $qty ?></td> 
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

<table width="100%" border="0" style="font-size:8px">

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
			<?php echo $mata_uang." ".number_format((float)$ppn_nya_____, 2, '.', ','); ?>
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
			<?php echo $mata_uang."( ".number_format((float)$value_pph, 2, '.', ',')." )"; ?>
		</td>		
	</tr>	
	<tr>
		<td width="70%">
			
		</td>
			
		<td >
			Penambahan(Pengurangan) 
		</td>
		<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang." ".number_format((float)$total_utang_debit + $total_other, 2, '.', ',') ?>
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

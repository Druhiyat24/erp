<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';
include_once '../forms/journal_interface.php';
$images = '../../include/img-01.png';
$id=$_GET['id'];
$journal_detail = journal_detail();
$sql="
	SELECT GLOBAL.*,(SUM(GLOBAL.debits) - SUM(GLOBAL.value_utangs) - (GLOBAL.pphs) )debit,(SUM(GLOBAL.debits) )debit_ori,GLOBAL.pphs pph,SUM(GLOBAL.value_utangs)value_utang,SUM(GLOBAL.pajaks)pajak FROM (
		SELECT A.*
				,if(B.is_utang = '1',0,if(B.id_coa = '15207' OR B.id_coa = '15204',0,B.deb ))debits
				,if(B.id_coa = '15207' OR B.id_coa = '15204',B.deb,0 )pajaks
				,B.Cre credit
				,B.id_coa
				,pph.value_pph pphs
				,B.row_id
				,B.is_utang
				,if(B.is_utang = '1',B.deb,0)value_utangs
				,C.id_journal nokontrabon
				,C.date_journal tglkontrabon	
				,ifnull(BPB.pono,POH_WIP.pono) no_po
				,ifnull(PO.podate,POH_WIP.podate) tgl_po			
				,C.date_journal
				,BPB.pono po_no
				,ifnull(PO.jml_pterms,POH_WIP.jml_pterms) days_pterms
				,ifnull(DATE_ADD(C.date_journal, INTERVAL PO.jml_pterms DAY),DATE_ADD(C.date_journal, INTERVAL POH_WIP.jml_pterms DAY)) as  jatuh_tempo				
				,SUPPLIER.Supplier															
				,SUPPLIER.supplier_code
				,pph.nilai_before_pph nilai_kontrabon
				,PO.curr
				,if(B.curr = 'IDR','Rp','$')matauang
				,PO.kode_pterms
			FROM fin_status_journal_ap A
				LEFT JOIN (
					SELECT A.id_journal
					,A.row_id
					,A.is_utang
					,A.id_coa
					,A.percentage
					,A.curr
					,MAX(A.bpb_ref)bpb_ref
					,MAX(A.journal_ref)journal_ref
					,IFNULL((A.debit),0)Deb
					,IFNULL((A.credit),0)Cre
					,IFNULL((A.utang),0)Utang
					,(IFNULL((A.debit),0) - IFNULL(2*(A.utang),0))nilai
					FROM(
					SELECT a.reff_doc bpb_ref
						,a.reff_doc2 journal_ref
						,a.id_journal
						,a.id_coa
						,'0' percentage
						,a.row_id
						,a.nm_coa
						,a.debit 
						,a.credit
						,a.curr
						,if(a.debit !='0',if(c.id_coa IS NOT NULL,a.debit,0),0)utang
						,if(a.debit !='0',if(c.id_coa IS NOT NULL,1,0),0)is_utang
						FROM fin_journal_d a
						INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
							WHERE type_journal = '14'
						)b ON a.id_journal = b.id_journal 
						LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
						LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = b.n_pph 
						)A GROUP BY A.id_journal,A.row_id
				)B ON A.v_nojournal = B.id_journal
				LEFT JOIN(
					$journal_detail
				)pph ON pph.id_journal = A.v_nojournal
				LEFT JOIN(SELECT id_journal,reff_doc,date_journal FROM fin_journal_h)C
				ON A.v_nojournal = C.id_journal
					LEFT JOIN (
						SELECT bpbno_int,id_supplier,pono,id_jo,id_item FROM bpb GROUP BY bpbno_int
					)BPB on BPB.bpbno_int = C.reff_doc OR B.bpb_ref =BPB.bpbno_int
					LEFT JOIN(
						SELECT 	Id_Supplier,Supplier,supplier_code FROM mastersupplier
					)SUPPLIER ON BPB.id_supplier = SUPPLIER.Id_Supplier				
					LEFT JOIN(
						SELECT po_h.pono,po_h.id_terms,terms.days_pterms,poi.curr,po_h.podate,po_h.jml_pterms,terms.kode_pterms FROM po_header po_h LEFT JOIN(
							SELECT id, days_pterms,kode_pterms FROM masterpterms 
						)terms ON po_h.id_terms = terms.id
					LEFT JOIN (
						SELECT id_po,curr curr FROM po_item
					)poi ON poi.id_po = po_h.id	
					)PO ON BPB.pono = PO.pono				
				LEFT JOIN po_item POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen AND POI_WIP.cancel !='Y'
				LEFT JOIN po_header POH_WIP ON POH_WIP.id = POI_WIP.id_po
				LEFT JOIN(SELECT id, days_pterms,kode_pterms FROM masterpterms )PT_WIP ON POH_WIP.id_terms = PT_WIP.id						
			WHERE A.v_listcode = '$id' AND A.v_source = 'KB' AND B.deb !='0' GROUP BY A.v_nojournal,B.row_id
			
			UNION ALL
			
                SELECT A.*
				,PPPO.po_amount debits
				,'0' pajaks
				,PPPO.po_amount credit
				,'XXX' id_coa
				,'0' pphs
				,'' row_id 
				,'0' is_utang 
				,'0' value_utangs
				,'' nokontrabon
				,'' tglkontrabon
				,PPPO.pono no_po
				,PPPO.podate tgl_po
				,PPPO.podate date_journal
				,PPPO.pono
				,PPPO.jml_pterms days_pterms
				,DATE_ADD(PPPO.podate, INTERVAL PPPO.jml_pterms DAY) as  jatuh_tempo	
				,PPPO.supplier Supplier
				,PPPO.supplier_code
				,PPPO.po_amount nilai_kontrabon__
				,PPPO.curr
				,if(PPPO.curr = 'IDR','Rp','$')matauang
				,PT.kode_pterms
			FROM fin_status_journal_ap A
LEFT JOIN (
SELECT po.jml_pterms,po.pono,po.id_terms, po.podate, ms.supplier,ms.supplier_code,o.po_amount,o.curr, o.paid_amount, o.outstanding_amount
            FROM po_header po 
                LEFT JOIN mastersupplier ms ON po.id_supplier = ms.Id_Supplier
                INNER JOIN (
                     SELECT 
                        o.pono
						,o.curr
                        ,SUM(o.po_amount) po_amount
                        ,SUM(IFNULL(p.paid_amount,0)) paid_amount
                        ,(o.po_amount - IFNULL(p.paid_amount,0)) outstanding_amount
                    FROM
                    (
                        SELECT 
                            pono
                            ,id_coa
                            ,nm_coa
                            ,SUM(amount) po_amount
							,po.curr
                        FROM
                        (
                            SELECT 
                                ph.pono
                                ,ph.id_supplier
								,pd.curr
                                ,(pd.qty * pd.price) amount
                                ,mi.matclass kode_group
                                ,ms.vendor_cat
                                ,map.ir_k
                                ,mc.id_coa
                                ,mc.nm_coa
                            FROM
                                po_header ph
                                INNER JOIN po_item pd ON ph.id = pd.id_po
                                INNER JOIN masteritem mi ON pd.id_gen = mi.id_gen
                                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
                                LEFT JOIN mastersupplier ms ON ph.id_supplier = ms.Id_Supplier
                                LEFT JOIN mapping_coa map ON map.id_group = mg.id AND map.vendor_cat = ms.vendor_cat
                                LEFT JOIN mastercoa mc ON map.ir_k = mc.id_coa
                        ) po
                        GROUP BY 
                            pono
                            ,id_coa
                            ,nm_coa
                    ) o
                    LEFT JOIN
                    (
                        SELECT 
                            pono
                            ,id_coa
                            ,nm_coa
                            ,SUM(amount) paid_amount
                        FROM (
                            -- FULFILLMENT
                            SELECT 
                                jh.id_journal
                                ,bpb.pono
                                ,jd.id_coa
                                ,jd.nm_coa
                                ,SUM(jd.debit) amount
                            FROM 
                                fin_journal_h jh
                                LEFT JOIN (
                                    SELECT DISTINCT bpbno_int, pono FROM bpb
                                ) bpb ON jh.reff_doc = bpb.bpbno_int
                                LEFT JOIN fin_journal_d jd ON jh.id_journal = jd.id_journal
                            WHERE jh.type_journal = '3'
                                AND jh.src_reference = 'BPB'
                                AND jd.debit > 0
                                AND jh.fg_post = '2'
                            GROUP BY
                                jh.id_journal
                                ,bpb.pono
                                ,jd.id_coa
                                ,jd.nm_coa
                            UNION ALL
                            -- PREPAYMENT
                            SELECT 
                                jh.id_journal
                                ,jh.reff_doc pono
                                ,jd.id_coa
                                ,jd.nm_coa
                                ,SUM(jd.debit) amount
                            FROM 
                                fin_journal_h jh
                                LEFT JOIN fin_journal_d jd ON jh.id_journal = jd.id_journal
                            WHERE jh.type_journal = '3'
                                AND jh.src_reference = 'PO'
                                AND jd.debit > 0
                                AND jh.fg_post = '2'
                            GROUP BY
                                jh.id_journal
                                ,jh.reff_doc
                                ,jd.id_coa
                                ,jd.nm_coa
                        ) payment
                        GROUP BY 
                            pono
                            ,id_coa
                            ,nm_coa
                    ) p ON o.pono = p.pono AND o.id_coa = p.id_coa
                    GROUP BY o.pono
                 ) o ON po.pono = o.pono
            WHERE 1=1
                AND o.outstanding_amount > 0

            GROUP BY po.pono, po.podate, ms.Supplier


)PPPO ON A.v_nojournal = PPPO.pono 
LEFT JOIN
(masterpterms PT) ON  PPPO.id_terms = PT.id

WHERE A.v_source = 'PO' AND A.v_listcode = '$id' GROUP BY A.v_nojournal
)GLOBAL GROUP BY GLOBAL.v_nojournal
";

  

if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}

$rs=mysql_fetch_array(mysql_query($sql));
$v_listcode=$rs['v_listcode'];

$user = $rs['v_userinsert'];
$Supplier=$rs['Supplier'];

$tanggal_input=fd_view($rs['d_insert']);
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
	<td ><h5>LIST PAYMENT : '.$v_listcode.'</h5></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><h5>'.$Supplier.'</h5></td>
</tr>
</table>
<table style="width:100%;height:50%;font-size:10px; border-spacing: 15px;">
    <tr>

      <td style="width:9%;text-align:left;">DATE CREATED  :</td>
     
    </tr>
    <tr>

      <td style="width:18%; text-align:left;">'.$tanggal_input.'</td>
     
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

	

  <title>LIST PAYMENT</title>
</head>
<body style=" padding-left:5%; padding-right:5%;">
<?php echo $tbl_header ?>
<br>
<?php echo $tbl_no ?>
<table  border="1" cellspacing="0" style="width:100%;font-size:12px;border-spacing:2px;">
  <tr>
      <th style="width:20%;<?php echo $styleborder?>text-align:center;">NO.KONTRABON</th>
      <th style="width:20%;<?php echo $styleborder?>text-align:center;">TANGGAL KONTRA BON</th>
      <th style="width:8%;<?php echo $styleborder?>text-align:center;">NO.PO</th>
      <!--<th style="width:15%;<?php // echo $styleborder?>text-align:center;">TANGGAL PO</th> -->
	  <!-- <th style="width:15%;<?php // echo $styleborder?>text-align:center;">TERMS OF PAYMENT</th> -->
      <th style="width:15%;<?php echo $styleborder?>text-align:center;">AMOUNT</th>
      <th style="width:15%;<?php echo $styleborder?>text-align:center;">CURRENCY</th>
      <th style="width:15%;<?php echo $styleborder?>text-align:center;">TANGGAL JATUH TEMPO</th>
    </tr>
<tbody >
<?php  
$query=mysql_query($sql)or die(mysql_error());
$total = 0;
$id_journal = '';
$pph_kb = 0;
$pph_po = 0;
$total_curr  = 0;
$mata_uang = "";
$value_utang =0;
$amount__pajak=0;
while ($data=mysql_fetch_array($query)) {// perulangna untuk menampilkan data  
//if($data['id_coa'] == '15207' || $data['id_coa'] == '15204' ){
		$amount__pajak = $amount__pajak + $data['pajak'];
		$total = $total + ($data['debit_ori'] );
	//}
	
//	else{
		?>
   <tr>

      <td style="width:20%;text-align:center;"><?php echo $data['nokontrabon'] ?></td>
      <td style="width:20%;text-align:center;"><?php echo fd_view($data['tglkontrabon']) ?></td>
      <td style="width:8%;text-align:center;"><?php echo $data['no_po'] ?></td>
     <!-- <td style="width:15%;text-align:center;"><?php //echo $data['tgl_po'] ?></td> -->
	 <!-- <td style="width:15%;text-align:center;"><?php // echo $data['kode_pterms'] ?></td> -->
      <td style="width:15%;text-align:right;"><?php 
			//if($data['is_utang'] == '1' ){
			//	if($data['debit'] < 0 ){
			//		$data['debit'] = (-1*$data['debit']);
					
				//}
		//		echo $data['matauang']."( ".number_format((float)$data['debit'], 2, '.', ',')." )";
		//	}else{
				echo $data['matauang']." ".number_format((float)$data['debit'], 2, '.', ','); 
		//	}?>
		</td>
      <td style="width:15%;text-align:right;"><?php 			
	//  if($data['is_utang'] == '1' ){
		//		if($data['debit'] < 0 ){
		//			$data['debit'] = (-1*$data['debit']);
					
			//	}
			///	echo $data['matauang']."( ".number_format((float)$data['debit'], 2, '.', ',').")";
				
			//}else{
				echo $data['matauang']." ".number_format((float)$data['debit'], 2, '.', ','); 
		//	}?></td>
      <td style="width:15%;text-align:center;"><?php echo fd_view($data['jatuh_tempo']) ?> </td>
    </tr>
<?php
$value_utang = $value_utang + $data['value_utang'];
$mata_uang = $data['matauang'];
if($data['v_source']=='KB' ){
	//if($id_journal == '' || ($id_journal != $data['v_nojournal'])){
		$pph_kb = $pph_kb + $data['pph'];
	//}
}
if($data['v_source']=='PO'){
	//if($id_journal == '' || ($id_journal != $data['v_nojournal'])){
		$pph_po = $pph_po + $data['pph'];
	//}	
	
}


 } 
 $total_curr = $total - $value_utang - $amount__pajak;
 //$total_curr = $total;
 $total_pph = $pph_kb + $pph_po;
 //$utang = $total_curr;
 // $ori_utang = $utang - $amount__pajak;
 //if($total_pph != '0'){
	 $total_curr_pph = $total_curr + $amount__pajak - $total_pph;
 //}else{
	// $total_curr_pph = $utang ;
 //}
 //ori_utang
 
 ?>

  </tbody>
</table> 
<br>

<table width="100%" border="0" style="font-size:10px">
	<tr>
		<td width="70%">
			
		</td>
			
		<td >
			Total
		</td>
		<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang." ".number_format((float)$total_curr, 2, '.', ',') ?>
		</td>		
	</tr>

	<tr>
		<td width="70%">
			
		</td>
			
		<td >
			PPN
		</td>
		<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang." ".number_format((float)$amount__pajak, 2, '.', ',') ?>
		</td>		
	</tr>	
	
	<tr>
		<td width="70%">
			
		</td>
			
		<td >
			PPh
		</td>
		<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang."( ".number_format((float)$total_pph, 2, '.', ',')." )"; ?>
		</td>		
	</tr>		
	
	<tr>
		<td width="70%">
			
		</td>
			
		<td>
			Total(Currency)
		</td>
<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang." ".number_format((float)$total_curr_pph, 2, '.', ','); ?>
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

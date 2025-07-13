<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';
$images = '../../include/img-01.png';
$id=$_GET['id'];


$sql="SELECT X.* FROM(
SELECT  
		 ACT.kpno
		,JH.id_journal
		,JD.nilai
		,if(BPB.id IS NULL,JD.nilai,ifnull(BPB.qty,0) * ifnull(BPB.price,0) )amount
		,BPB.bpbdate
		,BPB.qty
		,BPB.id idbpb
		,ifnull(PPN.percentage,0) ppn
		,ifnull(PPH.percentage,0) pph
		,BPB.price
		,BPB.invno
		,BPB.unit
		,BPB.curr matauang
		,BPB.id_item id_item_bpb
		,BPB.bpbno_int
		,POI.price unit_price
		,POI.id idpo_det
		,JD.row_id id_row
		,POH.id idpo
		,J_HELP.v_fakturpajak
		,JD.description
		,JH.date_journal 
		,JH.fg_post
		,ifnull(JH.fg_tax,0)fg_tax
		,JH.n_ppn
		,JH.n_pph
		,JD.nilai debit
		,JD.bpb_ref
		,JD.journal_ref
		,MSS.Id_Supplier id_supplier
		,MSS.Supplier
		,POH.pono no_po
		,POH.podate tgl_po 
		,POH.id_terms
		,POH.jml_pterms days_pterms
		,POH.id_dayterms
		,DATE_ADD(JH.date_journal, INTERVAL POH.jml_pterms DAY) as  jatuh_tempo	
		FROM fin_journal_h JH 
		LEFT JOIN (
			SELECT reff_doc bpb_ref
					,reff_doc2 journal_ref
					,id_journal
					,row_id
					,description
					,debit nilai FROM fin_journal_d WHERE debit > 0
		)JD ON JD.id_journal = JH.id_journal 
		LEFT JOIN bpb BPB ON JD.bpb_ref = BPB.bpbno_int	
		LEFT JOIN mastersupplier MSS ON MSS.Id_Supplier = BPB.id_supplier
		LEFT JOIN po_header POH ON BPB.pono = POH.pono
		LEFT JOIN po_item POI ON BPB.id_item = POI.id_gen
		LEFT JOIN masterpterms MPT ON MPT.id = POH.id_terms
		LEFT JOIN fin_journalheaderdetail J_HELP on J_HELP.v_idjournal = JH.id_journal
		LEFT JOIN jo JO ON JO.id = BPB.id_jo
		LEFT JOIN jo_det JOD ON JOD.id_jo = JOD.id
		
		LEFT JOIN so SO ON SO.id = JOD.id_so
		LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost
		LEFT JOIN mtax PPN ON JH.n_ppn = PPN.idtax
		LEFT JOIN mtax PPH ON JH.n_pph = PPH.idtax
		
		
		WHERE 1 AND JD.nilai IS NOT NULL AND JH.type_journal = '14' AND JH.id_journal = '$id'  group by BPB.id )X 

		
		";
if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}

$rs=mysql_fetch_array(mysql_query($sql));
$v_listcode=$rs['id_journal'];

$user = $rs['v_userinsert'];
$Supplier=$rs['Supplier'];

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
      <th colspan="2" style="width:15%;<?php echo $styleborder?>text-align:center;">Quamtity</th>
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
$mata_uang = "";
$pajak_ppn = 0;
$pajak_pph = 0;
$fg_tax = 0;
$ppn_nya = 0;
$pph_nya = 0;
while ($data=mysql_fetch_array($query)) {// perulangna untuk menampilkan data  ?>
   <tr>
      <td style="width:20%;text-align:center;"><?php echo $data['no_po'] ?></td>
      <td style="width:20%;text-align:center;"><?php echo $data['bpbno_int'] ?></td>
      <td style="width:auto;text-align:center;"><?php echo fd_view($data['bpbdate']) //$data['no_po'] ?></td>
	  <td style="width:auto;text-align:center;"><?php echo  fd_view($data['tgl_po']) //$data['no_po'] ?></td>
      <td style="width:15%;text-align:center;"><?php echo $data['description'] ?></td>
	  <td style="width:auto;text-align:right;border-right:none"><?php echo $data['qty'] ?></td> 
	  <td style="width:auto;text-align:left;border-left:none"><?php echo $data['unit'] ?></td> 
      <td style="width:3%;text-align:left;border-right:none"><?php  echo $data['matauang']  ?></td>
	  <td style="width:auto;text-align:right;border-left:none"><?php echo number_format((float)$data['price'], 2, '.', ',') ?></td>
      <td style="width:3%;text-align:left;border-right:none"><?php  echo $data['matauang']  ?></td>
	  <td style="width:auto;text-align:right;border-left:none"><?php echo number_format((float)$data['amount'], 2, '.', ',') ?></td>
	   <td style="width:15%;text-align:center;"><?php echo fd_view($data['jatuh_tempo']) ?></td>
	  <td style="width:15%;text-align:center;"><?php echo  $data['invno'] ?></td>
      <td style="width:15%;text-align:center;"><?php echo $data['v_fakturpajak'] ?></td>
    </tr>
<?php
$mata_uang = $data['matauang'];
$total_curr  = $total_curr + $data['amount'];
$fg_tax= $data['fg_tax'];
$ppn_nya_= $data['ppn'];
$pph_nya_= $data['pph'];
 }
if($fg_tax > 0){
$total_curr;	
$ppn_nya = ($ppn_nya_/100) * $total_curr;
$pph_nya = ($pph_nya_/100) * $total_curr;
$grand_total = $total_curr + $ppn_nya - $pph_nya; 	
} else{
$ppn_nya = 0;
$pph_nya = 0;
$grand_total = $total_curr + $ppn_nya - $pph_nya; 		
	
}
 
 
 ?>

  </tbody>
</table> 
<br>

<table width="100%" border="0" style="font-size:10px">

	<tr>
		<td width="70%">
			
		</td>
			
		<td>
			Total Without Tax
		</td>
<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang." ".number_format((float)$total_curr, 2, '.', ','); ?>
		</td>		
	</tr>	
	

<!-- pajak -- -->	
<?php if($fg_tax > 0){ ?>

	<tr>
		<td width="70%">
			
		</td>
			
		<td>
			PPn 10% 
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
			PPh 10% 
		</td>
<td style="width:1%">:</td>
		<td style="text-align:right">
			<?php echo $mata_uang." ".number_format((float)$pph_nya, 2, '.', ','); ?>
		</td>		
	</tr>	
<<?php } ?>

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
<br/>
<br/><br/>
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

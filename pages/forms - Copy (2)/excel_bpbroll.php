<html>
<head>
	<title>Export Data Ke Excel Dengan PHP - www.malasngoding.com</title>
</head>

<?php
include "../../include/conn.php";
include "fungsi.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=PLPenerimaan.xls");

#echo $modenya;

	$id_h =  $_REQUEST['id_h'];
	

	$sql1="SELECT DISTINCT brh.bpbno,bpb.bpbno_int,bpb.dateinput,mi.goods_code,mi.itemdesc,ac.kpno,ms.Supplier,bpb.invno
		  FROM bpb_roll_h brh INNER JOIN bpb ON bpb.bpbno=brh.bpbno
	      INNER JOIN masteritem AS mi ON brh.id_item=mi.id_item
		  INNER JOIN jo_det AS jd ON jd.id_jo=brh.id_jo
		  INNER JOIN so  ON so.id=jd.id_so
		  INNER JOIN act_costing AS ac ON ac.id=so.id_cost
		  INNER JOIN mastersupplier AS ms ON ms.Id_Supplier=ac.id_buyer
		  WHERE brh.id='$id_h' ";
	//echo ($sql1);	
	$queryHD=mysql_query($sql1);	
	while($dataHD=mysql_fetch_array($queryHD))
	{
		echo '
		<p>Berikut ini adalah BPB  : <b>'.$dataHD['bpbno'].'</b></p>
		<table id="simple-table" class="table table-no-bordered">
			<tr><td>No. BPB</td><td> : '.$dataHD['bpbno_int'].'</td><td></td><td>Konsumen</td><td></td><td> : '.$dataHD['Supplier'].'</td><td></td><td>Jenis Kain</td><td> : '.$dataHD['itemdesc'].'['.$dataHD['goods_code'].'] </td></tr>
			<tr><td>Tgl. BPB</td> <td> : '.$dataHD['dateinput'].'</td><td></td><td>No WS</td><td></td><td> : '.$dataHD['kpno'].'</td><td></td><td>No SJ</td><td> : '.$dataHD['invno'].'</td></tr>			
		</table>
		';
	}
	echo "<table id='examplefixbarcode' width='100%' border='1' style='font-size:13px;'>";
		echo "
			<thead>		
			   <tr>
			        <th>#</th>
					<th>Lot #</th>
					<th>Satuan</th>
					<th>Qty SJ</th>
					<th>QTY Actual</th>
					<th>Selisih</th>
					<th>YARDS (SJ)</th>
					<th>YARDS (ACTUAL)</th>
					<th>YARDS (Selisih)</th>
					<th>QC PASS (Pengambilan)</th>
					<th>QC PASS (Pengembalian)</th>
					<th>QC PASS (Satuan (Yard/Kg))</th>
					<th>QC PASS (Keterangan)</th>
				</tr>
			</thead>
			
			<tbody>";
			$sql="select DISTINCT br.id,br.id_h,brh.id_item,brh.id_jo,roll_no,lot_no,roll_qty,
				   roll_qty_act,roll_qty_act-roll_qty as selisih,
			       roll_foc,br.unit,
				   concat(mr.kode_rak,' ',mr.nama_rak) raknya,concat(mrold.kode_rak,' ',mrold.nama_rak) raknyaold,
				   br.barcode,roll_qty_used from bpb_roll br inner join 
				   bpb_roll_h brh on br.id_h=brh.id 
				   left join master_rak mr on br.id_rak_loc=mr.id 
				   left join master_rak mrold on br.id_rak=mrold.id
				   where 
				   brh.id='$id_h' 
				   order by br.id";
			//echo $sql;
			$i=1;
			$query=mysql_query($sql);
			$total = 0;
			while($data=mysql_fetch_array($query))
			{	$total = $total	+ $data['roll_qty'] + $data['roll_foc'];
				echo "
					<tr>
						<td>$data[roll_no]</td>
						<td>$data[lot_no]</td>
						<td>$data[unit]</td>						
						<td>".number_format($data['roll_qty'],2)."</td>
						<td>".number_format($data['roll_qty_act'],2)."</td>
						<td>".number_format($data['selisih'],2)."</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						
					</tr>";
				$i++;
			};
	echo "</tbody>
	      </table>
		  "
		  	  ;


//---------------------------------------------------------------------------------------------------------------------


?>
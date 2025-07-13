<?php
include "../../include/conn.php";
//include "fungsi.php";

$modenya = $_GET['modeajax'];
//$jenis_company=flookup("jenis_company","mastercompany","company!=''");
//echo $modenya;

//adyz=============================================================================================


if ($modenya=="view_detailbpb")
{	
	$idPO="";
	$sql1="";

	$idPO = $_REQUEST['xpoid'];

	$sql1="SELECT X.pono,X.podate,Y.Supplier,X.ETD,X.ETA,X.expected_date,X.notes 
	FROM po_header  AS X INNER JOIN mastersupplier AS Y ON X.id_supplier=Y.Id_Supplier
	WHERE pono='$idPO' ";
	//echo ($sql1);

	$queryHD=mysql_query($sql1);	
	while($dataHD=mysql_fetch_array($queryHD))
	{
		echo '
		<p>Berikut ini adalah Detail PO : <b>'.$dataHD['pono'].'</b></p>
		<table id="simple-table" class="table table-no-bordered">
			<tr><td>NO PO</td><td> : '.$dataHD['pono'].'</td><td>Supplier</td><td> : '.$dataHD['Supplier'].'</td><td>Notes </td><td> : '.$dataHD['notes'].'</td></tr>
			<tr><td>Tanggal PO</td> <td> : '.$dataHD['podate'].'</td><td>Expected Date</td><td> : '.$dataHD['expected_date'].'</td><td></td><td></td></tr>			
		</table>
		';
	}
	echo "<table id='examplefixbarcode' width='100%' border='1' style='font-size:13px;'>";
		echo "
			<thead>		
			   <tr bgcolor='#eee'>
			        <th>#</th>
					<th>No WS</th>
					<th>Items</th>
					<th>Color</th>
					<th>Size</th>
					<th>Qty PO</th>
					<th>Unit PO</th>
					<th>Curr PO</th>
					<th>Price PO</th>
					<th>No BPB</th>
					<th>Tgl BPB</th>
					<th>Qty BPB</th>
					<th>Qty FOC</th>
					<th>Price BPB</th>
					<th>Selisih</th>
				</tr>
			</thead>
			
			<tbody>";
		 	//$sql1="SELECT V.itemdesc,V.color,V.size,sum(Z.qty) AS QTYPO,Z.unit AS UNITPO,Z.curr AS CURRPO,Z.price AS PRPO
			//	 FROM po_header  AS X 
			//	 INNER JOIN mastersupplier AS Y ON X.id_supplier=Y.Id_Supplier
			//	 INNER JOIN po_item AS Z ON X.id=Z.id_po
		    //	 INNER JOIN masteritem AS V ON V.id_gen=Z.id_gen
			//	 WHERE X.pono='$idPO' 
			//	 GROUP BY V.itemdesc,V.color,V.size,Z.unit ,Z.curr,Z.price ";

			$sql1="SELECT T.kpno,V.itemdesc,V.color,V.size,SUM(Z.qty) AS QTYPO,Z.unit AS UNITPO,Z.curr AS CURRPO,Z.price AS PRPO
					FROM po_header  AS X 
					INNER JOIN mastersupplier AS Y ON X.id_supplier=Y.Id_Supplier
					INNER JOIN po_item AS Z ON X.id=Z.id_po
					INNER JOIN masteritem AS V ON V.id_gen=Z.id_gen
					INNER JOIN jo_det AS U ON Z.id_jo=U.id_jo
					INNER JOIN so AS W ON U.id_so=W.id
					INNER JOIN act_costing AS T ON T.id=W.id_cost
					WHERE X.pono='$idPO' 
					GROUP BY T.kpno,V.itemdesc,V.color,V.size,Z.unit ,Z.curr,Z.price ";

			//echo $sql;
			$i=1;
			$query1=mysql_query($sql1);
			$totalPO = 0;
					    
			 while($dataD1=mysql_fetch_array($query1))
			  {	

				$totalPO= $dataD1['QTYPO'];

				//$sql2="SELECT DISTINCT A.pono,A.bpbno,A.bpbno_int, A.bpbdate,b.itemdesc,B.color,B.size,a.qty,a.qty_foc,a.unit,a.curr,a.price
				//	   FROM bpb AS a 
				//		INNER JOIN masteritem AS B ON A.id_item=B.id_item
			    //		WHERE a.pono='$idPO' AND a.cancel='N' 
				//		AND B.ITEMDESC='$dataD1[itemdesc]' AND B.COLOR='$dataD1[color]' AND B.SIZE='$dataD1[size]' ";
				$sql2="SELECT A.pono,A.bpbno,A.bpbno_int, A.bpbdate,E.kpno,b.itemdesc,E.kpno,B.color,B.size,a.qty,a.qty_foc,a.unit,a.curr,a.price
						FROM bpb AS a 
						INNER JOIN masteritem AS B ON A.id_item=B.id_item
						INNER JOIN jo_det AS c ON a.id_jo=c.id_jo
						INNER JOIN SO AS D ON C.id_so=D.id
						INNER JOIN act_costing AS E ON D.id_cost=E.id
						WHERE a.pono='$idPO' AND a.cancel='N' 
						AND E.KPNO='$dataD1[kpno]'
						AND B.ITEMDESC='$dataD1[itemdesc]' AND B.COLOR='$dataD1[color]' AND B.SIZE='$dataD1[size]'					
						";	

				   $query2=mysql_query($sql2);
				   $ix =1;
				   $totalBPB=0;

				   if(mysql_num_rows($query2)>=1)
				   {

				   	while($dataD2=mysql_fetch_array($query2))
				   	{
					  if ($ix==1)
						{

						echo "
						<tr>
					    <td align='center'>$i</td>
						<td>$dataD1[kpno]</td>
						<td>$dataD1[itemdesc]</td>
						<td>$dataD1[color]</td>
						<td>$dataD1[size]</td>
						<td align='right'>".number_format($dataD1['QTYPO'],2)."</td>
						<td>$dataD1[UNITPO]</td>
						<td>$dataD1[CURRPO]</td>
						<td align='right'>".number_format($dataD1['PRPO'],2)."</td>
						<td align='center' >$dataD2[bpbno_int]</td>
						<td align='center' >$dataD2[bpbdate]</td>					
						<td align='right'>".number_format($dataD2['qty'],2)."</td>
						<td align='right'>".number_format($dataD2['qty_foc'],2)."</td>
						<td align='right'>".number_format($dataD2['price'],2)."</td>
						<td align='right'></td>					
						</tr>";

						}	

						elseif ($ix >= 2)
						{
						echo "
						<tr>
					    <td align='center'></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td align='right'></td>
						<td></td>
						<td></td>
						<td align='right'></td>
						<td align='center' >$dataD2[bpbno_int]</td>
						<td align='center' >$dataD2[bpbdate]</td>					
						<td align='right'>".number_format($dataD2['qty'],2)."</td>
						<td align='right'>".number_format($dataD2['qty_foc'],2)."</td>
						<td align='right'>".number_format($dataD2['price'],2)."</td>
						<td align='right'></td>					
						</tr>";

						}
						$totalBPB += $dataD2['qty'];
                   		$ix++;
				  	 }			
				
						echo "
						<tr bgcolor='#ddd'>
					    <td colspan='5'>SUB TOTAL : </td>
						<td align='right' >".number_format($totalPO,2)."</td>
						<td colspan='5'></td>
						<td></td>
						<td align='right'>".number_format($totalBPB,2)."</td>
						<td></td>
						<td align='right'>".number_format($totalBPB-$totalPO,2)."</td>					
						</tr>";


			  		 $i++;
					}

			} 
			
	/*echo "</tbody>
	      </table>
		  <br>
		  <a target='blank' href='excel_mutasi_bbws.php?id_h=$idPO'>EXPORT KE EXCEL</a>"		  
		  ;		
		  */
}

?>
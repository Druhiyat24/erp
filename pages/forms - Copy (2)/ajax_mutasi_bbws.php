<?php
include "../../include/conn.php";
include "fungsi.php";

$modenya = $_GET['modeajax'];
$jenis_company=flookup("jenis_company","mastercompany","company!=''");
//echo $modenya;

//adyz=============================================================================================


if ($modenya=="view_detailws")
{	
	$id_h="";
	$sql1="";

	$id_h = $_REQUEST['itid'];

	$sql1="SELECT goods_code,matclass,ITEMDESC, Color,Size,GROUP_CONCAT(DISTINCT TRIM(id_item) SEPARATOR ' - ') AS id_item
	      FROM masteritem WHERE goods_code='$id_h' 
		  GROUP by goods_code,matclass,ITEMDESC, Color,Size ";
	//echo ($sql1);

	$queryHD=mysql_query($sql1);	
	while($dataHD=mysql_fetch_array($queryHD))
	{
		echo '
		<p>Berikut ini adalah Detail Kode Barang  : <b>'.$dataHD['goods_code'].'</b></p>
		<table id="simple-table" class="table table-no-bordered">
			<tr><td>Nama Barang</td><td> : '.$dataHD['ITEMDESC'].'</td><td>Color</td><td> : '.$dataHD['Color'].'</td><td>ID </td><td> : '.$dataHD['id_item'].'</td></tr>
			<tr><td>Kategori</td> <td> : '.$dataHD['matclass'].'</td><td>Size</td><td> : '.$dataHD['Size'].'</td><td></td><td></td></tr>			
		</table>
		';
	}
	echo "<table id='examplefixbarcode' width='100%' border='1' style='font-size:13px;'>";
		echo "
			<thead>		
			   <tr>
			        <th>#</th>
					<th>No. Transaksi</th>
					<th>Tgl Transaksi</th>
					<th>No WS</th>
					<th>Qty </th>
					<th>Satuan</th>
				</tr>
			</thead>
			
			<tbody>";
		 $sql="SELECT ifnull(a.bpbno_int,a.bpbno) as bpbno,a.bpbdate,f.kpno,
		     SUM(c.roll_qty)+SUM(c.roll_foc)  AS QTYIN,  
		     SUM(IFNULL(c.roll_qty_used,0))+SUM(IFNULL(c.roll_qty_foc_used,0)) AS QTYOUT, 
			 (SUM(c.roll_qty)+SUM(c.roll_foc))-(SUM(IFNULL(c.roll_qty_used,0))+SUM(IFNULL(c.roll_qty_foc_used,0))) as selisih,a.unit
			   FROM bpb AS a
		 	   INNER JOIN bpb_roll_h AS b ON a.bpbno=b.bpbno
		 	   INNER JOIN bpb_roll AS c ON c.id_h=b.id 
		       INNER JOIN jo_det AS D ON b.id_jo=d.id_jo
		       INNER JOIN so AS e ON e.id=d.id_so
		       INNER JOIN act_costing AS f ON f.id=e.id_cost
		       WHERE b.id_item IN (SELECT id_item FROM masteritem WHERE goods_code='$id_h')
		       GROUP BY a.bpbno,a.bpbno_int,a.bpbdate,a.unit
			   HAVING (SUM(c.roll_qty)+SUM(c.roll_foc))-(SUM(IFNULL(c.roll_qty_used,0))+SUM(IFNULL(c.roll_qty_foc_used,0))) >0 ";
			//echo $sql;
			$i=1;
			$query=mysql_query($sql);
			$total = 0;
			while($data=mysql_fetch_array($query))
			{	
				echo "
					<tr>
					    <td align='center'>$i</td>
						<td>$data[bpbno]</td>
						<td align='center' >$data[bpbdate]</td>
						<td>$data[kpno]</td>						
						<td align='right'>".number_format($data['QTYIN'],2)."</td>
						<td align='center'>$data[unit]</td>
												
					</tr>";
				$i++;
			};
	echo "</tbody>
	      </table>
		  <br>
		  <a target='blank' href='excel_mutasi_bbws.php?id_h=$id_h'>EXPORT KE EXCEL</a>"		  
		  ;		
}

?>
<?php
	session_start();
	include "../../include/conn.php";
	include "../forms/fungsi.php";
	$mode=$_GET['modeajax'];
	$user=$_SESSION['username'];
	$app_date=date("Y-m-d H:i:s");
	if($mode=="RN")
	{	$status=$_REQUEST['stnya'];
		$notes=$_REQUEST['ntnya'];
		$idrn=$_REQUEST['rnnya'];
		$cek=flookup("reqno","reqnon_header","id='$idrn' and app_by='$user'");
		if($cek!="")
		{	$sql="update reqnon_header set app='$status',app_notes='$notes',app_date='$app_date' 
				where id='$idrn' and app_by='$user'";
			insert_log($sql,$user);
		}
		else
		{	$cek=flookup("reqno","reqnon_header","id='$idrn' and app_by2='$user'");
			if($cek!="")
			{	$sql="update reqnon_header set app2='$status',app_notes2='$notes',app_date2='$app_date' 
					where id='$idrn' and app_by2='$user'";
				insert_log($sql,$user);
			}
		}
	}


if ($mode=="view_list_rak_loc_trx_new")
{	
	$id_h = $_REQUEST['id_h'];
	

	$sql1="select * from reqnon_header where id = '$id_h' ";
	//echo ($sql1);		
	$queryHD=mysql_query($sql1);	
	while($dataHD=mysql_fetch_array($queryHD))
	{	
		echo '
		<table id="examplefixbarcode" class="table table-no-bordered">
			<tr>
			<td style = "font-weight:bold; font-size:20px;">'.$dataHD['reqno'].'</td>
			</tr>
			<tr>
			<td>Pembuat</td><td> 	: '.$dataHD['username'].'</td>
			<td>Tgl. Req</td><td> 	: '.fd_view($dataHD['reqdate']).'</td>
			</tr>
			<tr>
			<td>Keterangan</td><td> : '.$dataHD['notes'].'</td>
			</tr>			
		</table>
		';
	}
	echo "<table id='examplefix' width='100%' border='1' style='font-size:13px;'>";
		echo "
			<thead>		
			   <tr>
			        <th>No</th>
					<th>Item Code</th>
					<th>Description</th>
					<th>Color</th>
					<th>Size</th>
					<th>Supplier</th>
					<th>Qty</th>
					<th>Unit</th>
					<th>Curr</th>
					<th>Price</th>
					<th>Amount</th>
				</tr>
			</thead>
			
			<tbody>";
			$sql="select goods_code,itemdesc,s.color,s.size,ms.supplier,reqitem.qty,reqitem.unit,
        price,a.notes remark
        ,tmppo.username userpo,tmppo.podate,a.username userreq,a.reqdate,
        a.app,a.app_by,a.app_date,a.app_notes,a.app2,a.app_by2,a.app_date2,a.app_notes2 
        from reqnon_header a inner join reqnon_item reqitem on a.id=reqitem.id_reqno
        inner join masteritem s on reqitem.id_item=s.id_item 
        left join mastersupplier ms on reqitem.id_supplier=ms.id_supplier 
        left join (select s.id_jo,a.username,a.podate from po_header a inner join po_item s on a.id=s.id_po 
            where jenis='N' and s.id_jo='$id_h' group by s.id_jo) tmppo on tmppo.id_jo=a.id
        where a.id='$id_h' and reqitem.cancel='N'";
			//echo $sql;
			$i=1;
			$tamt = 0;
      		$tqty = 0;
			$query=mysql_query($sql);

			while($data=mysql_fetch_array($query))
			{

			$amt = $data['price'] * $data['qty'];	
				echo "
					<tr>
						<td>$i</td>
						<td>$data[goods_code]</td>
          				<td>$data[itemdesc]</td>
          				<td>$data[color]</td>
         				<td>$data[size]</td>
          				<td>$data[supplier]</td>
          				<td>$data[qty]</td>
          				<td>$data[unit]</td>
          				<td>$data[curr]</td>
          				<td align='right'>".fn($data["price"],2)."</td>
          				<td align='right'>".fn($amt,2)."</td>									
					</tr>";
				$tamt = $tamt + $amt;
        		$tqty = $tqty + $data['qty'];	
				$i++;
			};
	echo "
      <tr>
        <td colspan='6' style='text-align:center;font-weight:bold'>Total</td>
        <td>".fn($tqty,0)."</td>
        <td colspan='3' style='text-align:center;font-weight:bold'> Total Harga</td>
        <td style='text-align:right;font-weight:bold' >".fn($tamt,2)."</td>
      </tr>
	</tbody>
	      </table>"		  
		  ;		
}

?>
<?php
include "../../include/conn.php";
include "../forms/fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$st_company=$rscomp["status_company"];
	$status_company=$rscomp["jenis_company"];
	$modenya = $_GET['modeajax'];
	$user=$_SESSION['username'];
#echo $modenya;


	if ($modenya == 'simpan_temp')
	{
		$cbokat = $_REQUEST['cbokat'];
		$txt_value = $_REQUEST['txt_value'];
		$txt_desc = $_REQUEST['txt_desc'];
		$chk_ppn = $_REQUEST['chk_ppn'];
		if ($chk_ppn == 1) {
			$ppn_value = 11;
		}else{
			$ppn_value = 0;
		}
		if ($cbokat!="")
		{	
			// $sql_fil  = mysql_query("select sum(id) filter from (select count(id_ctg) id from memo_det where inv_vendor = '$inv_vendor_add' and biaya = '$biaya_add' Union select count(id_ctg) id from memo_det_tmp where inv_vendor = '$inv_vendor_add' and biaya = '$biaya_add') a");
			// $row_fil = mysql_fetch_array($sql_fil);
			// $filter = $row_fil['filter'];

			// if ($filter == '0') {
				$sql = "insert into po_add_biaya_temp (id_kategori,total,ppn,keterangan,status,created_by,created_at) 
				values ('$cbokat','$txt_value','$ppn_value',upper('$txt_desc'),'Y','$user','')";
				insert_log($sql,$user);	
			// }
			
			$sqlsaldo = mysql_query("select sum(total) sub,round(sum(total * ppn/100),2) tot_ppn,(sum(total) + round(sum(total * ppn/100),2)) total from po_add_biaya_temp where created_by = '$user'");
			// echo $sqlsaldo;
			$datasaldo = mysql_fetch_array($sqlsaldo);
			$sub = $datasaldo['sub'];
			$tot_ppn = $datasaldo['tot_ppn'];
			$total = $datasaldo['total'];

		echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:14px;'>";
				echo "
					<thead>
						<tr>";
							echo "
							<th style='text-align: left;'>No #</th>
							<th style='text-align: left;'>Kategori</th>
							<th style='text-align: left;'>Subtotal</th>
							<th style='text-align: left;'>PPN</th>
							<th style='text-align: left;'>Total</th>
							<th style='text-align: left;'>Description</th>
							<th style='text-align: left;'>Action</th>";
						echo "
						</tr>
					</thead>
					<tbody>";
						$sql="select a.id,id_kategori,nama_kategori,total sub,round(total * ppn/100,2) tot_ppn,(total + round(total * ppn/100,2)) total, keterangan from po_add_biaya_temp a inner join po_master_pilihan b on b.id = a.id_kategori where a.created_by = '$user'";
					
					#echo $sql;
					$i=1;
					$query=mysql_query($sql);
					while($data=mysql_fetch_array($query))
					{	echo "
							<tr>";
								echo "
								<td>$i</td>
								<td>$data[nama_kategori]</td>
								<td>$data[sub]</td>
								<td>$data[tot_ppn]</td>
								<td>$data[total]</td>
								<td>$data[keterangan]</td>
								<td><button type='button' name='delete_temp' class='btn btn-primary' onclick='del_item($data[id])'>Hapus</button></td>";
							echo "
							</tr>";
						$i++;
					};
			echo "</tbody>
			<tfoot>
			<tr>
			<td colspan = '2' >Total : </td>
			<td> $sub <input type = 'hidden' readonly size='8' value = '$sub'> </td>
			<td> $tot_ppn <input type = 'hidden' readonly size='8' value = '$tot_ppn'> </td>
			<td> $total <input type = 'hidden' readonly size='8' value = '$total'> </td>
			<td></td>
			<td></td>
			</tr>
			</tfoot>			
			</table>";
		}					
	}

	if ($modenya == 'delete_temp')
	{
		$id_temp = $_REQUEST['id_temp'];
		if ($id_temp!="")
		{	
			$sql = "delete from po_add_biaya_temp where id = '$id_temp'";
			insert_log($sql,$user);
			
			$sqlsaldo = mysql_query("select sum(total) sub,round(sum(total * ppn/100),2) tot_ppn,(sum(total) + round(sum(total * ppn/100),2)) total from po_add_biaya_temp where created_by = '$user'");
			// echo $sqlsaldo;
			$datasaldo = mysql_fetch_array($sqlsaldo);
			$sub = $datasaldo['sub'];
			$tot_ppn = $datasaldo['tot_ppn'];
			$total = $datasaldo['total'];

		echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:14px;'>";
				echo "
					<thead>
						<tr>";
							echo "
							<th style='text-align: left;'>No #</th>
							<th style='text-align: left;'>Kategori</th>
							<th style='text-align: left;'>Subtotal</th>
							<th style='text-align: left;'>PPN</th>
							<th style='text-align: left;'>Total</th>
							<th style='text-align: left;'>Description</th>
							<th style='text-align: left;'>Action</th>";
						echo "
						</tr>
					</thead>
					<tbody>";
						$sql="select a.id,id_kategori,nama_kategori,total sub,round(total * ppn/100,2) tot_ppn,(total + round(total * ppn/100,2)) total, keterangan from po_add_biaya_temp a inner join po_master_pilihan b on b.id = a.id_kategori where a.created_by = '$user'";
					
					#echo $sql;
					$i=1;
					$query=mysql_query($sql);
					while($data=mysql_fetch_array($query))
					{	echo "
							<tr>";
								echo "
								<td>$i</td>
								<td>$data[nama_kategori]</td>
								<td>$data[sub]</td>
								<td>$data[tot_ppn]</td>
								<td>$data[total]</td>
								<td>$data[keterangan]</td>
								<td><button type='button' name='delete_temp' class='btn btn-primary' onclick='del_item($data[id])'>Hapus</button></td>";
							echo "
							</tr>";
						$i++;
					};
			echo "</tbody>
			<tfoot>
			<tr>
			<td colspan = '2' >Total : </td>
			<td> $sub <input type = 'hidden' readonly size='8' value = '$sub'> </td>
			<td> $tot_ppn <input type = 'hidden' readonly size='8' value = '$tot_ppn'> </td>
			<td> $total <input type = 'hidden' readonly size='8' value = '$total'> </td>
			<td></td>
			<td></td>
			</tr>
			</tfoot>			
			</table>";
		}					
	}	

?>
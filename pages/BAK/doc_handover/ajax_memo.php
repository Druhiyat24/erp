<?php
include "../../include/conn.php";
include "fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$st_company=$rscomp["status_company"];
	$status_company=$rscomp["jenis_company"];
	$jenis_company=$rscomp["jenis_company"];
	$logo_company=$rscomp["logo_company"];
	$modenya = $_GET['modeajax'];
	$user=$_SESSION['username'];
#echo $modenya;

if ($modenya=="view_list_invoice")
{ 
	$id_buyer = $_REQUEST['id_buyer'];	
	$sql = "select id isi, no_invoice tampil from tbl_book_invoice where id_customer = '$id_buyer' 
	and status != 'cancel' order by tgl_book_inv desc";
	IsiCombo($sql,'','');

}

if ($modenya=="view_list_invoice_data")
{	$id_inv = json_encode($_REQUEST['id_inv']); }
	if ($id_inv!="")
	{	
	echo "<table id='examplefix2' style='width: 100%;'>";

			echo " 
				<thead>
						<tr>
						<th colspan = '11'>Rincian List Invoice</th>
						</tr>
						<tr>";
					echo "
							<th>No Invoice</th>
							<th>No SO</th>
							<th>No BPPB</th>
							<th>Shipment</th>
							<th>Reff No</th>
							<th>Style</th>
							<th>Style No Prod</th>
							<th>PO</th>
							<th>Season</th>
							<th>WS</th>
							<th>Qty</th>
							<th>No Dok</th>";
					echo "
					</tr>
				</thead>
				<tbody>"; 

				$id_inv = str_replace("[","",$id_inv);
  				$id_inv = str_replace("]","",$id_inv);
  				$sql="select book.id, no_invoice, so_number, shipp_number, shipp, bppb.qty, ac.styleno, sd.styleno_prod, ws , doc_number, reff_no, buyerno, season 
				  from tbl_book_invoice book
				  left join tbl_invoice_detail det on book.id = det.id_book_invoice
				  left join bppb on det.id_bppb = bppb.id
				  left join so_det sd on bppb.id_so_det = sd.id
				  left join so on sd.id_so = so.id
				  left join masterseason ms on so.id_season = ms.id_season
				  left join act_costing ac on so.id_cost = ac.id
				  where book.id in ($id_inv)";
				#echo $sql;
				$i=1;
				$query=mysql_query($sql);
				if (!$query) { die($sql. mysql_error()); }
				while($data=mysql_fetch_array($query))
				{
				$id = $data[id];	
					echo "
						<tr>";
							echo "
							</td>";
							echo "								  
								  <td>
								  <input type = 'checkbox'  style='display:none;' checked id = 'id_cek' name = 'id_cek[$id]' value = '$data[id]'> 
								  <input type ='hidden' name ='no_invoice[$id]' value='$data[no_invoice]'>
								  $data[no_invoice]
								  </td>
								  <td>$data[so_number]</td>
								  <td>$data[shipp_number]</td>
								  <td>$data[shipp]</td>
								  <td>$data[reff_no]</td>
								  <td>$data[styleno]</td>
								  <td>$data[styleno_prod]</td>
								  <td>$data[buyerno]</td>
								  <td>$data[season]</td>
								  <td>$data[ws]</td>
								  <td>$data[qty]</td>	
								  <td>$data[doc_number]</td>";
							echo "
							</td>
						</tr>";
					$i++;
				};
		
	}

	if ($modenya=="view_sub_category")
	{ 
		$id_kat = $_REQUEST['id_kat'];	
		$sql_sub = "select id_sub_ctg isi, nm_sub_ctg tampil from master_memo_subctg where id_ctg = '$id_kat'";
		IsiCombo($sql_sub,'','');
	
	}

	if ($modenya == 'simpan_temp')
	{
		$id_kat_add = $_REQUEST['id_kat_add'];
		$id_sub_kat_add = $_REQUEST['id_sub_kat_add'];
		$biaya_add = $_REQUEST['biaya_add'];
		$inv_vendor_add = $_REQUEST['inv_vendor_add'];
		$inv_faktur_add = $_REQUEST['inv_faktur_add'];
		if ($id_kat_add!="")
		{	
			$sql_fil  = mysql_query("select sum(id) filter from (select count(id_ctg) id from memo_det where inv_vendor = '$inv_vendor_add' and biaya = '$biaya_add' Union select count(id_ctg) id from memo_det_tmp where inv_vendor = '$inv_vendor_add' and biaya = '$biaya_add') a");
			$row_fil = mysql_fetch_array($sql_fil);
			$filter = $row_fil['filter'];

			if ($filter == '0') {
				$sql = "insert into memo_det_tmp (id_ctg,id_sub_ctg,biaya,inv_vendor,faktur_pajak,user) 
				values ('$id_kat_add','$id_sub_kat_add','$biaya_add',upper('$inv_vendor_add'),upper('$inv_faktur_add'),'$user')";
				insert_log($sql,$user);	
			}
			
			$sqlsaldo = mysql_query("select sum(biaya) total from memo_det_tmp where user = '$user'");
			echo $sqlsaldo;
			$datasaldo = mysql_fetch_array($sqlsaldo);
			$saldo_sisa = $datasaldo['total'];

		echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:14px;'>";
				echo "
					<thead>
						<tr>";
							echo "
							<th>No #</th>
							<th>Kategori</th>
							<th>Sub Kategori</th>
							<th>Biaya</th>
							<th>Invoice Vendor</th>
							<th>Faktur Pajak</th>
							<th>Act</th>";
						echo "
						</tr>
					</thead>
					<tbody>";
						$sql="select a.id,a.id_ctg, b.nm_ctg, a.id_sub_ctg, c.nm_sub_ctg, a.biaya,a.inv_vendor,a.faktur_pajak, a.user from memo_det_tmp a
						inner join master_memo_ctg b on a.id_ctg = b.id_ctg
						inner join master_memo_subctg c on a.id_sub_ctg = c.id_sub_ctg
						 where user = '$user'";
					
					#echo $sql;
					$i=1;
					$query=mysql_query($sql);
					while($data=mysql_fetch_array($query))
					{	echo "
							<tr>";
								echo "
								<td>$i</td>
								<td>$data[nm_ctg]
								</td>
								<td>$data[nm_sub_ctg]</td>
								<td>$data[biaya]</td>
								<td>$data[inv_vendor]</td>
								<td>$data[faktur_pajak]</td>
								<td><button type='button' name='delete_temp' class='btn btn-primary' onclick='del_item($data[id])'>Hapus</button></td>";
							echo "
							</tr>";
						$i++;
					};
			echo "</tbody>
			<tfoot>
			<tr>
			<td colspan = '3' >Total : </td>
			<td> <input type = 'text' readonly size='4' value = '$saldo_sisa'> </td>
			<td></td>
			<td></td>
			<td></td>
			</tr>
			</tfoot>			
			</table>";
		}					
	}

	if ($modenya == 'delete_temp')
	{
		$id_book_temp = $_REQUEST['id_book_temp'];
		if ($id_book_temp!="")
		{	
			$sql = "delete from memo_det_tmp where id = '$id_book_temp'";
			insert_log($sql,$user);
			
			$sqlsaldo = mysql_query("select sum(biaya) total from memo_det_tmp where user = '$user'");
			echo $sqlsaldo;
			$datasaldo = mysql_fetch_array($sqlsaldo);
			$saldo_sisa = $datasaldo['total'];
			
			echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:14px;'>";
				echo "
					<thead>
						<tr>";
							echo "
							<th>No #</th>
							<th>Kategori</th>
							<th>Sub Kategori</th>
							<th>Biaya</th>
							<th>Invoice Vendor</th>
							<th>Faktur Pajak</th>
							<th>Act</th>";
						echo "
						</tr>
					</thead>
					<tbody>";
						$sql="select a.id,a.id_ctg, b.nm_ctg, a.id_sub_ctg, c.nm_sub_ctg, a.biaya, a.inv_vendor,a.faktur_pajak, a.user from memo_det_tmp a
						inner join master_memo_ctg b on a.id_ctg = b.id_ctg
						inner join master_memo_subctg c on a.id_sub_ctg = c.id_sub_ctg
						 where user = '$user'";
					
					#echo $sql;
					$i=1;
					$query=mysql_query($sql);
					while($data=mysql_fetch_array($query))
					{	echo "
							<tr>";
								echo "
								<td>$i</td>
								<td>$data[nm_ctg]
								</td>
								<td>$data[nm_sub_ctg]</td>
								<td>$data[biaya]</td>
								<td>$data[inv_vendor]</td>
								<td>$data[faktur_pajak]</td>
								<td><button type='button' name='delete_temp' class='btn btn-primary' onclick='del_item($data[id])'>Hapus</button></td>";
							echo "
							</tr>";
						$i++;
					};
			echo "</tbody>
			<tfoot>
			<tr>
			<td colspan = '3' >Total : </td>
			<td> <input type = 'text' readonly size='4' value = '$saldo_sisa'> </td>
			<td></td>
			<td></td>
			<td></td>
			</tr>
			</tfoot>			
			</table>";
		}					
	}	

?>
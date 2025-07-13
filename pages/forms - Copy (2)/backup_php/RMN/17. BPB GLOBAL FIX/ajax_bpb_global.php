<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$st_company=$rscomp["status_company"];
	$status_company=$rscomp["jenis_company"];
	$jenis_company=$rscomp["jenis_company"];
	$logo_company=$rscomp["logo_company"];
	$modenya = $_GET['modeajax'];
#echo $modenya;

if ($modenya=="view_list_ws")
{ 
  $id_supp = $_REQUEST['id_supp'];
  $id_tipe = $_REQUEST['id_tipe'];

  $sql = "select a.id_jo isi , ac.kpno tampil from bom_jo_global_item a
  inner join jo_det jd on a.id_jo = jd.id_jo
  inner join so on jd.id_so = so.id
  inner join act_costing ac on so.id_cost = ac.id
  inner join masteritem mi on a.id_item = mi.id_item
  where a.id_supplier = '$id_supp' and mi.mattype = '$id_tipe'
  group by a.id_jo";
  IsiCombo($sql,'','Pilih WS');
}


if ($modenya=="view_list_bom")
{	$crinya 	= $_REQUEST['id_ws'];
	$critipe 	= $_REQUEST['id_tipe'];
	$crisupp 	= $_REQUEST['id_supp'];
	if ($crinya!="")
	{			
		echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:14px;'>";
			echo "
				<thead>
					<tr>";
						echo "
						<th>No #</th>
						<th>WS #</th>
						<th>Product</th>
						<th>ID Item</th>
						<th>Kode Barang</th>
						<th>Kode Bahan Baku</th>
						<th>Qty BOM</th>
						<th>Satuan BOM</th>
						<th>Balance</th>
						<th>Qty Terima</th>";
					echo "
					</tr>
				</thead>
				<tbody>";
					$sql="select a.id_jo,ac.kpno, mp.product_group, c.id_item, c.goods_code, c.itemdesc,sum(a.qty) qty, a.unit, round(sum(a.qty) - coalesce(bpb.qty,0),2) balance 
			from bom_jo_global_item a
			inner join masteritem c on a.id_item = c.id_item
			inner join mastersupplier ms on a.id_supplier = ms.id_supplier
			inner join jo on a.id_jo = jo.id
			inner join jo_det jd on a.id_jo = jd.id_jo
			inner join so on jd.id_so = so.id
			inner join act_costing ac on so.id_cost = ac.id
			inner join masterproduct mp on ac.id_product = mp.id
			left join (select sum(qty) qty, id_item from bpb where bpbdate >= '2022-01-01' and id_jo = '$crinya' group by id_item) bpb on a.id_item = bpb.id_item
			where a.id_jo = '$crinya' and c.mattype = '$critipe' and ms.id_supplier = '$crisupp' and a.cancel = 'N'
			group by id_item
			order by goods_code asc ";
				
				#echo $sql;
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	echo "
						<tr>";
							echo "
							<td>$i</td>
							<td>$data[kpno]
							<input type ='hidden' name='kpno[$i]' id='kpno$i' value ='$data[kpno]'>
							</td>
							<td>$data[product_group]</td>
							<td>$data[id_item]
							<input type = 'hidden' name='id_item[$i]' id='id_item$i' value ='$data[id_item]'>
							</td>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td>$data[qty]</td>
							<td>$data[unit]</td>
							<td>$data[balance]</td>
						<td>
							<input type ='number' step='0.001' size='4' name ='qtybpb[$i]' max='$data[balance]' id='qtybpb$i' class='form-control qtybpbclass' onFocus='startCalcBpb();' onBlur='stopCalcBpb();'>
							<input type ='hidden' name ='unitbpb[$i]' id='unitbpb$i' value='$data[unit]' class='form-control unitbpbclass'>
							<input type ='hidden' name ='id_jo[$i]' id='id_jo$i' value='$data[id_jo]' class='form-control idjoclass'>
							</td>
							";
						echo "
						</tr>";
					$i++;
				};
		echo "</tbody>
		<tfoot>
		<tr>
		<td colspan = '9' >Total : </td>
		<td> <input type = 'text' size='5' id = 'total_qty_chk'> </td>
		</tr>
		</tfoot>			
		</table>";
	}

}

if ($modenya=="view_list_roll")
{	$crinya_roll = $_REQUEST['cri_item'];
	$cri_rak = $_REQUEST['txtrak'];
	if (isset($_REQUEST['sat_nya'])) { $defsat=$_REQUEST['sat_nya']; } else { $defsat=""; }
	if ($crinya_roll!="")
	{	echo "<table style='width: 100%;'>";
			echo "<thead>";
				echo "<tr>";						
						$cnya=$defsat; $ket="R";
						echo "
						<th width='4%'>No. Roll</th>
						<th width='10%'>Lot #</th>
						<th width='15%'>Qty</th>
						<th width='auto'>No.rak</th>";
					
				echo "</tr>";
			echo "</thead>";
			for ($x = 1; $x <= 100; $x++) 
      	{
			 echo "<tr>"; show_roll_bpb_global($x,$x,$crinya_roll,$ket,$cri_rak); echo "</tr>"; 
		}
		echo "<tfoot>
		<tr>
		<td colspan = '2' >Total : </td>
		<td> <input type = 'text' id = 'total'> </td>
		<td> </td>
		</tr>
		</tfoot>";
    	
		echo "</table>";
	}
}


?>
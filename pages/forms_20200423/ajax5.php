<?php
include "../../include/conn.php";
include "fungsi.php";

$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];
#echo $modenya;

if ($modenya=="view_list_stock_sc_jo")
{	$crinya = json_encode($_REQUEST['id_jo']);
	$jenismat = $_REQUEST['jenismat'];
	if ($crinya!="")
	{	$crinya = str_replace("[","",$crinya);
  	$crinya = str_replace("]","",$crinya);
  	$id_jo=$crinya;
		echo "<table id='examplefix2' style='width: 100%;'>";
			echo "
				<thead>
					<tr>";
						echo "<th>JO #</th>";
						if($jenismat=="WIP")
						{	echo "<th>Kode WIP</th>"; }
						else
						{	echo "<th>Kode Scrap</th>"; }
						echo "
						<th>Deskripsi</th>
						<th>Eks Dok</th>
						<th>Stock</th>
						<th>Unit</th>
						<th>Qty Out</th>
						<th>Curr</th>
						<th>Price</th>
					</tr>
				</thead>
				<tbody>";
				if($jenismat=="Scrap")
				{ 
					$crimat="mi.mattype in ('S','L')"; 
					$sql_join="and tbl_in.id_item_bppb=tbl_out.id_item_bb";
				}
				else
				{ 
					$crimat="mi.mattype in ('C')"; 
					$sql_join="";
				}
				$sql="select '' id_supplier,0 qtyreq,mi.goods_code,mi.itemdesc,tbl_in.id_item,tbl_in.id_jo,tbl_in.qty_in,
					if(tbl_out.qty_out is null,0,tbl_out.qty_out) qty_out,
					tbl_in.unit,tbl_in.id_item_bppb 
					from masteritem mi inner join 
					(select id_item,id_jo,sum(qty) qty_in,unit,id_item_bppb from bpb 
						where id_jo in ($id_jo) group by id_item,id_jo,id_item_bppb) as tbl_in 
					on mi.id_item=tbl_in.id_item
					left join 
					(select id_item,id_jo,sum(qty) qty_out,id_item_bb from bppb 
						where id_jo in ($id_jo) group by id_item,id_jo,id_item_bb) as tbl_out
					on tbl_in.id_item=tbl_out.id_item and tbl_in.id_jo=tbl_out.id_jo 
					$sql_join   
					where $crimat ";
				#echo $sql;
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id=$data['id_item'].":".$data['id_jo'].":".$data['id_item_bppb'];
					$sisa = $data['qty_in'] - $data['qty_out'];
					$jonya = flookup("jo_no","jo","id='$data[id_jo]'");
					if($jenismat=="WIP")
					{
						$eksdok=flookup("group_concat(jenis_dok,' (',lpad(bcno,6,'0'),')')","bpb","id_jo='$data[id_jo]' 
							and id_item='$data[id_item]' group by jenis_dok,bcno"); 
					}
					else
					{
						$eksdok=flookup("group_concat(jenis_dok,' (',lpad(bcno,6,'0'),')')","bpb","id_jo='$data[id_jo]' 
							and id_item='$data[id_item_bppb]' group by jenis_dok,bcno"); 
					}
					echo "
						<tr>
							<td>$jonya</td>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td>$eksdok</td>
							<td><input type ='text' size='4' name ='qtysisa[$id]' value='$sisa' id='qtysisa$i' class='qtysisaclass' readonly></td>
							<td>
								<input type ='text' size='4' name ='unitsisa[$id]' value='$data[unit]' id='unitsisa$i' readonly>
							</td>";
							if($modenya=="view_list_stock_req")
							{	echo "<td><input type ='text' size='4' name ='qtybpb[$id]' value='$data[qtyreq]' id='qtybpb$i' class='qtybpbclass' readonly></td>"; }
							echo "
							<td>
								<input type ='text' size='4' name ='qtyout[$id]' id='qtyout$i' class='qtyoutclass'>
								<input type ='hidden' name ='jono[$id]' id='jono$i' class='jonoclass' value='$data[id_jo]'>
								<input type ='hidden' name ='id_supp[$id]' id='id_supp$i' class='id_suppclass' value='$data[id_supplier]'>
							</td>
							<td>
								<select style='width:70px; height: 26px;' name ='curr[$id]' 
									class='select2 curr' id='curr$i'>";
		            $sql="select nama_pilihan isi,nama_pilihan tampil
		              from masterpilihan where kode_pilihan='Curr' ";
		            IsiCombo($sql,'','Pilih Curr');
		            echo "</select>
							</td>
							<td><input type ='text' style='width:70px;' name ='price[$id]' id='price$i' class='price'></td>
						</tr>";
					$i++;
				};
		echo "</tbody></table>";
	}
}
?>
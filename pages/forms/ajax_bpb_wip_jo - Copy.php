<?php
include "../../include/conn.php";
include "fungsi.php";

$modenya = $_GET['modeajax'];
#echo $modenya;
if ($modenya=="view_list_bom")
{	$crinya = $_REQUEST['id_jo'];
	if ($crinya!="")
	{	$ponya=$crinya;
		$id_jo=$crinya;
		echo "<table id='examplefix2' style='width: 100%;'>";
			echo "
				<thead>
					<tr>
						<th>Kode WIP</th>
						<th>Deskripsi</th>
						<th>Qty BOM</th>
						<th>Unit</th>
						<th>Balance</th>
						<th>Qty BPB</th>
						<th>Curr</th>
						<th>Price</th>
					</tr>
				</thead>
				<tbody>";
				$sql="select k.id_item,l.color,l.size,mi.goods_code,mi.itemdesc,
			  l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
			  k.unit from bom_jo_item k inner join so_det l on k.id_so_det=l.id 
			  inner join masteritem mi on k.id_item=mi.id_item
			  where k.id_jo='$id_jo' and k.cancel='N' and k.status='P' group by k.id_item";
				#echo $sql;
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id=$data['id_item'];
					$bpb=flookup("sum(qty)","bpb","id_jo='$data[id_jo]' and id_item='$data[id_item]'");
					$sisa=$data['qty_bom'] - $bpb;
					echo "
						<tr>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td><input type ='text' style='width:100px;' name ='qtybpb[$id]' value='$data[qty_bom]' id='qtybpb$i' class='form-control qtybpbclass' readonly></td>
							<td><input type ='text' style='width:50px;' name ='unitsisa[$id]' value='$data[unit]' id='unitsisa$i' class='form-control' readonly></td>
							<td><input type ='text' style='width:100px;' name ='qtybal[$id]' value='$sisa' id='qtybal$i' 
								class='form-control qtybalclass' readonly></td>
							<td>
								<input type ='text' style='width:100px;' name ='qtysc[$id]' id='qtysc$i' 
									class='form-control qtysc'>
								<input type ='hidden' name ='jono[$id]' id='jono$i' class='jono' value='$id_jo'>
							</td>
							<td>
								<select style='width:70px; height: 26px;' name ='currsc[$id]' 
									class='select2 currsc' id='currsc$i'>";
		            $sql="select nama_pilihan isi,nama_pilihan tampil
		              from masterpilihan where kode_pilihan='Curr' ";
		            IsiCombo($sql,'','Pilih Curr');
		            echo "</select>
							</td>
							<td>
								<input type ='text' style='width:100px;' name ='pxsc[$id]' id='pxsc$i' 
									class='form-control pxsc'>
							</td>
						</tr>";
					$i++;
				};
		echo "</tbody></table>";
	}
}
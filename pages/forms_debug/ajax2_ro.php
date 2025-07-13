<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$logo_company=$rscomp["logo_company"];
	$jenis_company=$rscomp["jenis_company"];
	$gr_po_need_app=$rscomp["gr_po_need_app"];
$modenya = $_GET['modeajax'];

if ($modenya=="view_list_sj_ro")
{	
	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$bpbno=$crinya;
		$sql="select a.id_jo,a.id_item,s.mattype,s.goods_code,concat(s.itemdesc,' ',s.color,' ',s.size,' ',s.add_info) itemdesc,s.color,a.qty,
			a.unit,a.id_po_item from bpb a inner join masteritem s 
			on a.id_item=s.id_item where bpbno='$bpbno' 
			order by s.mattype desc ";
		echo "<table style='width: 100%;' id='examplefix2'>";
			echo "<thead>";
				echo "<tr>";
					echo "
					<th>No</th>
					<th>Kode</th>
					<th>Deskripsi</th>
					<th>Qty BPB</th>
					<th>Satuan</th>
					<th>Stock/JO</th>
					<th>Stock</th>
					<th>Qty RO</th>
					<th></th>";
				echo "</tr>";
			echo "</thead>";
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	
					$sisaall = flookup("sum(qty)","bpb","id_item='$data[id_item]' and bpbno not like 'FG%'") - 
						flookup("sum(qty)","bppb","id_item='$data[id_item]' and bppbno not like 'SJ-FG%'");
					$sisaall = round($sisaall,2);
					$sisajo = flookup("sum(qty)","bpb","id_item='$data[id_item]' and id_jo='$data[id_jo]' and bpbno not like 'FG%'") - 
						flookup("sum(qty)","bppb","id_item='$data[id_item]' and id_jo='$data[id_jo]' and bppbno not like 'SJ-FG%'");
					$sisajo = round($sisajo,2);
					$id = $data['id_item'].":".$data['id_jo'];
					$id2 = $data['id_item']."|".$data['id_jo'];
					$cekovetoll = flookup("qty_ret","bpb_over","bpbno='$bpbno' and id_po_item='$data[id_po_item]'");
					if($cekovetoll>0)
					{
						$defqty = $cekovetoll;
						$readqty = " ";
					}
					else
					{
						$defqty = "";
						$readqty = "";
					}
					echo "<tr>";
						echo "
							<td>$i</td>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td><input type ='text' size='4' name ='itemstock[$id]' value='$data[qty]' 
								id='stockajax$i' class='qtysjclass' readonly></td>
							<td>$data[unit]</td>
							<td><input type ='text' readonly size='4' name ='stjo[$id]' id='stjoajax' class='stjoclass' value='$sisajo'></td>
							<td><input type ='text' readonly size='4' name ='stall[$id]' id='stallajax' class='stallclass' value='$sisaall'></td>
							<td><input type ='text' size='4' name ='item[$id]' id='itemajax' class='itemclass' value='$defqty' $readqty></td>";
							if($data['mattype']=="A" or $data['mattype']=="F")
							{
								echo "
								<td>
									<input type ='hidden' size='4' name='qtyroll[$id]' id='qtyroll$i' 
										class='qtyrollclass'>
									<input type ='hidden' size='4' name='totqtyroll[$id]' id='totqtyroll$i' 
										class='totqtyrollclass'>
									<input type ='hidden' size='4' name='qtyrollori[$id]' id='qtyrollori$i' 
										class='qtyrolloriclass' value='$id2'>
									<button type='button' class='btn btn-primary' data-toggle='modal' 
										data-target='#myRak' onclick='choose_rak($data[id_jo],$data[id_item])'>Rak</button>
								</td>";	
							}
							else
							{
								echo "
								<td>
								</td>";
							}
					echo "</tr>";
					$i++;
				};
		echo "</table>";
	}
}
?>
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


if ($modenya=="view_list_sj_ro2")
{	
	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$bpbno=$crinya;
		$sql="select * from (select * from (select a.*,b.*,(COALESCE(a.roll_qty,0) - COALESCE(b.qty_out,0) - COALESCE(a.qty_mutasi,0)) qty_sisa from (select bd.*,
          bpb.bpbdate tgl_bpb, 
          jd.kpno, 
          mi.itemdesc, 
          mi.goods_code,
          mr.kode_rak, 
          concat(mr.kode_rak, ' ', mr.nama_rak)rak, 
          supplier,
          pono,
          invno,
          buyer,
          styleno
          from bpb_det bd
         inner join (select bpbno, pono, id_supplier,bpbno_int, bpbdate,invno from bpb 
         group by bpbno) bpb on bd.bpbno = bpb.bpbno
         inner join masteritem mi on bd.id_item = mi.id_item
				 inner join (select ac.id_buyer, supplier buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd
         inner join so on jd.id_so = so.id
         inner join act_costing ac on so.id_cost = ac.id
				 inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
         where jd.cancel = 'N'
         group by id_cost order by id_jo asc) jd on bd.id_jo = jd.id_jo
         inner join master_rak mr on bd.id_rak_loc = mr.id
         inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier 
         where bd.bpbno_int = '$bpbno'
         order by bpb.bpbdate asc) a left join 
				 (select id_bpb_det,SUM(roll_qty) qty_out from bppb_det where cancel = 'N' GROUP BY id_bpb_det) b on b.id_bpb_det = a.id) a ) a "; ?>

	<table id="examplefix2" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>
         	<th style="width: 5%">No</th>
			<th style="width: 20%">Rak</th>
			<th style="width: 10%">Id Item</th>
			<th style="width: 12%">Kode</th>
			<th style="width: 25%">Deskripsi</th>
			<th style="width: 10%">Qty BPB</th>
			<th style="width: 8%">Satuan</th>
			<th style="width: 10%">Qty RO</th>
        </tr>
      </thead>
      <tbody>
			<?php
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	

					$sisajo = flookup("sum(qty)","bpb","id_item='$data[id_item]' and id_jo='$data[id_jo]' and bpbno not like 'FG%'") - 
						flookup("sum(qty)","bppb","id_item='$data[id_item]' and id_jo='$data[id_jo]' and bppbno not like 'SJ-FG%'");
					$sisajo = round($sisajo,2);
					echo "<tr>";
						echo "
							<td>$i</td>
							<td>$data[rak]</td>
							<td>$data[id_item]</td>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td><input  class='form-control' type='number' min = '0' max = '$sisajo' id='stockajax$i' name ='itemstock[$id]' value = '$sisajo' readonly></td>
							<td>$data[unit]</td>
							<td><input  class='form-control' type='number' min = '0' max = '$sisajo' id='itemajax$i' name ='item[$id]' value = '' >
							<input  class='form-control' type='hidden' id='iditemajax$i' name ='iditem[$id]' value = '$data[id_item]'>
							<input  class='form-control' type='hidden' id='idjoajax$i' name ='idjo[$id]' value = '$data[id_jo]'>
							<input  class='form-control' type='hidden' id='idrak$i' name ='idrak[$id]' value = '$data[id_rak_loc]'>
							<input  class='form-control' type='hidden' id='idbpbdet$i' name ='idbpbdet[$id]' value = '$data[id]'></td>";
					echo "</tr>";
					$i++;
				} ?>
		</tbody>
	</table>
	<?php
	}
}
?>
<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$st_company=$rscomp["status_company"];
	$status_company=$rscomp["jenis_company"];
	$jenis_company=$rscomp["jenis_company"];
	$logo_company=$rscomp["logo_company"];
	$harus_bpb=$rscomp["req_harus_bpb"];
	$out_harus_qc=$rscomp["out_cek_qc"];
	$master_rak=flookup("master_rak","userpassword","master_rak='1' limit 1");
$modenya = $_GET['modeajax'];
#echo $modenya;

if ($modenya=="cari_supp_req" or $modenya=="cari_supp_sc")
{	$crinya = $_REQUEST['cri_item'];
	if($modenya=="cari_supp_req") {$tbl="bppb_req";} else {$tbl="bppb";}
	$sql="select supplier,jo_no from $tbl a inner join mastersupplier s on a.id_supplier=s.id_supplier 
		inner join jo on a.id_jo=jo.id where bppbno='$crinya' ";
	$rsjo=mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rsjo['supplier'],$rsjo['jo_no']));	
}

  // where breq.bppbno = '$noreq' and br.roll_qty - br.roll_qty_used >'0'

if ($modenya=="get_list_barcode")
{ $noreq = $_REQUEST['noreq'];
  $sql = "select br.id isi, concat(br.id,' - ' ,mi.itemdesc, ' - ', ac.kpno) tampil 
  from bpb_roll br 
  inner join bpb_roll_h brh on br.id_h = brh.id 
  inner join bppb_req breq on brh.id_item = breq.id_item and brh.id_jo = breq.id_jo 
  inner join masteritem mi on brh.id_item = mi.id_item 
  inner join jo_det jd on brh.id_jo = jd.id_jo 
  inner join so on jd.id_so = so.id 
  inner join act_costing ac on so.id_cost = ac.id
  where breq.bppbno = '$noreq' 
  union
  select br.id isi, concat(br.id,' - ' ,mi.itemdesc, ' - ', ac.kpno) tampil 
  from bpb_roll br 
  inner join bpb_roll_h brh on br.id_h = brh.id
  inner join jo_det jd on brh.id_jo = jd.id_jo 
  inner join so on jd.id_so = so.id 
  inner join act_costing ac on so.id_cost = ac.id
  inner join bppb_req breq on brh.id_item = breq.id_item and ac.kpno = breq.idws_act 
  inner join masteritem mi on brh.id_item = mi.id_item 
  where breq.bppbno = '$noreq' ";
  IsiCombo($sql,'','');
}


if ($modenya=="view_list_rak_loc")
{	
	$id_item = $_REQUEST['id_item'];
	$id_jo = $_REQUEST['id_jo'];
	#echo $id_item."-".$id_jo;
	echo "<br>
 		<div style='font-size:18px;'>Total : <p id='total_qty_chk_rak' style='display:none' value=''></p> </div>
    <table id='examplefix' width='100%' style='font-size:13px;'>";
		echo "
			<thead>
				<tr>
					<th>..</th>
					<th>ID Rak</th>
					<th>#</th>
					<th>Lot #</th>
					<th>Qty Roll</th>
					<th>Qty Sudah Digunakan</th>
					<th>Qty sisa</th>
					<th>Unit</th>
					<th>Rak #</th>
				</tr>
			</thead>
			<tbody>";
			$sql="select br.id,br.id_h,brh.id_item,brh.id_jo,roll_no,lot_no,roll_qty,roll_qty_used,roll_qty - roll_qty_used qty_sisa,roll_foc,br.unit, concat(kode_rak,' ',nama_rak) raknya,br.barcode from bpb_roll br inner join 
				bpb_roll_h brh on br.id_h=brh.id 
				inner join master_rak mr on br.id_rak_loc=mr.id where 
				brh.id_jo='$id_jo' and brh.id_item='$id_item' and br.id_rak_loc!='' 
				order by br.id";
			#echo $sql;
			$i=1;
			$query=mysql_query($sql);
			$matnya = "";
			while($data=mysql_fetch_array($query))
			{	$x = $data['id_item']."|".$data['id_jo']."|".$data['id']."|".$data['id_h'];
				if($matnya=="") 
				{
					$matnya = flookup("mattype","masteritem","id_item='$data[id_item]'");
					$cekuser = flookup("username","userpassword","username='$user' and adjust_rak='1'");
				}
				if($matnya=="A" or $cekuser != "") { $txtread=""; } else { $txtread="readonly"; }
				$tot_isi = $data['qty_sisa'];
				echo "
					<tr>
						<td>
							<input type='checkbox' class='chkclass_rak' onchange='calc_chk_rak()'>
							<input type='hidden' name='txtbts[$x]' id='txtbtsajax' class='txtbtsclass' 
								value='$tot_isi'>
							<input type='hidden' name='txtcri[$x]' id='txtcriajax' class='txtcriclass' 
								value='$x'>
						</td>
						<td>$data[id]</td>
						<td>$data[roll_no]</td>
						<td>$data[lot_no]</td>
						<td>$data[roll_qty]
<input type='hidden' style='width:100px;height:34px;' class='txtuseclass_rak' value='$tot_isi' onchange='calc_chk_rak()' readonly >
						</td>
						<td>$data[roll_qty_used]</td>
						<td>$data[qty_sisa]</td>
						<td>$data[unit]</td>
						<td>$data[raknya]</td>
					</tr>";
				$i++;
			};
	echo "</tbody>
<tfoot>
            <tr>
                <th colspan='6' style='text-align:right'>Total:</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>

	</table>";
}




if ($modenya=="view_list_stock_req")
{	$crinya = $_REQUEST['id_jo'];
	if ($crinya!="")
	{	if($modenya=="view_list_stock_req")
		{	$ponya=$crinya;
			$id_jo=flookup("group_concat(distinct id_jo)","bppb_req","bppbno='$crinya' group by bppbno");
			$id_jo_2=flookup("jd.id_jo","bppb_req a
			inner join act_costing ac on a.idws_act = ac.kpno
			inner join so on ac.id = so.id_cost
			inner join jo_det jd on so.id = jd.id_so","bppbno='$crinya' group by bppbno");
		}
		else
		{	$id_jo=$crinya; }
		if($out_harus_qc=="Y")
		{ $sql_qc=""; }
		else
		{ $sql_qc=" "; }

		echo "<table id='examplefix1' style='width: 100%;font-size:14px;'>";
			echo "
				<thead>
						<tr>
						<th colspan = '3' >Daftar Request</th>
						<th colspan = '10' ></th>
						</tr>
					<tr>";
						echo "
						<th>WS #</th>
						<th>Style #</th>
						<th>Buyer</th>
						<th>ID Item</th>
						<th>Kode Bahan Baku</th>
						<th>Deskripsi</th>
						<th>Stock/JO</th>
						<th>Stock</th>
						<th>Unit</th>
						<th>Qty Req</th>
						<th>Sisa Qty Req</th>
						<th>Qty Out</th>";
						if($jenis_company!="VENDOR LG")
						{	echo "<th>Detail</th>";}
					echo "
					</tr>
				</thead>
				<tbody>";
				if($modenya=="view_list_stock_req")
				{	$sql="select jod.jo_no,breq.id_supplier,breq.qty qtyreq,breq.qty_out qty_sdh_out,breq.qty - breq.qty_out qty_sisa_out,mi.id_item,mi.goods_code,
					concat(mi.itemdesc,' ',mi.color,' ',mi.size,' ',mi.add_info) itemdesc,tbl_in.id_item,
					tbl_in.id_jo,tbl_in.qty_in,
					ifnull(tbl_out.qty_out,0) qty_out,
					tbl_in.unit,
					ac.kpno,ac.styleno,mbuyer.supplier buyer,tbl_in.rak
					from bppb_req breq  
					inner join masteritem mi on mi.id_item=breq.id_item inner join 
					(select id_item,id_jo,sum(qty) qty_in,unit,group_concat(nomor_rak) rak from bpb 
						where id_jo in ($id_jo) $sql_qc group by id_item,id_jo) as tbl_in 
					on mi.id_item=tbl_in.id_item and breq.id_jo=tbl_in.id_jo 	  
					left join 
					(select id_item,id_jo,sum(qty) qty_out from bppb where id_jo in ($id_jo)  
						group by id_item,id_jo) as tbl_out
					on tbl_in.id_item=tbl_out.id_item and tbl_in.id_jo=tbl_out.id_jo
					INNER JOIN 
					(select jo_no,id_so,id_jo from jo_det a inner join jo s on a.id_jo=s.id where id_jo in ($id_jo) 
						group by id_jo)  jod on breq.id_jo=jod.id_jo 
          inner join 
          (select so.id,id_cost,min(sod.deldate_det) mindeldate from so inner join so_det sod on 
          	so.id=sod.id_so group by so.id) so on jod.id_so=so.id 
          inner join act_costing ac on so.id_cost=ac.id
					inner join mastersupplier mbuyer on ac.id_buyer=mbuyer.id_supplier
					where breq.bppbno='$crinya'
union
									
select jod.jo_no,breq.id_supplier,breq.qty qtyreq,breq.qty_out qty_sdh_out,breq.qty - breq.qty_out qty_sisa_out,mi.id_item,mi.goods_code,
concat(mi.itemdesc,' ',mi.color,' ',mi.size,' ',mi.add_info) itemdesc,tbl_in.id_item,
tbl_in.id_jo,tbl_in.qty_in,
ifnull(tbl_out.qty_out,0) qty_out,
tbl_in.unit,
ac.kpno,ac.styleno,mbuyer.supplier buyer,tbl_in.rak
from (select a.*,jd.id_jo id_jo_2 from bppb_req a
inner join act_costing ac on a.idws_act = ac.kpno
inner join so on ac.id = so.id_cost
inner join jo_det jd on so.id = jd.id_so
where a.bppbno = '$crinya') breq  
inner join masteritem mi on mi.id_item=breq.id_item inner join 
(select id_item,id_jo,sum(qty) qty_in,unit,group_concat(nomor_rak) rak from bpb 
	where id_jo in ($id_jo_2) $sql_qc group by id_item,id_jo) as tbl_in 
on mi.id_item=tbl_in.id_item and breq.id_jo_2=tbl_in.id_jo 	  
left join 
(select id_item,id_jo,sum(qty) qty_out from bppb where id_jo in ($id_jo_2)  
	group by id_item,id_jo) as tbl_out
on tbl_in.id_item=tbl_out.id_item and tbl_in.id_jo=tbl_out.id_jo
INNER JOIN 
(select jo_no,id_so,id_jo from jo_det a inner join jo s on a.id_jo=s.id where id_jo in ($id_jo_2) 
	group by id_jo)  jod on breq.id_jo_2=jod.id_jo 
inner join 
(select so.id,id_cost,min(sod.deldate_det) mindeldate from so inner join so_det sod on 
so.id=sod.id_so group by so.id) so on jod.id_so=so.id 
inner join act_costing ac on so.id_cost=ac.id
inner join mastersupplier mbuyer on ac.id_buyer=mbuyer.id_supplier
where breq.bppbno='$crinya'	
					";
				}
				#echo $sql;
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id=$data['id_item']."|".$data['id_jo'];
					$idrak=$data['id_item']."X".$data['id_jo'];
					$sisa = $data['qty_in'] - $data['qty_out'];
					$sisa = round($sisa,2);
					$sisaall = flookup("sum(qty)","bpb","id_item='$id' and bpbno not like 'FG%'") - 
						flookup("sum(qty)","bppb","id_item='$id' and bppbno not like 'SJ-FG%'");
					$sisaall = round($sisaall,2);
					echo "
						<tr>";
							if($jenis_company=="VENDOR LG")
							{	echo "<td>$data[jo_no]</td>"; }
							else
							{	echo "<td>$data[kpno]</td>"; }
							echo "
							<td>$data[styleno]</td>
							<td>$data[buyer]</td>
							<td>$data[id_item]</td>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td>
								<input type ='hidden' size='4' name ='rak[$id]' id='rak$i' class='rakclass' readonly value='$data[rak]'>
								<input type ='text' size='4' name ='qtysisa[$id]' value='$sisa' id='qtysisa$i' class='form-control qtysisaclass' readonly></td>
							<td><input type ='text' size='4' name ='qtysisaall[$id]' value='$sisaall' id='qtysisaall$i' class='form-control qtysisaallclass' readonly></td>
							<td>
								<input type ='text' size='4' name ='unitsisa[$id]' value='$data[unit]' id='unitsisa$i' readonly class='form-control'>
							</td>
							<td>
								<input type ='text' size='4' name ='qtybpb[$id]' value='$data[qtyreq]' id='qtybpb$i' class='form-control qtybpbclass' readonly>
							</td>
							<td>
								<input type ='text' size='4' value='$data[qty_sisa_out]' class='form-control' readonly>
							</td>							
							<td>
								<input type ='text' size='4' name ='qtyout[$id]' required id='qtyout' class='form-control qtyoutclass'>
								<input type ='hidden' name ='jono[$id]' id='jono$i' class='jonoclass' value='$data[id_jo]'>
								<input type ='hidden' name ='id_item[$id]' id='id_item$i' class='id_itemclass' value='$data[id_item]'>
								<input type ='hidden' name ='id_supp[$id]' id='id_supp$i' class='id_suppclass' value='$data[id_supplier]'>
							</td>";
	echo "
								<td>
									<input type ='hidden' size='4' name='qtyroll[$id]' id='qtyroll$i' 
										class='qtyrollclass'>
									<input type ='hidden' size='4' name='totqtyroll[$id]' id='totqtyroll$i' 
										class='totqtyrollclass'>
									<input type ='hidden' size='4' name='qtyrollori[$id]' id='qtyrollori$i' 
										class='qtyrolloriclass' value='$id'>
									<button type='button' class='btn btn-primary' data-toggle='modal' 
										data-target='#myRak' onclick='choose_rak($data[id_jo],$data[id_item])'>Rak</button>	
									</td>"; 
						echo "
						</tr>";
					$i++;
				};
		echo "</tbody></table>";
	}

}

if ($modenya=="view_list_stock")
{	$cribarcode = json_encode($_REQUEST['id_barcode']); }
	if ($cribarcode!="")
	{	
	echo "<table id='examplefix2' style='width: 100%;'>";

			echo " 
				<thead>
						<tr>
						<th colspan = '3'>Daftar Barcode</th>
						<th colspan = '9' ></th>
						</tr>
						<tr>";
					echo "
							<th><input type='checkbox' onchange='checkAll(this)' name='chk[]''></th>
							<th>No Roll</th>
							<th>Lot</th>
							<th>WS #</th>
							<th>ID Item</th>
							<th>Kode Barang</th>
							<th>Nama Barang</th>
							<th>Qty Sisa</th>
							<th>Qty Input</th>
							<th>Unit</th>
							<th>Nama Rak</th>";
					echo "
					</tr>
				</thead>
				<tbody>";

				$cribarcode = str_replace("[","",$cribarcode);
  				$cribarcode = str_replace("]","",$cribarcode);
  				$sql="select br.id, brh.id_item, brh.id_jo, roll_no,lot_no,mi.goods_code, mi.itemdesc, roll_qty - roll_qty_used sisa,br.unit, kode_rak, ac.kpno from bpb_roll br 
inner join bpb_roll_h brh on br.id_h = brh.id 
inner join masteritem mi on brh.id_item = mi.id_item 
inner join jo_det jd on brh.id_jo = jd.id_jo 
inner join so on jd.id_so = so.id 
inner join act_costing ac on so.id_cost = ac.id 
inner join master_rak mr on br.id_rak_loc = mr.id
where br.id in ($cribarcode)";
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
								  <input type = 'checkbox' checked id = 'id_chk' name = 'id_chk[$id]' value = '$data[id]' onchange='calc_chk()' class='chkclass'>
								  </td>								  
								  <td>$data[roll_no]</td>
								  <td>$data[lot_no]</td>
								  <td>$data[kpno]</td>
								  <td>$data[id_item]</td>	
								  <td>$data[goods_code]</td>
								  <td>$data[itemdesc]</td>
								  <td>$data[sisa]</td>
								  <td>
								  <input type ='text' size = '2' value = '$data[sisa]' max = '$data[sisa]' name='qtyout_roll[$id]' onkeyup='calc_chk()' class='txtuseclass'>
								  <input type ='hidden' name ='id_item_barcode[$id]' value='$data[id_item]'>
								  <input type ='hidden' name ='id_jo_barcode[$id]' value='$data[id_jo]'>
								  </td>						  
								  <td>$data[unit]</td>
								  <td>$data[kode_rak]</td>";
							echo "
							</td>
						</tr>";
					$i++;
				};
		
	}

?>
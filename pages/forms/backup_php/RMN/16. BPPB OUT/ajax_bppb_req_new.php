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

if ($modenya=="view_list_tipe")
{ $tipe_mat = $_REQUEST['tipe_mat'];
if ($tipe_mat != "")
{	
	$sql="select type_ws isi, type_ws tampil from act_costing 
	group by type_ws
	order by 
	case type_ws when 'STD' then '1'
	when 'DTH' then '2'
	when 'GLOBAL' then '3'
	else '4'
	end";           
	IsiCombo($sql,'','Pilih tipe WS #');
}
}


if ($modenya=="view_list_ws")
{ $tipews = $_REQUEST['tipews'];
if ($tipews == "STD")
{	
  $sql = "select a.id isi,concat(a.jo_no,' | ',ac.styleno,' | ',ac.kpno) tampil 
  from jo a inner join jo_det s on a.id=s.id_jo 
  inner join  so on s.id_so=so.id 
  inner join act_costing ac on so.id_cost=ac.id 
  where ac.type_ws = 'STD'
  group by a.id";
}
else if ($tipews == "GLOBAL")
{	
  $sql = "select a.id isi,concat(a.jo_no,' | ',ac.styleno,' | ',ac.kpno) tampil 
  from jo a inner join jo_det s on a.id=s.id_jo 
  inner join  so on s.id_so=so.id 
  inner join act_costing ac on so.id_cost=ac.id 
  where ac.type_ws = 'GLOBAL'
  group by a.id";
}
else if ($tipews == "DTH")
{	
  $sql = "select a.id isi,concat(a.jo_no,' | ',ac.styleno,' | ',ac.kpno) tampil 
  from jo a inner join jo_det s on a.id=s.id_jo 
  inner join  so on s.id_so=so.id 
  inner join act_costing ac on so.id_cost=ac.id 
  where ac.type_ws = 'DTH'
  group by a.id";
}      
  IsiCombo($sql,'','Masukkan JO / WS');

}


if ($modenya=="view_list_ws_act")
{ 
	$tipews_a = $_REQUEST['tipews'];
  	$id_jo_a = $_REQUEST['id_jo'];

if ($tipews_a == "GLOBAL")
{	
  $sql = "select ac.kpno isi,concat(jo.jo_no,' | ',ac.styleno,' | ',ac.kpno) tampil  from bom_jo_global_child a
  inner join jo_det jd on a.id_jo_child = jd.id_jo
  inner join jo on a.id_jo_child = jo.id
  inner join so on jd.id_so = so.id
  inner join act_costing ac on so.id_cost = ac.id
  where a.id_jo_global = '$id_jo_a' and a.cancel = 'N'
  group by a.id_jo_child";
}
else
{
	$sql = "select ac.kpno isi,concat(a.jo_no,' | ',ac.styleno,' | ',ac.kpno) tampil 
	from jo a inner join jo_det s on a.id=s.id_jo 
	inner join  so on s.id_so=so.id 
	inner join act_costing ac on so.id_cost=ac.id 
	inner join (select id_jo from bom_jo_item group by id_jo)	k on s.id_jo = k.id_jo
	where ac.type_ws = 'STD'
	group by a.id";	
}    
  IsiCombo($sql,'','Masukan WS Actual');

}

if ($modenya=="view_list_stock")
{	
	// $id_jo = json_encode($_REQUEST['id_jo']);
	$id_jo = $_REQUEST['id_jo'];
	$tipe_mat = $_REQUEST['tipe_mat'];
	$tipews = $_REQUEST['tipews'];

	if ($id_jo!="")
	{
		if ($tipews == 'STD' && $tipe_mat == 'F')
		{
			$sql = "select a.id_item,ac.kpno,jo_no,a.goods_code,a.id_jo,a.mattype,a.matclass,a.itemdesc,	round( COALESCE ( sum( qty_bpb ), 0 ), 2 ) qty_bpb,
			round(COALESCE ( sum( qty_bppb ), 0 ),2) qty_bppb,
			round(COALESCE ( sum( qty_bpb ), 0 ) - COALESCE ( sum( qty_bppb ), 0 ),2) sisa_stok,
			round(COALESCE ( br.qty_br, 0 ),2) qty_br,
			round(COALESCE ( br.qty_br_out, 0 ),2) qty_br_out,
			round( COALESCE ( br.qty_br, 0 ) - COALESCE ( br.qty_br_out, 0 ), 2 ) sisa_req,  a.unit from
			(select mi.id_item,mi.goods_code,k.id_jo,mi.mattype, mi.matclass, mi.itemdesc, sum(bpb.qty) qty_bpb, '0' qty_bppb, bpb.unit
			from (select id_item,id_jo from bom_jo_item k where k.id_jo = '$id_jo' group by id_item) k 
			inner join masteritem mi on k.id_item = mi.id_gen
			left join bpb on mi.id_item = bpb.id_item and k.id_jo = bpb.id_jo		   
			where k.id_jo = '$id_jo' and mi.mattype = '$tipe_mat' 
			group by mi.id_item, bpb.id_jo, bpb.unit
			union
			select mi.id_item,mi.goods_code,id_jo,mi.mattype, mi.matclass, mi.itemdesc, '0' qty_bpb, sum(bppb.qty) qty_bppb, bppb.unit
			from bppb 
			inner join masteritem mi on bppb.id_item = mi.id_item
			where bppb.id_jo = '$id_jo' and mi.mattype = '$tipe_mat'  and bppb.unit is not null
			group by mi.id_item, bppb.id_jo, bppb.unit					
			) a
			inner join jo_det jd on a.id_jo = jd.id_jo
			inner join jo on jd.id_jo = jo.id
			inner join so on jd.id_so = so.id
			inner join act_costing ac on so.id_cost = ac.id
			left join 
			(select mi.id_item,mi.goods_code,br.id_jo,mi.mattype, mi.matclass, mi.itemdesc,round(sum(br.qty),2) qty_br,round(sum(bppb.qty),2) qty_br_out
			from bppb_req br
			inner join masteritem mi on br.id_item = mi.id_item
			left join bppb on br.bppbno = bppb.bppbno_req and br.id_item = bppb.id_item and br.id_jo = bppb.id_jo
			where br.id_jo = '$id_jo' and mi.mattype = '$tipe_mat'  and br.unit is not null
			group by mi.id_item, br.id_jo
			) br on a.id_item = br.id_item and a.id_jo = br.id_jo
			group by a.id_item, a.id_jo, a.unit";
		}


		if (($tipews == 'STD' && $tipe_mat == 'A') or ($tipews == 'DTH' && $tipe_mat == 'A'))
			// left join (select * from bpb where bpbdate >= '2023-01-01') bpb on mi.id_item = bpb.id_item and k.id_jo = bpb.id_jo	line 155	
		{
			// $sql = "select a.id_item,ac.kpno,jo_no,a.goods_code,a.id_jo,a.mattype,a.matclass,a.itemdesc,	round( COALESCE ( sum( qty_bpb ), 0 ), 2 ) qty_bpb,
			// round(COALESCE ( sum( qty_bppb ), 0 ),2) qty_bppb,
			// round(COALESCE ( sum( qty_bpb ), 0 ) - COALESCE ( sum( qty_bppb ), 0 ),2) sisa_stok,
			// round(COALESCE ( br.qty_br, 0 ),2) qty_br,
			// round(COALESCE ( br.qty_br_out, 0 ),2) qty_br_out,
			// round( COALESCE ( br.qty_br, 0 ) - COALESCE ( br.qty_br_out, 0 ), 2 ) sisa_req,  a.unit from
			// (select mi.id_item,mi.goods_code,k.id_jo,mi.mattype, mi.matclass, mi.itemdesc, sum(bpb.qty) qty_bpb, '0' qty_bppb, bpb.unit
			// from (select id_item,id_jo from bom_jo_item k where k.id_jo = '$id_jo' group by id_item) k 
			// inner join masteritem mi on k.id_item = mi.id_gen
			// left join (select * from bpb where bpbdate >= '2023-02-01') bpb on mi.id_item = bpb.id_item and k.id_jo = bpb.id_jo		   
			// where k.id_jo = '$id_jo' and mi.mattype = '$tipe_mat' 
			// group by mi.id_item, bpb.id_jo, bpb.unit
			// union
			// select mi.id_item,mi.goods_code,s.id_jo,mi.mattype, mi.matclass, mi.itemdesc, sum(s.qty) qty_bpb, '0' qty_bppb, s.unit
			// from saldoawal_rak s
			// inner join masteritem mi on s.id_item = mi.id_item   
			// where s.id_jo = '$id_jo' and mi.mattype = '$tipe_mat' and s.periode = '2023-02-01'
			// group by mi.id_item, s.id_jo, s.unit			
			// union
			// select mi.id_item,mi.goods_code,id_jo,mi.mattype, mi.matclass, mi.itemdesc, '0' qty_bpb, sum(bppb.qty) qty_bppb, bppb.unit
			// from bppb 
			// inner join masteritem mi on bppb.id_item = mi.id_item
			// where bppb.id_jo = '$id_jo' and mi.mattype = '$tipe_mat'  and bppb.unit is not null
			// group by mi.id_item, bppb.id_jo, bppb.unit					
			// ) a
			// inner join jo_det jd on a.id_jo = jd.id_jo
			// inner join jo on jd.id_jo = jo.id
			// inner join so on jd.id_so = so.id
			// inner join act_costing ac on so.id_cost = ac.id
			// left join 
			// (select mi.id_item,mi.goods_code,br.id_jo,mi.mattype, mi.matclass, mi.itemdesc,round(sum(br.qty),2) qty_br,round(sum(bppb.qty),2) qty_br_out
			// from bppb_req br
			// inner join masteritem mi on br.id_item = mi.id_item
			// left join bppb on br.bppbno = bppb.bppbno_req and br.id_item = bppb.id_item and br.id_jo = bppb.id_jo
			// where br.id_jo = '$id_jo' and mi.mattype = '$tipe_mat'  and br.unit is not null
			// group by mi.id_item, br.id_jo
			// ) br on a.id_item = br.id_item and a.id_jo = br.id_jo
			// group by a.id_item, a.id_jo, a.unit";


			$sql = "select a.id_item,ac.kpno,jo_no,a.goods_code,a.id_jo,a.mattype,a.matclass,a.itemdesc,
			round( COALESCE ( sum( qty_sa ), 0 ), 2 ) + round( COALESCE ( sum( qty_bpb ), 0 ), 2 ) qty_bpb,
			round(COALESCE ( sum( qty_bppb ), 0 ),2) qty_bppb,
			round(COALESCE ( sum( qty_bpb ), 0 ) - COALESCE ( sum( qty_bppb ), 0 ),2) sisa_stok,
			round(COALESCE ( br.qty_br, 0 ),2) qty_br,
			round(COALESCE ( br.qty_br_out, 0 ),2) qty_br_out,
			round( COALESCE ( br.qty_br, 0 ) - COALESCE ( br.qty_br_out, 0 ), 2 ) sisa_req,
			 a.unit
			 from
			(
			select mi.id_item,mi.goods_code,s.id_jo,mi.mattype, mi.matclass, mi.itemdesc,sum(s.qty) qty_sa,'0' qty_bpb, '0' qty_bppb, s.unit
						from saldoawal_rak s
						inner join masteritem mi on s.id_item = mi.id_item   
						where s.id_jo = '$id_jo' and mi.mattype = 'A' and s.periode = '2023-02-01'
						group by mi.id_item, s.id_jo, s.unit
			union
			select mi.id_item,mi.goods_code,bpb.id_jo,mi.mattype, mi.matclass, mi.itemdesc,'0' qty_sa,sum(bpb.qty) qty_bpb, '0' qty_bppb, bpb.unit
						from bpb
						inner join masteritem mi on bpb.id_item = mi.id_item   
						where bpb.id_jo = '$id_jo' and mi.mattype = 'A' and bpb.bpbdate >= '2023-02-01'
						group by mi.id_item, bpb.id_jo, bpb.unit
			union			
			select mi.id_item,mi.goods_code,bppb.id_jo,mi.mattype, mi.matclass, mi.itemdesc,'0' qty_sa,'0' qty_bpb, sum(bppb.qty) qty_bppb, bppb.unit
						from bppb
						inner join masteritem mi on bppb.id_item = mi.id_item   
						where bppb.id_jo = '$id_jo' and mi.mattype = 'A' and bppb.bppbdate >= '2023-02-01'
						group by mi.id_item, bppb.id_jo, bppb.unit					
			) a
						inner join jo_det jd on a.id_jo = jd.id_jo
						inner join jo on jd.id_jo = jo.id
						inner join so on jd.id_so = so.id
						inner join act_costing ac on so.id_cost = ac.id
						left join 
						(select mi.id_item,mi.goods_code,br.id_jo,mi.mattype, mi.matclass, mi.itemdesc,round(sum(br.qty),2) qty_br,round(sum(bppb.qty),2) qty_br_out
						from bppb_req br
						inner join masteritem mi on br.id_item = mi.id_item
						left join bppb on br.bppbno = bppb.bppbno_req and br.id_item = bppb.id_item and br.id_jo = bppb.id_jo
						where br.id_jo = '$id_jo' and mi.mattype = 'A'  and br.unit is not null and br.bppbdate >='2023-02-01'
						group by mi.id_item, br.id_jo
						) br on a.id_item = br.id_item and a.id_jo = br.id_jo
			group by a.id_item, a.id_jo, a.unit
			
			";
		}		

		else if ($tipews == 'DTH' && $tipe_mat == 'F')
		{
			$sql = "select mi.id_item, ac.kpno,jo.jo_no,mi.goods_code,mi.itemdesc, a.id_jo, 
			round(coalesce(sum(a.qty_in),0),2) qty_bpb, 
			round(coalesce(sum(a.qty_out),0),2) qty_bppb,
			round(coalesce(sum(a.qty_in) - sum(a.qty_out),0),2) sisa_stok, 
			round(coalesce(sum(qty_br),0),2) qty_br, 
			round(coalesce(sum(qty_br_out),0),2) qty_br_out,
			round(coalesce(sum(qty_br) - sum(qty_br_out),0),2) sisa_req,
			a.unit
			from (
			select mi.id_item,id_jo, sum(bpb.qty) qty_in, '0' qty_out,bpb.unit
			from bpb 
			inner join masteritem mi on bpb.id_item = mi.id_item
			where id_jo = '$id_jo' and mi.mattype = '$tipe_mat'
			group by id_item, id_jo, unit
			union 
			select mi.id_item,id_jo, '0' qty_in,sum(bppb.qty) qty_out,bppb.unit
			from bppb 
			inner join masteritem mi on bppb.id_item = mi.id_item
			where id_jo = '$id_jo' and mi.mattype = '$tipe_mat'
			group by id_item, id_jo, unit
			) a
			left join masteritem mi on a.id_item = mi.id_item
			inner join jo_det jd on a.id_jo  = jd.id_jo
			inner join jo on a.id_jo = jo.id
			inner join so on jd.id_so = so.id
			inner join act_costing ac on so.id_cost = ac.id
			left join (select mi.id_item, br.id_jo,round(coalesce(sum(br.qty),0),2) qty_br,round(coalesce(sum(bppb.qty),0),2) qty_br_out, br.unit from bppb_req br
			inner join masteritem mi on br.id_item = mi.id_item
			left join bppb on br.bppbno = bppb.bppbno_req and br.id_item = bppb.id_item and br.id_jo = bppb.id_jo
			where br.id_jo= '$id_jo' and mi.mattype = '$tipe_mat' and br.bppbdate >= '2023-01-01'
			group by mi.id_item, br.unit) br on a.id_item = br.id_item and a.id_jo = br.id_jo and a.unit = br.unit
			group by mi.id_item, a.id_jo, unit
			having sum(a.qty_in) - sum(a.qty_out) >'0'";			
		}	
		else if ($tipews == 'GLOBAL')
		{
			$sql = "select a.id_item,ac.kpno,jo_no,a.goods_code,a.id_jo,a.mattype,a.matclass,a.itemdesc,	round( COALESCE ( sum( qty_bpb ), 0 ), 2 ) qty_bpb,
			round(COALESCE ( sum( qty_bppb ), 0 ),2) qty_bppb,
			round(COALESCE ( sum( qty_bpb ), 0 ) - COALESCE ( sum( qty_bppb ), 0 ),2) sisa_stok,
			round(COALESCE ( br.qty_br, 0 ),2) qty_br,
			round(COALESCE ( br.qty_br_out, 0 ),2) qty_br_out,
			round( COALESCE ( br.qty_br, 0 ) - COALESCE ( br.qty_br_out, 0 ), 2 ) sisa_req,  a.unit from
			(select mi.id_item,mi.goods_code,k.id_jo,mi.mattype, mi.matclass, mi.itemdesc, sum(bpb.qty) qty_bpb, '0' qty_bppb, bpb.unit
			from (select id_item,id_jo from bom_jo_global_item k where k.id_jo = '$id_jo' group by id_item) k 
			inner join masteritem mi on k.id_item = mi.id_item
			left join bpb on mi.id_item = bpb.id_item and k.id_jo = bpb.id_jo		   
			where k.id_jo = '$id_jo' and mi.mattype = '$tipe_mat'
			group by mi.id_item, bpb.id_jo, bpb.unit
			union
			select mi.id_item,mi.goods_code,id_jo,mi.mattype, mi.matclass, mi.itemdesc, '0' qty_bpb, sum(bppb.qty) qty_bppb, bppb.unit
			from bppb 
			inner join masteritem mi on bppb.id_item = mi.id_item
			where bppb.id_jo = '$id_jo' and mi.mattype = '$tipe_mat'  and bppb.unit is not null
			group by mi.id_item, bppb.id_jo, bppb.unit					
			) a
			inner join jo_det jd on a.id_jo = jd.id_jo
			inner join jo on jd.id_jo = jo.id
			inner join so on jd.id_so = so.id
			inner join act_costing ac on so.id_cost = ac.id
			left join 
			(select mi.id_item,mi.goods_code,br.id_jo,mi.mattype, mi.matclass, mi.itemdesc,round(sum(br.qty),2) qty_br,round(sum(bppb.qty),2) qty_br_out
			from bppb_req br
			inner join masteritem mi on br.id_item = mi.id_item
			left join bppb on br.bppbno = bppb.bppbno_req and br.id_item = bppb.id_item and br.id_jo = bppb.id_jo
			where br.id_jo = '$id_jo' and mi.mattype = '$tipe_mat'  and br.unit is not null
			group by mi.id_item, br.id_jo
			) br on a.id_item = br.id_item and a.id_jo = br.id_jo
			group by a.id_item, a.id_jo, a.unit";			
		}		
		
	echo "<table id='examplefix2' class='row-border' style='width: 100%;font-size:13px;'>";
					echo "
					<thead>
					<tr>
					<td colspan = '10' >Total : </td>
					<td> <input type = 'text' size='5' id = 'total_qty_req'> </td>
					<td></td>
					</tr>
					<tr>					
							<th>JO #</th>
							<th>WS #</th>
							<th>ID Item</th>
							<th>Kode Barang</th>
							<th>Nama Barang</th>
							<th>Qty In</th>
							<th>Qty Out</th>
							<th>Qty Sisa</th>
							<th>Qty Sisa Req</th>
							<th>Qty Stok Skrg</th>
							<th>Qty Input</th>
							<th>Unit</th>";
					echo "
					</tr>
				</thead>
				<tbody>";
  				$sql="$sql";
				#echo $sql;
				$i=1;
				$query=mysql_query($sql);
				if (!$query) { die($sql. mysql_error()); }
				while($data=mysql_fetch_array($query))
				{
				$qty_sisa_req= $data[sisa_req];

				if ($qty_sisa_req < '0')
				{
					$qty_sisa_req_fix = '0';
				}
				else
				{
					$qty_sisa_req_fix = $qty_sisa_req;
				}				

				$qty_sisa = $data[sisa_stok];

				$qty_sisa_stok = $qty_sisa - $qty_sisa_req_fix; 
				
				if ($qty_sisa_stok <= '0')
				{
					$status = 'disabled';
				}
				else
				{
					$status = '';
				}


					echo "
						<tr style='width: 100%;'>";
							echo "								  
								  <td>
								  <input type = 'hidden'  style='display:none;' checked id = 'id_cek' name = 'id_cek[$id]' value = '$data[id]'>
								  <input type = 'hidden' checked id = 'id_chk' name = 'id_chk[$id]' value = '$data[id]' onchange='calc_chk()' class='chkclass'>								  
								  $data[jo_no]</td>
								  <td>$data[kpno]</td>
								  <td>$data[id_item]</td>	
								  <td style='width: 10%;'>$data[goods_code]</td>
								  <td>$data[itemdesc]</td>
								  <td>$data[qty_bpb]</td>
								  <td>$data[qty_bppb]</td>
								  <td>$data[sisa_stok]</td>
								  <td>$qty_sisa_req_fix</td>
								  <td>$qty_sisa_stok</td>
								  <td>
								  <input type ='text' size = '4' step='0.01'  max = '$qty_sisa_stok' name='qtyreq[$i]' id='qtyreq$i' $status class='form-control qtyreqclass' onFocus='startCalcReq();' onBlur='stopCalcReq();'>
								  <input type ='hidden' name ='id_item_req[$i]' id = 'id_item_req$i' value='$data[id_item]'>
								  <input type ='hidden' name ='id_jo_req[$i]' id = 'id_jo_req$i' value='$data[id_jo]'>
								  <input type ='hidden' name ='unit_req[$i]' id = 'unit_req$i' value='$data[unit]'>
								  </td>						  
								  <td>$data[unit]</td>";
							echo "
							</td>
						</tr>";
					$i++;
				};
				echo "</tbody>			
				</table>";
			
			}
	}
?>
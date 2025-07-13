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


if ($modenya=="view_list_req")
{ $tipe_mat = $_REQUEST['tipe_mat'];
if ($tipe_mat != "")
{	
	$sql="select bppbno isi, concat(bppbno,' | ',ac.kpno,' | ',ac.styleno,' | ',mb.supplier) tampil from (
		select br.*, coalesce(qty_out,0) qty_out from
		(select bppbno, id_item, id_jo, sum(qty) qty_req, bppbdate from bppb_req group by bppbno, id_item, id_jo) br
		left join (select bppbno,bppbno_req, id_item, id_jo, sum(qty) qty_out from bppb group by bppbno_req, id_item, id_jo) bppb 
		on br.bppbno = bppb.bppbno_req and br.id_item = bppb.id_item and br.id_jo = bppb.id_jo
		where bppbdate >= '2023-01-01' and br.bppbno like 'RQ-$tipe_mat%'
		group by bppbno, id_item, id_jo
		having br.qty_req > qty_out
		) a
		inner join jo_det jd on a.id_jo = jd.id_jo
		inner join so on jd.id_so = so.id
		inner join act_costing ac on so.id_cost = ac.id
		inner join mastersupplier mb on ac.id_buyer=mb.id_supplier
		group by bppbno
		order by bppbno desc, bppbdate desc";           
	IsiCombo($sql,'','Pilih No Req #');
}
}

if ($modenya=="cari_supp_req")
{	$crinya = $_REQUEST['cri_item'];
	$sql="select s.supplier,jo_no, ac.kpno, idws_act, mb.supplier buyer from bppb_req a 
	inner join mastersupplier s on a.id_supplier=s.id_supplier 
	inner join jo on a.id_jo=jo.id 
	inner join jo_det jd on a.id_jo = jd.id_jo
	inner join so on jd.id_so = so.id
	inner join act_costing ac on so.id_cost = ac.id 
	inner join mastersupplier mb on ac.id_buyer = mb.id_supplier 
	where bppbno='$crinya' limit 1";
	$rsjo=mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rsjo['supplier'],$rsjo['jo_no'],$rsjo['kpno'],$rsjo['idws_act'],$rsjo['buyer']));	
}

if ($modenya=="view_list_stock_req")
  {	$crinya = $_REQUEST['no_req'];
	$tipe_mat = $_REQUEST['tipe_mat'];
	  if ($crinya!="")
	  {			  	
		 $id_jo=flookup("group_concat(distinct id_jo)","bppb_req","bppbno='$crinya' group by bppbno");
		   
		  echo "<table id='examplefix1' style='width: 100%;font-size:14px;'>";
			  echo "
				  <thead>
						  <tr>
						  <th colspan = '3' >Daftar Request</th>
						  <th colspan = '10' ></th>
						  </tr>
					  <tr>";
						  echo "
						  <th>No</th>
						  <th>Style</th>
						  <th>ID Item</th>
						  <th>Kode Bahan Baku</th>
						  <th>Deskripsi</th>
						  <th>Stock/JO</th>
						  <th>Stock</th>
						  <th>Unit</th>
						  <th>Qty Req</th>
						  <th>Sisa Qty Req</th>
						  <th>Qty Out</th>
						  <th>Detail</th>";
					  echo "
					  </tr>
				  </thead>
				  <tbody>";
				  if($modenya=="view_list_stock_req" && $tipe_mat=="F")
				  {	$sql="select br.bppbno, br.id_supplier, ac.styleno,br.id_item, mi.itemdesc, mi.goods_code, br.id_jo, round(coalesce(qty_in,0) - coalesce(tbl_out.qty_out,0),2) stok_sisa, round(coalesce(br.qty,0),2) qty_req, round(coalesce(a.qty_out,0),2) qty_out, 
					round(coalesce(br.qty,0) - coalesce(a.qty_out,0),2) sisa_req,br.unit  
					from bppb_req br
					left join 
					(
					select bppbno_req,id_jo, id_item,sum(qty) qty_out, unit from bppb where bppbno_req = '$crinya' group by id_item, id_jo, bppbno_req
					) a
					 on br.bppbno = a.bppbno_req and br.id_item = a.id_item and br.id_jo = a.id_jo and br.unit = a.unit 
					left join 
					(
					 select id_item, id_jo,sum(qty) qty_in, unit from bpb where id_jo = '$id_jo' and bpbno not like 'FG%' group by id_item, unit
					)tbl_in on br.id_item = tbl_in.id_item and br.id_jo = tbl_in.id_jo and br.unit = tbl_in.unit 
					left join 
					(
					 select id_item, id_jo,sum(qty) qty_out, unit from bppb where id_jo = '$id_jo' and bppbno not like 'SJ-FG%' group by id_item, unit
					)tbl_out on br.id_item = tbl_out.id_item and br.id_jo = tbl_out.id_jo and br.unit = tbl_out.unit 
					inner join masteritem mi on br.id_item = mi.id_item
					inner join jo_det jd on br.id_jo = jd.id_jo
					inner join so on jd.id_so = so.id
					inner join act_costing ac on so.id_cost = ac.id
					where br.bppbno = '$crinya' and br.cancel = 'N'
					group by bppbno, id_item, id_jo, unit
					  ";
				  }
				  elseif($modenya=="view_list_stock_req" && $tipe_mat=="A")
				  {	$sql="select br.bppbno, br.id_supplier, ac.styleno,br.id_item, mi.itemdesc, mi.goods_code, br.id_jo, round(coalesce(qty_sa,0),2) + round(coalesce(qty_in,0),2) - round(coalesce(tbl_out.qty_out,0),2) stok_sisa, round(coalesce(br.qty,0),2) qty_req, round(coalesce(a.qty_out,0),2) qty_out, 
					round(coalesce(br.qty,0) - coalesce(a.qty_out,0),2) sisa_req,br.unit  
					from bppb_req br
					left join 
					(
					select bppbno_req,id_jo, id_item,sum(qty) qty_out, unit from bppb where bppbno_req = '$crinya' group by id_item, id_jo, bppbno_req
					) a
					 on br.bppbno = a.bppbno_req and br.id_item = a.id_item and br.id_jo = a.id_jo and br.unit = a.unit
					 left join (select id_item, id_jo,sum(qty) qty_sa, unit from saldoawal_rak where id_jo = '$id_jo' and periode = '2023-02-01' and tipe_mat = 'A' group by id_item, unit) tbl_sa
					 on br.id_item = tbl_sa.id_item and br.id_jo = tbl_sa.id_jo and br.unit = tbl_sa.unit  
					left join 
					(
					 select id_item, id_jo,sum(qty) qty_in, unit from bpb where id_jo = '$id_jo' and bpbdate >= '2023-02-01' and bpbno not like 'FG%' group by id_item, unit
					)tbl_in on br.id_item = tbl_in.id_item and br.id_jo = tbl_in.id_jo and br.unit = tbl_in.unit 
					left join 
					(
					 select id_item, id_jo,sum(qty) qty_out, unit from bppb where id_jo = '$id_jo' and bppbdate >= '2023-02-01' and bppbno not like 'SJ-FG%' group by id_item, unit
					)tbl_out on br.id_item = tbl_out.id_item and br.id_jo = tbl_out.id_jo and br.unit = tbl_out.unit 
					inner join masteritem mi on br.id_item = mi.id_item
					inner join jo_det jd on br.id_jo = jd.id_jo
					inner join so on jd.id_so = so.id
					inner join act_costing ac on so.id_cost = ac.id
					where br.bppbno = '$crinya' and br.cancel = 'N'
					group by bppbno, id_item, id_jo, unit
					  ";
				  }				  
				  #echo $sql;
				  $i=1;
				  $query=mysql_query($sql);
				  while($data=mysql_fetch_array($query))
				  {	
					if ($tipe_mat == "A")
					{
						$id=$data['id_item']."|".$data['id_jo'];				
						$id_item = $data['id_item'];						
						$sisaall =
						flookup("sum(qty)","saldoawal_rak","id_item='$id_item' and periode = '2023-02-01'") 
						+
						flookup("sum(qty)","bpb","id_item='$id_item' and bpbno not like 'FG%' and bpbdate >= '2023-02-01'") - 
						flookup("sum(qty)","bppb","id_item='$id_item' and bppbno not like 'SJ-FG%' and bppbdate >= '2023-02-01'");	
						$sisaall = number_format((float)$sisaall, 2, '.', '');					
					}

					else if ($tipe_mat == "F")
					{
					$id=$data['id_item']."|".$data['id_jo'];				
					$id_item = $data['id_item'];
					$sisaall = flookup("sum(qty)","bpb","id_item='$id_item' and bpbno not like 'FG%'") - 
					flookup("sum(qty)","bppb","id_item='$id_item' and bppbno not like 'SJ-FG%'");
					$sisaall = number_format((float)$sisaall, 2, '.', '');
					}
					  echo "
						  <tr>"; 
							  echo "
							  <td>$i</td>
							  <td>$data[styleno]</td>
							  <td>
							  		$data[id_item]
								  <input type ='hidden' size='7' name ='id_item[$id]' value='$data[id_item]' id='id_item$i' readonly class='form-control' readonly>
							  </td>
							  <td>$data[goods_code]</td>
							  <td>$data[itemdesc]</td>
							  <td>
								  <input type ='text' size='7' name ='qtysisa[$id]' value='$data[stok_sisa]' id='qtysisa$i' class='form-control qtysisaclass' readonly>
							  </td>
							  <td>
							  	<input type ='text' size='8' name ='qtysisaall[$id]' value='$sisaall' id='qtysisaall$i' class='form-control qtysisaallclass' readonly>
							  </td>
							  <td>
								  <input type ='text' size='7' name ='unitsisa[$id]' value='$data[unit]' id='unitsisa$i' readonly class='form-control' readonly>
							  </td>
							  <td>
								  <input type ='text' size='7' name ='qty_req[$id]' value='$data[qty_req]' id='qty_req$i' class='form-control qty_reqclass' readonly>
							  </td>
							  <td>
								  <input type ='text' size='7' value='$data[sisa_req]' class='form-control sisa_req' readonly>
							  </td>							
							  <td>
							  <input type ='text' style='width:75px;height:34px;background-color : #F5F5F5' name='totqtyroll[$id]' id='totqtyroll$i' 
								  class='totqtyrollclass' readonly>
								  <input type ='hidden' size='8' name='qtyroll[$id]' id='qtyroll$i' 
								  class='qtyrollclass'>  
								  <input type ='hidden' name ='jono[$id]' id='jono$i' class='jonoclass' value='$data[id_jo]'>
								  <input type ='hidden' name ='id_supp[$id]' id='id_supp$i' class='id_suppclass' value='$data[id_supplier]'>
							  </td>";
	  echo "
								  <td>
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
  if ($modenya=="view_list_rak_loc")
{	
	$id_item = $_REQUEST['id_item'];
	$id_jo = $_REQUEST['id_jo'];
	$tipe_mat_1 = $_REQUEST['tipe_mat'];
	#echo $id_item."-".$id_jo;
	echo "<br>
	<form method='post' name='form1'>
 		<div style='font-size:18px;'>Total : <p id='total_qty_chk' style='display:none' value=''></p> </div>
    <table id='examplefix' width='100%' style='font-size:13px;'>";
		echo "
			<thead>";
			if ($tipe_mat_1 == 'F')
			{ echo"
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
			</thead>";
			}
			if ($tipe_mat_1 == 'A')
			{ echo"
				<tr>
					<th>..</th>
					<th>Nomor BPB</th>
					<th>ID</th>
					<th>No Pack</th>
					<th>Qty Awal</th>
					<th>Qty Sudah Digunakan</th>
					<th>Qty sisa</th>
					<th>Unit</th>
					<th>Rak #</th>
				</tr>
			</thead>";
			}			
			echo "<tbody>";
			if ($tipe_mat_1 == "F")
			{
			$sql="SELECT br.id,
			br.id_h,
			brh.id_item,
			brh.id_jo,
			roll_no,
			lot_no,
			COALESCE ( round( roll_qty, 2 ), 0 ) roll_qty,
			coalesce(round(roll_qty_used,2),0) roll_qty_used,
			COALESCE ( round( roll_qty, 2 ) - round( roll_qty_used, 2 ), 0 ) qty_sisa,
			roll_foc,
			br.unit,
			concat( kode_rak, ' ', nama_rak ) raknya,
			br.barcode 
		FROM
			bpb_roll br
			INNER JOIN bpb_roll_h brh ON br.id_h = brh.id
			INNER JOIN master_rak mr ON br.id_rak_loc = mr.id 
				where 
				brh.id_jo='$id_jo' and brh.id_item='$id_item' and br.id_rak_loc!='' 
				order by br.id";
			}
			else if ($tipe_mat_1 == "A")
			{
			$sql="SELECT a.id,a.bpbno_int, a.no_pack, a.id_item, a.id_jo,
			round(coalesce(a.roll_qty,0),2) roll_qty,
			round(coalesce(qty_out,0),2) qty_out,
			round(coalesce(a.roll_qty,0),2) - round(coalesce(qty_out,0),2) qty_sisa,
			a.unit,a.id_rak_loc,mr.kode_rak, concat( kode_rak, ' ', nama_rak ) raknya from bpb_det a 
			inner join master_rak mr on a.id_rak_loc = mr.id 
			left join (select sum(roll_qty) qty_out, id_bpb_det from bppb_det where id_item = '$id_item' and id_jo = '$id_jo' group by id_bpb_det) b on a.id = b.id_bpb_det
			where a.cancel = 'N' and a.id_item = '$id_item' and a.id_jo = '$id_jo'";
			}
			#echo $sql; 
			
			$i=1;
			$query=mysql_query($sql);
			$matnya = "";
			while($data=mysql_fetch_array($query))
			{

			if($tipe_mat_1 == "F")
			{	
				$x = $data['id_item']."|".$data['id_jo']."|".$data['id']."|".$data['id_h'];
				$tot_isi = $data['qty_sisa'];

			}
			else if($tipe_mat_1 == "A")
			{
				$x = $data['id_item']."|".$data['id_jo']."|".$data['id']."|".$data['id_rak_loc'];
				$tot_isi = $data['qty_sisa'];				
			} 

				if ($tot_isi <= '0')
				{
					$status = 'disabled';
				}
				else
				{
					$status = '';
				}
				if($tipe_mat_1 == "F")
				{
				echo "
					<tr>
					<td>
						<input type='checkbox' name='chkid[$x]' $status id='chkajax' class='chkclass' onchange='calc_chk()'>
						<input type='hidden' name='txtbts[$x]' id='txtbtsajax' class='txtbtsclass' 
						value='$tot_isi'>
						<input type='hidden' name='txtcri[$x]' id='txtcriajax' class='txtcriclass' 
						value='$x'>
					</td>
						<td>$data[id]</td>
						<td>$data[roll_no]</td>
						<td>$data[lot_no]</td>
					<td>
						<input type='hidden' name='id_item_barcode[$x]' id='id_item_barcode' class='id_item_barcode' 
						value='$data[id_item]'>
						<input type='hidden' name='id_jo_barcode[$x]' id='id_jo_barcode' class='id_jo_barcode' 
						value='$data[id_jo]'>													
						$data[roll_qty]
					</td>
						<td>$data[roll_qty_used]</td>
						<td><input type='number' style='width:100px;height:34px;' name='txtuse[$x]' id='txtuseajax' class='txtuseclass' 
						value='$tot_isi' step='0.01' max='$tot_isi' onchange='calc_chk()' $status ></td>
						<td>$data[unit]</td>
						<td>$data[raknya]</td>
					</tr>";
				}
				else if($tipe_mat_1 == "A")
				{
				echo "
					<tr>
					<td>
						<input type='checkbox' name='chkid[$x]' $status id='chkajax' class='chkclass' onchange='calc_chk()'>
						<input type='hidden' name='txtbts[$x]' id='txtbtsajax' class='txtbtsclass' 
						value='$tot_isi'>
						<input type='hidden' name='txtcri[$x]' id='txtcriajax' class='txtcriclass' 
						value='$x'>
					</td>
						<td>$data[bpbno_int]</td>
						<td>$data[id]</td>
						<td>$data[no_pack]</td>
					<td>
						<input type='hidden' name='id_item_barcode[$x]' id='id_item_barcode' class='id_item_barcode' 
						value='$data[id_item]'>
						<input type='hidden' name='id_jo_barcode[$x]' id='id_jo_barcode' class='id_jo_barcode' 
						value='$data[id_jo]'>													
						$data[roll_qty]
					</td>
						<td>$data[qty_out]</td>
						<td><input type='number' style='width:100px;height:34px;' name='txtuse[$x]' id='txtuseajax' class='txtuseclass' 
						value='$tot_isi' step='0.01' max='$tot_isi' onchange='calc_chk()' $status ></td>
						<td>$data[unit]</td>
						<td>$data[raknya]</td>
					</tr>";
				}					
				$i++;
			};
	echo "</tbody>
	</table>
	</form>";

}

<?php

include "../../include/conn.php";

include "fungsi.php";



$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));

	$nm_company=$rscomp["company"];
	$allowance_bpb = $rscomp["allowance_bpb"];
	$st_company=$rscomp["status_company"];

	$status_company=$rscomp["jenis_company"];

	$jenis_company=$rscomp["jenis_company"];

	$logo_company=$rscomp["logo_company"];

	$harus_bpb=$rscomp["req_harus_bpb"];

	$out_harus_qc=$rscomp["out_cek_qc"];

	$master_rak=flookup("master_rak","userpassword","master_rak='1' limit 1");

$modenya = $_GET['modeajax'];

#echo $modenya;

if ($modenya=="calc_price_satuan" or $modenya=="calc_total_nilai")

{	$cri=unfn($_REQUEST['nilainya']);

	$qty=unfn($_REQUEST['qtynya']);

	if($modenya=="calc_price_satuan")

	{	$hsl=$cri / $qty;	}

	else

	{	$hsl=$cri * $qty;	}

	echo json_encode(array(fn($hsl,8),fn($cri,8)));	

}



if ($modenya=="conv_amt_to_usds")

{	$cri=unfn($_REQUEST['tglnya']);

	$tanggalnya = date('d M Y', strtotime($_REQUEST['tglnya']));

	$tanggalnya = date('Y-m-d', strtotime($tanggalnya));

	if($cri!="")

	{	if($logo_company=="S")

		{	$sql="SELECT * FROM masterrate WHERE v_codecurr IN('HARIAN') AND curr = '$_REQUEST[currnya]'  

				AND tanggal = '$tanggalnya'"; 

		}

		else

		{	$sql="SELECT * FROM masterrate WHERE curr = '$_REQUEST[currnya]'  

				AND tanggal = '$tanggalnya'"; 

		}

		$rs=mysql_fetch_array(mysql_query($sql));

	///	print_r($sql);

		$kanversinya = $rs['rate'] * $_REQUEST['pricenya'];

		echo json_encode

		(array

			(	number_format($kanversinya)

			)

		);

	}	

}



if ($modenya=="conv_amt_to_usd")

{	$price=unfn($_REQUEST['pricenya']);

	$curr=$_REQUEST['currnya'];

	echo json_encode(array($price,$price));	

}



if ($modenya=="cari_data_sec" or $modenya=="calc_total_nilai")

{	$cri=unfn($_REQUEST['id_sec']);

	if($cri!="")

	{	$sql="select * from list_in_out where id='$cri'";

		$rs=mysql_fetch_array(mysql_query($sql));

		echo json_encode

		(array

			(	$rs['nomor_sj'],

				$rs['nomor_polisi'],

				$rs['dateinput']

			)

		);

	}	

}



if ($modenya=="view_list_po")

{	$crinya = $_REQUEST['cri_item'];

	if ($crinya!="")

	{	$ponya=$crinya;

		echo "<table id='examplefix2' style='width: 100%;font-size:12px;'>";

			echo "

				<thead>

					<tr>";

						echo "<th>No</th>";

						if($jenis_company=="VENDOR LG")

						{	echo "<th>Nomor JO</th>"; }

						else

						{	echo "<th>Nomor WS</th>"; }

						echo "	

						<th>Product</th>

						<th>Kode Bahan Baku</th>

						<th>Deskripsi</th>

						<th>Qty PO</th>

						<th>Balance</th>

						<th>Satuan</th>

						<th>Qty BPB</th>

						<th>Qty Reject</th>";

						// <th>Nomor Rak</th>

						echo "

						<th>Berat Bersih</th>

						<th>Berat Kotor</th>";

						// <th>Keterangan</th>

					echo "

					</tr>

				</thead>

				<tbody>";

				$jenispo=flookup("jenis","po_header","pono='$ponya'");

				if ($jenis_company=="VENDOR LG" or $jenispo=="N" or $jenispo=="P")

				{	$sql_join=" s.id_gen=d.id_item "; }

				else 

				{	$sql_join=" s.id_gen=d.id_gen "; }

				if($jenispo=="N")

				{	$sql="select s.id line_item,d.id_item,'' kpno,d.goods_code,d.itemdesc,s.qty,s.unit, 

					s.id_jo,s.price,s.curr,tmpbpb.qty_bpb from po_header a inner join po_item s on a.id=s.id_po 

					inner join masteritem d on $sql_join 

					left join (select id_po_item,sum(qty) qty_bpb from bpb where pono='$ponya' group by id_po_item) tmpbpb 

					on tmpbpb.id_po_item=s.id  

					where a.pono='$ponya' and s.cancel='N' 

					order by s.id ";

				}

				else

				{	$sql="select s.id line_item,d.id_item,ac.kpno,d.goods_code,concat(d.itemdesc,' ',d.color,' ',d.size,' ',d.add_info) itemdesc,s.qty,s.unit, 

					s.id_jo,s.price,s.curr,tmpbpb.qty_bpb,mp.product_group,jo_no from po_header a inner join po_item s on a.id=s.id_po 

					inner join masteritem d on $sql_join inner join jo_det jod on s.id_jo=jod.id_jo 

					inner join jo on jod.id_jo=jo.id 

					inner join so on jod.id_so=so.id

					inner join act_costing ac on so.id_cost=ac.id 

					inner join masterproduct mp on ac.id_product=mp.id 

					left join (select id_po_item,sum(qty)-sum(coalesce(qty_reject)) qty_bpb from bpb where pono='$ponya' group by id_po_item) tmpbpb 

					on tmpbpb.id_po_item=s.id  

					where a.pono='$ponya' and s.cancel='N' 

					group by s.id order by d.id_item ";

				}

				//echo "<pre>$sql</pre>";

				$i=1;
				#echo $sql;
				$query=mysql_query($sql);

				while($data=mysql_fetch_array($query))

				{	$id=$data['id_item'].":".$data['line_item'];

					$qtypoallo = round($data['qty'] + ($data['qty'] * $allowance_bpb/100),5);
					$qtybal=round($qtypoallo - $data['qty_bpb'],0,PHP_ROUND_HALF_UP);
					echo "

						<tr>";

							echo "<td>$i</td>";

							if($jenis_company=="VENDOR LG")

							{	echo "<td>$data[jo_no]</td>"; }

							else

							{	echo "<td>$data[kpno]</td>"; }

							echo "

							<td>$data[product_group]</td>

							<td>$data[goods_code]</td>

							<td>$data[itemdesc]</td>

							<td>

								<input type ='text' size='4' name ='qtypo[$id]' value='".round($data['qty'],5)."' id='qtypo$i' readonly>

								<input type ='hidden' name ='pricepo[$id]' value='$data[price]' id='pricepo$i'>

								<input type ='hidden' name ='currpo[$id]' value='$data[curr]' id='currpo$i'>

							</td>

							<td><input type ='text' size='4' value='$qtybal' name ='qtybal[$id]' class='qtybalclass' id='qtybal$i' readonly></td>

							<td><input type ='text' size='4' name ='unitpo[$id]' value='$data[unit]' id='unitpo$i' readonly></td>

							<td>

								<input type ='number' step ='0.01' name ='qtybpb[$id]' max = '$qtybal' class='qtyclass' id='qtybpb$i' onchange='calc_total()'>

								<input type ='hidden' value='$data[id_jo]' name ='id_jo[$id]' id='id_jo$i'>

							</td>";

							// if($master_rak!="")

							// {	echo "<td><select style='width:70px; height: 26px;' name ='nomrak[$id]' 

							// 		class='nomrakclass' id='nomrak$i'>";

		     //        $sql="select kode_rak isi,concat(kode_rak,' ',nama_rak) tampil

		     //          from master_rak ";

		     //        IsiCombo($sql,'','Pilih Rak');

		     //        echo "</select></td>";

		     //      }

							// else

							// {	echo "<td><input type ='text' size='4' name ='nomrak[$id]' 

							// 		class='nomrakclass' id='nomrak$i'></td>"; 

							// }

							echo "

							<td>

								<input type ='text' size='4' name ='qtyreject[$id]' id='qtyreject$i' 

									class='qtyreject'>

							</td>

							<td><input type ='text' size='4' name ='beratb[$id]' class='beratbclass' id='beratb$i'></td>

							<td><input type ='text' size='4' name ='beratk[$id]' class='beratkclass' id='beratk$i'></td>";

							// <td><input type ='text' size='15' name ='ket[$id]' class='ketclass' id='ket$i'></td>

						echo "

						</tr>";

					$i++;

				};

		echo "</tbody></table>";

	}

}



if ($modenya=="view_list_stock" or $modenya=="view_list_stock2" or $modenya=="view_list_stock2FG")

{	if($modenya=="view_list_stock")

	{	$crinya = json_encode($_REQUEST['id_jo']); }

	else if($modenya=="view_list_stock2FG")

	{	$crinya = $_REQUEST['id_so'];	}

	else

	{	$crinya = $_REQUEST['id_jo'];	}

	if ($crinya!="")

	{	$ponya=$crinya;

		echo "<table id='examplefix' class='display responsive' style='width: 100%;'>";

			echo "

				<thead>

					<tr>";

						if($modenya=="view_list_stock2" or $modenya=="view_list_stock2FG")

						{	echo "

							<th>From JO</th>

							<th>Product</th>"; 

						}

						else

						{	echo "

							<th>JO #</th>

							<th>Product</th>"; 

						}

						if($modenya!="view_list_stock2FG")

						{	echo "

							<th>Kode Bahan Baku</th>

							<th>Deskripsi</th>";

						}

						echo "

						<th>Qty In</th>

						<th>Qty Out</th>";

						if ($modenya=="view_list_stock2" or $modenya=="view_list_stock2FG" or $harus_bpb=="Y")

						{	echo "<th>Stock</th>"; }

						else

						{	echo "<th>Qty BOM</th>"; }

						if ($jenis_company=="VENDOR LG" and $modenya!="view_list_stock2" and $modenya!="view_list_stock2FG")

						{	echo "<th>Cons</th>"; }

						echo "

						<th>Unit</th>";

						if ($modenya=="view_list_stock2" or $modenya=="view_list_stock2FG")

						{	echo "<th>Qty Transfer</th>"; }

						else

						{	echo "<th>Qty Request</th>"; }

					echo "

					</tr>

				</thead>

				<tbody>";

				if($jenis_company=="VENDOR LG") { $sql_join="k.id_item=mi.id_item"; } else { $sql_join="k.id_item=mi.id_gen"; }

				if($modenya=="view_list_stock2")

				{	

					$list_item_to_jo="";

					$sqllist="select mi.id_item from bom_jo_item k inner join masteritem mi on $sql_join

						where id_jo='$crinya' and k.status='M' group by id_jo,mi.id_item";

					#echo $sqllist;

					$qlist=mysql_query($sqllist);

					while($rslist=mysql_fetch_array($qlist))

					{	if($list_item_to_jo=="")

						{	$list_item_to_jo = $rslist['id_item'];	}

						else

						{	$list_item_to_jo = $list_item_to_jo.",".$rslist['id_item'];	}

					}

					if($list_item_to_jo=="") { $list_item_to_jo="'NO_JO_FOUND'"; }

					$sql="select mi.goods_code,mi.itemdesc,mi.id_item,jo.id as id_jo,jo_no,

						IFNULL(tbl_in.qty_in,0) qty_in,

						IFNULL(tbl_out.qty_out,0) qty_out,

						tbl_in.unit,mp.product_group 

						from  

			      	(select id_item,id_jo,sum(qty) qty_in,unit from bpb where 

			      		id_item in ($list_item_to_jo) and id_jo!='$crinya' 

			      		group by id_item,id_jo) as tbl_in 

						inner join masteritem mi on mi.id_item=tbl_in.id_item 

						inner join jo on jo.id=tbl_in.id_jo 

						inner join jo_det jod on jo.id=jod.id_jo 

						inner join so  on jod.id_so=so.id 

						inner join act_costing ac on so.id_cost=ac.id 

						inner join masterproduct mp on ac.id_product=mp.id   

						left join 

							(select id_item,id_jo,sum(qty) qty_out from bppb where 

								id_item in ($list_item_to_jo) and id_jo!='$crinya'  

								group by id_item,id_jo) as tbl_out

						on tbl_in.id_item=tbl_out.id_item and tbl_in.id_jo=tbl_out.id_jo 

						group by mi.id_item,id_jo ";

					#echo $sql;

				}

				else if($modenya=="view_list_stock2FG")

				{	

					$product_to_jo=flookup("ac.id_product","so inner join act_costing ac on so.id_cost=ac.id

						inner join so_det sod on so.id=sod.id_so ","sod.id='$crinya'");

					$sql="select tbl_in.id_so_det,id_jo,jo_no,

						IFNULL(tbl_in.qty_in,0) qty_in,

						IFNULL(tbl_out.qty_out,0) qty_out,

						tbl_in.unit,mp.product_group 

						from  

			      	(select ac.id_product,id_so_det,sum(a.qty) qty_in,a.unit,jod.id_jo,jo_no from bpb a inner join so_det sod on a.id_so_det=sod.id 

			      		inner join so on sod.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 

			      		inner join jo_det jod on so.id=jod.id_so 

			      		inner join jo on jod.id_jo=jo.id  

			      		where 

			      		id_product='$product_to_jo' and id_so_det!='$crinya' 

			      		group by id_so_det) as tbl_in 

						inner join masterproduct mp on mp.id=tbl_in.id_product 

						left join 

							(select id_so_det,sum(a.qty) qty_out from bppb a inner join so_det sod on a.id_so_det=sod.id 

			      		inner join so on sod.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 

			      		where 

								id_product='$product_to_jo' and id_so_det!='$crinya'  

								group by id_so_det) as tbl_out

						on tbl_in.id_so_det=tbl_out.id_so_det  

						where IFNULL(tbl_in.qty_in,0) - IFNULL(tbl_out.qty_out,0) > 0 

						group by id_so_det ";

					#echo $sql;

				}

				else

				{	

					if($harus_bpb=="Y") { $sqljoin=" inner "; } else { $sqljoin=" left "; }

					$crinya = str_replace("[","",$crinya);

  				$crinya = str_replace("]","",$crinya);

  				$sql="select jo_no,mi.goods_code,mi.itemdesc,mi.id_item,k.id_jo,

						IFNULL(tbl_in.qty_in,0) qty_in,

						IFNULL(tbl_out.qty_out,0) qty_out,

						round(sum(l.qty*k.cons),2) qty_bom,k.unit,mp.product_group,k.cons 

						from bom_jo_item k inner join so_det l on k.id_so_det=l.id 

						inner join jo on k.id_jo=jo.id 

						inner join jo_det jod on jo.id=jod.id_jo 

						inner join so on jod.id_so=so.id 

						inner join act_costing ac on so.id_cost=ac.id 

						inner join masterproduct mp on ac.id_product=mp.id  

						inner join masteritem mi on $sql_join $sqljoin join

						(select id_item,id_jo,sum(qty) qty_in,unit from bpb 

							where id_jo in ($crinya) group by id_item,id_jo) as tbl_in 

						on mi.id_item=tbl_in.id_item and k.id_jo=tbl_in.id_jo 

						left join 

						(select id_item,id_jo,sum(qty) qty_out from bppb 

							where id_jo in ($crinya) group by id_item,id_jo) as tbl_out

						on tbl_in.id_item=tbl_out.id_item and tbl_in.id_jo=tbl_out.id_jo 

						where k.id_jo in ($crinya) group by k.id_jo,mi.id_item ";

					#echo $sql;

				}

				$i=1;

				$query=mysql_query($sql);

				if (!$query) { die($sql. mysql_error()); }

				while($data=mysql_fetch_array($query))

				{	if($modenya=="view_list_stock2FG")

					{	$id=$data['id_so_det']; }

					else

					{	$id=$data['id_item'].":".$data['id_jo']; }

					if($modenya=="view_list_stock2" or $harus_bpb=="Y")

					{$sisa = round($data['qty_in'],2) - round($data['qty_out'],2);}

					else

					{$sisa = $data['qty_bom'];}

					echo "

						<tr>";

							echo "

							<td>

								$data[jo_no]";

								if($modenya=="view_list_stock2FG")

								{	echo "<input type ='hidden' size='4' name ='jo[$id]' value='$data[id_so_det]' id='jo$i' readonly>";	}

								else

								{	echo "<input type ='hidden' size='4' name ='jo[$id]' value='$data[id_jo]' id='jo$i' readonly>"; }

							echo "

							</td>";

							echo "<td>$data[product_group]</td>";

							if($modenya!="view_list_stock2FG")

							{	echo "

								<td>$data[goods_code]</td>

								<td>$data[itemdesc]</td>";

							}

							echo "

							<td>".round($data['qty_in'],2)."</td>

							<td>".round($data['qty_out'],2)."</td>

							<td><input type ='text' size='4' name ='qtysisa[$id]' value='$sisa' id='qtysisa$i' class='qtysisaclass' readonly></td>";

							if($jenis_company=="VENDOR LG" and $modenya!="view_list_stock2" and $modenya!="view_list_stock2FG")

							{	echo "<td>$data[cons]</td>"; }

							echo "

							<td><input type ='text' size='4' name ='unitsisa[$id]' value='$data[unit]' id='unitsisa$i' readonly></td>

							<td>

								<input type ='number' step='any' style='width:4em;' name ='qtybpb[$id]' id='qtybpb$i' 

									class='qtybpbclass' step='any'>";

								if($modenya=="view_list_stock2" or $modenya=="view_list_stock2FG")

								{	echo "<input type ='hidden' style='width:4em;' name ='jo[$id]' id='jo$i' class='joclass' value='$data[id_jo]'>"; }

							echo "

							</td>

						</tr>";

					$i++;

				};

		echo "</tbody></table>";

	}

}



if ($modenya=="view_list_stock_req")

{	$crinya = $_REQUEST['id_jo'];

	if ($crinya!="")

	{	if($modenya=="view_list_stock_req")

		{	$ponya=$crinya;

			$id_jo=flookup("group_concat(distinct id_jo)","bppb_req","bppbno='$crinya' group by bppbno");

		}

		else

		{	$id_jo=$crinya; }

		if($jenis_company=="VENDOR LG")

		{ $cap_ws="JO #"; }

		else

		{ $cap_ws="WS #"; }

		if($out_harus_qc=="Y")

		{ $sql_qc=" and dicekqc!='N'"; }

		else

		{ $sql_qc=" "; }

		echo "<table id='examplefix3' style='width: 100%;font-size:12px;'>";

			echo "

				<thead>

					<tr>";

						echo "

						<th>$cap_ws</th>

						<th>Style #</th>

						<th>Buyer</th>

						<th>Kode Bahan Baku</th>

						<th>Deskripsi</th>

						<th>Stock/JO</th>

						<th>Stock</th>

						<th>Unit</th>

						<th>Qty Req</th>

						<th>Qty Out</th>";

						if($jenis_company!="VENDOR LG")

						{	echo "<th>Detail</th>"; }

					echo "

					</tr>

				</thead>

				<tbody>";

				if($modenya=="view_list_stock_req")

				{	$sql="select jod.jo_no,breq.id_supplier,breq.qty qtyreq,mi.goods_code,mi.itemdesc,tbl_in.id_item,

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

					where breq.bppbno='$crinya'";

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

								<input type ='text' size='4' name ='qtyout[$id]' id='qtyout$i' class='form-control qtyoutclass'>

								<input type ='hidden' name ='jono[$id]' id='jono$i' class='jonoclass' value='$data[id_jo]'>

								<input type ='hidden' name ='id_supp[$id]' id='id_supp$i' class='id_suppclass' value='$data[id_supplier]'>

							</td>";

							if($jenis_company!="VENDOR LG")

							{	echo "

								<td>

									<input type ='hidden' size='4' name='qtyroll[$id]' id='qtyroll$i' 

										class='form-control qtyrollclass'>

									<input type ='hidden' size='4' name='totqtyroll[$id]' id='totqtyroll$i' 

										class='totqtyrollclass'>

									<input type ='hidden' size='4' name='qtyrollori[$id]' id='qtyrollori$i' 

										class='qtyrolloriclass' value='$id'>

									<button type='button' class='btn btn-primary' data-toggle='modal' 

										data-target='#myRak' onclick='choose_rak($data[id_jo],$data[id_item])'>Rak</button>

								</td>"; 

							}

						echo "

						</tr>";

					$i++;

				};

		echo "</tbody></table>";

	}

}



if ($modenya=="cari_sup")

{	$crinya = $_REQUEST['cri_item'];

	$sql = "select a.id_supplier isi,a.supplier tampil from 

		mastersupplier a inner join po_header s on a.id_supplier=s.id_supplier

		where pono='$crinya' limit 1";

	$rs=mysql_fetch_array(mysql_query($sql));

	$supplier=$rs['isi'];

	IsiCombo($sql,$supplier,'Pilih Supplier');

}



if ($modenya=="copy_po")

{	$crinya = $_REQUEST['cri_item'];

	print($crinya);

}

?>
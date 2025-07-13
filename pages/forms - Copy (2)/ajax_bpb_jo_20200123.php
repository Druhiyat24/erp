<?php
include "../../include/conn.php";
include "fungsi.php";

$modenya = $_GET['modeajax'];
$jenis_company=flookup("jenis_company","mastercompany","company!=''");
#echo $modenya;

if ($modenya=="view_list_det_mon_ord")
{	$mat = $_REQUEST['mat'];
	$id_jo = $_REQUEST['id_jo'];
	$pro = $_REQUEST['trxnya'];
	if($mat=="1")
	{ $nm_group="FABRIC"; }
	elseif($mat=="2")
	{ $nm_group="ACCESORIES SEWING"; }
	elseif($mat=="3")
	{ $nm_group="ACCESORIES PACKING"; }
	if($pro=="1" or $pro=="2")
	{	echo "<table id='examplefix' width='100%' style='font-size:11px;'>";
			echo "
				<thead>
					<tr>
						<th>No.</th>
						<th>Item #</th>
						<th>Description</th>
						<th>Supplier</th>";
						if($pro=="2")
						{ echo "
							<th>PO #</th>
							<th>PO Date</th>
							<th>Qty PO</th>
							<th>Unit PO</th>
							<th>Created By</th>"; 
						}
					echo "
					</tr>
				</thead>
				<tbody>";
				$sql = "select mi.goods_code,mi.itemdesc,ms.supplier,vponya,maxpo,qtypo,unitpo,userpo     
					from bom_jo_item bom INNER JOIN masterdesc j on bom.id_item=j.id
	        INNER JOIN mastercolor i on i.id=j.id_color
	        INNER JOIN masterweight h on h.id=i.id_weight
	        INNER JOIN masterlength g on g.id=h.id_length
	        INNER JOIN masterwidth f on f.id=g.id_width
	        INNER JOIN mastercontents e on e.id=f.id_contents
	        INNER JOIN mastertype2 d on d.id=e.id_type
	        INNER JOIN mastersubgroup s on s.id=d.id_sub_group
	        INNER JOIN mastergroup a on a.id=s.id_group
					left join masteritem mi on j.id=mi.id_gen 
					left join mastersupplier ms on bom.id_supplier=ms.id_supplier 
					left join 
					(select id_gen,group_concat(distinct pono) vponya,max(podate) maxpo,sum(qty) qtypo,unit unitpo,username userpo  
						from po_header a inner join po_item s on a.id=s.id_po 
						where s.id_jo='$id_jo' and cancel='N' and jenis='M' 
						group by s.id_gen ) tmppo on tmppo.id_gen=mi.id_gen 	
					where bom.id_jo='$id_jo' and bom.cancel='N' 
					and nama_group='$nm_group' and bom.status='M'  
					group by bom.id_item ";
	  		#echo $sql;
				$i=1;
				$query=mysql_query($sql);
				$i=1;
				while($data=mysql_fetch_array($query))
				{	echo "
						<tr>
							<td>$i</td>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td>$data[supplier]</td>";
							if($pro=="2")
							{ echo "
								<td>$data[vponya]</td>
								<td>".fd_view($data['maxpo'])."</td>
								<td>$data[qtypo]</td>
								<td>$data[unitpo]</td>
								<td>$data[userpo]</td>"; 
							}
						echo "
						</tr>";
					$i++;
				};
		echo "</tbody></table>";
	}
}
if ($modenya=="view_list_temp_loc")
{	$crinya = $_REQUEST['cri_item'];
	echo "<table id='examplefix' width='100%' style='font-size:12px;'>";
		echo "
			<thead>
				<tr>
					<th>BPB #</th>
					<th>Item</th>
					<th>Deskripsi</th>
					<th>#</th>
					<th>Lot #</th>
					<th>Qty</th>
					<th>Qty FOC</th>
					<th>Unit</th>
					<th>Barcode</th>
					<th>Temp Rak #</th>
					<th>Rak #</th>
				</tr>
			</thead>
			<tbody>";
			$sql="select brh.bpbno,br.id,br.id_h,roll_no,lot_no,roll_qty,roll_foc,br.unit,
				concat(kode_rak,' ',nama_rak) raknya,br.barcode,mi.goods_code,mi.itemdesc from bpb_roll br inner join 
				bpb_roll_h brh on br.id_h=brh.id 
				inner join master_rak mr on br.id_rak=mr.id 
				inner join masteritem mi on brh.id_item=mi.id_item where 
				brh.id_jo='$crinya' and br.id_rak_loc is null ";
			#echo $sql;
			$i=1;
			$query=mysql_query($sql);
			while($data=mysql_fetch_array($query))
			{	$x = $data['id']."|".$data['id_h'];
				$bpbno_int=flookup("bpbno_int","bpb","bpbno='$data[bpbno]'");
				if ($bpbno_int=="") { $bpbno_int=$data['bpbno']; }
				echo "
					<tr>
						<td>$bpbno_int</td>
						<td>$data[goods_code]</td>
						<td>$data[itemdesc]</td>
						<td>$data[roll_no]</td>
						<td>$data[lot_no]</td>
						<td>$data[roll_qty]</td>
						<td>$data[roll_foc]</td>
						<td>$data[unit]</td>
						<td>$data[barcode]</td>
						<td>$data[raknya]</td>
						<td>
							<select class='form-control select2 rakclass' name ='rak_rollk[$x]' 
								id='rakajax'>";
							$sql="select id isi,concat(kode_rak,' ',nama_rak) tampil from master_rak 
								order by kode_rak";
							IsiCombo($sql,'','Pilih Rak');
							echo "
							</select>
						</td>

					</tr>";
				$i++;
			};
	echo "</tbody></table>";
}

if ($modenya=="view_list_rak_loc")
{	$id_item = $_REQUEST['id_item'];
	$id_jo = $_REQUEST['id_jo'];
	#echo $id_item."-".$id_jo;
	echo "<table id='examplefix' width='100%' style='font-size:13px;'>";
		echo "
			<thead>
				<tr>
					<th>..</th>
					<th>#</th>
					<th>Lot #</th>
					<th>Qty</th>
					<th>Qty FOC</th>
					<th>Unit</th>
					<th>Barcode</th>
					<th>Rak #</th>
				</tr>
			</thead>
			<tbody>";
			$sql="select br.id,br.id_h,brh.id_item,brh.id_jo,roll_no,lot_no,roll_qty,roll_foc,br.unit,
				concat(kode_rak,' ',nama_rak) raknya,br.barcode from bpb_roll br inner join 
				bpb_roll_h brh on br.id_h=brh.id 
				inner join master_rak mr on br.id_rak_loc=mr.id where 
				brh.id_jo='$id_jo' and brh.id_item='$id_item' and br.id_rak_loc!='' 
				and (roll_qty+roll_foc)-ifnull(roll_qty_used,0) > 0 
				order by br.id";
			#echo $sql;
			$i=1;
			$query=mysql_query($sql);
			while($data=mysql_fetch_array($query))
			{	$x = $data['id_item']."|".$data['id_jo']."|".$data['id']."|".$data['id_h'];
				$tot_isi = $data['roll_qty'] + $data['roll_foc'];
				echo "
					<tr>
						<td>
							<input type='checkbox' name='chkid[$x]' id='chkajax' class='chkclass'>
							<input type='hidden' name='txtbts[$x]' id='txtbtsajax' class='txtbtsclass' 
								value='$tot_isi'>
							<input type='hidden' name='txtcri[$x]' id='txtcriajax' class='txtcriclass' 
								value='$x'>
						</td>
						<td>$data[roll_no]</td>
						<td>$data[lot_no]</td>
						<td>$data[roll_qty]</td>
						<td>$data[roll_foc]</td>
						<td>$data[unit]</td>
						<td>$data[barcode]</td>
						<td>$data[raknya]</td>
					</tr>";
				$i++;
			};
	echo "</tbody></table>";
}

if ($modenya=="view_list_rak_loc_trx")
{	$id_h = $_REQUEST['id_h'];
	echo "<table id='examplefix' width='100%' style='font-size:13px;'>";
		echo "
			<thead>
				<tr>
					<th>#</th>
					<th>Lot #</th>
					<th>Qty</th>
					<th>Qty FOC</th>
					<th>Qty Used</th>
					<th>Unit</th>
					<th>Barcode</th>
					<th>Rak # Temp</th>
					<th>Rak # Loc</th>
				</tr>
			</thead>
			<tbody>";
			$sql="select br.id,br.id_h,brh.id_item,brh.id_jo,roll_no,lot_no,roll_qty,roll_foc,br.unit,
				concat(mr.kode_rak,' ',mr.nama_rak) raknya,concat(mrold.kode_rak,' ',mrold.nama_rak) raknyaold,
				br.barcode,roll_qty_used from bpb_roll br inner join 
				bpb_roll_h brh on br.id_h=brh.id 
				left join master_rak mr on br.id_rak_loc=mr.id 
				left join master_rak mrold on br.id_rak=mrold.id
				where 
				brh.id='$id_h' 
				order by br.id";
			#echo $sql;
			$i=1;
			$query=mysql_query($sql);
			$total = 0;
			while($data=mysql_fetch_array($query))
			{	$total = $total	+ $data['roll_qty'] + $data['roll_foc'];
				echo "
					<tr>
						<td>$data[roll_no]</td>
						<td>$data[lot_no]</td>
						<td>$data[roll_qty]</td>
						<td>$data[roll_foc]</td>
						<td>$data[roll_qty_used]</td>
						<td>$data[unit]</td>
						<td>$data[barcode]</td>
						<td>$data[raknyaold]</td>
						<td>$data[raknya]</td>
					</tr>";
				$i++;
			};
	echo "</tbody></table>";
	echo "<h1>Total : ".$total;
}

if ($modenya=="view_list_pack")
{	$id = $_REQUEST['id'];
	#echo $id_item."-".$id_jo;
	echo "<table id='examplefix' width='100%' style='font-size:13px;'>";
		echo "
			<thead>
				<tr>
					<th>..</th>
					<th>Packing Process</th>
				</tr>
			</thead>
			<tbody>";
			$sql="select * from master_pack order by nama_pack";
			#echo $sql;
			$i=1;
			$query=mysql_query($sql);
			while($data=mysql_fetch_array($query))
			{	$x = $data['id']."|".$id;
				echo "
					<tr>
						<td>
							<input type='checkbox' name='chkid[$x]' id='chkajax' class='chkpackclass'>
							<input type='hidden' name='txtcri[$x]' id='txtcriajax' class='txtcriclass' 
								value='$x'>
						</td>
						<td>$data[nama_pack]</td>
					</tr>";
				$i++;
			};
	echo "</tbody></table>";
}

if ($modenya=="view_list_ratio")
{	$id = $_REQUEST['id'];
	#echo $id_item."-".$id_jo;
	echo "<table id='examplefix' width='100%' style='font-size:13px;'>";
		echo "
			<thead>
				<tr>
					<th>Size</th>
					<th>Ratio</th>
				</tr>
			</thead>
			<tbody>";
			$sql="select a.size from so_det a left join mastersize s on a.size=s.size where id_so='$id' group by a.size order by urut";
			#echo $sql;
			$i=1;
			$query=mysql_query($sql);
			while($data=mysql_fetch_array($query))
			{	$x = $id;
				echo "
					<tr>
						<td>$data[size]</td>
						<td>
							<input type='hidden' size='4' name='txtsize[$x]' id='txtsize' class='txtsizeclass' value='$data[size]'>
							<input type='text' size='4' name='txtrat[$x]' id='txtrat' class='txtratclass'>
							<input type='hidden' size='4' name='txtratid[$x]' id='txtratid' class='txtratidclass' value='$x'>
						</td>
					</tr>";
				$i++;
			};
	echo "</tbody></table>";
}

if ($modenya=="view_list_pack_pro")
{	$id = $_REQUEST['id'];
	#echo $id_item."-".$id_jo;
	echo "<table id='examplefix' width='100%' style='font-size:13px;'>";
		echo "
			<thead>
				<tr>
					<th>Packing Process</th>
				</tr>
			</thead>
			<tbody>";
			$sql="select mp.* from master_pack mp inner join so_pack sp on mp.id=sp.id_pack 
				where id_so='$id' order by nama_pack";
			#echo $sql;
			$i=1;
			$query=mysql_query($sql);
			while($data=mysql_fetch_array($query))
			{	$x = $data['id']."|".$id;
				echo "
					<tr>
						<td>$data[nama_pack]</td>
					</tr>";
				$i++;
			};
	echo "</tbody></table>";
}

if ($modenya=="view_list_loc")
{	$crinya = $_REQUEST['cri_item'];
	$cek_arr=explode("|",$crinya);
	$bpbno=$cek_arr[0];
	$id_item=$cek_arr[1];
	echo "<table id='examplefix' width='10%'>";
		echo "
			<thead>
				<tr>
					<th>..</th>
					<th>Roll #</th>
					<th>Roll Qty</th>
					<th>Konv Qty</th>
				</tr>
			</thead>
			<tbody>";
			$sql="select br.id,roll_no,roll_qty from bpb_roll br inner join 
				bpb_roll_h brh on br.id_h=brh.id where 
				brh.id_item='$id_item'";
			#echo $sql;
			$i=1;
			$query=mysql_query($sql);
			while($data=mysql_fetch_array($query))
			{	$id=$data['id'];
				echo "
					<tr>
						<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$id' class='bchkclass'></td>
						<td>$data[roll_no]</td>
						<td><input type ='text' size='5' name ='txtqty[$id]' id='txtqtyajax$id' class='qtyclass' readonly value='$data[roll_qty]'></td>
						<td>1</td>
					</tr>";
				$i++;
			};
	echo "</tbody></table>";
}

if ($modenya=="view_list_bom")
{	$crinya = json_encode($_REQUEST['id_jo']);
	$crinya = str_replace("[","",$crinya);
  	$crinya = str_replace("]","",$crinya);
  	if ($crinya!="")
	{	$ponya=$crinya;
		$id_jo=$crinya;
		echo "<table id='examplefix2' style='width: 100%;'>";
			echo "
				<thead>
					<tr>
						<th>JO #</th>
						<th>Product</th>
						<th>Kode Bahan Baku</th>
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
				if($jenis_company=="VENDOR LG")
				{	$sql_join="mi.id_item";	}
				else
				{	$sql_join="mi.id_gen";	}
				$sql="select jo_no,mi.id_item,l.color,l.size,mi.goods_code,mi.itemdesc,
		      l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
		      k.unit,mp.product_group,k.id_jo from bom_jo_item k inner join so_det l on k.id_so_det=l.id 
		      inner join jo on k.id_jo=jo.id  
		      inner join masteritem mi on k.id_item=$sql_join 
		      inner join so on so.id=l.id_so  
		      inner join act_costing ac on so.id_cost=ac.id 
		      inner join masterproduct mp on ac.id_product=mp.id  
		      left join (select id_po,id_gen,id_jo from po_item where id_jo in ($id_jo) 
		      	group by id_gen,id_jo) tpo on k.id_jo=tpo.id_jo and k.id_item=tpo.id_gen  
		      where k.id_jo in ($id_jo) and k.cancel='N' and k.status='M' 
		      and tpo.id_po is null group by k.id_item,k.id_jo";
				#echo $sql;
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id=$data['id_item'].":".$data['id_jo'];
					$bpb=flookup("sum(qty)","bpb","id_jo='$data[id_jo]' and id_item='$data[id_item]'");
					$sisa=$data['qty_bom'] - $bpb;
					echo "
						<tr>
							<td>$data[jo_no]</td>
							<td>$data[product_group]</td>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td><input type ='text' style='width:100px;' name ='qtybpb[$id]' value='$data[qty_bom]' id='qtybpb$i' class='form-control qtybpbclass' readonly></td>
							<td><input type ='text' style='width:50px;' name ='unitsisa[$id]' value='$data[unit]' id='unitsisa$i' readonly class='form-control'></td>
							<td><input type ='text' style='width:100px;' name ='qtybal[$id]' value='$sisa' id='qtybal$i' class='form-control qtybalclass' readonly></td>
							<td>
								<input type ='text' style='width:100px;' name ='qtysc[$id]' id='qtysc$i' class='form-control qtysc' onchange='Calc_Qty()'>
								<input type ='hidden' name ='jono[$id]' id='jono$i' class='jono' value='$data[id_jo]'>
							</td>
							<td>
								<select style='width:70px; height: 26px;' name ='curr[$id]' 
									class='select2 curr' id='curr$i'>";
		            $sql="select nama_pilihan isi,nama_pilihan tampil
		              from masterpilihan where kode_pilihan='Curr' ";
		            IsiCombo($sql,'','Pilih Curr');
		            echo "</select>
							</td>
							<td><input type ='text' style='width:70px;' name ='price[$id]' id='price$i' class='form-control price'></td>
						</tr>";
					$i++;
				};
		echo "</tbody></table>";
	}
}
?>
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

if ($modenya=="view_list_supp")
{ 
  $id_tipe = $_REQUEST['id_tipe'];

  $sql = "select a.id_supplier isi, supplier tampil from mastersupplier a where tipe_sup = 'S' order by supplier asc";
  IsiCombo($sql,'','Pilih Supplier');
}

if ($modenya=="view_list_po")
{ 
  $id_supp = $_REQUEST['id_supp'];

  $sql = "select ph.id isi, concat(pono,' || ',if(count(distinct pi.id_jo)>1,' (Combine)',ac.kpno)) tampil
  from po_header ph
  inner join po_item pi on ph.id = pi.id_po
  inner join jo_det jd on pi.id_jo = jd.id_jo
  inner join so on jd.id_so = so.id
  inner join act_costing ac on so.id_cost = ac.id
  where app = 'A' and podate >= '2022-10-01' and jenis = 'M' and id_supplier = '$id_supp' group by ph.id";
  IsiCombo($sql,'','Pilih Nomor PO');
}

if ($modenya=="view_list_data_po")
{	$cripo 	= $_REQUEST['id_po'];
	$critipe 	= $_REQUEST['id_tipe'];
	$crisupp 	= $_REQUEST['id_supp'];
	if ($cripo!="")
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
						<th>Qty PO</th>
						<th>Qty Terima</th>
						<th>Satuan PO</th>
						<th>Curr</th>
						<th>Balance</th>
						<th>Qty Terima</th>";
					echo "
					</tr>
				</thead>
				<tbody>";
					$sql="select ac.kpno, pi.id_jo,pi.id,pi.price,ph.pono,ph.id_supplier, product_group, mi.id_item, mi.goods_code, mi.itemdesc, round(coalesce(pi.qty,0),2) qty_po,round(coalesce(bpb.qty_total,0),2) qty_terima, pi.unit, pi.curr, round(coalesce(pi.qty,0),2) - round(coalesce(bpb.qty_total,0),2) balance 
					from po_header ph 
					inner join po_item pi on ph.id = pi.id_po
					inner join jo_det jd on pi.id_jo = jd.id_jo
					inner join so on jd.id_so = so.id
					inner join act_costing ac on so.id_cost = ac.id
					inner join masterproduct mp on ac.id_product = mp.id
					inner join masteritem mi on pi.id_gen = mi.id_gen
					left join (select id_po_item,sum(qty) qty_total from bpb group by id_po_item) bpb on pi.id = bpb.id_po_item
					where ph.id = '$cripo' and pi.cancel = 'N' and mi.mattype = '$critipe'
					order by ac.kpno asc, id_item asc  
					 ";
				
				#echo $sql;
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	
					$balance = $data['balance'];
					if ($balance <= '0')
					{
						$status = 'disabled';
					}
					else
					{
						$status = '';
					}
					echo "
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
							<td>$data[qty_po]</td>
							<td>$data[qty_terima]</td>
							<td>$data[unit]</td>
							<td>$data[curr]
							<input type = 'hidden' name='curr[$i]' id='curr$i' value ='$data[curr]'>
							</td>
							<td>$balance</td>
						<td>
							<input type ='number' $status step='0.01' size='4' name ='qtybpb[$i]' min = '0' id='qtybpb$i' class='form-control qtybpbclass' onFocus='startCalcBpb();' onBlur='stopCalcBpb();'>
							<input type ='hidden' name ='unitbpb[$i]' id='unitbpb$i' value='$data[unit]' class='form-control unitbpbclass'>
							<input type ='hidden' name ='id_jo[$i]' id='id_jo$i' value='$data[id_jo]' class='form-control idjoclass'>
							<input type ='hidden' name ='id_po_item[$i]' id='id_po_item$i' value='$data[id]' class='form-control id_po_item'>
							<input type ='hidden' name ='pono[$i]' id='pono$i' value='$data[pono]' class='form-control pono'>
							<input type ='hidden' name ='price[$i]' id='price$i' value='$data[price]' class='form-control price'>
							</td>
							";
						echo "
						</tr>";
					$i++;
				};
		echo "</tbody>
		<tfoot>
		<tr>
		<td colspan = '11' >Total : </td>
		<td> <input type = 'text' size='14' id = 'total_qty_chk'> </td>
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

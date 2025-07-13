<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$nm_company = $rscomp["company"];
$st_company = $rscomp["status_company"];
$status_company = $rscomp["jenis_company"];
$jenis_company = $rscomp["jenis_company"];
$logo_company = $rscomp["logo_company"];
$modenya = $_GET['modeajax'];
#echo $modenya;

if ($modenya == "get_list_barcode") {
	$cboreq = $_REQUEST['cboreq'];
	$sql = "select b.id_barcode isi, b.id_barcode tampil from 
	(select * from req_material where no_req = '$cboreq') a
	inner join 
	(
	select a.*, b.kode_barang, b.nama_barang,b.job_order, b.unit from in_material_det a 
	inner join in_material b on a.id_in_material = b.id
	where a.cancel = 'N' and b.cancel = 'N'
	) b on a.kode_barang = b.kode_barang and a.job_order = b.job_order and a.unit = b.unit";
	IsiCombo($sql, '', '');
}



if ($modenya == "cari_data_req") {
	$crinya = $_REQUEST['cboreq'];
	$sql = "select supplier,job_order from req_material where no_req = '$crinya' and cancel = 'N' limit 1 ";
	$rsreq = mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rsreq['supplier'], $rsreq['job_order']));
}

if ($modenya == "view_list_req") {
	$cboreq = $_REQUEST['cboreq'];
	$cribarcode = json_encode($_REQUEST['id_barcode']);
	if ($cboreq != "" && $cribarcode == "") {
		echo "<table id='examplefix1' style='width: 100%;'>";

		echo " 
					<thead>
							<tr>
							<th colspan = '3'>Daftar Req</th>
							<th colspan = '7' ></th>
							</tr>
							<tr>";
		echo "
								<th>No Req</th>
								<th>Kode Barang</th>
								<th>Nama Barang</th>
								<th>Job Order #</th>
								<th>Qty Req</th>
								<th>Sisa Req</th>
								<th>Qty Input</th>
								<th>Unit</th>";
		echo "
						</tr>
					</thead>
					<tbody>";
		$sql = "select a.*,
		round(coalesce(a.qty,0),2)qtyreq,
		round(coalesce(c.qtytot,0),2)qtytot,
		round(coalesce(a.qty,0) - coalesce(c.qtytot,0),2) qtysisa
		from				 
		(select * from req_material where no_req = '$cboreq') a
		left join (select no_req,kode_barang,nama_barang,job_order, sum(qty)qtytot, unit from out_material a group by no_req,kode_barang,nama_barang, job_order, unit ) c 
				   on a.no_req = c.no_req and a.kode_barang = c.kode_barang and a.nama_barang = c.nama_barang and a.job_order	= c.job_order and a.unit = c.unit		";
		#echo $sql;
		$i = 1;
		$query = mysql_query($sql);
		if (!$query) {
			die($sql . mysql_error());
		}
		while ($data = mysql_fetch_array($query)) {
			$id = $data[id];
			echo "
							<tr>";
			echo "
								</td>";
			echo "
									  <td>$data[no_req]</td>								  
									  <td>$data[kode_barang]</td>
									  <td>$data[nama_barang]</td>
									  <td>$data[job_order]</td>
									  <td>$data[qtyreq]</td>
									  <td>$data[qtysisa]</td>
									  <td>0</td>
									  <td>$data[unit]</td>";
			echo "
								</td>
							</tr>
							</tbody>";
			$i++;
		};
	} else if ($cboreq != "" && $cribarcode != "") {
		echo "<table id='examplefix1' style='width: 100%;'>";

		echo " 
					<thead>
							<tr>
							<th colspan = '3'>Daftar Req</th>
							<th colspan = '7' ></th>
							</tr>
							<tr>";
		echo "
								<th>No Req</th>
								<th>Kode Barang</th>
								<th>Nama Barang</th>
								<th>Job Order #</th>
								<th>Qty Req</th>
								<th>Sisa Req</th>
								<th>Qty Input</th>
								<th>Unit</th>
								<th>Status</th>";
		echo "
						</tr>
					</thead>
					<tbody>";
		$cribarcode = str_replace("[", "", $cribarcode);
		$cribarcode = str_replace("]", "", $cribarcode);
		$sql = "select a.*,
		round(coalesce(a.qty,0),2)qtyreq,
		round(coalesce(c.qtytot,0),2)qtytot,
		round(coalesce(a.qty,0) - coalesce(c.qtytot,0),2) qtysisa,
		round(coalesce(d.total,0),2) total_input,
		round(coalesce(100/((coalesce(a.qty,0) - coalesce(c.qtytot,0)) / coalesce(d.total)),0),1) percent
		from				 
		(select * from req_material where no_req = '$cboreq') a
		left join (select no_req,kode_barang,nama_barang,job_order, sum(qty)qtytot, unit from out_material a group by no_req,kode_barang,nama_barang, job_order, unit ) c 
		on a.no_req = c.no_req and a.kode_barang = c.kode_barang and a.nama_barang = c.nama_barang and a.job_order	= c.job_order and a.unit = c.unit
		left join (select b.kode_barang,b.nama_barang,b.job_order,b.unit, sum(roll_qty)total from in_material_det a 
		inner join in_material b on a.id_in_material = b.id
		where a.cancel = 'N' and b.cancel = 'N' and id_barcode in ($cribarcode)) d
		on a.kode_barang = d.kode_barang and a.nama_barang = d.nama_barang and a.job_order = d.job_order and a.unit = d.unit
		";
		#=echo $sql;
		$i = 1;
		$query = mysql_query($sql);
		if (!$query) {
			die($sql . mysql_error());
		}
		while ($data = mysql_fetch_array($query)) {
			if ($data['percent'] == "0") {
				$col = "gray'";
				$width = "25";
				$stat = "hidden";
			} else if ($data['percent'] > "0" && $data['percent'] <= "50") {
				$col = "goldenrod";
				$width = "50";
				$stat = "true";
			} else if ($data['percent'] > "50" && $data['percent'] <= "100") {
				$col = "CornflowerBlue'";
				$width = "75";
				$stat = "true";
			} else if ($data['percent'] > "100") {
				$col = "red'";
				$width = "100";
				$stat = "true";
			}

			$id = $data[id];
			echo "
							<tr>";
			echo "
								</td>";
			echo "
									  <td>$data[no_req]</td>								  
									  <td>$data[kode_barang]</td>
									  <td>$data[nama_barang]</td>
									  <td>$data[job_order]</td>
									  <td>$data[qtyreq]</td>
									  <td>$data[qtysisa]</td>
									  <td>$data[total_input]</td>
									  <td>$data[unit]</td>
									  <td><div class='progress-bar' role='progressbar'  style='width: 50%;background-color:$col	;color: black;' aria-valuenow='50'
									  aria-valuemin='0' aria-valuemax='100'>$data[percent]%</div>
									  </td>";
			echo "
								</td>
							</tr>
							</tbody>";
			$i++;
		};
	}
}





if ($modenya == "view_list_barcode") {
	$cribarcode = json_encode($_REQUEST['id_barcode']);

	if ($cribarcode != "") {
		echo "<table id='examplefix2' style='width: 100%;'>";

		echo " 
					<thead>
							<tr>
							<th colspan = '3'>Daftar Barcode</th>
							<th colspan = '8' ></th>
							</tr>
							<tr>";
		echo "
								<th>Kode Barcode</th>
								<th>No Roll</th>
								<th>Lot</th>
								<th>Job Order #</th>
								<th>Kode Barang</th>
								<th>Nama Barang</th>
								<th>Qty Input</th>
								<th>Unit</th>
								<th>Nama Rak</th>";
		echo "
						</tr>
					</thead>
					<tbody>";

		$cribarcode = str_replace("[", "", $cribarcode);
		$cribarcode = str_replace("]", "", $cribarcode);
		$sql = "	select a.*, b.kode_barang, b.nama_barang,b.job_order, b.unit from in_material_det a 
		inner join in_material b on a.id_in_material = b.id
		where a.cancel = 'N' and b.cancel = 'N' and id_barcode in ($cribarcode)
		order by id_barcode";
		#echo $sql;
		$i = 1;
		$query = mysql_query($sql);
		if (!$query) {
			die($sql . mysql_error());
		}
		while ($data = mysql_fetch_array($query)) {
			$id = $data[id];
			echo "
							<tr>";
			echo "
								</td>";
			echo "
									  <td>$data[id_barcode]</td>								  
									  <td>$data[roll_no]</td>
									  <td>$data[lot_no]</td>
									  <td>$data[job_order]</td>
									  <td>$data[kode_barang]</td>
									  <td>$data[nama_barang]</td>
									  <td>
									  $data[roll_qty]
									  <input type ='hidden'  value = '$data[roll_qty]' name='qtyout[$id]'>
									  <input type ='hidden' name ='id_barcode[$id]' value='$data[id_barcode]'>
									  <input type ='hidden' name ='kode_barang[$id]' value='$data[kode_barang]'>
									  <input type ='hidden' name ='nama_barang[$id]' value='$data[nama_barang]'>
									  <input type ='hidden' name ='job_order[$id]' value='$data[job_order]'>
									  <input type ='hidden' name ='unit[$id]' value='$data[unit]'>
									  </td>						  
									  <td>$data[unit]</td>
									  <td>$data[kode_rak]</td>";
			echo "
								</td>
							</tr>
							</tbody>";
			$i++;
			// echo "						</table>";
		};
		$sql_tot = mysql_query("select sum(roll_qty)total from in_material_det a 
		inner join in_material b on a.id_in_material = b.id
		where a.cancel = 'N' and b.cancel = 'N' and id_barcode in ($cribarcode)");
		$datatot      = mysql_fetch_array($sql_tot);
		echo "
		<tfoot>
			<tr>
				<th colspan='6' style='text-align:right'>Total:</th>
				<th>$datatot[total]</th>
				<th></th>
				<th></th>
			</tr>
		</tfoot>";
	}
}

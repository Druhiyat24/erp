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


if ($modenya == "view_list_data") {
	$crijo	 	= $_REQUEST['txt_jo'];
	if ($crijo != "") {
		echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:14px;'>";
		echo "
				<thead>
					<tr>";
		echo "
						<th>No #</th>
						<th>Stok Sudah di Lokasi</th>
						<th>Job Order</th>
						<th>Kode Barang</th>
						<th>Nama Barang</th>
						<th>Qty Sisa</th>
						<th>Unit</th>
						<th>Qty Req</th>";
		echo "
					</tr>
				</thead>
				<tbody>";
		$sql = "
		select  
		tbl_in.kode_barang, 
		tbl_in.nama_barang, 
		tbl_in.job_order, 
		tbl_in.unit,
		round(coalesce(tbl_in.qty,0),2) qty,
		round(coalesce(tbl_det.roll_qty,0),2) roll_qty,
		round(coalesce(100/(tbl_in.qty / tbl_det.roll_qty),0),1) percent
		from 
		(select kode_barang, nama_barang,job_order,sum(qty) qty, unit from in_material a 
		where job_order = '$crijo'
		group by kode_barang, nama_barang, unit) tbl_in
		left join 
		(select job_order,kode_barang, nama_barang, sum(roll_qty) roll_qty, unit from in_material_det a
		inner join in_material b on a.id_in_material = b.id
		where b.job_order = '$crijo'
		group by kode_barang, nama_barang, unit
		) tbl_det 
		on tbl_in.kode_barang = tbl_det.kode_barang and 
		tbl_in.nama_barang = tbl_det.nama_barang and
		tbl_in.job_order = tbl_det.job_order and
		tbl_in.unit = tbl_det.unit";
		#echo $sql;
		$i = 1;
		$query = mysql_query($sql);
		while ($data = mysql_fetch_array($query)) {
			$balance = $data['roll_qty'];
			if ($balance <= '0') {
				$status = 'disabled';
			} else {
				$status = '';
			}
			if ($data['percent'] == "0") {
				$col = "brown'";
				$width = "20";
			} else if ($data['percent'] > "0" && $data['percent'] <= "50") {
				$col = "goldenrod";
				$width = "50";
			} else if ($data['percent'] > "50" && $data['percent'] <= "99") {
				$col = "CornflowerBlue'";
				$width = "75";
			} else if ($data['percent'] = "100") {
				$col = "mediumseagreen'";
				$width = "100";
			}





			echo "
						<tr>";
			echo "
							<td>$i</td>
							<td><div class='progress-bar' role='progressbar'  style='width: $width%;background-color:$col	;color: black;' aria-valuenow='50'
							aria-valuemin='0' aria-valuemax='100'>$data[percent]%</div></td>
							<td>$data[job_order]
							<input type ='hidden' name='job_order[$i]' id='job_order$i' value ='$data[job_order]'>
							</td>
							<td>$data[kode_barang]
							<input type ='hidden' name='kode_barang[$i]' id='kode_barang$i' value ='$data[kode_barang]'>
							</td>
							<td>$data[nama_barang]
							<input type ='hidden' name='nama_barang[$i]' id='nama_barang$i' value ='$data[nama_barang]'>							
							</td>
							<td>$data[roll_qty]
							<input type = 'hidden' name='roll_qty[$i]' id='roll_qty$i' value ='$data[roll_qty]'>
							</td>
							<td>$data[unit]
							<input type = 'hidden' name='unit[$i]' id='unit$i' value ='$data[unit]'>
							</td>
							<td><input type ='number' step='0.01'  name ='qtyreq[$i]' min = '0' id='qtyreq$i' max = '$data[roll_qty]'class='form-control qtybpbclass' onFocus='startCalcBpb();' onBlur='stopCalcBpb();' $status>
							</td>
							";
			echo "
						</tr>";
			$i++;
		};
		echo "</tbody>
		<tfoot>
		<tr>
		<td colspan = '7' >Total : </td>
		<td> <input type = 'text' id = 'total_qty_chk'> </td>
		</tr>
		</tfoot>			
		</table>";
	}
}

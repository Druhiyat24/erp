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
	$cbows 	= $_REQUEST['cbows'];
	if ($cbows != "") {
		echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:14px;'>";
		echo "
				<thead>
					<tr>";
		echo "
						<th>No #</th>
						<th>WS #</th>
						<th>Buyer</th>
						<th>Style</th>
						<th>Color</th>
						<th>Size</th>
						<th>Reff No</th>
						<th>Qty Order</th>
						<th>Unit</th>";
		echo "
					</tr>
				</thead>
				<tbody>";
		$sql = "select ac.kpno, supplier buyer, ac.styleno, sd.styleno_prod reff_no, sd.color, sd.size, sum(sd.qty) qty_order 
			from so_det sd
			inner join so on sd.id_so = so.id
			inner join act_costing ac on so.id_cost = ac.id
			inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
			inner join master_size_new msn on sd.size = msn.size
			where ac.id = '$cbows' and sd.cancel = 'N'
			group by color, size
			order by color asc, msn.urutan asc ";
	}
	#echo $sql;
	$i = 1;
	$query = mysql_query($sql);
	while ($data = mysql_fetch_array($query)) {
		echo "<tr>";
		echo "
				<td>$i</td>
				<td>$data[kpno]</td>
				<td>$data[buyer]</td>
				<td>$data[styleno]</td>
				<td>$data[color]</td>
				<td>$data[size]</td>
				<td>$data[reff_no]</td>
				<td>$data[qty_order]</td>
				<td>PCS</td>";
		echo "</tr>";
		$i++;
	};
	echo "</tbody>";
	echo "<tfoot>
		<tr>
		<td colspan = '7' >Total : </td>
		<td> <input type = 'text' id = 'total'> </td>
		<td>PCS</td>
		</tr>
		</tfoot>";
	echo "</table>";
}

if ($modenya == "view_list_data_with_color") {
	$cbows 	= $_REQUEST['cbows'];
	$cbocolor 	= $_REQUEST['cbocolor'];
	if ($cbows != "") {
		echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:14px;'>";
		echo "
				<thead>
					<tr>";
		echo "
						<th>No #</th>
						<th>WS #</th>
						<th>Buyer</th>
						<th>Style</th>
						<th>Color</th>
						<th>Size</th>
						<th>Reff No</th>
						<th>Qty Order</th>
						<th>Unit</th>";
		echo "
					</tr>
				</thead>
				<tbody>";
		$sql = "select ac.kpno, supplier buyer, ac.styleno, sd.styleno_prod reff_no, sd.color, sd.size, sum(sd.qty) qty_order 
			from so_det sd
			inner join so on sd.id_so = so.id
			inner join act_costing ac on so.id_cost = ac.id
			inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
			inner join master_size_new msn on sd.size = msn.size
			where ac.id = '$cbows' and sd.color = '$cbocolor' and sd.cancel = 'N'
			group by color, size
			order by color asc, msn.urutan asc ";
	}
	#echo $sql;
	$i = 1;
	$query = mysql_query($sql);
	while ($data = mysql_fetch_array($query)) {
		echo "<tr>";
		echo "
				<td>$i</td>
				<td>$data[kpno]</td>
				<td>$data[buyer]</td>
				<td>$data[styleno]</td>
				<td>$data[color]</td>
				<td>$data[size]</td>
				<td>$data[reff_no]</td>
				<td>$data[qty_order]</td>
				<td>PCS</td>";
		echo "</tr>";
		$i++;
	};
	echo "</tbody>";
	echo "<tfoot>
		<tr>
		<td colspan = '7' >Total : </td>
		<td> <input type = 'text' id = 'total'> </td>
		<td>PCS</td>
		</tr>
		</tfoot>";
	echo "</table>";
}




if ($modenya == "view_list_color") {
	$cbows 	= $_REQUEST['cbows'];
	if ($cbows != '') {

		$sql = "select sd.color isi, sd.color tampil from so_det sd
		inner join so on sd.id_so = so.id
		inner join act_costing ac on so.id_cost = ac.id
		where ac.id = '$cbows'
		group by sd.color
				";
		IsiCombo($sql, '', 'Pilih Color #');
	} else {
		IsiCombo($sql, '', 'Pilih Color #');
	}
}




if ($modenya == "cari_data_ws") {
	$crinya = $_REQUEST['cri_item'];
	$sql = "select ac.id, ac.kpno, mb.supplier buyer, ac.styleno 
	from act_costing ac 
	inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
	where ac.id = '$crinya'";
	$rsjo = mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rsjo['kpno'], $rsjo['buyer'], $rsjo['styleno']));
}


if ($modenya == "view_list_line") {
	$cboline = json_encode($_REQUEST['cboline']);
	if ($cboline != "") {
		echo "<table id='examplefix2' style='width: 100%;'class='table table-striped table-bordered'>";

		echo " 
				<thead>
				<tr>
				<th style='width:auto;'>Isi</th>
				<th style='width:auto;'>Tampil</th>
				</tr>
				</thead>
				<tbody>";

		$cboline = str_replace("[", "", $cboline);
		$cboline = str_replace("]", "", $cboline);
		$sql = "select username isi, fullname tampil from userpassword where groupp = 'SEWING'
		and username in ($cboline)
		order by username asc ";
		#echo $sql;
		$i = 1;
		$query = mysql_query($sql);
		if (!$query) {
			die($sql . mysql_error());
		}
		while ($data = mysql_fetch_array($query)) {
			echo "<tr>";
			echo "</td>";
			echo "
								  
		<td> <input type ='text' name ='username_arr[$i]' id='username_arr$i' value = '$data[isi]'></td>
		<td> <input type ='text' name ='fullname_arr[$i]' id='fullname_arr$i' value = '$data[tampil]'></td>		
		";
			echo "
	
		</tr>";
			$i++;
		};
	}
}

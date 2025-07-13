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

if ($modenya == "cari_data_ws") {
	$crinya = $_REQUEST['cri_item'];
	$sql = "select ac.id, ac.kpno, mb.supplier buyer, ac.styleno 
	from act_costing ac 
	inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
	where ac.id = '$crinya'";
	$rsjo = mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rsjo['kpno'], $rsjo['buyer'], $rsjo['styleno']));
}


if ($modenya == "view_list_color") {
	$cbows 	= $_REQUEST['cbows'];
	if ($cbows != '') {

		$sql = "select sd.color isi, sd.color tampil from so_det sd
		inner join so on sd.id_so = so.id
		inner join act_costing ac on so.id_cost = ac.id
		where ac.id = '$cbows' and sd.cancel = 'N'
		group by sd.color
				";
		IsiCombo($sql, '', 'Pilih Color #');
	} else {
		IsiCombo($sql, '', 'Pilih Color #');
	}
}

if ($modenya == "view_list_size") {
	$cbows 	= $_REQUEST['cbows'];
	$cbocolor 	= $_REQUEST['cbocolor'];
	if ($cbows != '' && $cbocolor != '') {

		$sql = "select sd.size isi, sd.size tampil from so_det sd
		inner join so on sd.id_so = so.id
		inner join act_costing ac on so.id_cost = ac.id
		inner join master_size_new msn on sd.size = msn.size
		where ac.id = '$cbows' and sd.color = '$cbocolor' and sd.cancel = 'N'
		group by sd.size
		order by msn.urutan asc";
		#echo $sql;
		IsiCombo($sql, '', '');
	}
}

if ($modenya == "view_list_data") {
	$cbows 	= $_REQUEST['cbows'];
	$cbocolor 	= $_REQUEST['cbocolor'];
	$cbosize = json_encode($_REQUEST['cbosize']);
}
if ($cbosize != "") {
	echo "<table id='examplefix2' style='width: 100%;'class='table table-striped table-bordered'>";

	echo " 
			<thead>
			<tr>
			<th style='width:auto;'>WS</th>
			<th style='width:auto;'>Color</th>
			<th style='width:auto;'>Size</th>
			<th style='width:70px;'>Ratio</th>
			<th style='width:70px;'>Qty Cut</th>
			<th style='width:70px;'>Range Awal</th>
			<th style='width:70px;'>Range Akhir</th>
			</tr>
			</thead>
			<tbody>";

	$cbosize = str_replace("[", "", $cbosize);
	$cbosize = str_replace("]", "", $cbosize);
	$sql = "select ac.kpno,min(sd.id) id, sd.color, sd.size, ifnull(st.range_akhir + 1,'1') data_range_akhir 
	from so_det sd
	inner join so on sd.id_so = so.id
	inner join act_costing ac on so.id_cost = ac.id
	inner join master_size_new msn on sd.size = msn.size
	left join (select max(range_akhir) range_akhir, id_so_det from stocker where cancel = 'N' group by id_so_det) st on sd.id = st.id_so_det	
	where ac.id = '$cbows' and sd.color = '$cbocolor ' and sd.cancel = 'N' and sd.size in ($cbosize)
	group by sd.size
	order by msn.urutan asc";
	#echo $sql;
	$i = 1;
	$query = mysql_query($sql);
	if (!$query) {
		die($sql . mysql_error());
	}
	while ($data = mysql_fetch_array($query)) {
		$id = $data[id];
		echo "<tr>";
		echo "</td>";
		echo "
							  
	<td>$data[kpno]</td>
	<td>$data[color]</td>
	<td>$data[size]</td>
	<td>
	<input type ='text' style='width:70px;' required min = '1' autocomplete = 'off' name ='txtratio[$id]'  oninput='sum()' id='txtratio$i' class='txtratio'>
	<input type ='hidden' style='width:70px;' name ='id_so_det[$id]' id='id_so_det$i' value = '$id'>
	<input type ='hidden' style='width:70px;' name ='color[$id]' id='color$i' value = '$data[color]'>
	<input type ='hidden' style='width:70px;' name ='size[$id]' id='color$i' value = '$data[size]'>
	</td>
	<td>
	<input type ='text' style='width:70px;' readonly name ='txtqtycut[$id]' oninput='sum()'  id='txtqtycut$i' class='txtqtycut'>
	</td>
	<td>
	<input type ='text' style='width:70px;' name ='txtrange_awal[$id]' oninput='sum()'  id='txtrange_awal$i' value = '$data[data_range_akhir]' readonly class='txtrange_awal'>
	</td>
	<td>
	<input type ='text' style='width:70px;' name ='txtrange_akhir[$id]' oninput='sum()'  id='txtxtrange_akhir$i' class='txtrange_akhir'>
	</td>
	
	";
		echo "

	</tr>";
		$i++;
	};
}

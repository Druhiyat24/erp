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

if ($modenya == "view_list_juml_roll") {
	$cborak = $_REQUEST['cborak'];

	$sql = "select a.kode_rak,(a.kapasitas - coalesce(b.kap,0)) kap  
	from m_rak a
	left join
	(SELECT count(kode_rak) kap,kode_rak FROM in_material_det where kode_rak = '$cborak' group by kode_rak) b on a.kode_rak = b.kode_rak
	where a.kode_rak = '$cborak'";
	$query = mysql_query($sql);
	$datasql      = mysql_fetch_array($query);
	$kap       = $datasql['kap'];
	for ($x = 1; $x <= $kap; $x++) {
		echo "<option value='$x'>$x</option>";
	}
}

if ($modenya == "cari_data_rak") {
	$crinya = $_REQUEST['cborak'];
	$sql = "select a.kode_rak,(a.kapasitas - coalesce(b.kap,0)) kap  
	from m_rak a
	left join
	(SELECT count(kode_rak) kap,kode_rak FROM in_material_det where kode_rak = '$crinya' group by kode_rak) b on a.kode_rak = b.kode_rak
	where a.kode_rak = '$crinya'";
	$rsrak = mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rsrak['kap']));
}

if ($modenya == "view_list_roll") {
	$crinya_roll = $_REQUEST['cri_item'];
	$cri_rak = $_REQUEST['txtrak'];
	$cri_sat = $_REQUEST['sat_nya'];

	if ($crinya_roll != "") {
		echo "<table style='width: 100%;'>";
		echo "<thead>";
		echo "<tr>";
		echo "
						<th width='5%'>No</th>
						<th width='5%'>No. Roll</th>
						<th width='10%'>Lot #</th>
						<th width='15%'>Qty</th>
						<th width='15%'>Unit</th>
						<th width='auto'>No. Rak</th>";

		echo "</tr>";
		echo "</thead>";
		for ($x = 1; $x <= 100; $x++) {
			echo "<tr>";
			show_roll_in_material($x, $x, $crinya_roll, $ket, $cri_sat, $cri_rak);
			echo "</tr>";
		}
		echo "<tfoot>
		<tr>
		<td colspan = '3' >Total : </td>
		<td> <input type = 'text' id = 'total'> </td>
		<td> </td>
		</tr>
		</tfoot>";

		echo "</table>";
	}
}

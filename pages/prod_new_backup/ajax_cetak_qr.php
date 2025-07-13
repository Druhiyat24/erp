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

if ($modenya == "view_list_ws") {
	$id_buyer = $_REQUEST['id_buyer'];
	if ($id_buyer == 'ALL') {
		$sql = "select id isi, kpno tampil from act_costing where cost_date >= '2023-01-01'";
	} else if ($id_buyer != 'ALL') {
		$sql = "select id isi, kpno tampil from act_costing where id_buyer = '$id_buyer' and cost_date >= '2023-01-01'";
	}
	IsiCombo($sql, '', 'Pilih No WS #');
}

if ($modenya == "view_list_color") {
	$id_buyer = $_REQUEST['id_buyer'];
	$id_ws = $_REQUEST['id_ws'];
	if ($id_buyer != '' && $id_ws != '') {

		$sql = "select sd.color isi, sd.color tampil from so_det sd
		inner join so on sd.id_so = so.id
		inner join act_costing ac on so.id_cost = ac.id
		where ac.id = '$id_ws' and ac.id_buyer = '$id_buyer'
		group by sd.color
				";
		IsiCombo($sql, '', 'Pilih Color #');
	} else {
		IsiCombo($sql, '', 'Pilih Color #');
	}
}

if ($modenya == "view_list_size") {
	$id_buyer = $_REQUEST['id_buyer'];
	$id_ws = $_REQUEST['id_ws'];
	$id_color = $_REQUEST['id_color'];
	if ($id_color != '') {
		$sql = "select sd.size isi, sd.size tampil from so_det sd
		inner join so on sd.id_so = so.id
		inner join act_costing ac on so.id_cost = ac.id
		left join master_size_new msn on sd.size = msn.size
		where ac.id = '$id_ws' and ac.id_buyer = '$id_buyer' and sd.color = '$id_color'
		group by sd.size
		order by msn.urutan asc
				";
		IsiCombo($sql, '', 'Pilih Size #');
	} else {
		IsiCombo('', '', 'Pilih Size #');
	}
}

if ($modenya == "view_list_data") {
	$cribuyer 	= $_REQUEST['id_buyer'];
	$criws		= $_REQUEST['id_ws'];
	$cricolor	= $_REQUEST['id_color'];
	$crisize	= $_REQUEST['id_size'];
	if ($criws != "") {
		echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:12px;'>";
		echo "
				<thead>
					<tr>";
		echo "
						<th>No #</th>
						<th>No SO</th>
						<th>No WS</th>
						<th>Style</th>
						<th>Warna</th>
						<th>Size</th>
						<th>Dest</th>
						<th>Qty SO</th>
						<th>Unit</th>
						<th>No PO</th>
						<th>Product</th>
						<th>Product Type</th>
";
		echo "
					</tr>
				</thead>
				<tbody>";

		if ($cricolor != '') {
			$sqlcolor = "and sd.color = '$cricolor'";
		} else {
			$sqlcolor = "";
		}
		if ($crisize != '') {
			$sqlsize = "and sd.size = '$crisize'";
		} else {
			$sqlsize = "";
		}
		$sql = "select sd.id id_so_det, so.so_no, ac.kpno, supplier buyer, ac.styleno, sd.color, sd.size, sd.dest, sd.qty, sd.unit, so.buyerno, product_group, product_item from act_costing ac 
		inner join so on ac.id = so.id_cost
		inner join so_det sd on so.id = sd.id_so
		inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
		inner join masterproduct mp on ac.id_product = mp.id
		where ac.id = '$criws' $sqlcolor $sqlsize
					 ";
		#echo $sql;
		$i = 1;
		$query = mysql_query($sql);
		while ($data = mysql_fetch_array($query)) {
			echo "
					<tr>
							<td>$i</td>
							<td>$data[so_no]</td>
							<td>$data[kpno]</td>
							<td>$data[styleno]</td>
							<td>$data[color]</td>
							<td>$data[size]</td>
							<td>$data[dest]</td>
							<td>$data[qty]</td>
							<td>$data[unit]</td>
							<td>$data[buyerno]</td>
							<td>$data[product_group]</td>
							<td>$data[product_item]</td>
					</tr>";
			$i++;
		};
		echo "</tbody>			
		</table>";
	}
}

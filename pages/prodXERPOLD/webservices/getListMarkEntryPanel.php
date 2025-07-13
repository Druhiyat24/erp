<?php 
$data = $_POST;
$id_cost = $_GET['id_cost'];
$color = $_GET['color'];

$getListData = new getListData();
// $id = $_POST['id'];

$List = $getListData->get($id_cost, $color);
print_r($List);

class getListData {
	public function get($id_cost, $color){
		include __DIR__ .'/../../../include/conn.php';

		$q = "SELECT 
				mp.id,
				mp.nama_panel
			FROM masterpanel AS mp
			WHERE mp.id NOT IN (
				SELECT 
					med.id_panel 
				FROM prod_mark_entry_detail AS med
				WHERE med.id_cost = '{$id_cost}' AND med.color = '{$color}')
		";
		// echo $q;
		$stmt = mysql_query($q);


		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"panel":"'.rawurlencode($row["nama_panel"]).'"}';
		}
		$result = '{ "respon": "200", "status": "ok", "message": "1", "records": ['.$outp.']    }';
		return $result;
	}
}
?>
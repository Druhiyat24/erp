<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
$id_ws = $_GET['id_cost'];
$color = $_GET['color'];
//print_r($data);

$getListData = new getListData();

$List = $getListData->get($id_ws, $color);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id_ws, $color){
		include __DIR__ .'/../../../include/conn.php';

		$q1 = "SELECT 
				ac.id AS id_cost,
				ac.kpno AS ws,
				s.id AS id_so,
				j.id AS id_jo,
				vpb.id_item_ms_item AS id_item,
				vpb.fabric_code,
				vpb.fabric_desc
				-- IF(med.item_id IS NULL OR med.item_id = '0', '0', med.item_id) AS item_id_detail,
				-- med.id_panel
			FROM act_costing AS ac
			INNER JOIN (
				SELECT 
					s.id,
					s.id_cost
				FROM so AS s
			) AS s ON s.id_cost = ac.id
			INNER JOIN (
				SELECT 
					jd.id,
					jd.id_so,
					jd.id_jo
				FROM jo_det AS jd
			) AS jd ON jd.id_so = s.id
			INNER JOIN (
				SELECT 
					j.id 
				FROM jo AS j
			) AS j ON j.id = jd.id_jo
			INNER JOIN (
				SELECT 
					vpb.id_item_ms_item,
					vpb.goods_code AS fabric_code,
					vpb.fabric_desc,
					vpb.color,
					vpb.id_jo
				FROM view_portal_bom AS vpb
			) AS vpb ON vpb.id_jo = j.id
			-- LEFT JOIN (
			-- 	SELECT 
			-- 		med.id_cost,
			-- 		med.color,
			-- 		med.id_item AS item_id,
			-- 		med.id_panel
			-- 	FROM prod_mark_entry_detail AS med WHERE med.id_cost = '{$id_ws}' AND med.color = '{$color}' GROUP BY med.id_item
			-- ) AS med ON med.item_id = vpb.id_item_ms_item
			WHERE ac.id = '{$id_ws}'
			AND vpb.color = '{$color}'
		"; 
		// echo $q1;
		$stmt = mysql_query($q1);


		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id_item":"'.rawurlencode($row['id_item']).'",';
			$outp .= '"id_jo":"'. rawurlencode($row["id_jo"]). '",';
			$outp .= '"item":"'.rawurlencode($row["fabric_desc"]). '"}';
		}
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>





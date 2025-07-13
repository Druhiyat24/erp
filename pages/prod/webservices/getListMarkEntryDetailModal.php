<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();
$id_cost = $_POST['id'];
$color = $_POST['clr'];
$group = $_POST['g'];

$List = $getListData->get($id_cost,$color,$group);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id_cost,$color,$group){
		include __DIR__ .'/../../../include/conn.php';

		$q = "SELECT 
				med.id_mark_detail,
				med.id_cost,
				med.id_group,
				med.color,
				med.size,
				med.qty,
				med.ratio
			FROM prod_mark_entry_detail AS med
			WHERE med.id_cost = '{$id_cost}' AND med.color = '{$color}'
			AND med.id_group = '{$group}'
			ORDER BY med.id_mark_detail ASC
		"; 
		//echo $q;
		$stmt = mysql_query($q);


		$id = array();

		$num = 0;
		$field = "";
		$outp = '';
		$detail = array();
		while($row = mysql_fetch_array($stmt)){
			$field .="<div class='col-md-3'>";
			$field .="<label>".$row['size']." (".$row['qty'].")</label>";
			$field .="</div>";
			$field .="<div class='col-md-9'>";
			$id = $row['id_mark_detail'];
			$field .="<input type='number' onkeyup='handleKeyUp(this);' name='size' id='size_$num' class='form-control' value=".'"'.$row['ratio'].'"'.">";
			$field .="</div>";

			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id_mark_detail']).'",';
			$outp .= '"group":"'.rawurlencode($row["id_group"]). '",';
			$outp .= '"qty":"'.rawurlencode($row["qty"]). '",';
			$outp .= '"ratio":"'.rawurlencode($row["ratio"]). '"}';

			$num++;
		}

		$field = rawurlencode($field);


		$q_group = "SELECT 
						meg.id_group,
						meg.spread,
						meg.unit_yds,
						meg.unit_inch
					FROM prod_mark_entry_group AS meg
					WHERE meg.id_group = '{$group}' AND meg.id_cost = '{$id_cost}' AND meg.color = '{$color}'
		";
		// echo $q_group;
		$stmt2 = mysql_query($q_group);


		// $id = array();
		$g = '';
		while($row2 = mysql_fetch_array($stmt2)){
			if ($g != "") {$g .= ",";}
			$g .= '{"id":"'.rawurlencode($row2['id_group']).'",';
			$g .= '"spread":"'.rawurlencode($row2["spread"]). '",';
			$g .= '"yds":"'.rawurlencode($row2["unit_yds"]). '",';
			$g .= '"inch":"'.rawurlencode($row2["unit_inch"]). '"}';
		}


		$result = '{ "status":"ok", "message":"1", "records": "'.$field.'", "detail" : ['.$outp.'], "group": ['.$g.'] }';
		return $result;
	}
}
?>
<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();

$id = explode("_",$_POST['id']);
$id_cost = $id[0];
$sew_in = $id[1];
// print_r($sew_in);die();

$List = $getListData->get($id_cost,$sew_in);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id_cost,$sew_in){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT 
				swi.id_sew_in,
				swi.id_cost,
				swi.id_line,
				li.nm_line
			FROM prod_sew_in AS swi
			INNER JOIN (
				SELECT 
					li.Id_Supplier AS id_line,
					li.Supplier AS nm_line,
					li.area
				FROM mastersupplier AS li
				WHERE li.area = 'LINE'
			) AS li ON li.id_line = swi.id_line
			WHERE swi.id_cost = '{$id_cost}' AND swi.id_sew_in = '{$sew_in}'
		"; 
		// echo $q;die();
		$stmt = mysql_query($q);		

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id_sew_in']).'",';
			$outp .= '"id_line":"'. rawurlencode($row["id_line"]). '",';
			$outp .= '"nm_line":"'. rawurlencode($row["nm_line"]). '"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>
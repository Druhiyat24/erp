<?php 




		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$NumberJournal = new NumberJournal();
$List = $NumberJournal->get();
print_r($List);
}
else{
	exit;
}
class NumberJournal {
	public function get(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT period,id_journal,num_journal FROM fin_journal_h";
				//echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		while($row = mysql_fetch_array($stmt)){
			array_push($numberjournal,$row['num_journal']);
			array_push($id,$row['id_journal']);
		}
		$records[] 				= array();
		$records['numberjournal'] = $numberjournal;
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
}




?>





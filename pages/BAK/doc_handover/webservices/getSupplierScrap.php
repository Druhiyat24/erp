<?php 
file_get_contents('php://input');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
		$data = $_GET;
		//print_r($data);
		//$data = (object)$_POST['data'];
$getListData = new getListData();
if(!ISSET($_GET['ids'])){
	$_GET['ids'] = '0';
}
//if($data['code'] == '1' ){
$List = $getListData->get($_GET['id_bkb']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	function CompileQuery($query,$mode){
		include __DIR__ .'/../../../include/conn.php';
		$stmt = mysqli_query($conn_li,$query);	
		if(mysqli_error($conn_li)){
		
			$result = mysqli_error($conn_li)."__ERRTRUE";
			return $result;
		}	
		else{
			if($mode == "CRUD"){
				print_r($query);
				$result = '{ "status":"ok", "message":"1"}';
				return $result;
			}
			else{
				
				if(mysqli_num_rows($stmt) == '0' ){
					$result = '{ "status":"ok", "message":"2"}';
					return '0';
				}
				else{
					return $stmt;
				}
			}
		} 
	}	
	public function get($id){
		$q = "SELECT BKB.id,BKB.bppbno_int,MS.id_supplier,MS.Supplier supplier  FROM bppb BKB 
		INNER JOIN mastersupplier MS ON BKB.id_supplier = MS.Id_Supplier
		
		WHERE BKB.id = '{$id}'
		";

		$MyList = $this->CompileQuery($q,'SELECT');
		if($MyList == '0'){
			//$result = '{ "status":"ok", "message":"2", "records":"0"}';
				$outp = '';
				if ($outp != "") {$outp .= ",";}
							$outp .= '{"id":" ",'; 
							$outp .= '"isi":" "}'; 
		}
		else{
			    if (!is_object($MyList)) {
					$EXP = explode("__ERRTRUE",$MyList);			
					if($EXP[0]){
						$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
					}
				}
				else{	
						$outp = '';
						while($row = mysqli_fetch_array($MyList)){
		
							if ($outp != "") {$outp .= ",";}
							$outp .= '{"id_supplier":"'.rawurlencode($row['id_supplier']).'",'; 
							$outp .= '"supplier":"'.rawurlencode($row['supplier']).'"}'; 
				
						} 		
							$result = '{"records":['.$outp.']}';	
					}		
			}
						return $result;
	}
}




?>





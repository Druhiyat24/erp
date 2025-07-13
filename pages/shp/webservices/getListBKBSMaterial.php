<?php 

/* include "../../forms/fungsi.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
		$data = $_GET;
		// print_r($data);
		//$data = (object)$_POST['data'];
$getListData = new getListData();
if(!ISSET($_GET['ids'])){
	$_GET['ids'] = '0';
}
//if($data['code'] == '1' ){
$List = $getListData->get();
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
	public function get(){
		$q = "SELECT MAX(BKB.id) id,MAX(BKB.bppbno_int) isi,BKB.id_supplier,MS.area FROM bppb BKB
			INNER JOIN mastersupplier MS ON BKB.id_supplier = MS.Id_Supplier
		WHERE MS.area !='F' AND SUBSTR(BKB.bppbno,4,1) = 'A' AND confirm ='Y'
		
GROUP BY BKB.bppbno_int
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
					if($EXP[1]){
						$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
					}
				}
				else{	
						$outp = '';
						$my_id = 1; 
						while($row = mysqli_fetch_array($MyList)){
		
							if ($outp != "") {$outp .= ",";}
							$outp .= '{"id":"'.rawurlencode($row['id']).'",'; 
							$outp .= '"isi":"'.rawurlencode($row['isi']).'"}'; 
				
						} 		
							$result = '{"records":['.$outp.']}';	
					}		
			}
						return $result;
	}
}




?>





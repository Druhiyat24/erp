<?php 
file_get_contents('php://input');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
		$data = $_POST;
		//print_r($data);
		//$data = (object)$_POST['data'];
$getListData = new getListData();
if(!ISSET($_GET['ids'])){
	$_GET['ids'] = '0';
}
//if($data['code'] == '1' ){
$List = $getListData->get($_POST['id']);
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
		$q = "SELECT A.invno
		,A.id
		,A.id_buyer
		,A.user_insert
		,A.date_invoice
		,A.d_insert
		,A.fg_ppn
		,A.user_post
		,A.d_post
		,A.user_update
		,A.d_update
		,A.id_pterms
		,A.id_coa
		,MS.Supplier buyer
		,MS.Id_Supplier id_buyer
		,MAX(INVSC_DET.id_bppb)id_bppb
 FROM shp_invoice_scrap_header A
INNER JOIN mastersupplier MS ON A.id_buyer = MS.Id_Supplier
INNER JOIN shp_invoice_scrap_detail INVSC_DET ON A.id = INVSC_DET.id_inv_sc
WHERE A.id = '{$id}'
GROUP BY A.id
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
							$outp .= '{"invno":"'.rawurlencode($row['invno']).'",'; 
							$outp .= '"id":"'.rawurlencode($row['id']).'",';
							$outp .= '"id_buyer":"'.rawurlencode($row['id_buyer']).'",';
							$outp .= '"id_bppb":"'.rawurlencode($row['id_bppb']).'",';
							$outp .= '"date_invoice":"'.rawurlencode($row['date_invoice']).'",';
							$outp .= '"fg_ppn":"'.rawurlencode($row['fg_ppn']).'",';
							$outp .= '"id_coa":"'.rawurlencode($row['id_coa']).'",';
							$outp .= '"id_terms":"'.rawurlencode($row['id_pterms']).'"}'; //,A.d_insert
						} 		
							$result = '{"records":['.$outp.']}';	
					}		
			}
						return $result;
	}
}




?>





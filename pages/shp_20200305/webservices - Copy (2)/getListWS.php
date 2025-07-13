<?php 
session_start();
include "../../forms/fungsi.php";
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
$List = $getListData->get($_GET['id_buyer'],$_GET['ids']);
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
	public function get($id_buyer,$id_inv){
		
		if($id_inv != '0'){
			$where = "IN";
			$where_inv_1 =" AND n_idinvoiceheader = '$id_inv'";
			$where_inv_2 =" AND id_inv = '$id_inv'";
			$where_existing = " OR BPPB.bppbno IN (
SELECT bpbno  FROM invoice_commercial WHERE bpbno IS NOT null  AND n_idinvoiceheader = '702'

	)";
		}else{
			$where = "NOT IN";
			$where_inv_1 ="";
			$where_inv_2 ="";
			$where_existing ="";
		}
				
		  
		$q = "select SUBSTRING(BPPB.bppbno,4,1)str 
	,SOD.destination
	,MS.Supplier
	,MD.country_name,MD.id idcustomer
	,ACT.id idcosting,ACT.kpno,ACT.styleno,ACT.id_buyer,BPPB.bppbno_int,BPPB.id_item,BPPB.bppbno  from 
    act_costing ACT LEFT JOIN(
		SELECT id,so_no,id_cost FROM so 
	)SO ON SO.id_cost = ACT.id
	LEFT JOIN (
		SELECT MAX(n_id_dest)destination,id_so,id FROM so_det GROUP BY id_so,n_id_dest
	)SOD ON SOD.id_so = SO.id
	LEFT JOIN(  
		SELECT bppbno_int,bppbno,id_item,id_jo,id_so_det FROM bppb
	)BPPB ON BPPB.id_so_det = SOD.id
	LEFT JOIN(
		SELECT id,country_name FROM master_destination
	)MD ON SOD.destination = MD.id
	LEFT JOIN(
		SELECT Id_Supplier,Supplier FROM mastersupplier
	)MS ON MS.Id_Supplier = ACT.id_buyer	
	WHERE ACT.id_buyer = '$id_buyer' AND BPPB.bppbno_int IS NOT NULL
	AND BPPB.bppbno_int $where (
SELECT bpbno bppbno_int FROM invoice_commercial WHERE bpbno IS NOT null $where_inv_1
UNION ALL
SELECT bppbno bppbno_int FROM invoice_detail WHERE bppbno IS NOT null $where_inv_2
	)
	
	
	$where_existing
	AND SUBSTRING(BPPB.bppbno,4,1) != 'C'
	
	
	GROUP BY BPPB.bppbno_int
		";
		//echo $q; 
		//echo $q; 
		$MyList = $this->CompileQuery($q,'SELECT');
		if($MyList == '0'){
			//$result = '{ "status":"ok", "message":"2", "records":"0"}';
				$outp = '';
				if ($outp != "") {$outp .= ",";}
				$outp .= '{"checkbox":" ",'; 
				$outp .= '"ws":"'.$row['kpno'].'",'; 
				$outp .= '"destination":" ",'; 
				$outp .= '"customer":" ",'; 
				$outp .= '"bppbno_int":" ",';
				$outp .= '"bppbno":" ",'; 
				$outp .= '"styleno":" "}'; 			
				$result = '{"data":['.$outp.']}';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{	
		$outp = '';
		$my_id = 1; 
 		while($row = mysqli_fetch_array($MyList)){
			$checkbox = '';//idcosting
		if($id_inv != '0'){
			$checkbox .= "<input type='checkbox' data-destination='$row[destination]' data-idcosting='$row[idcosting]' data-idcustomer='$row[idcustomer]' data-bppbno_int='$row[bppbno_int]' data-bppbno_int='$row[bppbno_int]' checked='checked' disabled onclick='GenerateDetail(this)' id='__$row[idcosting]' />";
		}else{
			$checkbox .= "<input type='checkbox' data-destination='$row[destination]' data-idcosting='$row[idcosting]' data-idcustomer='$row[idcustomer]' data-bppbno_int='$row[bppbno_int]' data-bppbno_int='$row[bppbno_int]' onclick='GenerateDetail(this)' id='__$row[idcosting]' />";
		}			
			
			

			if ($outp != "") {$outp .= ",";}
			$outp .= '{"checkbox":"'.rawurlencode($checkbox).'",'; 
			$outp .= '"ws":"'.$row['kpno'].'",'; 
			$outp .= '"destination":"'.$row['country_name'].'",'; 
			$outp .= '"customer":"'.$row['Supplier'].'",'; 
			$outp .= '"bppbno_int":"'.$row['bppbno_int'].'",';
			$outp .= '"bppbno":"'.$row['bppbno'].'",'; 
			$outp .= '"idcosting":"'.$row['idcosting'].'",'; 
			$outp .= '"styleno":"'.$row['styleno'].'"}'; 
			$my_id++;
		} 		
			$result = '{"data":['.$outp.']}';	
			}		
		}
		return $result;
	}
}




?>





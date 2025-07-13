<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['id']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "	SELECT 
		A.id
		,A.id_inv
		,A.v_noso
		,A.id_so_det
		,A.qty i_qty
		,A.unit u
		,A.lot
		,A.carton
		,C.size
		,C.qty
		,C.dest
		,C.color
		,C.unit
		,E.so_no
		,A.price 
		,D.qty BPB_qty
		,ACT.styleno
		FROM 
			invoice_detail A
		LEFT JOIN
			(SELECT id,invno FROM invoice_header) B
			ON A.id_inv = B.id 
		LEFT JOIN (SELECT size,id,dest,color,unit,qty,id_so FROM so_det) C
		ON A.id_so_det = C.id					
		LEFT JOIN bppb D
		ON A.id_so_det = D.id_so_det
		LEFT JOIN so E
		ON E.id = C.id_so			
		LEFT JOIN act_costing ACT 
		ON ACT.id = E.id_cost
		WHERE B.id = '$id' GROUP BY A.id_so_det";
				
	
				//echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"id_inv":"'. rawurlencode($row["id_inv"]). '",'; 	
			$outp .= '"v_noso":"'. rawurlencode($row["so_no"]). '",'; 
			$outp .= '"lot":"'. rawurlencode($row["lot"]). '",'; 
			$outp .= '"carton":"'. rawurlencode($row["carton"]). '",'; 
			$outp .= '"id_so_det":"'. rawurlencode($row["id_so_det"]). '",'; 	
			$outp .= '"i_qty":"'. rawurlencode($row["i_qty"]). '",'; 
			$outp .= '"qty":"'. rawurlencode($row["qty"]). '",'; 
			$outp .= '"qtybpb":"'. rawurlencode($row["BPB_qty"]). '",'; 			
			$outp .= '"size":"'. rawurlencode($row["size"]). '",'; 	
			$outp .= '"dest":"'. rawurlencode($row["dest"]). '",'; 
			$outp .= '"unit":"'. rawurlencode($row["unit"]). '",'; 	
			$outp .= '"color":"'. rawurlencode($row["color"]). '",'; 	
			$outp .= '"style":"'. rawurlencode($row["styleno"]). '",'; 	
			$outp .= '"price":"'. rawurlencode($row["price"]). '"}'; 
		}
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>





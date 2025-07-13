<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

	$getListData = new getListData();
	$data =json_decode($_GET['string']);
	//print_r($data);
$List = $getListData->get($data->id_cost,$data->color);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function pivot_size($id_cost,$color,$type_data){
		include __DIR__ .'/../../../include/conn.php';
		$query = " SELECT 
	   Y.color
	   ,X.size
	   ,ifnull(Y.qty,0)qty
	   FROM(
SELECT ACT.id,SUM(SOD.qty)qty,SOD.size,ifnull(MZ.urut,99)urut,color FROM act_costing ACT	
	LEFT JOIN so SO ON ACT.id = SO.id_cost
	LEFT JOIN so_det SOD ON SOD.id_so = SO.id
	LEFT JOIN mastersize MZ ON MZ.size = SOD.size
	WHERE ACT.id = '{$id_cost}'
	GROUP BY SOD.size ORDER BY MZ.urut)X
	LEFT JOIN(
SELECT ACT.id,SUM(SOD.qty)qty,SOD.size,ifnull(MZ.urut,99)urut,color FROM act_costing ACT	
	LEFT JOIN so SO ON ACT.id = SO.id_cost
	LEFT JOIN so_det SOD ON SOD.id_so = SO.id 
	LEFT JOIN mastersize MZ ON MZ.size = SOD.size
	WHERE ACT.id = '{$id_cost}' AND SOD.color = '{$color}'
	GROUP BY SOD.size ORDER BY MZ.urut
	)Y ON X.size = Y.size
	";
		$stmt = mysql_query($query);
		$str ="";
		$x=0;
		$size = array();
		$qty = array();
		while($row = mysql_fetch_array($stmt)){
			if($type_data == "HEADER"){
				$str .= "SUM(if(X.size ='".$row['size']."',ifnull(Y.qty,0),0)) AS size_".str_replace('"','_pd',$row['size']).",";
			}else if($type_data == "BODY"){
				$str .= "MAX(if(X.size ='".$row['size']."',ifnull(X.ratio,0),0)) AS size_".str_replace('"','_pd',$row['size']).",";
			}
			
			array_push($size,$row['size']);
			array_push($qty,$row['qty']);
		}
		$_res = array(
			"str"	=> $str,
			"size" => $size,
			"qty"	=> $qty
		);
	
		return $_res;
	}
	
	public function main_query($header,$body,$id_cost,$color){
		include __DIR__ .'/../../../include/conn.php';
	
		$_q = "
	
 
		SELECT X.id_mark_detail id
			,IFNULL(X.ratio,0)qty
			,X.size
			,X.urut
			,X.color
			,$body
			''nan
			,X.id_group_det
FROM(

SELECT MED.id_mark_detail
	,MED.id_mark
	,MED.id_cost
	,MED.id_group
	,MED.id_group_det
	,MED.color
	,MED.size
	,MED.qty
	,MED.ratio
	,MED.spread
	,ifnull(MZ.urut,99)urut
FROM prod_mark_entry_detail MED
LEFT JOIN mastersize MZ ON MZ.size = MED.size
WHERE MED.id_cost='$id_cost' AND MED.color ='$color'
ORDER BY urut ASC
)X GROUP BY X.id_group_det";

return $_q;
	
	}
	
	public function get($id_cost,$color){
		include __DIR__ .'/../../../include/conn.php';
		$field_size_header =$this->pivot_size($id_cost,$color,'HEADER');
		$field_size_body =$this->pivot_size($id_cost,$color,'BODY');
		
		 $pop_size = $field_size_header['size'];
		  $pop_qty = $field_size_header['qty'];
		$q = $this->main_query($field_size_header['str'],$field_size_body['str'],$id_cost,$color);
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$outp = '';
		$rasio_luar = array();
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
				$rasio = array();
				for($i=0;$i<count($pop_size);$i++){
					$nilai_rasio = "size_".str_replace('"','_pd',$pop_size[$i]);
					$outp .= '"'.str_replace('"','_pd',$pop_size[$i]).'" : "'.($row[$nilai_rasio]).'",';
						$_pop_rasio = array(
							"size"			=> str_replace('"','_pd',$pop_size[$i]),
							"rasio"			=> $row[$nilai_rasio],
							"id_mark_det"	=> $row['id']
						);
						array_push($rasio,$_pop_rasio);	
				}
				array_push($rasio_luar,$rasio);
		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.json_encode($rasio_luar).'] ,"populasi_size": ['.json_encode($pop_size).'] ,"populasi_qty": "'.json_encode($pop_qty).'"  }';
		return $result;
	}
}




?>





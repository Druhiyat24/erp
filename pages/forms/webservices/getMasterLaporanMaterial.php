<?php 
set_time_limit(900);
$data = $_POST;
	// print_r($_POST);
if (isset($_POST['idws']))
{	

		$getListData = new getListData();
		$List = $getListData->get($_POST['idws'], $_POST['type']);
		print_r($List);
		

}

else{
	exit;
}
class getListData {
	public function get($idws,$type){
		$kondisi = "";
		include __DIR__ .'/../../../include/conn.php';
		$q = "
SELECT  
		 ACT.kpno
		,ACT.id id_kpno
		,POH.id id_po
		,POH.pono
		,BPB.id id_bpb
		,POI.id_poi
		,POI.unit
		,MYITEM.id id_group
		,ifnull(BPB.bpbno_int,'NA')bpbno_int
		,BPB.id_item 	
		,MI.itemdesc
		,POH.app
		,POH.eta
		,IFNULL(POI.qty,0) qty_po
		,IFNULL(BPB.qty,0) qty_bpb
		,1 count_po
		,if(BPB.id IS NOT NULL,1,0) count_bpb
		FROM po_header POH
		LEFT JOIN 
		(SELECT id id_poi,id_po,id_gen,qty,price,unit,id_jo FROM po_item WHERE cancel ='N')POI ON 
		POI.id_po = POH.id
		LEFT JOIN(
			SELECT * FROM masteritem
		)MI ON MI.id_gen = POI.id_gen
		LEFT JOIN(
			SELECT id,bpbno_int,pono, qty, id_item,id_jo FROM bpb 
		)BPB ON POH.pono =BPB.pono AND BPB.id_item = MI.id_item AND BPB.id_jo = POI.id_jo
			LEFT JOIN(
SELECT MS_ITEM.id_item,MS_GROUP.* ,MS_ITEM.itemdesc,MS_ITEM.id_gen FROM mastergroup MS_GROUP
	INNER JOIN mastersubgroup MS_SUB_GROUP on MS_GROUP.id=MS_SUB_GROUP.id_group
	INNER JOIN mastertype2 MS_TYPE 			ON MS_SUB_GROUP.id=MS_TYPE.id_sub_group
	INNER JOIN mastercontents MS_CONTENTS   ON MS_TYPE.id=MS_CONTENTS.id_type
	INNER JOIN masterwidth MS_WIDTH 		ON MS_CONTENTS.id=MS_WIDTH.id_contents 
	INNER JOIN masterlength  MS_LENGTH		ON MS_WIDTH.id=MS_LENGTH.id_width
	INNER JOIN masterweight  MS_WEIGHT		ON MS_LENGTH.id=MS_WEIGHT.id_length
	INNER JOIN mastercolor  MS_COLOR		ON MS_WEIGHT.id=MS_COLOR.id_weight
	INNER JOIN masterdesc   MS_DESC			ON MS_COLOR.id=MS_DESC.id_color
	INNER JOIN masteritem MS_ITEM   ON MS_ITEM.id_gen = MS_DESC.id		
) MYITEM ON POI.id_gen = MYITEM.id_gen	
            inner join jo_det JOD on JOD.id_jo=POI.id_jo 
            inner join jo JO on JO.id=JOD.id_jo  
            inner join so SO on JOD.id_so=SO.id 
            inner join act_costing ACT on SO.id_cost=ACT.id 
WHERE  1=1 AND POH.jenis!='N' AND MYITEM.id IN('$type') AND ACT.kpno = '$idws'		
		
		
		";  
/* 		echo $q;
		die();  */
		$stmt = mysql_query($q);
		$num_rows = mysql_num_rows($stmt);		 
		$outp = '';
		if($num_rows > 0){
		while($row = mysql_fetch_array($stmt)){
			$item_desc = $row['itemdesc'];
			$bpbint = $row['bpbno_int'];
			
			if($row["count_po"] == '1' ){
				$count_po = 'OK';
			}
				if($row["count_bpb"] == '1' ){
				$count_bpb = 'PASS';
			}else{
				$count_bpb = 'NA';
			}	
		if ($outp != "") {$outp .= ",";}
		
			$outp .= '{"item_d":"'.rawurlencode($item_desc).'",'; 
			$outp .= '"bpb":"'. rawurlencode($bpbint). '",'; 
			$outp .= '"pono":"'. rawurlencode($row["pono"]). '",'; 
			$outp .= '"count_bpb":"'. rawurlencode($count_bpb). '",'; 
			$outp .= '"count_po":"'. rawurlencode($count_po). '",';
			$outp .= '"unit":"'. rawurlencode($row["unit"]). '",';
			$outp .= '"qty_bpb":"'. rawurlencode(number_format((float)$row['qty_bpb'], 2, '.', ',')). '",';
			$outp .= '"qty":"'. rawurlencode(number_format((float)$row['qty_po'], 2, '.', ',')).'"}'; 					
		}			
		}
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}

?>
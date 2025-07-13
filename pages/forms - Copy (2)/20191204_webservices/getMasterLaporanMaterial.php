<?php 
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
		if($type=='F'){
			$kondisi = "AND f.id_jo=e.id AND d.id_item=h.id_item AND h.mattype='F' AND (h.matclass IN ('FABRIC','FABRIC WOVEN','FABRIC KNIT'))";
		} elseif ($type=='AS') {
			$kondisi = "AND f.id_jo=e.id AND d.id_item=h.id_item AND h.matclass='ACCESORIES SEWING' GROUP BY d.id_item";
		} else {
			$kondisi = "AND f.id_jo=e.id AND d.id_item=h.id_item AND h.matclass='ACCESORIES PACKING'";
		}
		include __DIR__ .'/../../../include/conn.php';
		$q = "	


SELECT   ACT.id
		,ACT.kpno 
		,POH.pono
		,ifnull(BPB.bpbno_int,BPB.bpbno)bpbno
		,BPB.unit
		,if(MYITEM.kode_group = 'FK' 
			OR MYITEM.kode_group = 'F' 
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
			,ifnull(BPB.qty,0),0)bpb_fabric_qty
		,if(MYITEM.kode_group = 'AS',ifnull(BPB.qty,0),0)bpb_sewing_qty
		,if(MYITEM.kode_group = 'AP',ifnull(BPB.qty,0),0)bpb_packing_qty
		,MYITEM.itemdesc item
		,BPB.qty
FROM act_costing ACT
	LEFT JOIN(
		SELECT id_cost,id FROM so
	)SO ON ACT.id = SO.id_cost
	LEFT JOIN(
		SELECT id_so,id_jo id_jos FROM jo_det 	)JOD ON SO.id = JOD.id_so
	LEFT JOIN(
		SELECT id,jo_no FROM jo
	)JO ON JOD.id_jos = JO.id
	LEFT JOIN(
		SELECT id_po,id_jo,id_gen,qty FROM po_item
	)POI ON JO.id = POI.id_jo
	LEFT JOIN(
		SELECT pono,id FROM po_header
	)POH ON POH.id = POI.id_po
	LEFT JOIN(
		SELECT pono,bpbno_int,qty,id_item,unit,bpbno FROM bpb 
	)BPB ON BPB.pono = POH.pono
	
			LEFT JOIN(
SELECT MS_ITEM.id_item,MS_GROUP.* ,MS_ITEM.itemdesc FROM mastergroup MS_GROUP
	JOIN master_rootgroup MS_ROOT ON MS_GROUP.root_group = MS_ROOT.root_id
	INNER JOIN mastersubgroup MS_SUB_GROUP on MS_GROUP.id=MS_SUB_GROUP.id_group
	INNER JOIN mastertype2 MS_TYPE 			ON MS_SUB_GROUP.id=MS_TYPE.id_sub_group
	INNER JOIN mastercontents MS_CONTENTS   ON MS_TYPE.id=MS_CONTENTS.id_type
	INNER JOIN masterwidth MS_WIDTH 		ON MS_CONTENTS.id=MS_WIDTH.id_contents 
	INNER JOIN masterlength  MS_LENGTH		ON MS_WIDTH.id=MS_LENGTH.id_width
	INNER JOIN masterweight  MS_WEIGHT		ON MS_LENGTH.id=MS_WEIGHT.id_length
	INNER JOIN mastercolor  MS_COLOR		ON MS_WEIGHT.id=MS_COLOR.id_weight
	INNER JOIN masterdesc   MS_DESC			ON MS_COLOR.id=MS_DESC.id_color
		INNER JOIN masteritem MS_ITEM   ON MS_ITEM.id_gen = MS_DESC.id		
) MYITEM ON BPB.id_item = MYITEM.id_item WHERE ACT.kpno = '$idws' AND ifnull(BPB.bpbno_int,BPB.bpbno) IS NOT NULL AND BPB.qty > 0 GROUP BY ifnull(BPB.bpbno_int,BPB.bpbno)

UNION ALL

SELECT  ACT.id,ACT.kpno 
		,'' pono
		,ifnull(BPB.bpbno_int,BPB.bpbno)bpbno
		,BPB.unit	
		,if(MYITEM.kode_group = 'FK' 
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
			,ifnull(BPB.qty,0),0)bpb_fabric_qty
		,if(MYITEM.kode_group = 'AS',ifnull(BPB.qty,0),0)bpb_sewing_qty
		,if(MYITEM.kode_group = 'AP',ifnull(BPB.qty,0),0)bpb_packing_qty
		,MYITEM.itemdesc item
		,BPB.qty
FROM act_costing ACT
	LEFT JOIN(
		SELECT id_cost,id FROM so
	)SO ON ACT.id = SO.id_cost
	LEFT JOIN(
		SELECT id_so,id_jo id_jos FROM jo_det 	)JOD ON SO.id = JOD.id_so
	LEFT JOIN(
		SELECT id,jo_no FROM jo 
	)JO ON JOD.id_jos = JO.id
	LEFT JOIN(
		SELECT pono,bpbno_int,qty,id_item,bpbno,id_po_item,id_jo,unit FROM bpb 
	)BPB ON BPB.id_jo = JO.id
			LEFT JOIN(
SELECT MS_ITEM.id_item,MS_GROUP.* ,MS_ITEM.itemdesc FROM mastergroup MS_GROUP
	JOIN master_rootgroup MS_ROOT ON MS_GROUP.root_group = MS_ROOT.root_id
	INNER JOIN mastersubgroup MS_SUB_GROUP on MS_GROUP.id=MS_SUB_GROUP.id_group
	INNER JOIN mastertype2 MS_TYPE 			ON MS_SUB_GROUP.id=MS_TYPE.id_sub_group
	INNER JOIN mastercontents MS_CONTENTS   ON MS_TYPE.id=MS_CONTENTS.id_type
	INNER JOIN masterwidth MS_WIDTH 		ON MS_CONTENTS.id=MS_WIDTH.id_contents 
	INNER JOIN masterlength  MS_LENGTH		ON MS_WIDTH.id=MS_LENGTH.id_width
	INNER JOIN masterweight  MS_WEIGHT		ON MS_LENGTH.id=MS_WEIGHT.id_length
	INNER JOIN mastercolor  MS_COLOR		ON MS_WEIGHT.id=MS_COLOR.id_weight
	INNER JOIN masterdesc   MS_DESC			ON MS_COLOR.id=MS_DESC.id_color
		INNER JOIN masteritem MS_ITEM   ON MS_ITEM.id_gen = MS_DESC.id		
) MYITEM ON BPB.id_item = MYITEM.id_item
	WHERE  ACT.kpno = '$idws' AND ifnull(BPB.bpbno_int,BPB.bpbno) IS NOT NULL AND left(BPB.bpbno,1) in ('A','F','B') and (BPB.id_po_item='' or BPB.id_po_item is null) and BPB.id_jo!='' 
AND BPB.qty > 0
		";
		$stmt = mysql_query($q);
		//echo $q;		 
		$outp = '';
		//echo "$type";
		while($row = mysql_fetch_array($stmt)){
			if($type == 'F'){
				$qty = $row["bpb_fabric_qty"];
				$itemdesc =$row["item"];
				$bpbint = $row["bpbno"];
				$unitbpb = $row["unit"];
			}
			else if($type == 'AS'){
				$qty = $row["bpb_sewing_qty"];
				$itemdesc =$row["item"];
				$bpbint = $row["bpbno"];
				$unitbpb = $row["unit"];
			}
			else{
				$qty = $row["bpb_packing_qty"];
				$itemdesc =$row["item"];
				$bpbint = $row["bpbno"];
				$unitbpb = $row["unit"];
			}
			
			if($qty > 0){
if ($outp != "") {$outp .= ",";}
		
			$outp .= '{"item_d":"'.rawurlencode($itemdesc).'",';
			$outp .= '"fabric":"'. rawurlencode($row["bpb_fabric_qty"]). '",';
			
			$outp .= '"acc_sewing":"'. rawurlencode($row["bpb_sewing_qty"]). '",'; 
			$outp .= '"bpb":"'. rawurlencode($bpbint). '",'; 
			$outp .= '"pono":"'. rawurlencode($row["pono"]). '",'; 
			$outp .= '"acc_packing":"'. rawurlencode($row["packing_qty"]). '",';
	
			$outp .= '"unit":"'. rawurlencode($unitbpb). '",';
			$outp .= '"qty":"'. rawurlencode($qty). '"}'; 					
				
			}
			
		}
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}

?>
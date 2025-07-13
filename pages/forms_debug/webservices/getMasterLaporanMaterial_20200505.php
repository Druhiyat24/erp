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

	public function getNilaiFromRunningStok($idws,$type,$id_item_nya){
		include __DIR__ .'/../../../include/conn.php';
		
		if($type=='F'){
			$kondisi = "WHERE RUNNING.kpno = '$idws' AND RUNNING.root_group = '1' AND RUNNING.id_item = '$id_item_nya'";
			
		} elseif ($type=='AS') {

			$kondisi = "WHERE RUNNING.kpno = '$idws' AND RUNNING.root_group = '2' AND RUNNING.id_item = '$id_item_nya'";
		} else {

			$kondisi = "WHERE RUNNING.kpno = '$idws' AND RUNNING.root_group = '3' AND RUNNING.id_item = '$id_item_nya'";
		}		
		
		$sql = "SELECT RUNNING.* FROM(
		SELECT
				JOINS.id
				,JOINS.kpno
				,JOINS.bpbno_int
				,JOINS.id_item
				,ifnull((JOINS.running_fabric_qty),0)running_fabric_qty
				,ifnull((JOINS.running_sewing_qty),0)running_sewing_qty
				,ifnull((JOINS.running_packing_qty),0)running_packing_qty
				
				,(JOINS.running_fabric_count)running_fabric_count
				,(JOINS.running_sewing_count)running_sewing_count
				,(JOINS.running_packing_count)running_packing_count				
				FROM(

SELECT  ACT.id,ACT.kpno 
		,BPB.bpbno_int
		,BPB.bpbno
		,BPB.id_item
		,MYITEM.kode_group
		,if(MYITEM.kode_group = 'FK' 
		OR MYITEM.kode_group = 'F'
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
		,ifnull(BPB.qty,0),0)running_fabric_qty
		,if(MYITEM.kode_group = 'AS',ifnull(BPB.qty,0),0)running_sewing_qty
		,if(MYITEM.kode_group = 'AP',ifnull(BPB.qty,0),0)running_packing_qty
		
		
,if(MYITEM.kode_group = 'FK' 
			OR MYITEM.kode_group = 'F' 
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
			,ifnull(1,0),0)running_fabric_count
				,if(MYITEM.kode_group = 'AS',ifnull(1,0),0)running_sewing_count
				,if(MYITEM.kode_group = 'AP',ifnull(1,0),0)running_packing_count		
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
		SELECT pono,bpbno_int,qty,id_item,bpbno,id_po_item,id_jo FROM bpb 
	)BPB ON BPB.id_jo = JO.id
			LEFT JOIN(
SELECT MS_ITEM.id_item,MS_GROUP.* ,MS_ITEM.itemdesc FROM mastergroup MS_GROUP
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
	WHERE left(BPB.bpbno,1) in ('A','F','B') and (BPB.id_po_item='' or BPB.id_po_item is null) and BPB.id_jo!='' 
	)JOINS  
)RUNNING ";
$stmt = mysql_query($sql);
			$num_rows = mysql_num_rows($stmt);		 
		$qty_ = 0;
		if($num_rows > 0){
			while($row = mysql_fetch_array($stmt)){
				$qty_ = $qty_ + $row['qty'];
			}	
			return $qty_;
			
		}else{
			return $qty_;
			
		}
	}
	
	public function getNilaiFromBpb($idws,$type,$id_item_nya){
		include __DIR__ .'/../../../include/conn.php';
		if($type=='F'){
			$kondisi = "WHERE FROMBPB.kpno = '$idws' AND FROMBPB.root_group = '1' AND FROMBPB.id_item = '$id_item_nya'";
			
		} elseif ($type=='AS') {

			$kondisi = "WHERE FROMBPB.kpno = '$idws' AND FROMBPB.root_group = '2' AND FROMBPB.id_item = '$id_item_nya'";
		} else {

			$kondisi = "WHERE FROMBPB.kpno = '$idws' AND FROMBPB.root_group = '3' AND FROMBPB.id_item = '$id_item_nya'";
		}	

		$sql = " SELECT FROMBPB.* FROM(

SELECT
		JOINS.id
		,JOINS.kpno
		,JOINS.bpbno
		,JOINS.pono
		,JOINS.bpbno_int	
		,JOINS.root_group
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',1,0)ABC
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull((JOINS.bpb_fabric_qty),0))bpb_fabric_qty
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull((JOINS.bpb_sewing_qty),0))bpb_sewing_qty
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull((JOINS.bpb_packing_qty),0))bpb_packing_qty
                                                                  
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull((JOINS.bpb_fabric_count),0))bpb_fabric_count
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull((JOINS.bpb_sewing_count),0))bpb_sewing_count
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull((JOINS.bpb_packing_count),0))bpb_packing_count
		,JOINS.item
		,JOINS.qty
		,JOINS.id_item
		FROM(
SELECT  ACT.id,ACT.kpno ,MYITEM.root_group
		,ifnull(BPB.bpbno_int,BPB.bpbno)bpbno
		,POH.pono
		,BPB.bpbno_int
		,if(MYITEM.kode_group = 'FK' 
		OR MYITEM.kode_group = 'F'
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
			,ifnull(BPB.qty,0),0)bpb_fabric_qty
		,if(MYITEM.kode_group = 'AS',ifnull(BPB.qty,0),0)bpb_sewing_qty
		,if(MYITEM.kode_group = 'AP',ifnull(BPB.qty,0),0)bpb_packing_qty
		
,if(MYITEM.kode_group = 'FK' 
			OR MYITEM.kode_group = 'F' 
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
			,ifnull(1,0),0)bpb_fabric_count
				,if(MYITEM.kode_group = 'AS',ifnull(1,0),0)bpb_sewing_count
				,if(MYITEM.kode_group = 'AP',ifnull(1,0),0)bpb_packing_count		
		
		,MYITEM.itemdesc item
		,BPB.qty
		,BPB.id_item
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
		SELECT pono,bpbno_int,qty,id_item,bpbno FROM bpb 
	)BPB ON BPB.pono = POH.pono
	
			LEFT JOIN(
SELECT MS_ITEM.id_item,MS_GROUP.* ,MS_ITEM.itemdesc FROM mastergroup MS_GROUP
	INNER JOIN mastersubgroup MS_SUB_GROUP on MS_GROUP.id=MS_SUB_GROUP.id_group
	INNER JOIN mastertype2 MS_TYPE 			ON MS_SUB_GROUP.id=MS_TYPE.id_sub_group
	INNER JOIN mastercontents MS_CONTENTS   ON MS_TYPE.id=MS_CONTENTS.id_type
	INNER JOIN masterwidth MS_WIDTH 		ON MS_CONTENTS.id=MS_WIDTH.id_contents 
	INNER JOIN masterlength  MS_LENGTH		ON MS_WIDTH.id=MS_LENGTH.id_width
	INNER JOIN masterweight  MS_WEIGHT		ON MS_LENGTH.id=MS_WEIGHT.id_length
	INNER JOIN mastercolor  MS_COLOR		ON MS_WEIGHT.id=MS_COLOR.id_weight
	INNER JOIN masterdesc   MS_DESC			ON MS_COLOR.id=MS_DESC.id_color
		INNER JOIN masteritem MS_ITEM   ON MS_ITEM.id_gen = MS_DESC.id		
) MYITEM ON BPB.id_item = MYITEM.id_item GROUP BY ifnull(BPB.bpbno_int,BPB.bpbno)
	)JOINS WHERE JOINS.bpbno_int IS NOT NULL OR JOINS.bpbno_int != ''  
)FROMBPB
$kondisi
";
		$stmt = mysql_query($sql);
		$num_rows = mysql_num_rows($stmt);		 
		$qty_ = 0;
		$bpb = 'NA';
		if($num_rows > 0){
			while($row = mysql_fetch_array($stmt)){
				$qty_ = $qty_ + $row['qty'];
				$bpb = $row['bpbno'];
			}	
			
			return $qty_."__".$bpb;
			
		}else{
			return $qty_."__".$bpb;
			
		}
	}
	
	public function get($idws,$type){
		$kondisi = "";
		if($type=='F'){
			$kondisi = "WHERE FROMPO.kpno = '$idws' AND FROMPO.root_group = '1'";
		} elseif ($type=='AS') {
			$kondisi = "WHERE FROMPO.kpno = '$idws' AND FROMPO.root_group = '2'";
		} else {
			$kondisi = "WHERE FROMPO.kpno = '$idws' AND FROMPO.root_group = '3'";
		}
		include __DIR__ .'/../../../include/conn.php';
		$q = "	SELECT 
		FROMPO.id
        ,FROMPO.kpno
		,FROMPO.deldate
		,FROMPO.eta
		,DATEDIFF(FROMPO.deldate,FROMPO.eta) AS status_material
        ,FROMPO.po_fabric_qty
        ,FROMPO.po_sewing_qty
        ,FROMPO.po_packing_qty
        ,FROMPO.po_fabric_count
        ,FROMPO.po_sewing_count
        ,FROMPO.po_packing_count		
		,FROMPO.root_group
		/*
				,FROMBPB.bpbno
        ,ifnull(FROMBPB.bpb_fabric_qty,0 )bpb_fabric_qtyY
        ,ifnull(FROMBPB.bpb_sewing_qty,0 )bpb_sewing_qtyY
        ,ifnull(FROMBPB.bpb_packing_qty,0)bpb_packing_qtyY	


        ,ifnull(FROMBPB.bpb_fabric_count,0 )bpb_fabric_countY
        ,ifnull(FROMBPB.bpb_sewing_count,0 )bpb_sewing_countY
        ,ifnull(FROMBPB.bpb_packing_count,0)bpb_packing_countY	
		
        ,ifnull(RUNNING.running_fabric_count,0 )running_fabric_count
        ,ifnull(RUNNING.running_sewing_count,0 )running_sewing_count
        ,ifnull(RUNNING.running_packing_count,0)running_packing_count	

		,(ifnull(FROMBPB.bpb_fabric_qty,0 ) + ifnull(RUNNING.running_fabric_qty,0 ) )bpb_fabric_qty
		,(ifnull(FROMBPB.bpb_sewing_qty,0 ) + ifnull(RUNNING.running_sewing_qty,0 ) )bpb_sewing_qty
		,(ifnull(FROMBPB.bpb_packing_qty,0 ) + ifnull(RUNNING.running_packing_qty,0 ) )bpb_packing_qty
		,(ifnull(FROMBPB.bpb_fabric_count,0 ) + ifnull(RUNNING.running_fabric_count,0 ) )bpb_fabric_count
		,(ifnull(FROMBPB.bpb_sewing_count,0 ) + ifnull(RUNNING.running_sewing_count,0 ) )bpb_sewing_count
		,(ifnull(FROMBPB.bpb_packing_count,0 ) + ifnull(RUNNING.running_packing_count,0 ) )bpb_packing_count		
		*/
        ,FROMPO.item
        ,FROMPO.pono
        ,FROMPO.qty
		,FROMPO.id_item
		,FROMPO.id_gen
		,FROMPO.unit
FROM
	(
		SELECT
				JOINS.id
				,JOINS.kpno
				,JOINS.deldate
				,JOINS.eta
				,JOINS.root_group
				,ifnull((JOINS.po_fabric_qty),0)po_fabric_qty
				,ifnull((JOINS.po_sewing_qty),0)po_sewing_qty
				,ifnull((JOINS.po_packing_qty),0)po_packing_qty
				,ifnull((JOINS.po_fabric_count),0)po_fabric_count
				,ifnull((JOINS.po_sewing_count),0)po_sewing_count
				,ifnull((JOINS.po_packing_count),0)po_packing_count				
				,JOINS.item
				,JOINS.pono
				,JOINS.qty
				,JOINS.id_item
				,JOINS.id_gen
				,JOINS.unit
				FROM(
		SELECT  ACT.id,ACT.kpno,ACT.deldate,POH.eta,MYITEM.root_group,MYITEM.id_item,MYITEM.id_gen,POI.unit
		,if(MYITEM.kode_group = 'FK' 
			OR MYITEM.kode_group = 'F' 
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
			,ifnull(POI.qty,0),0)po_fabric_qty
				,if(MYITEM.kode_group = 'AS',ifnull(POI.qty,0),0)po_sewing_qty
				,if(MYITEM.kode_group = 'AP',ifnull(POI.qty,0),0)po_packing_qty

,if(MYITEM.kode_group = 'F' 
			,ifnull(1,0),0)po_fabric_count
				,if(MYITEM.kode_group = 'AS',ifnull(1,0),0)po_sewing_count
				,if(MYITEM.kode_group = 'AP',ifnull(1,0),0)po_packing_count
				,MYITEM.itemdesc item
				,POH.pono
				,POI.qty
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
				SELECT id_po,id_jo,id_gen,qty,unit FROM po_item
			)POI ON JO.id = POI.id_jo
			LEFT JOIN(
				SELECT pono,id,eta,app FROM po_header WHERE app = 'A'
			)POH ON POH.id = POI.id_po
			
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
			)JOINS 
			)FROMPO
$kondisi
		"; 
		$stmt = mysql_query($q);
		$num_rows = mysql_num_rows($stmt);		 
		$outp = '';
		if($num_rows > 0){
		while($row = mysql_fetch_array($stmt)){
			$item_desc = $row[item];
			$qty_item_po = $row['qty'];
			$bpb = $this -> getNilaiFromBpb($idws,$type,$row['id_item']);
			$bpb_explode = explode("__",$bpb);
			$bpb_qty = $bpb_explode[0];
			$bpbint = $bpb_explode[1];
			
			$qty_item_running_nya = $this -> getNilaiFromRunningStok($idws,$type,$row['id_item']);
			$qty_item_bpb = $bpb_qty + $qty_item_running_nya;
			
			if($qty_item_bpb == '0'){
				$count_bpb = 0;
				
			}else{
				$count_bpb = 1;
				
			}
			if($qty_item_po == '0'){
				$count_po = 0;
				
			}else{
				$count_po = 1;
				
			}
		if ($outp != "") {$outp .= ",";}
		
			$outp .= '{"item_d":"'.rawurlencode($item_desc).'",'; 
			$outp .= '"bpb":"'. rawurlencode($bpbint). '",'; 
			$outp .= '"pono":"'. rawurlencode($row["pono"]). '",'; 
			$outp .= '"count_bpb":"'. rawurlencode($count_bpb). '",'; 
			$outp .= '"count_po":"'. rawurlencode($count_po). '",';
			$outp .= '"unit":"'. rawurlencode($row["unit"]). '",';
			$outp .= '"qty_bpb":"'. rawurlencode($bpb_qty). '",';
			$outp .= '"qty":"'. rawurlencode($qty_item_po). '"}'; 					
		}			
			
		}
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}

?>
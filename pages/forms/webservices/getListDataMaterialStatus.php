<?php 
	function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
		return $d_to;
	}



include __DIR__ .'/../../../include/conn.php';
## Read value
$data = $_GET;
$d_from = $d_from =date("Y-m-d", strtotime($data['from']));
$d_to = $d_to =date("Y-m-d", strtotime($data['to']));
/*
print_r($_POST);
die();  */
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT   ACT.id
		,ACT.kpno
		,ACT.deldate
		,MASTER_FABRIC.eta eta_fabric
		,MASTER_SEWING.eta eta_sewing
		,MASTER_PACKING.eta eta_packing
		,DATEDIFF(ACT.deldate,MASTER_FABRIC.eta) AS rfpd_fabric
		,DATEDIFF(ACT.deldate,MASTER_SEWING.eta) AS rfpd_sewing
		,DATEDIFF(ACT.deldate,MASTER_PACKING.eta) AS rfpd_packing
		,ifnull(MASTER_FABRIC.count_po_fabrics,0)count_po_fabrics
        ,(ifnull(count_bpb_r_fabrics,0) + ifnull(MASTER_FABRIC.count_bpb_fabrics,0))count_bpb_fabrics
        ,ifnull(MASTER_FABRIC.qty_po_fabrics,0)qty_po_fabrics
		,(ifnull(qty_bpb_r_fabrics,0) +   ifnull(MASTER_FABRIC.qty_bpb_fabrics,0)) qty_bpb_fabrics
		
		,ifnull(MASTER_SEWING.count_po_sewings,0)count_po_sewings
        ,(ifnull(count_bpb_r_sewings,0) + ifnull(MASTER_SEWING.count_bpb_sewings,0))count_bpb_sewings
        ,ifnull(MASTER_SEWING.qty_po_sewings,0)qty_po_sewings
		,(ifnull(qty_bpb_r_sewings,0) + ifnull(MASTER_SEWING.qty_bpb_sewings,0))qty_bpb_sewings
	
		,ifnull(MASTER_PACKING.count_po_packings,0)count_po_packings
        ,(ifnull(count_bpb_r_packings,0) + ifnull(MASTER_PACKING.count_bpb_packings,0))count_bpb_packings
        ,ifnull(MASTER_PACKING.qty_po_packings,0)qty_po_packings
		,(ifnull(qty_bpb_r_packings,0) + ifnull(MASTER_PACKING.qty_bpb_packings,0))qty_bpb_packings

	
		,CONCAT(ROUND(ifnull(MASTER_FABRIC.count_bpb_fabrics,0),0),'/',ROUND(ifnull(MASTER_FABRIC.count_po_fabrics,0),0))items_complete_fabric
		,CONCAT(ROUND(ifnull(MASTER_FABRIC.qty_bpb_fabrics,0),0),'/',ROUND(ifnull(MASTER_FABRIC.qty_po_fabrics,0),0))qty_complete_fabric
		,CONCAT(ROUND(ifnull(MASTER_SEWING.count_bpb_sewings,0),0),'/',ROUND(ifnull(MASTER_SEWING.count_po_sewings,0),0))items_complete_sewing
		,CONCAT(ROUND(ifnull(MASTER_SEWING.qty_bpb_sewings,0),0),'/',ROUND(ifnull(MASTER_SEWING.qty_po_sewings,0),0))qty_complete_sewing	
		,CONCAT(ROUND(ifnull(MASTER_PACKING.count_bpb_packings,0),0),'/',ROUND(ifnull(MASTER_PACKING.count_po_packings,0),0))items_complete_packing
		,CONCAT(ROUND(ifnull(MASTER_PACKING.qty_bpb_packings,0),0),'/',ROUND(ifnull(MASTER_PACKING.qty_po_packings,0),0))qty_complete_packing			
		,ifnull((  ( ( ifnull(MASTER_FABRIC.qty_bpb_fabrics,0) + ifnull(qty_bpb_r_fabrics,0) )/ ifnull(MASTER_FABRIC.qty_po_fabrics,0) )* (100)  ),0)percent_fabric
		,ifnull((  ( ( ifnull(MASTER_SEWING.qty_bpb_sewings,0)  + ifnull(qty_bpb_r_fabrics,0) )/ ifnull(MASTER_SEWING.qty_po_sewings,0) )* (100)  ),0)percent_sewing
		,ifnull((  ( ( ifnull(MASTER_PACKING.qty_bpb_packings,0) + ifnull(qty_bpb_r_fabrics,0) )/ ifnull(MASTER_PACKING.qty_po_packings,0) )* (100)  ),0)percent_packing
	FROM act_costing ACT
	LEFT JOIN(
	
SELECT 	 SUM(FABRIC.count_po_fabric) 	count_po_fabrics
		,SUM(FABRIC.count_bpb_fabric) 	count_bpb_fabrics
		,SUM(FABRIC.qty_po_fabric) 		qty_po_fabrics
		,SUM(FABRIC.qty_bpb_fabric) 	qty_bpb_fabrics

		
		,FABRIC.* FROM (
SELECT  
		 ACT.kpno
		,ACT.id id_kpno
		,POH.id id_po
		,POH.pono
		,BPB.id id_bpb
		,POI.id_poi
		,MYITEM.id id_group
		,BPB.bpbno_int
		,BPB.id_item 	
		,MI.itemdesc
		,POH.app
		,POH.eta
		,IFNULL(POI.qty,0) qty_po_fabric
		,IFNULL(BPB.qty,0) qty_bpb_fabric
		,1 count_po_fabric
		,if(BPB.id IS NOT NULL,1,0) count_bpb_fabric			
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
WHERE 1=1 AND POH.jenis!='N' AND (ACT.deldate >= '{$d_from}' AND ACT.deldate <= '{$d_to}') AND MYITEM.id IN('1'))FABRIC
GROUP BY FABRIC.id_kpno
	)MASTER_FABRIC ON ACT.id = MASTER_FABRIC.id_kpno
	
LEFT JOIN (

SELECT 	 SUM(SEWING.count_po_sewing) count_po_sewings
		,SUM(SEWING.count_bpb_sewing) count_bpb_sewings
		,SUM((SEWING.qty_po_sewing)) qty_po_sewings
		,SUM((SEWING.qty_bpb_sewing)) qty_bpb_sewings

		
		,SEWING.* FROM (
SELECT  
		 ACT.kpno
		,ACT.id id_kpno
		,POH.id id_po
		,POH.pono
		,BPB.id id_bpb
		,POI.id_poi
		,MYITEM.id id_group
		,BPB.bpbno_int
		,BPB.id_item 	
		,MI.itemdesc
		,POH.app
		,POH.eta
		,IFNULL(POI.qty,0) qty_po_sewing
		,IFNULL(BPB.qty,0) qty_bpb_sewing
		,1 count_po_sewing
		,if(BPB.id IS NOT NULL,1,0) count_bpb_sewing		
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
WHERE 1=1 AND POH.jenis!='N' AND (ACT.deldate >= '{$d_from}' AND ACT.deldate <= '{$d_to}') AND MYITEM.id IN('2'))SEWING
GROUP BY SEWING.id_kpno


)MASTER_SEWING ON ACT.id = MASTER_SEWING.id_kpno	

LEFT JOIN(
SELECT MASTER_PACKING.* FROM (
		
SELECT 	 SUM(PACKING.count_po_packing)  count_po_packings
		,SUM(PACKING.count_bpb_packing) count_bpb_packings
		,SUM((PACKING.qty_po_packing))  qty_po_packings
		,SUM((PACKING.qty_bpb_packing)) qty_bpb_packings
 
		
		,PACKING.* FROM (
SELECT  
		 ACT.kpno
		,ACT.id id_kpno
		,POH.id id_po
		,POH.pono
		,BPB.id id_bpb
		,POI.id_poi
		,MYITEM.id id_group
		,BPB.bpbno_int
		,BPB.id_item 	
		,MI.itemdesc
		,POH.app
		,POH.eta
		,IFNULL(POI.qty,0) qty_po_packing
		,IFNULL(BPB.qty,0) qty_bpb_packing
		,1 count_po_packing
		,if(BPB.id IS NOT NULL,1,0) count_bpb_packing
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
WHERE 1=1 AND POH.jenis!='N' AND (ACT.deldate >= '{$d_from}' AND ACT.deldate <= '{$d_to}') AND MYITEM.id IN('3'))PACKING
GROUP BY PACKING.id_kpno)MASTER_PACKING


)MASTER_PACKING ON ACT.id = MASTER_PACKING.id_kpno	
/* RUNNING FABRIC */

LEFT JOIN(
SELECT 	
		SUM(R_FABRIC.count_bpb_r_fabric) count_bpb_r_fabrics
		,SUM(R_FABRIC.qty_bpb_r_fabric) qty_bpb_r_fabrics
		,R_FABRIC.* FROM (
	SELECT  
		ACT.kpno
		,ACT.id id_kpno
		,BPB.id
		,BPB.bpbno_int
		,BPB.bpbno
		,BPB.id_po_item
		,BPB.id_item
		,MI.itemdesc
		,1 count_bpb_r_fabric
		,ifnull(BPB.qty,0)qty_bpb_r_fabric
		FROM bpb BPB
		LEFT JOIN masteritem MI ON MI.id_item = BPB.id_item
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
) MYITEM ON MI.id_gen = MYITEM.id_gen
            inner join jo_det JOD on JOD.id_jo=BPB.id_jo 
            inner join jo JO on JO.id=JOD.id_jo  
            inner join so SO on JOD.id_so=SO.id 
            inner join act_costing ACT on SO.id_cost=ACT.id 
WHERE 1=1 AND (ACT.deldate >= '{$d_from}' AND ACT.deldate <= '{$d_to}') AND MYITEM.id IN('1') AND left(BPB.bpbno,1) in ('A','F','B') and (BPB.id_po_item='' or BPB.id_po_item is null) and BPB.id_jo!='')R_FABRIC			
GROUP BY R_FABRIC.id_kpno			

)MS_R_FABRIC ON ACT.id = MS_R_FABRIC.id_kpno 
	
	
/* RUNNING SEWING */

LEFT JOIN(
SELECT 	
		SUM(R_SEWING.count_bpb_r_sewing) count_bpb_r_sewings
		,SUM(R_SEWING.qty_bpb_r_sewing) qty_bpb_r_sewings
		,R_SEWING.* FROM (
	SELECT  
		ACT.kpno
		,ACT.id id_kpno
		,BPB.id
		,BPB.bpbno_int
		,BPB.bpbno
		,BPB.id_po_item
		,BPB.id_item
		,MI.itemdesc
		,1 count_bpb_r_sewing
		,ifnull(BPB.qty,0)qty_bpb_r_sewing
		FROM bpb BPB
		LEFT JOIN masteritem MI ON MI.id_item = BPB.id_item
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
) MYITEM ON MI.id_gen = MYITEM.id_gen
            inner join jo_det JOD on JOD.id_jo=BPB.id_jo 
            inner join jo JO on JO.id=JOD.id_jo  
            inner join so SO on JOD.id_so=SO.id 
            inner join act_costing ACT on SO.id_cost=ACT.id 
WHERE 1=1 AND (ACT.deldate >= '{$d_from}' AND ACT.deldate <= '{$d_to}') AND MYITEM.id IN('2') AND left(BPB.bpbno,1) in ('A','F','B') and (BPB.id_po_item='' or BPB.id_po_item is null) and BPB.id_jo!='')R_SEWING	
GROUP BY R_SEWING.id_kpno			

)MS_R_SEWING ON ACT.id = MS_R_SEWING.id_kpno 	


/* RUNNING PACKING */

LEFT JOIN(
SELECT 	
		SUM(R_PACKING.count_bpb_r_packing) count_bpb_r_packings
		,SUM(R_PACKING.qty_bpb_r_packing) qty_bpb_r_packings
		,R_PACKING.* FROM (
	SELECT  
		ACT.kpno
		,ACT.id id_kpno
		,BPB.id
		,BPB.bpbno_int
		,BPB.bpbno
		,BPB.id_po_item
		,BPB.id_item
		,MI.itemdesc
		,1 count_bpb_r_packing
		,ifnull(BPB.qty,0)qty_bpb_r_packing
		FROM bpb BPB
		LEFT JOIN masteritem MI ON MI.id_item = BPB.id_item
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
) MYITEM ON MI.id_gen = MYITEM.id_gen
            inner join jo_det JOD on JOD.id_jo=BPB.id_jo 
            inner join jo JO on JO.id=JOD.id_jo  
            inner join so SO on JOD.id_so=SO.id 
            inner join act_costing ACT on SO.id_cost=ACT.id 
WHERE 1=1 AND (ACT.deldate >= '{$d_from}' AND ACT.deldate <= '{$d_to}') AND MYITEM.id IN('2') AND left(BPB.bpbno,1) in ('A','F','B') and (BPB.id_po_item='' or BPB.id_po_item is null) and BPB.id_jo!=''

)R_PACKING
GROUP BY R_PACKING.id_kpno			

)MS_R_PACKING ON ACT.id = MS_R_PACKING.id_kpno 
	
	WHERE 1=1 AND ACT.deldate >= '{$d_from}' AND ACT.deldate <= '{$d_to}'
	
)X";
## Search
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.kpno       	    	LIKE'%".$searchValue."%'
	

)
";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 

$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($conn_li,"select count(*)  allcount from $table WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
$colomn = "		 
			
				 X.id
				,X.kpno
				,X.deldate
				,X.eta_fabric
				,X.eta_sewing
				,X.eta_packing
				,X.rfpd_fabric
				,X.rfpd_sewing
				,X.rfpd_packing
				,X.items_complete_fabric
				,X.qty_complete_fabric
				,X.items_complete_sewing
				,X.qty_complete_sewing	
				,X.items_complete_packing
				,X.qty_complete_packing			
				,X.percent_fabric
				,X.percent_sewing
				,X.percent_packing
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
/* echo $empQuery;
die(); */
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];

	$exp_fabric = explode("/",$row['qty_complete_fabric']);
	$exp_fabric[0] = number_format((float)$exp_fabric[0], 2, '.', ',');
	$exp_fabric[1] = number_format((float)$exp_fabric[1], 2, '.', ',');
	
	$exp_sewing = explode("/",$row['qty_complete_sewing']);
	$exp_sewing[0] = number_format((float)$exp_sewing[0], 2, '.', ',');
	$exp_sewing[1] = number_format((float)$exp_sewing[1], 2, '.', ',');	


	$exp_packing = explode("/",$row['qty_complete_packing']);
	$exp_packing[0] = number_format((float)$exp_packing[0], 2, '.', ',');
	$exp_packing[1] = number_format((float)$exp_packing[1], 2, '.', ',');		
	
	$row['qty_complete_fabric'] = $exp_fabric[0]."/".$exp_fabric[1];
	$row['qty_complete_sewing'] = $exp_fabric[0]."/".$exp_fabric[1];
	$row['qty_complete_packing'] = $exp_fabric[0]."/".$exp_fabric[1];
	
		$button_items_complete_fabric ="<a href='#' data-toggle='modal' data-target='.detmatstatcount' data-backdrop='static' data-keyboard='false'  onclick='getMasterLaporanMaterialCount(".'"'.$row['kpno'].'"'.",".'"1"'.")'>".($row['items_complete_fabric'])."</a>";
        $button_qty_complete_fabric ="<a href='#' data-toggle='modal' data-target='.detmatstat' onclick='getMasterLaporanMaterial(".'"'.$row['kpno'].'"'.",".'"1"'.")'>".$row['qty_complete_fabric']."</a>";
        $button_items_complete_sewing = "<a href='#'  data-toggle='modal' data-target='.detmatstatcount' data-backdrop='static' data-keyboard='false'  onclick='getMasterLaporanMaterialCount(".'"'.$row['kpno'].'"'.",".'"2"'.")'>".($row['items_complete_sewing'])."</a>";
        $button_qty_complete_sewing	= "<a href='#' data-toggle='modal' data-target='.detmatstat' onclick='getMasterLaporanMaterial(".'"'.$row['kpno'].'"'.",".'"2"'.")'>".$row['qty_complete_sewing']."</a>";
        $button_items_complete_packing = "<a href='#' data-toggle='modal' data-target='.detmatstatcount' data-backdrop='static' data-keyboard='false'   onclick='getMasterLaporanMaterialCount(".'"'.$row['kpno'].'"'.",".'"3"'.")'>".($row['items_complete_packing'])."</a>";
        $button_qty_complete_packing = "<a href='#' data-toggle='modal' data-target='.detmatstat'  onclick='getMasterLaporanMaterial(".'"'.$row['kpno'].'"'.",".'"3"'.")'>".$row['qty_complete_packing']."</a>";



   $data[] = array(
"kpno"=>htmlspecialchars($row['kpno']),   //ok             
"deldate"=>htmlspecialchars($row['deldate']), //ok
"eta_fabric"=>htmlspecialchars($row['eta_fabric']), //ok
"eta_sewing"=>htmlspecialchars($row['eta_sewing']), //ok
"eta_packing"=>htmlspecialchars($row['eta_packing']), //ok
"rfpd_fabric"=>htmlspecialchars($row['rfpd_fabric']), //ok
"rfpd_sewing"=>htmlspecialchars($row['rfpd_sewing']), //ok
"rfpd_packing"=>htmlspecialchars($row['rfpd_packing']), //ok
"percent_fabric"=>htmlspecialchars(round($row['percent_fabric'],0)), //ok
"percent_sewing"=>htmlspecialchars(round($row['percent_sewing'],0)), //ok
"percent_packing"=>htmlspecialchars(round($row['percent_packing'],0)), //ok
"button_items_complete_fabric"=>rawurlencode($button_items_complete_fabric),
"button_qty_complete_fabric"=>rawurlencode($button_qty_complete_fabric),
"button_items_complete_sewing"=>rawurlencode($button_items_complete_sewing),
"button_qty_complete_sewing"=>rawurlencode($button_qty_complete_sewing),
"button_items_complete_packing"=>rawurlencode($button_items_complete_packing),
"button_qty_complete_packing"=>rawurlencode($button_qty_complete_packing),

   );
}
## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecordwithFilter,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data
);
echo json_encode($response);
?>
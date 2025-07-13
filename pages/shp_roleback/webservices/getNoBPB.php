<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['bpb']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($bpb){
		include __DIR__ .'/../../../include/conn.php';
		//$q = "SELECT bpbno,bpbno_int, concat(bpbno,' ',bpbno_int) concats FROM bpb";
		 $extrawhere = "";
		 if($bpb !=""){
			 $extrawhere = "OR a.bpbno IN ('$bpb')";
		 }
			$q = "SELECT a.*,ac.kpno,s.goods_code,s.itemname itemdesc,supplier,jod.id_jo idjonya
					,concat(a.bppbno,' ',a.bppbno_int) concats
          FROM bppb a inner join masterstyle s on a.id_item=s.id_item
          inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
          inner join so_det sod on a.id_so_det=sod.id 
          inner join so on sod.id_so=so.id 
          inner join jo_det jod on so.id=jod.id_so 
          inner join act_costing ac on so.id_cost=ac.id
          WHERE  SUBSTRING(a.bppbno, 4, 2) ='FG' AND a.bppbno NOT IN (SELECT bpbno FROM invoice_commercial) 
          GROUP BY a.bppbno ASC order by bppbdate";
		
		//echo $q;
		
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['bppbno']).'",';
			$outp .= '"nama":"'. rawurlencode($row["bppbno"]." ".$row["kpno"]). '"}'; 	
		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>





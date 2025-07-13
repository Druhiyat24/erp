<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

	$getListData = new getListData();
$List = $getListData->get();
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT a.bppbno_int
		,a.bppbno
		,a.bppbno_req
		,a.bppbdate
		,a.invno
		,a.bcno
		,a.bcdate
		,a.jenis_dok
		,s.goods_code
		,s.itemdesc itemdesc
		,ms.supplier
		,mb.supplier buyer
		,ac.styleno
		,ac.kpno
		,a.username
		,bpb.unit
		,group_concat(distinct concat(' ',jo.jo_no))
		,concat(ac.kpno,'|',ac.styleno)	tampil	
			FROM bppb a 
				inner join masteritem s 
					on a.id_item=s.id_item 
				inner join mastersupplier ms 
					on a.id_supplier=ms.id_supplier 
				inner join jo_det jod 
					on a.id_jo=jod.id_jo 
				inner join jo 
					on jod.id_jo=jo.id 
				inner join so 
					on jod.id_so=so.id 
				inner join act_costing ac 
					on so.id_cost=ac.id 
				inner join mastersupplier mb 
					on ac.id_buyer=mb.id_supplier 
				inner join bpb bpb
					on jo.id = bpb.id_jo
					where mid(a.bppbno,4,1) in ('F') and mid(a.bppbno,4,2)!='FG' and a.bppbno_req!='' and a.bppbno NOT IN(SELECT bppbno FROM cut_in WHERE v_status = 'W')  GROUP BY a.bppbno order by bppbdate desc"; 
				//	echo $q;
		$stmt = mysql_query($q);		

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['bppbno']).'",';
			$outp .= '"nama":"'. rawurlencode($row["tampil"]). '"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>





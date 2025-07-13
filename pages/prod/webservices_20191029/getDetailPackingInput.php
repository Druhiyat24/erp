<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

	$getListData = new getListData();
$List = $getListData->get($_POST['id_jo']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id_cost){
		include __DIR__ .'/../../../include/conn.php';
		$q = "select sod.id,sum(mo.qty) qtyout,sum(co.qty) qtyprev,so.so_no,so.buyerno,sod.dest,sod.color,sod.size,sod.qty,so.unit
        from so inner join so_det sod on so.id=sod.id_so
        inner join 
        (select a.id_so_det,sum(a.qty) qty from cut_out a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost='$id_cost' group by id_so_det) co on sod.id=co.id_so_det
        left join 
        (select a.id_so_det,sum(a.qty) qty from mfg_out a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost='$id_cost' and id_mfg='$id_cost' group by id_so_det) mo on sod.id=mo.id_so_det
        where so.id_cost='$id_cost' and sod.cancel='N' group by sod.id"; 
		$stmt = mysql_query($q);		
		$id = array();
		$outp = '';
		$no = 0;
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"supplier":"'.rawurlencode($row['supplier']).'",';
			$outp .= '"no":"'. rawurlencode($no). '",';
			$outp .= '"styleno":"'. rawurlencode($row["styleno"]). '",';
			$outp .= '"kpno":"'. rawurlencode($row["kpno"]). '",';
			$outp .= '"so_no":"'. rawurlencode($row["so_no"]). '",';
			$outp .= '"buyerno":"'. rawurlencode($row["buyerno"]). '",';
			$outp .= '"dest":"'. rawurlencode($row["dest"]). '",';
			$outp .= '"color":"'. rawurlencode($row["color"]). '",';
			$outp .= '"dateinput":"'. rawurlencode($row["dateinput"]). '",';
			$outp .= '"dateoutput":"'. rawurlencode($row["dateoutput"]). '"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>





<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['id']);
print_r($List);
}
else{
	exit;
}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT 
				 id
				,invno
				,invdate
				,id_buyer
				,consignee
				,shipper
				,notify_party
				,country_of_origin
				,manufacture_address
				,vessel_name
				,port_of_loading
				,port_of_discharge
				,port_of_entrance
				,lc_no
				,lc_issue_by
				,hs_code
				,etd
				,eta
				,eta_lax
				,id_pterms
				,shipped_by
				,route
				,ship_to
				,nw
				,gw
				,measurement
				,container_no
				,seal_no
			FROM shp_pro_invoice_header WHERE id = '$id'";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"invno":"'.rawurlencode($row["invno"]). '",'; 	
			$outp .= '"invdate":"'.rawurlencode(date('d M Y',strtotime($row["invdate"]))). '",'; 	
			$outp .= '"id_buyer":"'.rawurlencode($row["id_buyer"]). '",'; 	
			$outp .= '"consignee":"'.rawurlencode($row["consignee"]). '",'; 	
			$outp .= '"shipper":"'.rawurlencode($row["shipper"]). '",'; 	
			$outp .= '"notify_party":"'.rawurlencode($row["notify_party"]). '",'; 	
			$outp .= '"country_of_origin":"'.rawurlencode($row["country_of_origin"]). '",'; 	
			$outp .= '"manufacture_address":"'.rawurlencode($row["manufacture_address"]). '",'; 	
			$outp .= '"vessel_name":"'.rawurlencode($row["vessel_name"]). '",'; 	
			$outp .= '"port_of_loading":"'.rawurlencode($row["port_of_loading"]). '",'; 	
			$outp .= '"port_of_discharge":"'.rawurlencode($row["port_of_discharge"]). '",'; 	
			$outp .= '"port_of_entrance":"'.rawurlencode($row["port_of_entrance"]). '",'; 	
			$outp .= '"lc_no":"'.rawurlencode($row["lc_no"]). '",'; 	
			$outp .= '"lc_issue_by":"'.rawurlencode($row["lc_issue_by"]). '",'; 	
			$outp .= '"hs_code":"'.rawurlencode($row["hs_code"]). '",'; 	
			$outp .= '"etd":"'.rawurlencode(date('d M Y',strtotime($row["etd"]))). '",'; 	
			$outp .= '"eta":"'.rawurlencode(date('d M Y',strtotime($row["eta"]))). '",'; 	
			$outp .= '"eta_lax":"'.rawurlencode(date('d M Y',strtotime($row["eta_lax"]))). '",'; 	
			$outp .= '"id_pterms":"'.rawurlencode($row["id_pterms"]). '",'; 	
			$outp .= '"shipped_by":"'.rawurlencode($row["shipped_by"]). '",'; 	
			$outp .= '"route":"'.rawurlencode($row["route"]). '",'; 	
			$outp .= '"ship_to":"'.rawurlencode($row["ship_to"]). '",'; 	
			$outp .= '"nw":"'.rawurlencode($row["nw"]). '",'; 	
			$outp .= '"gw":"'.rawurlencode($row["gw"]). '",'; 	
			$outp .= '"measurement":"'.rawurlencode($row["measurement"]). '",'; 	
			$outp .= '"container_no":"'.rawurlencode($row["container_no"]). '",'; 	
			$outp .= '"seal_no":"'.rawurlencode($row["seal_no"]). '"}'; 	
		}
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>





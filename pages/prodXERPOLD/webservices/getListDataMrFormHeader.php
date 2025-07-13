<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListWS = new getListWS();
$id_so = $data['id_so'];
$color = $data['color'];
$List = $getListWS->get($id_so,$color);

print_r($List);
//print_r($_POST);die();

class getListWS {
	public function get($id_so,$color){
		include __DIR__ .'/../../../include/conn.php';

		$q = "SELECT 
				SRN.id_number
				,SRN.id_mark_entry
				,SRN.id_cost
				,SRN.id_so
				,SRN.id_number_group
				,SRN.id_internal
				,SRN.color
				,SRN.cons
				,SRN.id_group_det
				,SRN.width_marker
				,SRN.length_marker
				,SRN.bagian
				,SO.buyerno
				,MS.Supplier nm_buyer
				,SRN.d_insert
				,ACT.kpno
				,SO.so_no
				,SO.id id_so
			FROM prod_spread_report_number SRN
			INNER JOIN
				act_costing ACT ON SRN.id_cost = ACT.id
			INNER JOIN so SO ON ACT.id = SO.id_cost
			INNER JOIN mastersupplier MS ON ACT.id_buyer = MS.Id_Supplier

			WHERE SRN.color = '{$color}' AND SRN.id_so = '{$id_so}'
			GROUP BY SRN.color
		";
/* echo $q;
die(); */
		$stmt = mysql_query($q);
		$id = array(); 
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if(ISSET($row['id_group_det'])){
				if($row['id_group_det'] > 0){
					$key_det = '1';
				}else{
					$key_det = '0';
				}
			}else{
				$key_det = '0';
			}
			
			/*
					,ACT.kpno
		,SO.so_no
		,SO.id id_so
			
			*/
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"buyer":"'.rawurlencode($row['nm_buyer']).'",';
			$outp .= '"buyerno":"'. rawurlencode($row["buyerno"]). '",';
			$outp .= '"color":"'. rawurlencode($row["color"]). '",';
			$outp .= '"id_group_det":"'. rawurlencode($row["id_group_det"]). '",';
			$outp .= '"cons":"'. rawurlencode($row["cons"]). '",';
			$outp .= '"id_mark_entry":"'. rawurlencode($row["id_mark_entry"]). '",';
			$outp .= '"panjang_marker":"'. rawurlencode($row["length_marker"]). '",';
			$outp .= '"lebar_marker":"'. rawurlencode($row["width_marker"]). '",';
			$outp .= '"bagian":"'. rawurlencode($row["bagian"]). '",';
			$outp .= '"id_cost":"'. rawurlencode($row["id_cost"]). '",';
			$outp .= '"id_number":"'. rawurlencode($row["id_number"]). '",';
			$outp .= '"kpno":"'. rawurlencode($row["kpno"]). '",';
			$outp .= '"so_no":"'. rawurlencode($row["so_no"]). '",';
			$outp .= '"bppbno_req":"'. rawurlencode($row["bppbno_int"]). '",';
			$outp .= '"id_so":"'. rawurlencode($row["id_so"]). '",';
			
			
			
			$outp .= '"d_insert":"'. rawurlencode(date('d M Y', strtotime($row["d_insert"]))). '"}';
		}
		

	$result = '{ "respon": "200", "status":"ok", "message":"1", "records":['.$outp.'] }';	
		
		
			//$result = '{ "respon": "200", "status":"ok", "message":"1", "records":['.$outp.'],"key_det" : "'.$key_det.'"   }';
		return $result;
	}
}
?>
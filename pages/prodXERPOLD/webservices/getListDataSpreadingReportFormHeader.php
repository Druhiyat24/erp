<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListWS = new getListWS();
$id_number = $data['id_number'];

$List = $getListWS->get($id_number);

print_r($List);
// print_r($id_url);die();

class getListWS {
	public function get($id_number){
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
				,SRN.efficiency
				,SRN.yield
				,SRN.bagian
				,SO.buyerno
				,MS.Supplier nm_buyer
				,SRN.d_insert
				,SRN_DET.bppbno_req
				,SRN_DET.bppbno_int
				,SRN_DET.id_item
				,SRN_DET.id_jo				
				,ACT.kpno
				,SO.so_no
				,SO.id id_so
				,SRN.id_panel
				-- ,med.id_group
			FROM prod_spread_report_number SRN
			INNER JOIN
				act_costing ACT ON SRN.id_cost = ACT.id
			INNER JOIN so SO ON ACT.id = SO.id_cost
			INNER JOIN mastersupplier MS ON ACT.id_buyer = MS.Id_Supplier
			LEFT JOIN(
				SELECT  
					srd.bppbno_req,
					srd.id_number,
					srd.id_item,
					srd.id_jo,
					pp.bppbno_int 
				FROM prod_spread_report_detail AS srd
				INNER JOIN bppb AS pp ON pp.bppbno_req = srd.bppbno_req
				WHERE srd.id_number='{$id_number}' GROUP BY srd.id_number
			)SRN_DET ON SRN_DET.id_number = SRN.id_number
			-- INNER JOIN prod_mark_entry_detail AS med ON med.id_mark = SRN.id_mark_entry 
			-- AND med.id_cost = SRN.id_cost AND med.color = SRN.color AND med.id_group_det = SRN.id_group_det
			WHERE SRN.id_number = '{$id_number}'
			-- GROUP BY SRN.id_group_det
		";

		// echo $q;die();
		$stmt = mysql_query($q);
		$id = array(); 
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if(ISSET($row['id_group_det'])){
				if($row['id_group_det'] > 0  && (ISSET($row['bppbno_req']) )){
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
			$outp .= '"efficiency":"'. rawurlencode($row["efficiency"]). '",';
			$outp .= '"yield":"'. rawurlencode($row["yield"]). '",';
			$outp .= '"bagian":"'. rawurlencode($row["bagian"]). '",';
			$outp .= '"id_cost":"'. rawurlencode($row["id_cost"]). '",';
			$outp .= '"id_number":"'. rawurlencode($row["id_number"]). '",';
			$outp .= '"kpno":"'. rawurlencode($row["kpno"]). '",';
			$outp .= '"so_no":"'. rawurlencode($row["so_no"]). '",';
			$outp .= '"bppbno_req":"'. rawurlencode($row["bppbno_req"]). '",';
			$outp .= '"bppbno_int":"'. rawurlencode($row["bppbno_int"]). '",';
			$outp .= '"id_jo":"'. rawurlencode($row["id_jo"]). '",';
			$outp .= '"id_item":"'. rawurlencode($row["id_item"]). '",';
			$outp .= '"id_panel":"'. rawurlencode($row["id_panel"]). '",';			
			$outp .= '"id_so":"'. rawurlencode($row["id_so"]). '",';
			// $outp .= '"id_group":"'. rawurlencode($row["id_group"]). '",';
			
			
			
			$outp .= '"d_insert":"'. rawurlencode(date('d M Y', strtotime($row["d_insert"]))). '"}';
		}
		

		
		
		
			$result = '{ "respon": "200", "status":"ok", "message":"1", "records":['.$outp.'],"key_det" : "'.$key_det.'"   }';
		return $result;
	}
}
?>
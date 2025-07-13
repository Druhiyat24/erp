<?php 
ini_set('max_execution_time', '6000'); //300 seconds = 5 minutes
include '../../forms/journal_interface.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Credentials: true" );
header( "Access-Control-Allow-Methods: POST");
file_get_contents();
		$data = $_POST;
if($data['code'] == '1' ){
	$getListData = new getListData();
		$array_invoice = explode(',',$data['idJournal']);
		$count = count($array_invoice);
		for($i=0;$i<$count;$i++){
			if(check_invoice_exist($array_invoice[$i]) == '1' ){
				update_inv_sales(trim(preg_replace('/\s+/', ' ', $array_invoice[$i])));
			}else if(check_invoice_exist($array_invoice[$i]) == '0'){
				$inv_detail = $getListData ->get(trim(preg_replace('/\s+/', ' ', $array_invoice[$i])));
				insert_inv_sales(trim(preg_replace('/\s+/', ' ', $array_invoice[$i])),$inv_detail[0]->discount,$inv_detail[0]->fg_discount);
			}else{
				$xx = "TIDAK ADA INVOICES";
			}
			//insert_bpb_gr($array_invoice[$i]);
		}
		
	//insert_bpb_ir_pajak($_POST['idJournal']);
	$result = '{ "status":"ok", "message_report":"1", "message":"1", "records":"" }';
}else{
	$result = '{ "status":"ok", "message_report":"0", "message":"1", "records":"" }';
}
print_r($result);

//else{
//	exit;
//}
class getListData {
 	public function get($inv){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT * FROM invoice_header WHERE invno = '$inv' LIMIT 1";
		$stmt = mysql_query($q);
		$outp = '';
		$jlh = 0;
		$array = array();
		while($row = mysql_fetch_array($stmt)){	
					$json = array(
					'discount' => $row['n_discount'],
					'fg_discount' => $row['fg_discount']
		
		);
		array_push($array,$json);
			//$outp .= '{"date_post":"'.rawurlencode($row['date_post']).'"}';	
		}
		//$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $array;
	}
/*	
	public function get_bpb($id_journal){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT reff_doc,reff_doc2 FROM fin_journal_d WHERE id_journal = '$id_journal' AND reff_doc !='' GROUP BY reff_doc";
		//echo "$q";
		$stmt = mysql_query($q);
		$outp = '';
		$bpb = array();
		$reff_doc = array();
		while($row = mysql_fetch_array($stmt)){	
			array_push($bpb,$row['reff_doc']);
			array_push($reff_doc,$row['reff_doc2']);
			//$outp .= '{"date_post":"'.rawurlencode($row['date_post']).'"}';	
		}
		$my_ref = array(
			$bpb,
			$reff_doc
		);
		//$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $my_ref;
	}	
	public function delete_journal($id_journal){
		include __DIR__ .'/../../../include/conn.php';
		$q = "DELETE FROM fin_journal_d WHERE id_journal = '$id_journal' ";
		//echo "$q";
		mysql_query($q);
		return "1";
	}	 */
}
?>




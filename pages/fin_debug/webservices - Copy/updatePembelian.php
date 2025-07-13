<?php 
ini_set('max_execution_time', '6000'); //300 seconds = 5 minutes
include '../../forms/journal_interface.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Credentials: true" );
header( "Access-Control-Allow-Methods: POST");
file_get_contents();
error_reporting(E_ALL);
		$data = $_POST;
//$data = (object)$_POST['data'];

if($data['code'] == '1' ){
	$getListData = new getListData();
		$array_bpb = explode(',',$data['idJournal']);
		$count = count($array_bpb);
		for($i=0;$i<$count;$i++){
			if(check_bpb_exist($array_bpb[$i]) == '1' ){
				update_bpb_gr(trim(preg_replace('/\s+/', ' ', $array_bpb[$i])));
			}else if(check_bpb_exist($array_bpb[$i]) == '0'){
				insert_bpb_gr(trim(preg_replace('/\s+/', ' ', $array_bpb[$i])));
			}else{
				$result = '{ "status":"ok", "message_report":"0", "message":"1", "records":"" }';
			}
			//insert_bpb_gr($array_bpb[$i]);
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
/* 	public function get($id_journal){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT count(id_journal)jlh FROM fin_journal_h WHERE id_journal = '$id_journal' AND type_journal = '14'";
		//echo "$q";
		$stmt = mysql_query($q);
		$outp = '';
		$jlh = 0;
		while($row = mysql_fetch_array($stmt)){	
			$jlh = $row['jlh'];
			//$outp .= '{"date_post":"'.rawurlencode($row['date_post']).'"}';	
		}
		//$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $jlh;
	}
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





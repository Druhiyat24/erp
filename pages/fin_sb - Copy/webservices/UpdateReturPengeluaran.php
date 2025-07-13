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
		$bppbno__ = $getListData->check_bppb($array_bpb[$i]);
/* 		echo $bppbno__;
		die(); */
			if($bppbno__ == 'XX' ){
				$result = '{ "status":"ok", "message_report":"0", "message":"1", "records":"" }';
				echo $result;
				die();
			}else{
				if(check_bppb_exist($bppbno__) == '1' ){
					update_bpb_gr_ret(trim(preg_replace('/\s+/', ' ', $bppbno__)));
				}else if(check_bppb_exist($bppbno__) == '0'){
					insert_bpb_gr_ret(trim(preg_replace('/\s+/', ' ', $bppbno__)));
				}else{
					$result = '{ "status":"ok", "message_report":"0", "message":"1", "records":"" }';
				}
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
 	public function check_bppb($bppb){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT bppbno,bppbno_int FROM bppb WHERE bppbno_int = '{$bppb}' LIMIT 1 ";
		$stmt = mysql_query($q);
		$outp = '';
		$jlh = 0;
		$bppbno = "XX";
		$_arr = array();
		while($row = mysql_fetch_array($stmt)){	
			$bppbno = $row['bppbno'];
			//$outp .= '{"date_post":"'.rawurlencode($row['date_post']).'"}';	
		}

		
		//$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $bppbno;
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





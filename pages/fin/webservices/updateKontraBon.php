<?php 

ini_set('max_execution_time', '6000'); //300 seconds = 5 minutes
include '../../forms/journal_interface.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Credentials: true" );
header( "Access-Control-Allow-Methods: POST");
//file_get_contents();
error_reporting(E_ALL);
		$data = $_POST;
//$data = (object)$_POST['data'];



if($data['code'] == '1' ){
	$getListData = new getListData();
	$arraykan_jurnal = explode(",",$_POST['idJournal']);
/*  print_r($arraykan_jurnal);
	die();  */
	$count = count($arraykan_jurnal);
	for($ix=0;$ix<$count;$ix++){
		$CheckAvailable = $getListData->get(TRIM($arraykan_jurnal[$ix]));		
		if($CheckAvailable > 0){
			$my_bpb =$getListData->get_bpb(TRIM($arraykan_jurnal[$ix]));
/* 			print_r($my_bpb);
			die(); */
/* 			print_r($arraykan_jurnal[$ix]);
			die();	 */			
			
			$row_not_bpb = $getListData->get_detail_not_bpb(TRIM($arraykan_jurnal[$ix]));
			$getListData->delete_journal(TRIM($arraykan_jurnal[$ix]));
			for($i = 0;$i<count($my_bpb[0]);$i++){
				//echo $my_bpb[0][$i]."<br/>";
				$is_retur = check_retur(TRIM($my_bpb[0][$i]));
/* 				echo $is_retur;
				die(); */
				if($is_retur == '1'){
					update_bpb_ir_ret($my_bpb[0][$i],TRIM($arraykan_jurnal[$ix]),$my_bpb[1][$i],$my_bpb[2][$i]);
				}else{
					update_bpb_ir($my_bpb[0][$i],TRIM($arraykan_jurnal[$ix]),$my_bpb[1][$i],$my_bpb[2][$i]);
				}
			}
			insert_bpb_ir_pajak(TRIM($arraykan_jurnal[$ix]));
			if($row_not_bpb['key']  =='1'){
				//last row id
				$last_row_id = $getListData->last_row_id(TRIM($arraykan_jurnal[$ix]));
				$getListData->insert_detail_other(TRIM($arraykan_jurnal[$ix]),$row_not_bpb['data'],$last_row_id);
			}		
			$result = '{ "status":"ok", "message_report":"1", "message":"1", "records":"" }';
		}else{
			$result = '{ "status":"ok", "message_report":"0", "message":"1", "records":"" }';
		}
		print_r($result);
	}
}
//else{
//	exit;
//}
class getListData {
	public function get($id_journal){
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
		$q = "SELECT 	 A.reff_doc
		,B.id_journal reff_doc2
		,ifnull(MAX(A.n_pph),0)n_pph 
		FROM fin_journal_d A 
			INNER JOIN(SELECT id_journal,reff_doc FROM fin_journal_h WHERE type_journal IN(2,17,19))B ON A.reff_doc = B.reff_doc
		WHERE A.id_journal = '$id_journal' AND A.reff_doc IS NOT NULL AND A.reff_doc !='' AND A.credit > 0
		AND A.id_journal NOT IN('15207') GROUP BY reff_doc";
/* 		echo "$q";
		die(); */ 
		$stmt = mysql_query($q);
		$outp = '';
		$bpb = array();
		$reff_doc = array();
		$pph = array();
		while($row = mysql_fetch_array($stmt)){	
			array_push($bpb,$row['reff_doc']);
			array_push($reff_doc,$row['reff_doc2']);
			array_push($pph,$row['n_pph']);
			//$outp .= '{"date_post":"'.rawurlencode($row['date_post']).'"}';	
		}
		$my_ref = array(
			$bpb,
			$reff_doc,
			$pph
		);
		//$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $my_ref;
	}	
	
	public function get_detail_not_bpb($id_journal){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT * FROM 
				fin_journal_d 
				WHERE id_journal = '$id_journal' 
				AND reff_doc IS NULL 
				AND id_journal NOT IN ('15204','15207')";
/* 		echo "$q";
		die(); */
		$stmt = mysql_query($q);
		$outp = '';
		$detail = array();
		if(mysql_num_rows($stmt) > 0){
			$detail =array(
				'key' => '1',
				'data' => $stmt
			
			);
			
		}else{
			$detail =array(
				'key' => '0',
				'data' => 'NULL'
			
			);
			
		}

		return $detail;
	}	
	
	public function last_row_id($id_journal){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT row_id FROM 
				fin_journal_d 
				WHERE id_journal = '$id_journal' 
				ORDER BY row_id DESC LIMIT 1	
					";
	//	echo "$q";
		$stmt = mysql_query($q);
		$outp = '';
		$row_id = 99;
		$detail = array();
		while($row = mysql_fetch_array($stmt)){	
			$row_id = $row['row_id'];
		}
		return $row_id;
	}		
	
	
	public function delete_journal($id_journal){
		include __DIR__ .'/../../../include/conn.php';
		$q = "DELETE FROM fin_journal_d WHERE id_journal = '$id_journal' ";
		//echo "$q";
		mysql_query($q);
		return "1";
	}	
	public function insert_detail_other($id_journal,$d,$last_id_nya){

		include __DIR__ .'/../../../include/conn.php';
		while($row = mysql_fetch_array($d)){	
		$last_id_nya++;
        $q = "
            INSERT INTO fin_journal_d
              (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
              ,id_costcenter, nm_costcenter, id_cost_category, nm_cost_category
              ,id_cost_dept, nm_cost_dept, id_cost_sub_dept, nm_cost_sub_dept
              ,nm_ws
              ,description, dateadd, useradd,n_rate)
            VALUES 
              ('{$row['id_journal']}', '{$last_id_nya}', '{$row['id_coa']}', '{$row['nm_coa']}' ,'{$row['curr']}', '{$row['debit']}', '{$row['credit']}'
              ,'{$row['id_costcenter']}', '{$row['nm_costcenter']}', '{$row['id_cost_category']}', '{$row['nm_cost_category']}'
              ,'{$row['id_cost_dept']}','{$row['nm_cost_dept']}', '{$row['id_cost_sub_dept']}', '{$row['nm_cost_sub_dept']}'
              ,'{$row['nm_ws']}'
              ,'{$row['description']}','{$row['dateadd']}', '{$row['useradd']}','{$row['n_rate']}'
              )
            ;
        ";	
		mysql_query($q);
		}
	return 1;
	}
}




?>





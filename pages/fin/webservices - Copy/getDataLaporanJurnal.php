<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', '60000');
error_reporting(E_ALL);
$data = $_GET;
//print_r($data);
//$data = (object)$_POST['data'];
$getListData = new getListData();
$d_from = $getListData->d_from($data['from']);
$d_to = $getListData->d_to($data['to']);
$t_journal = $getListData->tj($data['type_journal']);
$stts = $data['fg_post'];
$List = $getListData->detailLaporanJurnal($d_from,$d_to,$stts,$t_journal);
print_r($List);
class getListData {
	public function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	public function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
		return $d_to;
	}
	public function tj($t_journal){
		$type_jour = $t_journal;
		return $type_jour;
	}

	function CompileQuery($query,$mode){
		
		include __DIR__ .'/../../../include/conn.php';
		$stmt = mysqli_query($conn_li,$query);	
		if(mysqli_error($conn_li)){
			$result = mysqli_error($conn_li)."__ERRTRUE";
			return $result;
		}	
		else{
			if($mode == "CRUD"){
				print_r($result);
				$result = '{ "status":"ok", "message":"1"}';
				return $result;
			}
			else{
				
				if(mysqli_num_rows($stmt) == '0' ){
					$result = '{ "status":"ok", "message":"2"}';
					return '0';
				}
				else{
					return $stmt;
				}
			}
		} 
	}	
	public function detailLaporanJurnal($d_from,$d_to,$stts,$t_journal){
		if($stts == "" ){
			$where_status = "AND fjh.fg_post IN ('0','2')";
		}else{
			$where_status = "AND fjh.fg_post='$stts'";
			
		}
		$q = "SELECT fjh.type_journal
		, fjh.period 
		, fjd.row_id
		, fjh.id_journal
		, fjh.fg_post
		, fjh.date_journal
		, fjd.id_coa
		, fjd.nm_coa
		,IF(fjd.curr='USD' OR fjd.curr='' OR fjd.curr IS NULL,'USD','IDR')curr
		,IF(fjd.curr='USD' OR fjd.curr='' OR fjd.curr IS NULL,debit*ifnull(mr.rate,1),debit)debit_eqv
		,IF(fjd.curr='USD' OR fjd.curr='' OR fjd.curr IS NULL,credit*ifnull(mr.rate,1),credit)credit_eqv	
		
		, fjd.debit AS debit
		, fjd.credit AS credit
		, fjd.description
		, fjh.remark
		, b.id_costcenter
		, b.nm_costcenter
		, mr.rate
		, if(fjh.fg_post ='2','Posted','Parked')status
		FROM fin_journal_h fjh 
		LEFT JOIN fin_journal_d fjd ON fjh.id_journal=fjd.id_journal
		LEFT JOIN fin_journalheaderdetail fj ON fj.v_idjournal=fjh.id_journal
		LEFT JOIN mastercostcategory a ON a.id_cost_category=fjd.id_cost_category
		LEFT JOIN mastercostcenter b ON b.id_cost_category=a.id_cost_category
		LEFT JOIN mastercostdept c ON c.id_cost_dept=b.id_cost_dept
		LEFT JOIN mastercostsubdept d ON d.id_cost_sub_dept=b.id_cost_sub_dept
		LEFT JOIN (SELECT v_codecurr,rate,tanggal FROM masterrate WHERE v_codecurr = 'PAJAK') mr ON mr.tanggal = fjh.date_journal
		WHERE 1=1 $where_status AND fjh.type_journal='$t_journal' AND (fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to') AND fjd.id_coa!='' GROUP BY fjd.id_journal, fjd.row_id ORDER BY fjh.period DESC, fjh.date_journal DESC, fjh.id_journal DESC, fjd.debit DESC, fjd.credit DESC, fjd.id_coa DESC, fjd.nm_coa DESC
		";
		// echo $q; 
		$MyList = $this->CompileQuery($q,'SELECT');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
			else{
				$outp = '';
				while($row = mysqli_fetch_array($MyList)){
					if ($outp != "") {$outp .= ",";}
					$outp .= '{"period":"'.($row['period']).'",'; 
					$outp .= '"id_journal":"'.($row['id_journal']).'",'; 
					$outp .= '"date_journal":"'.($row['date_journal']).'",'; 
					$outp .= '"id_coa":"'.($row['id_coa']).'",'; 
					$outp .= '"nm_coa":"'.($row['nm_coa']).'",'; 
					$outp .= '"status":"'.($row['status']).'",';
					$outp .= '"description":"'.(rawurlencode($row['description'])).'",'; 
					$outp .= '"curr":"'.($row['curr']).'",'; 
					$outp .= '"debit":"'.(number_format((float)$row['debit'], 2, '.', ',')).'",'; 
					$outp .= '"curr":"'.($row['curr']).'",'; 
					$outp .= '"credit":"'.(number_format((float)$row['credit'], 2, '.', ',')).'",'; 
					$outp .= '"debit_eqv":"'.(number_format((float)$row['debit_eqv'], 2, '.', ',')).'",'; 			
					$outp .= '"credit_eqv":"'.(number_format((float)$row['credit_eqv'], 2, '.', ',')).'",'; 							
					$outp .= '"remark":"'.($row['remark']).'",'; 
					$outp .= '"id_costcenter":"'.($row['id_costcenter']).'",'; 
					$outp .= '"nm_costcenter":"'.($row['nm_costcenter']).'"}'; 
				} 		
			}		
		}
		$result = '{"data":['.$outp.']}';	
		return $result;
	}

}
?>

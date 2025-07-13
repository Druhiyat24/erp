<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class GetListData {
	protected function getOpeningBalance($from,$to,$account,$pencarian){
		$accno = '';
		if($pencarian == 'P'){
}
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT SUM(Z.mydebit) mydebit, SUM(Z.mycredit)mycredit,Z.d_insert FROM
				(SELECT Y.id_journal
				,sum(Y.debit)mydebit,sum(Y.credit)mycredit
				,date(Y.dateadd) d_insert
				FROM fin_journal_d Y GROUP BY Y.id_journal ) Z WHERE Z.d_insert < '2019-07-01'
			";	
			$stmt = mysql_query($q);
			$row=mysql_fetch_array($stmt);
			$openingBalancesatutiga = $row['mydebit'] - $row['mycredit']; 
			return $openingBalancesatutiga;
	}
	protected function getSumByIdCoa($from,$to,$account,$pencarian){
		
		$where = '';
	
		if($pencarian == 'P'){
			$where = " AND A.id_journal = '$account'";
		}
		//BEFOORE PERIODE

		$from = str_replace('/', '-', $from );
		$from = date("Y-m-d", strtotime($from));
		//echo "DATE :$from";
		
				
		$to = str_replace('/', '-', $to );
		$to = date("Y-m-d", strtotime($to));	
		
		
		$q = "
SELECT 		
			 Y.id_journal
			,Y.sumdebit
			,Y.sumcredit
			,Y.id_coa
			,Y.segment
			,Y.openingbalance
			,A.period 
			,A.id_journal
			,A.num_journal
			,A.date_post
			,A.type_journal
			,STR_TO_DATE(A.myperiod,'%d/%m/%Y')myperiod FROM(
SELECT period,id_journal,num_journal
		,date_post
		,CONCAT('01/',period) myperiod
,type_journal		
 FROM fin_journal_h ) A INNER JOIN (
SELECT Z.id_journal,Z.sumdebit
	,Z.sumcredit,Z.id_coa
	,Z.segment 
	,IF(Z.segment >= 1 AND Z.segment <= 3  ,ifnull(Z.sumdebit - Z.sumcredit,0),  ifnull(Z.sumcredit,0) - ifnull(Z.sumdebit,0) ) openingbalance
	FROM (
SELECT id_journal,
	SUM(debit) sumdebit
	,SUM(credit)sumcredit, id_coa
	,SUBSTRING(id_coa,'1','1') segment
	FROM fin_journal_d GROUP BY id_journal,segment) Z ) Y ON A.id_journal = Y.id_journal
	WHERE STR_TO_DATE(A.myperiod,'%d/%m/%Y') < '$from' $where
			";	
		//echo "$q";
		/*
			 Y.id_journal
			,Y.sumdebit
			,Y.sumcredit
			,Y.id_coa
			,Y.segment
			,Y.openingbalance
			,A.period 
			,A.id_journal
			,A.num_journal
			,A.date_post
			,STR_TO_DATE(A.myperiod,'%d/%m/%Y')myperiod		
		
		
		*/
			$stmt          = mysql_query($q);
			$id_journal    = array();
			$sumdebit      = array();
			$sumcredit     = array();
			$segment       = array();
			$id_coa        = array();
			$openingbalance= array();
			$num_journal   = array();
			$date_post     = array();
			$myperiod      = array();
			$type_journal      = array();
			while($row = mysql_fetch_array($stmt)){
				array_push($id_journal,$row['id_journal']);
				array_push($sumdebit,$row['sumdebit']);
				array_push($sumcredit,$row['sumcredit']);
				array_push($openingbalance,$row['openingbalance']);
				array_push($segment, $row['segment']);
				array_push($num_journal,$row['num_journal']);
				array_push($myperiod,$row['myperiod']);
				array_push($myperiod,$row['myperiod']);
				array_push($date_post,$row['date_post']);
				array_push($type_journal,$row['type_journal']);
			}
			$records[]                = array();
			$records['id_journal']    = $id_journal;
			$records['sumdebit']      = $sumdebit;
			$records['sumcredit']     = $sumcredit;
			$records['openingbalance']= $openingbalance;
			$records['segment']       = $segment;
			$records['num_journal']   = $num_journal;
			$records['myperiod']      = $opening;
			$records['date_post']     = $date_post;
			$records['type_journal']  = $type_journal;
			return $records;
			//print_r($records);
	}
	public function data($from,$to,$account,$pencarian){
		include __DIR__ .'/../../../include/conn.php';
		$sumbyidcoa = $this-> getSumByIdCoa($from,$to,$account,$pencarian);
		//print_r($sumbyidcoa);
		//get SUMMARY BY ID COA
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($sumbyidcoa).'}';
		return $result;
	}
}


	//print_r($data);
	
//		

$data = (object)$_POST['data'];
$code = $_POST['code'];
//print_r($data);
if($code == '1'){
	$pencarian = '';
	if(ISSET($data->pencarian)){
		$pencarian = $data->pencarian;
		
	}
	$GetListData = new GetListData();
	$List = $GetListData->data($data->datefrom,$data->dateto,$data->numberjournal,$pencarian);
	print_r($List);	
}
else{
	exit;
	
}


	//$from = "2019-07-01";
	//$to 
	//$accountNo 
	///$GetListData = new GetListData();
	//$List = $GetListData->data($period);
	//$data = $List ;
	//print_r($List);
	//echo $data;
?>





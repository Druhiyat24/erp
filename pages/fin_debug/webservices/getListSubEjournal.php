<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 class GetListData {

	public function getLisData($balance,$id,$from,$to){
		//echo"$from";
		include __DIR__ .'/../../../include/conn.php';
		$from = str_replace('/', '-', $from );
		$from = date("Y-m-d", strtotime($from));
		$to = str_replace('/', '-', $to );
		$to = date("Y-m-d", strtotime($to));
		$q = "SELECT 		
			 Y.id_journal
			,Y.debit
			,Y.credit
			,Y.id_coa
			,Y.segment
			,Y.nm_coa
			,Y.description
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
SELECT Z.id_journal,Z.debit
	,Z.credit,Z.id_coa
	,Z.nm_coa
	,Z.description
	,Z.segment 
	FROM (
SELECT id_journal,
		id_coa
		,nm_coa
		,debit
		,credit
		,description
		,SUBSTRING(id_coa,'1','1') segment
	FROM fin_journal_d ) Z ) Y ON A.id_journal = Y.id_journal WHERE STR_TO_DATE(A.myperiod,'%d/%m/%Y') >= '$from' AND STR_TO_DATE(A.myperiod,'%d/%m/%Y') <= '$to' and A.num_journal = '$id' ORDER BY Y.id_coa
			";	
			//echo "$q";
			$tmpbalance  = 0;
			$stmt        = mysql_query($q)  or die('Error: ' . mysql_error());
			//$row         = mysql_fetch_array($stmt);
			//print_r($row);
			$nm_coa      = array();
			$saldoakhir  = array();
			$description = array();
			$debit       = array();
			$credit      = array();
			$segment     = array();
			$id_coa      = array();
			$id_journal  = array();
			$date_post   = array();
			$type_journal= array();
			$num_journal = array();
			/*
			 BB.id_journal
			,BB.date_post
			,BB.type_journal
			,BB.num_journal			
			*/
			$count = 0;
			//$count = count(mysql_fetch_array($stmt));
			//$row = mysql_fetch_array($stmt);
			
			//"JUMLAH:".count($row);
			//print_r($row);\
			$tmpsaldoakhir = $balance;
			if(!ISSET($count) || EMPTY($count) ){
				$count = 0;
			}
			$x = 0;
	
			while($row = mysql_fetch_array($stmt)){
				$x++;
				
				//echo "123";
					if($row['segment'] >= 1 AND $row['segment'] <= 3){
						$tmpsaldoakhir = $tmpsaldoakhir + $row['debit'];
						$tmpsaldoakhir = $tmpsaldoakhir - $row['credit'];
						array_push($saldoakhir,$tmpsaldoakhir);
						
					}
					if($row['segment'] > 3){
						$tmpsaldoakhir = $tmpsaldoakhir + $row['credit'];
						$tmpsaldoakhir = $tmpsaldoakhir - $row['debit'];
						array_push($saldoakhir,$tmpsaldoakhir);
						
					}					
				array_push($nm_coa,$row['nm_coa']);
				array_push($segment, $row['segment']);
				array_push($id_coa,$row['id_coa']);
				array_push($debit,$row['debit']);
				array_push($credit,$row['credit']);
				array_push($id_journal,$row['id_journal']);
				array_push($date_post,$row['date_post']);
				array_push($type_journal, $row['type_journal']);
				array_push($num_journal,$row['num_journal']);	
				array_push($description,$row['description']);		
				
			}
			$records[] = array();

		


			$records['nm_coa']      = $nm_coa;
			$records['segment']     = $segment;
			$records['id_coa']      = $id_coa;
			
			
			
			$records['id_journal']  = $id_journal;
			$records['date_post']   = $date_post;
			$records['type_journal']= $type_journal;   
			$records['num_journal'] = $num_journal;   
			$records['description'] = $description;   
			$records['debit'] = $debit;   
			$records['credit'] = $credit;   
			$records['saldoakhir'] = $saldoakhir;   
		
		if($x > 0 ){
	
			if(!ISSET($records['id_journal'][0])){
				$message = 2;
				//echo "MASUK:".$records['id_journal'][0];
			}else{
				
				$message = 1;
			}
			
		}
		else{
			$message = 2;
			
			
		}
		$result = '{ "status":"ok", "message":"'.$message.'","totaldata":'.json_encode($x).', "records":'.json_encode($records).'}';
		return $result;
	}
}


	//print_r($data);
	
		$data = (object)($_POST);
	if($data->code =='1'){
	$GetListData = new GetListData();
//$List = $GetListData->getLisData($data->id,$data->balance);
$List = $GetListData->getLisData($data->balance,$data->id,$data->from,$data->to);

//$data = $List ;
print_r($List);
//echo $data;	
		
	}
	else{
		exit;
	}

?>





<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 class GetListData {

	public function getLisData($balance,$id,$from,$to){
		include __DIR__ .'/../../../include/conn.php';
		$from = str_replace('/', '-', $from );
		$from = date("Y-m-d", strtotime($from));
		$to = str_replace('/', '-', $to );
		$to = date("Y-m-d", strtotime($to));
		$q = "
				SELECT BB.id_journal
		,date(BB.date_post) date_post
		,BB.type_journal
		,BB.num_journal
		,AB.post_to,AB.nm_coa
		,ABC.id_coa
		,ABC.description
		,ifnull(mydebit,0)mysumdebit,ifnull(mycredit ,0)mysumcredit
		,SUBSTRING(AB.post_to,'1','1') segment
	 FROM (
		SELECT A.post_to,B.nm_coa,A.id_coa FROM mastercoa A
		INNER JOIN(
		SELECT id_coa,nm_coa,post_to FROM mastercoa
		) B ON A.post_to = B.id_coa
		GROUP BY A.post_to ) AB LEFT JOIN (
		SELECT P.id_coa
				,P.id_journal
				,P.nm_coa
				,P.dateadd 
				,P.mydebit
				,P.mycredit
				,P.description
		FROM (
		SELECT O.id_coa
				,O.id_journal
				,O.nm_coa
				,O.dateadd 
				,SUM(O.debit) mydebit
				,SUM(O.credit) mycredit
				,O.description
				FROM (
		SELECT id_coa
				,id_journal
				,nm_coa
				,dateadd
				,debit
				,credit 
				,description 
				FROM fin_journal_d WHERE date(dateadd) >= '$from' AND DATE(dateadd) <= '$to') O GROUP BY O.id_journal,O.id_coa) P ) ABC ON AB.id_coa = ABC.id_coa
				LEFT JOIN (SELECT id_journal,date_post,type_journal,
				num_journal
				 FROM fin_journal_h)BB ON ABC.id_journal = BB.id_journal
				WHERE AB.post_to ='$id'
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
						$tmpsaldoakhir = $tmpsaldoakhir + $row['mysumdebit'];
						$tmpsaldoakhir = $tmpsaldoakhir - $row['mysumcredit'];
						array_push($saldoakhir,$tmpsaldoakhir);
						
					}
					if($row['segment'] > 3){
						$tmpsaldoakhir = $tmpsaldoakhir + $row['mysumcredit'];
						$tmpsaldoakhir = $tmpsaldoakhir - $row['mysumdebit'];
						array_push($saldoakhir,$tmpsaldoakhir);
						
					}					
				array_push($nm_coa,$row['nm_coa']);
				array_push($segment, $row['segment']);
				array_push($id_coa,$row['id_coa']);
				array_push($debit,$row['mysumdebit']);
				array_push($credit,$row['mysumcredit']);
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
			$message = 1;
			
			
		}
		$result = '{ "status":"ok", "message":"'.$message.'","totaldata":'.json_encode($x).', "records":'.json_encode($records).'}';
		return $result;
	}
}


	//print_r($data);
	
		$data = (object)($_POST);
//print_r($_POST);
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





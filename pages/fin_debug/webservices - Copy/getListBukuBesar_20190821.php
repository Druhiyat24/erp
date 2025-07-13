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
		//echo "DATE :$from";
		$where = '';
	
		if($pencarian == 'P'){
			
			$where = "HAVING AB.post_to = '$account'";
		}
		//BEFOORE PERIODE
		$from = "01/".$from;
		$explode = explode("/",$from);
		$from = $explode[2]."-".$explode[1]."-".$explode[0];
	
		//echo "DATE :$from";
		
		$to = "31/".$to;	

		$explode = explode("/",$to);
		$to = $explode[2]."-".$explode[1]."-".$explode[0];		
		;	
		
		
		$q = "SELECT AB.post_to,AB.nm_coa,ABC.id_coa, ifnull(sum(ABC.mydebit),0)mysumdebit,ifnull(sum(ABC.mycredit),0)mysumcredit
			, SUBSTRING(AB.post_to,'1','1') segment
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
		FROM (
		SELECT O.id_coa
				,O.id_journal
				,O.nm_coa
				,O.dateadd 
				,SUM(O.debit) mydebit
				,SUM(O.credit) mycredit
				FROM (
		SELECT  U.id_coa
				,U.id_journal
				,U.nm_coa
				,U.dateadd
				,U.debit
				,U.credit 
				FROM fin_journal_d U
				LEFT JOIN(SELECT id_journal,date(date_post)date_post FROM fin_journal_h) UU ON U.id_journal = UU.id_journal
				WHERE UU.date_post < '$from') O GROUP BY O.id_journal,id_coa) P ) ABC ON AB.id_coa = ABC.id_coa GROUP BY AB.post_to  $where
			";	
		//echo "$q";
			$stmt = mysql_query($q);
			$post_to = array();
			$nm_coa = array();
			$openingbalance = array();
			$segment = array();
			$id_coa = array();
			while($row = mysql_fetch_array($stmt)){
				array_push($post_to, $row['post_to']);
				array_push($nm_coa,$row['nm_coa']);
				if($row['segment'] > 0 && $row['segment'] < 4 ){
					$opening = $row['mysumdebit'] - $row['mysumcredit'];
				}
				else{
					$opening = $row['mysumcredit'] - $row['mysumdebit'];		
				}
				array_push($openingbalance,$opening);
				array_push($segment, $row['segment']);
				array_push($id_coa,$row['id_coa']);
			}
			$records[] = array();
			$records['post_to']       = $post_to;
			$records['nm_coa']        = $nm_coa;
			$records['openingbalance']= $openingbalance;
			$records['segment']       = $segment;
			$records['id_coa']        = $id_coa;
			$records['opening']        = $opening;
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
if($code == '1'){
	$pencarian = '';
	if(ISSET($data->pencarian)){
		$pencarian = $data->pencarian;
		
	}
	$GetListData = new GetListData();
	$List = $GetListData->data($data->datefrom,$data->dateto,$data->accountno,$pencarian);
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





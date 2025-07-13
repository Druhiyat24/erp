<?php 

$config['compress_output'] = FALSE;
ini_set("zlib.output_compression", "4096");
class GetListData {
	protected function getListSupplier($from,$to,$pencarian){
		$x[] = array();
		$x['data']['id_supplier'] = array();
		$x['data']['supplier'] = array();
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT Supplier,Id_Supplier FROM mastersupplier ";
			$stmt= mysql_query($q)  or die('Error: ' . mysql_error());
		while($row = mysql_fetch_array($stmt)){
			array_push($x['data']['id_supplier'],$row['Id_Supplier']);
			
			array_push($x['data']['supplier'],$row['Supplier']);
		}

		return $x;
	}






	public function aktual_total_awal($codeJournal,$idsupplier,$from,$to,$pencarian){

		include __DIR__ .'/../../../include/conn.php';
		$debit = 0;
		/*SJ = (jurnal penjualan) */ $debit_penjualan= 0;
		/*RV = (jurnal pembelian) */ $debit_pembelian= 0;
	
		$total_awal    = 0;
		$total_row     = 0;
		$bulan_berjalan= 0;		
		$period = '';
		$different_date = '';
		$posisi_S = '';
		$posisi_D = '';
		$posisi_T = '';
		$posisi_E = '';
		$posisi_L = '';
		$posisi_E = '';
		$count = count($codeJournal);
		for($x=0;$x<$count;$x++){
			//print_r($codeJournal);
			if($x =='1'){
				$different_date = ",FLOOR((datediff(ZA.period,'$period')/30))different";
			}
			
		$q 	= "SELECT Z.id,Z.invno,Z.id_buyer
			,ZA.id_journal,ifnull(ZA.debit,0) debit,ZA.credit
			,ZA.period,ZA.codejournal,ZA.reff_doc
			$different_date
			
				FROM invoice_header Z LEFT JOIN (
			SELECT Y.id_journal,ifnull(SUM(Y.debit),0) debit,SUM(Y.credit) credit
			,W.period,W.codejournal,W.reff_doc
			FROM fin_journal_d Y
			INNER JOIN (
			SELECT X.period,X.codejournal,X.reff_doc,X.id_journal FROM (
			SELECT 
				STR_TO_DATE(CONCAT('01/',A.period), '%d/%m/%Y') period
				,SUBSTRING(A.id_journal,5,2) codejournal
				,A.reff_doc
				,A.id_journal
				 FROM fin_journal_h A ) X WHERE X.codejournal ='".$codeJournal[$x]."' ) W ON W.id_journal = Y.id_journal
				 GROUP BY W.reff_doc
				 ) ZA ON ZA.reff_doc = Z.invno		 
				WHERE Z.id_buyer = '$idsupplier' AND ZA.period>='$from' AND ZA.period>='$to
				ORDER BY ZA.period DESC
				';
			";
//ECHO $q;
		$stmt       	= mysql_query($q)  or die('Error: ' . mysql_error());
		while($row = mysql_fetch_array($stmt)){
			
			$debit = $row['debit'];
			//$periods = $row['period'];
if($codeJournal[$x] == "RV" ){
	
	
		if($row['different'] == '1' ){
			$posisi_S = $row['different'];
			
		}
		else if($row['different'] == '2' ){
			$posisi_D =$row['different'];
			
		}
		else if($row['different'] == '3' ){
			$posisi_T = $row['different'];
			
		}
		else if($row['different'] == '4' ){
			$posisi_R = $row['different'];
			
		}
		else if($row['different'] == '5' ){
			$posisi_L = $row['different'];
			
		}
		else if($row['different'] >= 6 ){
			
			$posisi_E = $row['different'];
		}			
	
	
	
	
}
			
		}			
		if($codeJournal[$x] == "SJ"){
			$debit_penjualan = $debit;
			//$period =  $periods;
			//echo "P:".$period;
		}	
		if($codeJournal[$x] == "RV"){
			$debit_pemnerimaan = $debit;

							
			
			
		}
			
			
		//menentukan posisirow;
		

			
			
}		

		
		
		$total_awal    = $debit_penjualan;
		$bulan_berjalan= $debit_penjualan - $debit_pemnerimaan;
		$array[] = array();;
		$array['posisi'] = array();
		$array['total_awal'] = $total_awal;
		$array['total_dua'] = $bulan_berjalan;  
		$array['total_tiga'] = $debit_pemnerimaan; 
        $array['debit_pemnerimaan'] = $debit_pemnerimaan;     
		$array['bulan_berjalan'] = $bulan_berjalan;	
		if($posisi_S !=''){
			$array['posisi']['posisi_S'] = $debit_pemnerimaan;
		}
		if($posisi_D !=''){
			$array['posisi']['posisi_D'] = $debit_pemnerimaan;
		}
		if($posisi_T !=''){
			$array['posisi']['posisi_T'] = $debit_pemnerimaan;
		}
		if($posisi_E !=''){
			$array['posisi']['posisi_E'] = $debit_pemnerimaan;
		}
		if($posisi_L !=''){
			$array['posisi']['posisi_L'] = $debit_pemnerimaan;
		}
		if($posisi_E !=''){
			$array['posisi']['posisi_E'] = $debit_pemnerimaan;
		}		

		//print_r($array);
		return $array;     

	}

	

	public function data($from,$to,$pencarian){
		/* Step 1Mencari List Supplier */
		

		$basedon[] =array();
		$basedon['idsupplier']              = array();	
		$basedon['namasupplier']            = array();
		$basedon['aktual'][]                = array();
		$basedon['aktual']['total']         = array();
		$basedon['aktual']['total2']         = array();
		$basedon['aktual']['total3']         = array();
		$basedon['aktual']['m1']            = array();
		$basedon['aktual']['m2']            = array();
		$basedon['aktual']['m3']            = array();
		$basedon['aktual']['m4']            = array();
		$basedon['aktual']['m5']            = array(); 
		$basedon['aktual']['m6']           = array();
		$basedon['aktual']['bulan_berjalan']= array();
		$basedon['aktual']['ardays']        = array();
		$code_journal = array('SJ','RV');
		//Induk Array
		$ListSupplier = $this->getListSupplier($from,$to,$pencarian);
		$count = count($ListSupplier['data']['id_supplier']);
		if(ISSET($count) && $count > 0 ){
	
	
	
	
			for($x=0;$x<$count;$x++){
				$totalRowBySupplier = $this->aktual_total_awal($code_journal,$ListSupplier['data']['id_supplier'][$x],$from,$to,$pencarian);
					array_push($basedon['idsupplier'],$ListSupplier['data']['id_supplier'][$x]);
					array_push($basedon['namasupplier'],$ListSupplier['data']['supplier'][$x]);
					array_push($basedon['aktual']['total'],$totalRowBySupplier['total_awal']);
					array_push($basedon['aktual']['total2'],$totalRowBySupplier['total_dua']);
					array_push($basedon['aktual']['total3'],$totalRowBySupplier['total_tiga']);		
					array_push($basedon['aktual']['m1'],$totalRowBySupplier['posisi']['posisi_S']);	
					array_push($basedon['aktual']['m2'],$totalRowBySupplier['posisi']['posisi_D']);	
					array_push($basedon['aktual']['m3'],$totalRowBySupplier['posisi']['posisi_T']);	
					array_push($basedon['aktual']['m4'],$totalRowBySupplier['posisi']['posisi_E']);	
					array_push($basedon['aktual']['m5'],$totalRowBySupplier['posisi']['posisi_L']);	
					array_push($basedon['aktual']['m6'],$totalRowBySupplier['posisi']['posisi_E']);		
					array_push($basedon['aktual']['bulan_berjalan'],$totalRowBySupplier['bulan_berjalan']);						
			}
			$records[] = array();
			$records = $basedon;
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
			return $result;
			
		}
			//for($x=0;$x=)
		//Induk Array
		/*Step 2 Mencari Nilai Aktual Masing Masing supplier*/

		/*Step 2 Mencari Nilai Aktual Masing Masing supplier*/
	}
}


	//print_r($data);
	
//		

$data = (object)$_POST['data'];
$code = $_POST['code'];
//print_r($code);
$code = '1';
$fromdate = $data->datefrom;
$todate = $data->dateto;


		$from = str_replace('/', '-', $fromdate );
		$fromdate = date("Y-m-d", strtotime($from));
		//echo "DATE :$from";
		
				
		$to = str_replace('/', '-', $todate );
		$todate = date("Y-m-d", strtotime($to));	

if($code == '1'){
	
	$pencarian = '';
	if(ISSET($data->pencarian)){
		$pencarian = $data->pencarian;
	}
	
	$GetListData = new GetListData();
	$List = $GetListData->data($fromdate,$todate,$pencarian);
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





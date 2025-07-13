<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 class GetListData {

	public function getLisData($balance,$id,$from,$to){
		include __DIR__ .'/../../../include/conn.php';
		$explode = explode("/",$from);
		$from = $explode[1]."-".$explode[0]."-01";
		$explode = explode("/",$to);
		$to = $explode[1]."-".$explode[0]."-01";	
		$tos = date('Y-m-d', strtotime('+1 month', strtotime($to))); //operasi penjumlahan tanggal sebanyak 6 hari
				//echo "TO:$tos";
		$q = "	SELECT Master.* FROM(
	SELECT jd.id_journal
		,jd.row_id
		,jd.nm_coa
		,jd.description
        ,jd.id_coa
        ,ifnull(jd.debit,0) debit
        ,ifnull(jd.credit,0)credit
        ,date(jh.date_post) datepost
		,jh.type_journal
        ,STR_TO_DATE(CONCAT('01/',jh.period), '%d/%m/%Y') period
		,getpostto.post_to
		,SUBSTRING(jd.id_coa,'1','1') segment
		FROM fin_journal_d jd 
        INNER JOIN (SELECT period
					,id_journal
					,date_post
					,type_journal FROM fin_journal_h WHERE date_post IS NOT NULL AND period !='')jh 
					ON jd.id_journal = jh.id_journal 
					left JOIN(SELECT id_coa,post_to FROM mastercoa)getpostto ON jd.id_coa=getpostto.id_coa) Master WHERE Master.post_to = '$id'
					AND Master.period >= '$from' AND Master.period<'$tos'
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
						$tmpsaldoakhir = $tmpsaldoakhir + $row['debit'];
						$tmpsaldoakhir = $tmpsaldoakhir - $row['credit'];
						array_push($saldoakhir,$tmpsaldoakhir);
						
					}	
					
					if($row['type_journal'] == '1'){
						$decriptionJournal = "Jurnal Penjualan";
					}
					if($row['type_journal'] == '2'){
						$decriptionJournal = "Jurnal Pembelian";
					}
					if($row['type_journal'] == '3'){
						$decriptionJournal = "Jurnal Pembayaran";
					}
					if($row['type_journal'] == '4'){
						$decriptionJournal = "Jurnal Penerimaan";
					}
					if($row['type_journal'] == '5'){
						$decriptionJournal = "Jurnal Jurnal Kas &amp; Bank";
					}
					if($row['type_journal'] == '6'){
						$decriptionJournal = "Jurnal Umum";
					}
					if($row['type_journal'] == '7'){
						$decriptionJournal = "Jurnal Aktiva Tetap";
					}
					if($row['type_journal'] == '8'){
						$decriptionJournal = "Jurnal Penyesuaian";
					}
					if($row['type_journal'] == '9'){
						$decriptionJournal = "Jurnal Closing";
					}
					/*
        '1' => 'Jurnal Penjualan',
        '2' => 'Jurnal Pembelian',
        '3' => 'Jurnal Pembayaran',
        '4' => 'Jurnal Penerimaan',
        '5' => 'Jurnal Kas &amp; Bank',
        '6' => 'Jurnal Umum',
        '7' => 'Jurnal Aktiva Tetap',
        '8' => 'Jurnal Penyesuaian',
        '9' => 'Jurnal Closing',


					*/
				array_push($nm_coa,$row['nm_coa']);
				array_push($segment, $row['segment']);
				array_push($id_coa,$row['id_coa']);
				array_push($debit,$row['debit']);
				array_push($credit,$row['credit']);
				array_push($id_journal,$row['id_journal']);
				array_push($date_post,$row['datepost']);
				array_push($type_journal, $decriptionJournal);
				array_push($num_journal,$row['id_journal']);	
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
//print_r($data);
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





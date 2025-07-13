<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($_POST['from'],$_POST['to'],$_POST['akun'],$_POST['idcashbank']);
print_r($List);
}
//else{
//	exit;
//}
class getListData {
	
	protected function getSaldoAwalByAkun($from,$to,$akun){
		include __DIR__ .'/../../../include/conn.php';
		$sql ="SELECT A.v_idcoa ,A.v_idjournal ,ifnull(SUM(A.n_nilai),0) nilai ,A.n_nilai FROM fin_prosescashbank A LEFT JOIN (SELECT id_journal,date_post FROM fin_journal_h WHERE date_post !='')B ON A.v_idjournal = B.id_journal WHERE A.v_idcoa = '$akun' AND B.date_post >='$from' AND B.date_post <='$to'
		
		";
		$stmt = mysql_query($sql);
		//echo $sql;
		while($row = mysql_fetch_array($stmt)){
			$saldoawal = $row['nilai'];
		}
		return $saldoawal;
	}
	
	
	public function get($from,$to,$akun,$idcashbank){

		//echo $from;
		$explode = explode("/",$from);
		$from = $explode[1]."-".$explode[0]."-01";
		$explode = explode("/",$to);
		$to = $explode[1]."-".$explode[0]."-31";		


//print_r($to);
		include __DIR__ .'/../../../include/conn.php';
		$andwhere = '';
		if($idcashbank != ''){
			$andwhere = "AND id_coa = '$idcashbank'";
		}
		$qCOA = "
SELECT id_coa,nm_coa,post_to FROM mastercoa WHERE post_to = '$akun' $andwhere
";
		$stmtCOA = mysql_query($qCOA);	
		$outp = '';
		$td = '';
		while($rowCOA = mysql_fetch_array($stmtCOA)){
		//	print_r($rowCOA['id_coa']);
			$saldoawal = $this->getSaldoAwalByAkun($from,$to,$rowCOA['id_coa']);
			$saldoberjalan = $saldoawal ;
/*Min Plus  			*/	$td .="<tr Class=$akun>"; 
/*ID COA				*/	$td .="<td align='center'></td>";	
/*nama Coa			*/		$td .="<td align='center'>$rowCOA[id_coa]</td>";
/*tanggal date post	*/		$td .="<td >$rowCOA[nm_coa]</td>";
/*no journal			*/	$td .="<td ></td>";
/*referensi document  */ 	$td .="<td align='center'></td>";		
/*keterrangan 		*/		$td .="<td align='center'></td>"; 
/*penambahan  		*/		$td .="<td align='center'><b>Saldo Awal :</b></td>";
/*pengurangan			*/	$td .="<td align='center'></td>";
/*saldo 				*/	$td .="<td align='center'></td>";
					$td .="<td align='center'>$saldoawal</td>";

		$td .= "</tr>";	





				$qList = "
					SELECT A.id_journal,A.row_id,A.id_coa,A.nm_coa,A.debit,A.credit,date(B.date_post)date_post,B.reff_doc FROM fin_journal_d A
					LEFT JOIN(SELECT id_journal,date_post,reff_doc FROM fin_journal_h ) B
					ON A.id_journal = B.id_journal
					INNER JOIN( SELECT v_idjournal,n_nilai,v_idcoa FROM fin_prosescashbank WHERE v_idcoa = '$rowCOA[id_coa]')C ON A.id_journal =C.v_idjournal
					WHERE B.date_post >='$from' AND B.date_post <='$to'
				"; //	echo $qList;
				$stmtList = mysql_query($qList);	
				$outp = '';
				while($rowList = mysql_fetch_array($stmtList)){
				$debit = 0;
				$credit = 0;
				if(intval($rowList['debit']) > 0 ){
						$debit = $rowList['debit'];
						$credit = 0;
						$saldoberjalan = $saldoberjalan + $debit;
				}if(intval($rowList['credit']) > 0 ){
						$debit = 0;
						$credit = $rowList['credit'];	
						$saldoberjalan = $saldoberjalan - $credit;
						}
					$td .="<tr Class=$akun>"; 
/*Min Plus  			*/$td .="<td align='center'></td>";	
/*ID COA				*/$td .="<td align='center'></td>";
/*nama Coa			*/	  $td .="<td ></td>";
/*tanggal date post	*/	  $td .="<td >$rowList[date_post]</td>";
/*no journal			*/$td .="<td align='center'>$rowList[id_journal]</td>";		
/*referensi document  */  $td .="<td align='center'>$rowList[reff_doc]</td>"; 
/*keterrangan 		*/	  $td .="<td align='center'>$rowList[nm_coa]</td>";
/*penambahan  		*/	  $td .="<td align='center'>$debit</td>";
/*pengurangan			*/$td .="<td align='center'>$credit</td>";
/*saldo 				*/$td .="<td align='center'>$saldoberjalan</td>";	
					$td .="</tr>"; 
				}
				

		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    } <-|->'.$td;
		return $result;
	}
}




?>





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
	
	protected function getSaldoAwalByAkun($from,$to,$idcashbank){
		include __DIR__ .'/../../../include/conn.php';
		$sql ="SELECT n_idcoa,v_namacoa,n_saldo FROM fin_saldoawal WHERE n_idcoa = '$idcashbank' 
		";
		//echo $sql;
		$stmt = mysql_query($sql);
		//echo $sql;
		while($row = mysql_fetch_array($stmt)){
			$saldoawal = $row['n_saldo'];
		}
		return $saldoawal;
	}
	
	
	public function get($from,$to,$akun,$idcashbank){
		$segment = substr($idcashbank,1,1);
		$explode = explode(" ",$from);
		$from = $explode[2]."-".date('m', strtotime($explode[1]))."-".$explode[0];
		$explodes = explode(" ",$to); 
		$to = $explodes[2]."-".date('m', strtotime($explodes[1]))."-".$explodes[0];		
		//echo $from;
		//$explode = explode("/",$from);
		//$from = $explode[1]."-".$explode[0]."-01";
		//$explode = explode("/",$to);
		//$to = $explode[1]."-".$explode[0]."-31";		


//print_r($to);
		include __DIR__ .'/../../../include/conn.php';
		$andwhere = '';
		if($idcashbank != ''){
			$andwhere = "AND id_coa = '$idcashbank'";
		}
		$qCOA = "SELECT id_coa,nm_coa,post_to FROM mastercoa WHERE post_to = '$akun' $andwhere";
		$stmtCOA = mysql_query($qCOA);	
		$outp = '';
		$td = '';
		while($rowCOA = mysql_fetch_array($stmtCOA)){
		//	print_r($rowCOA['id_coa']);
			$saldoawal = $this->getSaldoAwalByAkun($from,$to,$rowCOA['id_coa']);
			$saldoberjalan = $saldoawal ;
			//echo $saldoberjalan;
			
			
/*Min Plus  			*/      //	$td .="<tr Class=$akun>"; 
/*ID COA				*/      //	$td .="<td align='center'></td>";	
/*nama Coa			*/		    //  $td .="<td align='center'>$rowCOA[id_coa]</td>";
/*tanggal date post	*/		    //  $td .="<td >$rowCOA[nm_coa]</td>";
/*no journal			*/      //	$td .="<td ></td>";
/*referensi document  */  	    //  $td .="<td align='center'></td>";		
/*keterrangan 		*/		    //  $td .="<td align='center'></td>"; 
/*penambahan  		*/		    //  $td .="<td align='center'><b>Saldo Awal :</b></td>";
/*pengurangan			*/      //	$td .="<td align='center'></td>";
/*saldo 				*/      //	$td .="<td align='center'></td>";
					//$td .="<td align='center'>$saldoawal</td>";
			

if($segment == '0'){
	      $td .=" <tr Class=$akun>";
          $td .=" <td align='center'>			   </td>   ";
          $td .=" <td align='center'>				   </td>   ";
          $td .=" <td align='center'>				</td>  ";
          $td .=" <td align='center'>				</td>  ";
          $td .=" <td align='center'> </td>";
		  $td .=" <td > </td>";
          $td .=" <td align='center'> Saldo Awal :		</td>  ";
          $td .=" <td align='center'>			</td>   ";
          $td .=" <td align='center'>		</td>  ";
          $td .=" <td align='center'>	    </td>  ";
          $td .=" <td align='center'>		</td>  ";
          $td .=" <td align='center' style='display:none'>		</td>  ";
          $td .=" <td>											</td>  ";
		  $td .=" <td>											</td>  ";
		  $td .=" <td align='center'>".number_format($saldoawal,0,',','.')."										</td>  ";
          $td .=" </tr>";			
}
else if ($segment == '1'){
			
	      $td .="<tr Class=$akun>";
          $td .=" <td align='center'>			   </td>   ";
          $td .=" <td align='center'>				   </td>   ";
          $td .=" <td align='center'>				</td>  ";
          $td .=" <td align='center'>				</td>  ";
          $td .="<td align='center'> </td>";
		  $td .="<td > </td>";
          $td .=" <td align='center'> Saldo Awal :		</td>  ";
          $td .=" <td align='center'>			</td>   ";
		  $td .=" <td align='center'>			</td>   ";
          $td .=" <td align='center'>		</td>  ";
          $td .=" <td align='center'>	    </td>  ";
          $td .=" <td align='center'>		</td>  ";
          $td .=" <td align='center'>		</td>  ";
          $td .=" <td>											</td>  ";
		  $td .=" <td style='display:none'>											</td>  ";
          $td .=" <td>											</td>  ";
		  $td .=" <td>											</td>  ";		  
		  $td .=" <td align='center'>".number_format($saldoawal,0,',','.')."										</td>  ";
		  $td .=" <td>									</td>  ";
          $td .=" </tr>";			
						
	
	
}

				$qList = "SELECT 
	A.id_journal
	,A.row_id
	,A.id_coa
	,A.nm_coa
	,A.n_rate n_rate
	,A.debit
	,A.credit
	,(ifnull(A.debit,0)) - (ifnull(A.credit,0) ) saldoberjalan
	,date(B.date_post)date_post
	,B.reff_doc 
	,(SELECT id_coa FROM fin_journal_d WHERE id_journal = A.id_journal AND row_id != A.row_id LIMIT 1) id_lawan	
	,(SELECT nm_coa FROM fin_journal_d WHERE id_journal = A.id_journal AND row_id != A.row_id LIMIT 1) nama_lawan
	,Detail.v_idjournal
	,Detail.v_novoucher
	,Detail.v_fakturpajak
	,C.v_idkonsumen
	,C.v_namakonsumen
	,B.date_journal
	
	
	FROM
		fin_journal_d A LEFT JOIN(SELECT id_journal,date_post,reff_doc,date_journal FROM fin_journal_h ) B ON A.id_journal = B.id_journal INNER JOIN( SELECT v_idkonsumen,v_namakonsumen,v_idjournal,n_nilai,v_idcoa FROM fin_prosescashbank WHERE v_idcoa = '$idcashbank')C ON A.id_journal =C.v_idjournal 
	LEFT JOIN 
		(SELECT v_idjournal,v_novoucher,v_fakturpajak,rate FROM fin_journalheaderdetail)Detail ON
		A.id_journal = Detail.v_idjournal
	WHERE date_journal >='$from 00:00:00' AND date_journal <='$to 23:59:59' AND A.row_id > 1 AND date_post is not null
							";
				//echo $qList;
				
				$stmtList = mysql_query($qList);	
				$outp = '';
				//$saldoawal = 0;
				$saldoberjalan = $saldoawal;
				//echo $saldoberjalan;
				while($rowList = mysql_fetch_array($stmtList)){
//					$saldoberjalan = $saldoberjalan + $rowList['saldoberjalan'];
//					$saldoawal = $saldoawal + $rowList['debit'];
					//echo $rowList['saldoberjalan'];
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
						//echo $saldoberjalan;
						}
						

						//$td .="<tr Class=$akun>"; 
/*Min Plus  			*///$td .="<td align='center'></td>";	
/*ID COA				*///$td .="<td align='center'></td>";
/*nama Coa			*/	  //$td .="<td ></td>";
/*tanggal date post	*/	  //$td .="<td >$rowList[date_post]</td>";
/*no journal			*///$td .="<td align='center'>$rowList[id_journal]</td>";		
/*referensi document  */ // $td .="<td align='center'>$rowList[reff_doc]</td>"; 
/*keterrangan 		*/	 // $td .="<td align='center'>$rowList[nm_coa]</td>";
/*penambahan  		*/	 // $td .="<td align='center'>$debit</td>";
/*pengurangan			*///$td .="<td align='center'>$credit</td>";
/*saldo 				*///$td .="<td align='center'>$saldoberjalan</td>";	
					//$td .="</tr>"; 
					
					
if ($segment == '0'){
					$td .="<tr Class=$akun>";
					$td .=" <td align='center'>			    </td>";
					$td .=" <td align='center'>		$rowList[date_post]		</td>";
					$td .=" <td align='center'>		$rowList[id_journal]		</td>";
					$td .=" <td align='center'>		$rowList[v_novoucher]		</td>";
					$td .="<td align='center'>		$rowList[id_coa]		</td>";
					$td .="<td align='center'>		$rowList[nm_coa]		</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>		$rowList[v_idkonsumen]		</td>";
					$td .=" <td align='center'>		$rowList[v_namakonsumen]		</td>";
					$td .=" <td align='center'>	   	$rowList[v_fakturpajak]		</td>";
					$td .=" <td align='center'>		$rowList[reff_doc]		</td>";
					$td .=" <td align='center' style='display:none'>		$rowList[n_rate]		</td>";
					$td .=" <td align='center'>		".number_format($rowList['debit'],0,',','.')."		</td>";
					$td .=" <td align='center'>		".number_format($rowList['credit'],0,',','.')."		</td>";
					$td .=" <td align='center'>		".number_format($saldoberjalan,0,',','.')."</td>";
					$td .=" </tr>";	
}

else if ($segment == '1'){
					$td .="<tr Class=$akun>";
					$td .=" <td align='center'>			    </td>";
					$td .=" <td align='center'>		$rowList[date_post]		</td>";
					$td .=" <td align='center'>		$rowList[id_journal]		</td>";
					$td .=" <td align='center'>		$rowList[v_novoucher]		</td>";
					$td .="	<td align='center'>		$rowList[id_coa]		</td>";
					$td .="	<td align='center'>		$rowList[nm_coa]		</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>		$rowList[v_idkonsumen]		</td>";
					$td .=" <td align='center'>		$rowList[v_namakonsumen]		</td>";
					$td .=" <td align='center'>	   	$rowList[v_fakturpajak]		</td>";
					$td .=" <td align='center'>		$rowList[reff_doc]		</td>";
					$td .=" <td align='center' style='display:none'>				</td>";
					$td .=" <td align='center'>		".number_format($rowList['debit'],0,',','.')."		</td>";
					$td .=" <td align='center'>	</td>";
					$td .=" <td align='center'>		".number_format($rowList['credit'],0,',','.')."		</td>";
					$td .=" <td align='center'>			</td>";
					$td .=" <td align='center'>		".number_format($saldoberjalan,0,',','.')."		</td>";
					$td .=" <td align='center'>			</td>";
					$td .=" </tr>";	
}	
				}

if ($segment == '0'){
					$td .="<tr Class=$akun>";
					$td .=" <td align='center'>			    </td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .="<td align='center'>				</td>";
					$td .="<td align='center'>				</td>";
					$td .=" <td align='center'>	Saldo Akhir	:		</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>	   			</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center' style='display:none'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>		".number_format($saldoberjalan,0,',','.')."</td>";
					$td .=" </tr>";	
}

else if ($segment == '1'){
					$td .="<tr Class=$akun>";
					$td .=" <td align='center'>			    </td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .="	<td align='center'>				</td>";
					$td .="	<td align='center'>				</td>";
					$td .=" <td align='center'>	Saldo Akhir	:			</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>	   			</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center' style='display:none'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>	</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>		".number_format($saldoberjalan,0,',','.')."		</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" </tr>";	
}					
				
				//rekap
				$qListRekap = "
					SELECT A.id_journal,A.row_id,A.id_coa,A.nm_coa,A.debit,A.credit,date(B.date_post)date_post,B.reff_doc,date_journal FROM fin_journal_d A
					LEFT JOIN(SELECT id_journal,date_post,reff_doc,date_journal FROM fin_journal_h ) B
					ON A.id_journal = B.id_journal
					INNER JOIN( SELECT v_idjournal,n_nilai,v_idcoa FROM fin_prosescashbank WHERE v_idcoa = '$rowCOA[id_coa]')C ON A.id_journal =C.v_idjournal
					WHERE B.date_journal >='$from 00:00:00' AND B.date_journal <='$to 23:59:59' AND row_id > 1 AND B.date_post is not null
				"; 	
				//echo $qListRekap;
				$stmtListRekap = mysql_query($qListRekap);	
				$Footersaldoberjalan = $saldoawal;
				while($rowListRekap = mysql_fetch_array($stmtListRekap)){
				$debit = 0;
				$credit = 0;
				
				if(intval($rowListRekap['debit']) > 0 ){
						$debit = $rowListRekap['debit'];
						$credit = 0;
						$Footersaldoberjalan = $Footersaldoberjalan + $debit;
				}if(intval($rowListRekap['credit']) > 0 ){
						$debit = 0;
						$credit = $rowListRekap['credit'];	
						$Footersaldoberjalan = $Footersaldoberjalan - $credit;

						}	
						
						
				if ($outp != "") {$outp .= ",";}
						$outp .= '{"id_journal":"'.rawurlencode($rowListRekap['id_journal']).'",';
						$outp .= '"id_coa":"'. rawurlencode($rowListRekap["id_coa"]). '",'; 
						$outp .= '"saldoawal":"'. rawurlencode($saldoawal). '",'; 
						$outp .= '"debit":"'. rawurlencode(round($rowListRekap["debit"],0)). '",'; 
						$outp .= '"credit":"'. rawurlencode(round($rowListRekap["credit"],0)). '",'; 
						$outp .= '"date_post":"'. rawurlencode($rowListRekap["date_post"]). '",'; 
						$outp .= '"saldoberjalan":"'. rawurlencode($Footersaldoberjalan). '",'; 
						$outp .= '"nm_coa":"'. rawurlencode($rowListRekap["nm_coa"]). '"}'; 					
					}
		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    } <-|->'.$td;
		return $result;
	}
}




?>





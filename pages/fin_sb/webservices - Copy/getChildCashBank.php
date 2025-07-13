<?php 

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
	

	public function rate($from){
		include __DIR__ .'/../../../include/conn.php';
		$sql ="SELECT v_codecurr,tanggal,rate FROM masterrate WHERE tanggal = '$from' 
		";
		//echo $sql;
		$stmt = mysql_query($sql);
		//echo $sql;
		$rate = "0";
		$status_rate = "No";
		if(mysql_num_rows($stmt) > 0){
			while($row = mysql_fetch_array($stmt)){
				$rate = $row['rate'];
				$status_rate = "YES";
			}			
			
		}

		return $rate."__".$status_rate;		
		
	}
	
	public function getSaldoAwalByAkun($from,$to,$idcashbank){
		include __DIR__ .'/../../../include/conn.php';
		$sql ="SELECT n_idcoa,v_namacoa,n_saldo,v_curr FROM fin_history_saldo WHERE n_idcoa = '$idcashbank' AND d_cash_bank = '$from'; 
		";
		//echo $sql;
		$stmt = mysql_query($sql);
		//echo $sql;
		$curr = "IDR";
		$saldoawal = 0;
		$saldoawal_convert = 0;
		if(mysql_num_rows($stmt) > 0){
			while($row = mysql_fetch_array($stmt)){
				$saldoawal_convert = $row['n_saldo'];
				$curr = $row['v_curr'];
			}			
			
		}
		$rate = $this->rate($from);
		if($curr == "USD"){
			$saldoawal = $rate * $saldoawal_convert ;
		}else{
			$saldoawal = $saldoawal_convert;
		}
		return $saldoawal."__".$curr."__".$rate."__".$saldoawal_convert;
	}
	
	
	
	public function get($from,$to,$akun,$idcashbank){
		$segment = substr($idcashbank,1,1);
		$explode = explode(" ",$from);
		$from = $explode[2]."-".date('m', strtotime($explode[1]))."-".$explode[0];
		$explodes = explode(" ",$to); 
		$to = $explodes[2]."-".date('m', strtotime($explodes[1]))."-".$explodes[0];			
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
			//print_r($saldoawal);
			//echo $saldoawal;
			$explode = explode("__",$saldoawal);
			$saldoawal = $explode[0];
			$curr = $explode[1];
			$rate = $explode[2];
			$status_rate = $explode[3];
			$saldoawal_convert = $explode[4];
			$saldoberjalan = $saldoawal;
			$saldoberjalan_convert = $saldoawal_convert ;
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
          $td .=" <td align='center'>			   			</td>   ";
          $td .=" <td align='center'>				   		</td>   ";
          $td .=" <td align='center'>						</td>  ";
          $td .=" <td align='center'>						</td>  ";
          $td .=" <td align='center'> 						</td>";
		  $td .=" <td > 									</td>";
          $td .=" <td align='center'> Saldo Awal :			</td>  ";
          $td .=" <td align='center'>						</td>   ";
          $td .=" <td align='center'>						</td>  ";
          $td .=" <td align='center'>	    				</td>  ";
          $td .=" <td align='center'>						</td>  ";
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
          $td .=" <td>											</td>  ";
		  $td .=" <td>											</td>  ";		  

		  $td .=" <td align='center'>".number_format($saldoawal,0,',','.')."										</td>  ";
			  
		  
		    $td .=" <td align='center'>".number_format($saldoawal_convert,0,',','.')."										</td>  ";


          $td .=" </tr>";			
						
	
	
}

				$qList = "
SELECT  X.id_journal
        ,X.row_id
        ,X.id_coa
        ,X.nm_coa
        ,X.n_rate n_rate
        ,if(X.curr IS NULL OR X.curr = '','IDR','X.curr')curr 
		,if(X.curr = 'USD',X.debit,0)debit_convert
		,if(X.curr = 'IDR',X.debit,0)debit
		,if(X.curr = 'USD',X.credit,0)credit_convert
		,if(X.curr = 'IDR',X.credit,0)credit		
        ,if(X.curr = 'IDR',X.saldoberjalan,0)saldoberjalan
		,if(X.curr = 'USD',X.saldoberjalan,0)saldoberjalan_convert
        ,X.date_post
        ,X.reff_doc 
        ,X.fg_post
        ,X.id_lawan	
        ,X.nama_lawan
        ,X.v_idjournal
        ,X.v_novoucher
        ,X.v_fakturpajak
        ,X.v_idkonsumen
        ,X.v_namakonsumen
        ,X.date_journal
		FROM
(

SELECT 
	 A.id_journal
	,A.row_id
	,A.id_coa
	,A.nm_coa
	,A.n_rate n_rate
	,A.debit
	,A.credit
	,ifnull(A.curr,if(A.curr ='','IDR','USD'))curr
	,(ifnull(A.debit,0)) - (ifnull(A.credit,0) ) saldoberjalan
	,date(B.date_post)date_post
	,B.reff_doc 
	,B.fg_post
	,(SELECT id_coa FROM fin_journal_d WHERE id_journal = A.id_journal AND row_id != A.row_id LIMIT 1) id_lawan	
	,(SELECT nm_coa FROM fin_journal_d WHERE id_journal = A.id_journal AND row_id != A.row_id LIMIT 1) nama_lawan
	,Detail.v_idjournal
	,Detail.v_novoucher
	,Detail.v_fakturpajak
	,C.v_idkonsumen
	,C.v_namakonsumen
	,B.date_journal
	FROM
		fin_journal_d A LEFT JOIN(SELECT id_journal,date_post,reff_doc,date_journal,fg_post FROM fin_journal_h ) B ON A.id_journal = B.id_journal LEFT JOIN( SELECT v_idkonsumen,v_namakonsumen,v_idjournal,n_nilai,v_idcoa FROM fin_prosescashbank WHERE v_idcoa = '$idcashbank')C ON A.id_journal =C.v_idjournal 
	LEFT JOIN 
		(SELECT v_idjournal,v_novoucher,v_fakturpajak,rate FROM fin_journalheaderdetail)Detail ON
		A.id_journal = Detail.v_idjournal
	WHERE date_journal >='$from 00:00:00' AND date_journal <='$to 23:59:59' AND A.id_coa ='$idcashbank' AND B.fg_post = '2'
	
	)X
							";
				//echo "<pre>".$qList."</pre>";
				
				$stmtList = mysql_query($qList);	
				$outp = '';
				//$saldoawal = 0;
				$saldoberjalan = $saldoawal;
				$saldoberjalan_convert = $saldoawal_convert;
				//echo $saldoberjalan;
				while($rowList = mysql_fetch_array($stmtList)){

//					$saldoberjalan = $saldoberjalan + $rowList['saldoberjalan'];
//					$saldoawal = $saldoawal + $rowList['debit'];
					//echo $rowList['saldoberjalan'];
					$debit         = 0;
					$debit_convert = 0;
					$credit        = 0;
					$credit_convert= 0;
						if($rowList['curr'] == 'USD' ){
							if(intval($rowList['debit_convert']) > 0 ){
								$debit = $rowList['debit_convert']*$rate;
								//echo "CRED:". $debit;
								//die();
								$credit = 0;
								$saldoberjalan = $saldoberjalan + $debit;
								
								$debit_convert = $rowList['debit_convert'];
								$credit_convert = 0;
								$saldoberjalan_convert = $saldoberjalan_convert + $debit_convert;						
							}if(intval($rowList['credit_convert']) > 0 ){
								$debit = 0;
								$credit = $rowList['credit_convert']*$rate;	
								$saldoberjalan = $saldoberjalan - $credit;
								
								$debit_convert = 0;
								$credit_convert = $rowList['credit_convert'];	
								$saldoberjalan_convert = $saldoberjalan_convert - $credit_convert;						
								//echo $saldoberjalan;
							}	
						}else{
							if(intval($rowList['debit']) > 0 ){
								$debit = $rowList['debit'];
								$credit = 0;
								$saldoberjalan = $saldoberjalan + $debit;
								
								$debit_convert = $rowList['debit_convert'];
								$credit_convert = 0;
								$saldoberjalan_convert = $saldoberjalan_convert + $debit_convert;						
							}if(intval($rowList['credit']) > 0 ){
								$debit = 0;
								$credit = $rowList['credit'];	
								$saldoberjalan = $saldoberjalan - $credit;
								
								$debit_convert = 0;
								$credit_convert = $rowList['credit_convert'];	
								$saldoberjalan_convert = $saldoberjalan_convert - $credit_convert;						
								//echo $saldoberjalan;
							}							
							
							
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
					$td .=" <td align='center'>		$rowList[date_journal]		</td>";
					$td .=" <td align='center'>		$rowList[id_journal]		</td>";
					$td .=" <td align='center'>		$rowList[v_novoucher]		</td>";
					$td .="<td align='center'>		$rowList[id_coa]		</td>";
					$td .="<td align='center'>		$rowList[nm_coa]		</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>		$rowList[id_lawan]		</td>";
					$td .=" <td align='center'>		$rowList[nama_lawan]		</td>";
					$td .=" <td align='center'>	   	$rowList[v_fakturpajak]		</td>";
					$td .=" <td align='center'>		$rowList[reff_doc]		</td>";
					$td .=" <td align='center' style='display:none'>		$rowList[n_rate]		</td>";
					$td .=" <td align='center'>		".number_format((float)$debit, 2, '.', ',')."</td>";
					$td .=" <td align='center'>		".number_format((float)$credit, 2, '.', ',')."</td>";
					$td .=" <td align='center'>		".number_format((float)$saldoberjalan, 2, '.', ',')."</td>";
					$td .=" </tr>";	
}

else if ($segment == '1'){
					$td .="<tr Class=$akun>";
					$td .=" <td align='center'>			    </td>";
					$td .=" <td align='center'>		$rowList[date_journal]		</td>";
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

					$td .=" <td align='center'>		".number_format((float)$debit, 2, '.', ',')."		</td>";

					$td .=" <td align='center'>		".number_format((float)$debit_convert, 2, '.', ',')."		</td>";

					$td .=" <td align='center'>		".number_format((float)$credit,2, '.', ',')."		</td>";						
					$td .=" <td align='center'>		".number_format((float)$credit_convert, 2, '.', ',')."		</td>";	

					$td .=" <td align='center'>		".number_format((float)$saldoberjalan, 2, '.', ',')."		</td>";
					$td .=" <td align='center'>		".number_format((float)$saldoberjalan_convert, 2, '.', ',')."	</td>";
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
					$td .=" <td align='center'>		".number_format((float)$saldoberjalan, 2, '.', ',')."</td>";
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

					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";
					$td .=" <td align='center'>				</td>";

		  $td .=" <td align='center'>".number_format((float)$saldoberjalan, 2, '.', ',')."										</td>  ";			  
		    $td .=" <td align='center'>".number_format((float)$saldoberjalan_convert, 2, '.', ',')."										</td>  ";
	
					$td .=" </tr>";	
}					
				//rekap
				$qListRekap = "
SELECT  X.id_journal
        ,X.row_id
        ,X.id_coa
        ,X.nm_coa
        ,X.n_rate n_rate
        ,X.curr
		,if(X.curr = 'USD',X.debit,0)debit_convert
		,if(X.curr = 'IDR',X.debit,0)debit
		,if(X.curr = 'USD',X.credit,0)credit_convert
		,if(X.curr = 'IDR',X.credit,0)credit		
        ,X.saldoberjalan
		,if(X.curr = 'USD',X.saldoberjalan*0,X.saldoberjalan)saldoberjalan_convert
        ,X.date_post
        ,X.reff_doc 
        ,X.id_lawan	
        ,X.nama_lawan
        ,X.v_idjournal
        ,X.v_novoucher
        ,X.v_fakturpajak
        ,X.v_idkonsumen
        ,X.v_namakonsumen
        ,X.date_journal
        FROM
(
		SELECT A.id_journal
	,A.row_id
	,A.id_coa
	,A.nm_coa
	,A.n_rate n_rate
	,A.debit
	,A.credit
	,ifnull(A.curr,if(A.curr ='','IDR','USD'))curr
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
		fin_journal_d A LEFT JOIN(SELECT id_journal,date_post,reff_doc,date_journal FROM fin_journal_h ) B ON A.id_journal = B.id_journal LEFT JOIN( SELECT v_idkonsumen,v_namakonsumen,v_idjournal,n_nilai,v_idcoa FROM fin_prosescashbank WHERE v_idcoa = '$rowCOA[id_coa]')C ON A.id_journal =C.v_idjournal 
	LEFT JOIN 
		(SELECT v_idjournal,v_novoucher,v_fakturpajak,rate FROM fin_journalheaderdetail)Detail ON
		A.id_journal = Detail.v_idjournal
	WHERE date_journal >='$from 00:00:00' AND date_journal <='$to 23:59:59' AND A.id_coa = '$rowCOA[id_coa]' AND date_post is not null
			)X	"; 	
//echo "<pre>".$qList."</pre>";
				$stmtListRekap = mysql_query($qListRekap);	
				$Footersaldoberjalan = $saldoawal;
				$Footersaldoberjalan_convert = $saldoawal_convert;
				$cc = mysql_num_rows($stmtListRekap);

				while($rowListRekap = mysql_fetch_array($stmtListRekap)){
					$debit         = 0;
					$debit_convert = 0;
					$credit        = 0;
					$credit_convert= 0;
				
				
				
						if($rowListRekap['curr'] == 'USD' ){
							if(intval($rowListRekap['debit_convert']) > 0 ){
								$debit = $rowListRekap['debit_convert']*$rate;
							
								$credit = 0;
								$Footersaldoberjalan = $Footersaldoberjalan + $debit;								
								
								$debit_convert = $rowListRekap['debit_convert'];
								$credit_convert = 0;
								$Footersaldoberjalan_convert = $Footersaldoberjalan_convert + $debit_convert;									
								
				
							}if(intval($rowListRekap['credit_convert']) > 0 ){
								$credit = $rowListRekap['credit_convert']*$credit;
								$debit = 0;
								$Footersaldoberjalan = $Footersaldoberjalan - $credit;								
								
								$credit_convert = $rowListRekap['credit_convert'];
								$debit_convert = 0;
								$Footersaldoberjalan_convert = $Footersaldoberjalan_convert - $credit_convert;	
							}	
						}else{
							if(intval($rowListRekap['debit']) > 0 ){
								$debit = $rowListRekap['debit'];
								$credit = 0;
								$Footersaldoberjalan = $Footersaldoberjalan + $debit;					
							}if(intval($rowListRekap['credit']) > 0 ){
								$debit = 0;
								$credit = $rowListRekap['credit'];	
								$Footersaldoberjalan = $Footersaldoberjalan - $credit;					
								//echo $saldoberjalan;
							}							
							
							
						}
		
				if ($outp != "") {$outp .= ",";}
						$outp .= '{"id_journal":"'.rawurlencode($rowListRekap['id_journal']).'",';
						$outp .= '"id_coa":"'. rawurlencode($rowListRekap["id_coa"]). '",'; 
						$outp .= '"saldoawal":"'. rawurlencode(number_format((float)$saldoawal, 2, '.', ',')). '",'; 
						$outp .= '"saldoawal_convert":"'. rawurlencode(number_format((float)$saldoawal_convert, 2, '.', ',')). '",'; 
						$outp .= '"curr":"'. rawurlencode($curr). '",'; 
						$outp .= '"debit":"'. rawurlencode(number_format((float)$debit, 2, '.', ',')). '",'; 
						$outp .= '"credit":"'. rawurlencode(number_format((float)$credit, 2, '.', ',')). '",'; 
						$outp .= '"debit_convert":"'. rawurlencode(number_format((float)$debit_convert, 2, '.', ',')). '",'; 
						$outp .= '"credit_convert":"'. rawurlencode(number_format((float)$credit_convert, 2, '.', ',')). '",'; 						
						$outp .= '"date_post":"'. rawurlencode($rowListRekap["date_journal"]). '",'; 
						$outp .= '"saldoberjalan":"'. rawurlencode(number_format((float)$Footersaldoberjalan, 2, '.', ',')). '",'; 
						$outp .= '"saldoberjalan_convert":"'. rawurlencode(number_format((float)$Footersaldoberjalan_convert, 2, '.', ',')). '",'; 
						$outp .= '"nm_coa":"'. rawurlencode($rowListRekap["nm_coa"]). '"}'; 					
					}
		}
			$result = '{ "status":"ok", "message":"1","saldoawal_convert": "'.number_format((float)$saldoawal_convert, 2, '.', ',').'", "message":"1","saldoawal": "'.number_format((float)$saldoawal, 2, '.', ',').'","curr": "'.$curr.'", "records":['.$outp.']    } <-|->'.$td;
		return $result;
	}
}




?>





<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', '600');
error_reporting(E_ALL);
		$data = $_GET;
//$data = (object)$_POST['data'];
$getListData = new getListData();



$bulan_periode =  $getListData->bulan_periode($data['from'],$data['to']);
$d_from = $getListData->d_from($data['from'],$data['to']);
$d_to = $getListData->d_to($data['from'],$data['to']);

//if($data['code'] == '1' ){
$List = $getListData->get($bulan_periode,$d_from,$d_to);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	
	public function bulan_periode($from,$to){
		$bulan_periode = explode("/",$from."/01");
		$bulan_periode = $bulan_periode[1]."-".$bulan_periode[0]."-".$bulan_periode[2];
		$bulan_periode = date("Y-m-d", strtotime("-1 days",strtotime($bulan_periode)));	
		return $bulan_periode;
	}
	public function d_from($from,$to){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	public function d_to($from,$to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
		return $d_to;
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
				print_r($query);
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
	
	
	public function detailTrialBalance($id_coa,$bulan_periode,$d_from,$d_to){
		//echo $id_coa;
		$q = "SELECT BEGINS.id_coa
    ,BEGINS.nm_coa
    ,BEGINS.v_normal
    ,BEGINS.beg_debit_total
    ,BEGINS.beg_credit_total
    ,ifnull(MUTATION.mut_total_debit,0) mut_debit_total
    ,ifnull(MUTATION.mut_total_credit,0) mut_credit_total
    ,if(BEGINS.v_normal ='D',BEGINS.beg_debit_total+(ifnull(MUTATION.mut_total_debit,0)-ifnull(MUTATION.mut_total_credit,0)),0)end_debit_total
    ,if(BEGINS.v_normal ='C',BEGINS.beg_credit_total+(ifnull(MUTATION.mut_total_debit,0)-ifnull(MUTATION.mut_total_credit,0)),0)end_credit_total
    ,BEGINS.fg_posting
    ,BEGINS.fg_active
    FROM(	
	SELECT  BEG.id_coa
			/*MC_2.id_coa coa_beg */
			/*,MC_2.nm_coa nm_coa_beg*/
		 ,BEG.nm_coa
		,BEG.fg_posting
		,BEG.fg_active
		,BEG.post_to
		,BEG.v_normal
			,if(BEG.v_normal = 'D',BEG.n_saldo,'0')beg_debit_total
			,if(BEG.v_normal = 'C',BEG.n_saldo,'0')beg_credit_total
	FROM (
		SELECT   MC.id_coa
				,MC.nm_coa
				,MC.fg_posting
				,MC.fg_active
				,MC.post_to
				,MC.v_normal
				/*,ifnull(FHS.n_saldo,0)n_saldo */
				,if(FHS.v_curr = 'USD',ifnull(FHS.n_saldo,0)*ifnull(RATE.rate,0),ifnull(FHS.n_saldo,0))n_saldo
				FROM mastercoa MC
				LEFT JOIN(
					SELECT n_idcoa,n_saldo,v_curr, date(d_dateupdate)d_update
					FROM fin_history_saldo WHERE date(d_dateupdate) = '$bulan_periode'
				)FHS ON MC.id_coa = FHS.n_idcoa 
				LEFT JOIN (SELECT rate,tanggal FROM masterrate WHERE v_codecurr='HARIAN')RATE
				ON RATE.tanggal = FHS.d_update
				ORDER BY MC.id_coa
			)BEG LEFT JOIN(
				SELECT id_coa,nm_coa,fg_posting FROM mastercoa WHERE post_to = '0'
			)MC_2 ON BEG.post_to = MC_2.id_coa
			WHERE MC_2.id_coa IS NOT NULL AND BEG.post_to = '$id_coa')BEGINS
	LEFT JOIN(
		/*MUTATION */
		SELECT   MC.id_coa
				,MC.nm_coa
				,MC.fg_posting
				,MC.fg_active
				,MC.post_to
				,MC.v_normal
				,JOURNAL.id_journal
				,JOURNAL.date_journal
				,JOURNAL.period
				,JOURNAL.type_journal
				,JOURNAL.fg_post
				,JOURNAL.id_coa coa_journal
				,ifnull(JOURNAL.debit,0)mut_total_debit
				,ifnull(JOURNAL.credit,0)mut_total_credit
				FROM mastercoa MC
				LEFT JOIN (
		
		SELECT   FH.id_journal
				,FH.date_journal
				,FH.period
				,FH.type_journal
				,FH.fg_post
				,FD.id_coa
				,SUM(FD.debit)debit
				,SUM(FD.credit)credit
			FROM fin_journal_h FH
				LEFT JOIN (SELECT id_journal
							,id_coa
							,debit
							,credit 
							FROM fin_journal_d )FD
				ON FH.id_journal = FD.id_journal
				
				WHERE FH.fg_post = '2' AND FH.date_journal >='$d_from' AND FH.date_journal <= '$d_to'
			GROUP BY FD.id_coa)JOURNAL ON MC.id_coa = JOURNAL.id_coa
			WHERE MC.post_to = '$id_coa' 	
		)MUTATION	ON BEGINS.id_coa =MUTATION.id_coa";
		//echo $q;
		$MyList = $this->CompileQuery($q,'SELECT');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{
					
		$outp_2 = '';
 		while($row_2 = mysqli_fetch_array($MyList)){
			
			if ($outp_2 != "") {$outp_2 .= ",";}
			$outp_2 .= '{"id_coa":"'.$row_2['id_coa'].'",'; 
			$outp_2 .= '"nm_coa":"'.$row_2['nm_coa'].'",'; 
			$outp_2 .= '"beg_debit_total":"'.number_format((float)$row_2['beg_debit_total'], 2, '.', ',').'",'; 
			$outp_2 .= '"beg_credit_total":"'.number_format((float)$row_2['beg_credit_total'], 2, '.', ',').'",'; 
			$outp_2 .= '"mut_debit_total":"'.number_format((float)$row_2['mut_debit_total'], 2, '.', ',').'",'; 
			$outp_2 .= '"mut_credit_total":"'.number_format((float)$row_2['mut_credit_total'], 2, '.', ',').'",'; 
			$outp_2 .= '"end_debit_total":"'.number_format((float)$row_2['end_debit_total'], 2, '.', ',').'",'; 
			$outp_2 .= '"end_credit_total":"'.number_format((float)$row_2['end_credit_total'], 2, '.', ',').'"}';
		} 		
				
			}		
		}
		
		return $outp_2;
	

		
	}
	
	public function get($bulan_periode,$d_from,$d_to){
		$q = "SELECT BEGINS.coa_beg
	   ,BEGINS.nm_coa_beg
	   ,BEGINS.v_normal
	   ,BEGINS.beg_debit_total
	   ,BEGINS.beg_credit_total
	   ,MUTATION.mut_total_debit mut_debit_total
	   ,MUTATION.mut_total_credit mut_credit_total
	   ,if(BEGINS.v_normal ='D',BEGINS.beg_debit_total+(MUTATION.mut_total_debit-MUTATION.mut_total_credit),0)end_debit_total
	   ,if(BEGINS.v_normal ='C',BEGINS.beg_credit_total+(MUTATION.mut_total_debit-MUTATION.mut_total_credit),0)end_credit_total
	   ,BEGINS.fg_posting
	   ,BEGINS.fg_active
	   FROM(
	SELECT -- BEG.id_coa
			MC_2.id_coa coa_beg
			,MC_2.nm_coa nm_coa_beg
		-- ,BEG.nm_coa
		,BEG.fg_posting
		,BEG.fg_active
		,BEG.post_to
		,BEG.v_normal
			,if(BEG.v_normal = 'D',SUM(BEG.n_saldo),'0')beg_debit_total
			,if(BEG.v_normal = 'C',SUM(BEG.n_saldo),'0')beg_credit_total
	FROM (
		SELECT   MC.id_coa
				,MC.nm_coa
				,MC.fg_posting
				,MC.fg_active
				,MC.post_to
				,MC.v_normal
				/*,SUM(ifnull(FHS.n_saldo,0))n_saldo*/
				,FHS.d_update
				,if(FHS.v_curr = 'USD',SUM(ifnull(FHS.n_saldo,0)*ifnull(RATE.rate,0)),SUM(ifnull(FHS.n_saldo,0)))n_saldo
				FROM mastercoa MC
				LEFT JOIN(
					SELECT n_idcoa,n_saldo,v_curr, date(d_dateupdate)d_update
					FROM fin_history_saldo WHERE date(d_dateupdate) = '$bulan_periode'
				)FHS ON MC.id_coa = FHS.n_idcoa 
				LEFT JOIN (SELECT rate,tanggal FROM masterrate WHERE v_codecurr='HARIAN')RATE
				ON FHS.d_update = RATE.tanggal
				GROUP BY MC.post_to,MC.id_coa
				ORDER BY MC.id_coa
			)BEG LEFT JOIN(
				SELECT id_coa,nm_coa,fg_posting FROM mastercoa WHERE post_to = '0' OR post_to = ''
			)MC_2 ON BEG.post_to = MC_2.id_coa
			WHERE MC_2.id_coa IS NOT NULL 
			GROUP BY MC_2.id_coa)BEGINS
			LEFT JOIN(
				/* MUTATION */
				SELECT  MC_2.coa_mut,MC_2.nm_coa_mut,MUT.* FROM(
					SELECT   MC.id_coa
							,MC.nm_coa
							,MC.fg_posting
							,MC.fg_active
							,MC.post_to
							,MC.v_normal
							,JOURNAL.id_journal
							,JOURNAL.date_journal
							,JOURNAL.period
							,JOURNAL.type_journal
							,JOURNAL.fg_post
							,JOURNAL.id_coa coa_journal
							,SUM(ifnull(JOURNAL.debit,0))mut_total_debit
							,SUM(ifnull(JOURNAL.credit,0))mut_total_credit
							FROM mastercoa MC
							LEFT JOIN (
					SELECT   FH.id_journal
							,FH.date_journal
							,FH.period
							,FH.type_journal
							,FH.fg_post
							,FD.id_coa
							,SUM(FD.debit)debit
							,SUM(FD.credit)credit
						FROM fin_journal_h FH
							LEFT JOIN (SELECT id_journal
										,id_coa
										,debit
										,credit 
										FROM fin_journal_d )FD
							ON FH.id_journal = FD.id_journal
							WHERE FH.fg_post = '2' AND FH.date_journal >='$d_from' AND FH.date_journal <= '$d_to'
						GROUP BY FD.id_coa)JOURNAL ON MC.id_coa = JOURNAL.id_coa
						GROUP BY MC.post_to)MUT LEFT JOIN(
							SELECT id_coa coa_mut,nm_coa nm_coa_mut,fg_posting FROM mastercoa WHERE post_to = '0' OR post_to = ''
						)MC_2 ON MUT.post_to = MC_2.coa_mut
						WHERE MC_2.coa_mut IS NOT NULL 
			)MUTATION ON BEGINS.coa_beg = MUTATION.coa_mut
			WHERE BEGINS.fg_active = '1';
		";
		$MyList = $this->CompileQuery($q,'SELECT');
		
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{
					
		$outp = '';
		$my_id = 1;
 		while($row = mysqli_fetch_array($MyList)){ 
			$id_coa = $row["coa_beg"];
			$detail_nya = $this->detailTrialBalance($row["coa_beg"],$bulan_periode,$d_from,$d_to);
			$coa_detail = array();
			array_push($coa_detail,$detail_nya);
			//print_r($coa_detail);
			 
			
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.$my_id.'",'; 	
			$outp .= '"id_coa":"'.$row['coa_beg'].'",'; 
			$outp .= '"nm_coa":"'.$row['nm_coa_beg'].'",'; 
			$outp .= '"beg_debit_total":"'.number_format((float)$row['beg_debit_total'], 2, '.', ',').'",'; 
			$outp .= '"beg_credit_total":"'.number_format((float)$row['beg_credit_total'], 2, '.', ',').'",'; 
			$outp .= '"mut_debit_total":"'.number_format((float)$row['mut_debit_total'], 2, '.', ',').'",'; 
			$outp .= '"mut_credit_total":"'.number_format((float)$row['mut_credit_total'], 2, '.', ',').'",'; 
			$outp .= '"end_debit_total":"'.number_format((float)$row['end_debit_total'], 2, '.', ',').'",'; 
			$outp .= '"end_credit_total":"'.number_format((float)$row['end_credit_total'], 2, '.', ',').'",';
			$outp .= '"detail": ['.$detail_nya.']}'; 
			$my_id++;
		} 		
			$result = '{"data":['.$outp.']}';	
			}		
		}
		
		return $result;
	}
}




?>





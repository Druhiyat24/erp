<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
		$data = $_GET;
$getListData = new getListData();
$bulan_periode =  $getListData->bulan_periode($data['from'],$data['to']);
$d_from = $getListData->d_from($data['from'],$data['to']);
$d_to = $getListData->d_to($data['from'],$data['to']);
$jumlah_bulan = $getListData->j_bulan($d_from,$d_to);  
//$start_periode = $getListData->get_bulan_start_finish($d_from);
//$finish_periode = $getListData->get_bulan_start_finish($d_to);

//if($data['code'] == '1' ){
$List = $getListData->get($jumlah_bulan,$bulan_periode,$d_from,$d_to);
print_r($List);
//} 
//else{
//	exit;
//}
class getListData {
//	public function get_bulan_start_finish($date){
//		$date = explode("-",$date);
//		$date = intval($date[1]);
//		return $date;
//		
//	}
	
	public function j_bulan($from,$to){
$start_date = new DateTime($from);
$end_date = new DateTime($to );
$interval = $start_date->diff($end_date);
		return ($interval->days)/30;
	}	
	
	
	
	
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
	
	public function detailSubNeraca($jumlah_bulan,$id_coa,$bulan_periode,$d_from,$d_to){
		$q = "SELECT NERACA.id_coa
	    ,NERACA.map_category
		,NERACA.nm_coa
		,if(NERACA.v_normal = 'D',NERACA.end_debit_total,NERACA.end_credit_total)total_neraca
		FROM(
SELECT BEGINS.id_coa
    ,BEGINS.nm_coa
	,BEGINS.map_category
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
		  ,MC_2.map_category	
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
				SELECT id_coa,nm_coa,fg_posting,map_category FROM mastercoa WHERE post_to = '0'
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
		)MUTATION	ON BEGINS.id_coa =MUTATION.id_coa)NERACA";
	
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
					
		$outp_3 = '';
		
 		while($row_3 = mysqli_fetch_array($MyList)){
		 		
			if ($outp_3 != "") {$outp_3 .= ",";}
			$outp_3 .= '{"id_coa":"'.$row_3['id_coa'].'-'.$row_3['nm_coa'].'",'; 
			if($jumlah_bulan > 1){
				
			$outp_3  .=  $this->generate_field($jumlah_bulan);
			}					
			$outp_3 .= '"total_neraca_first":"'.number_format((float)$row_3['total_neraca'], 2, '.', ',').'"}';
		} 		
				
			}		
		}
		return $outp_3;
	}	
	public function detailNeraca($jumlah_bulan,$map_category,$bulan_periode,$d_from,$d_to){
		$q = "SELECT NERACA.coa_beg
	    ,NERACA.map_category
		,NERACA.nm_coa_beg
		,if(NERACA.v_normal = 'D',NERACA.end_debit_total,NERACA.end_credit_total)total_neraca
		FROM(
SELECT BEGINS.coa_beg
	   ,BEGINS.nm_coa_beg
	   ,BEGINS.map_category
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
			,MC_2.map_category
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
				SELECT id_coa,nm_coa,fg_posting,map_category FROM mastercoa WHERE post_to = '0' OR post_to = ''
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
			WHERE BEGINS.fg_active = '1')NERACA WHERE NERACA.map_category = '$map_category'";
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
		$id_coa = $row_2['coa_beg'];
		$detail_nya = $this->detailSubNeraca($jumlah_bulan,$id_coa,$bulan_periode,$d_from,$d_to);
		$coa_detail = array();
		array_push($coa_detail,$detail_nya);
			if ($outp_2 != "") {$outp_2 .= ",";}
			$outp_2 .= '{"id_coa":"'.$row_2['coa_beg'].'-'.$row_2['nm_coa_beg'].'",'; 	
			if($jumlah_bulan > 1){
			$outp_2 .= $this->generate_field($jumlah_bulan);
			}			
			$outp_2 .= '"total_neraca_first":"'.number_format((float)$row_2['total_neraca'], 2, '.', ',').'",';
			$outp_2 .= '"detail_coa": ['.$detail_nya.']}'; 
		} 		
				
			}		
		}
		
		return $outp_2;
	

		
	}
	
	public function get($jumlah_bulan,$bulan_periode,$d_from,$d_to){

		$q = "SELECT NERACA.coa_beg
	    ,NERACA.map_category
		,CATEGORY.nm_map
		,if(NERACA.v_normal = 'D',SUM(ifnull(NERACA.end_debit_total,0)),SUM(ifnull(NERACA.end_credit_total,0)))total_neraca
		FROM(
SELECT BEGINS.coa_beg
	   ,BEGINS.nm_coa_beg
	   ,BEGINS.map_category
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
			,MC_2.map_category
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
				SELECT id_coa,nm_coa,fg_posting,map_category FROM mastercoa WHERE post_to = '0' OR post_to = ''
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
			WHERE BEGINS.fg_active = '1')NERACA 
			INNER JOIN(
				SELECT nm_map,id_map FROM mastercoacategory 
			)CATEGORY ON NERACA.map_category = CATEGORY.id_map
			GROUP BY NERACA.map_category ORDER BY NERACA.coa_beg ASC;
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
			$map_category = $row["map_category"];
			$detail_nya = $this->detailNeraca($jumlah_bulan,$map_category,$bulan_periode,$d_from,$d_to);
			//print_r($detail_nya);
			$coa_detail = array();
			array_push($coa_detail,$detail_nya);
			//print_r($coa_detail);
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.$my_id.'",'; 	
			if($jumlah_bulan > 1){
			$outp .= $this->generate_field($jumlah_bulan);
			}
			$outp .= '"nama_category":"'.$row['nm_map'].'",'; 
			$outp .= '"map_category":"'.$row['map_category'].'",'; 
			$outp .= '"total_neraca_first":"'.number_format((float)$row['total_neraca'], 2, '.', ',').'",';
			$outp .= '"detail": ['.$detail_nya.']}'; 
			$my_id++;
		} 		
			$result = '{"data":['.$outp.']}';	
			}		
		}
		
		return $result;
	}
	
function generate_field($jumlah_bulan){
	//echo "MASUK SINI";
	$param = $jumlah_bulan;
	$outp_tmp = "";
	$xx = 0;
	for($i=1;$i<$jumlah_bulan;$i++){
		$outp_tmp .= '"total_neraca_'.$i.'":"'.number_format((float)$xx, 2, '.', ',').'",';
	}

	return $outp_tmp;
	
}	

}




?>





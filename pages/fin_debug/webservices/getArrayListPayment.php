<?php
//header('Content-Type: application/json');
/*  IF(!ISSET($_SESSION['username'])){
	$respon  = "503";
	$message = "SESSION TIDAK ADA/HABIS SILAHKAN LOGIN KEMBALI";
	$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
	return $result;
}  */

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data = (object)$_POST;
$code = $_POST['code'];
//print_r($data);
if($code == '1'){
	$Proses = new Proses();
	$Proses->GetListData($data->id_journal);
}
else{
	exit;
}

class Proses {/* 
/* 	 require_once "conn.php"; 
	public $proses_sql =$conn; */

/*  public function __construct($a, $b){
	/*  include "conn.php";  
		$connect = $conn; */
 
public function row($results){
	$row_ = array();
	while($rows = $results->fetch_object()){		
		array_push($row_,$rows);
					}	
	return $row_;
}
public function connect(){
	include __DIR__ .'/../../../include/conn.php';
	return $conn_li;
	
	
}
	public function GetListData($id){
		$connect = $this->connect();
		$sql="		SELECT A.curr,A.id_list_payment,MAX(id_supplier)id_supplier,ROUND(SUM(amount),2)amount FROM (
		
		SELECT   id_list_payment
				,curr
				,id_supplier 
				,((qty*price) + ((qty*price) * ( (n_ppn/100) * (qty*price) ) ) -  ((qty*price) * ( (n_pph/100) * (qty*price) ) ) )amount
				FROM fin_journal_d 
				WHERE id_journal = '$id'
		AND id_list_payment > 0 )A
		GROUP BY A.id_list_payment";
		$result = $connect->query($sql);
		if(!$connect->query($sql)){
			$message = "Error :".$connect->error;
			$respon  = "500";
			//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//return result;			
			
		}else{
			$message = "SUKSES!";
			$respon  = "200";			
			$outp = "";
				IF($result->num_rows > 0){
					while($row = $result->fetch_array()){
						
				if ($outp != "") {$outp .= ",";}
				$outp .= '{"id_supplier":"'.($row['id_supplier']).'",';
				$outp .= '"nilai":"'.($row["amount"]). '",'; 
				$outp .= '"curr":"'.($row["curr"]). '",'; 
				$outp .= '"id":"'.($row["id_list_payment"]). '"}'; 
											
					}
				}else{
					$sql="SELECT A.id_journal
					,AP.v_nojournal
					,A.reff_doc FROM fin_journal_h A 
					INNER JOIN(SELECT v_listcode,v_nojournal FROM fin_status_journal_ap WHERE v_source ='KB')AP
					ON AP.v_listcode = A.reff_doc
					WHERE A.id_journal = '$id'
					";
					$result = $connect->query($sql);
					if(!$connect->query($sql)){
						$message = "Error :".$connect->error;
						$respon  = "500";
						//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
						//return result;			
						
					}else{
						$id_kb = "";
						$data = $this->row($result);
						if(count($data) > 0 ){
							$count_n = count($data);
							$trigger = $count_n - 1;
							$id_list_payment_return = '';
							for($i=0;$i<$count_n;$i++){
								if($i == $trigger){
									$id_kb .= "'".$data[$i]->v_nojournal."'"; 
								}else{
									$id_kb .= "'".$data[$i]->v_nojournal."',";
								}
								
								
							}							
							
							
							$sql = "SELECT X.*,AP.n_id FROM (

SELECT 	 
		 MASTER.id_journal
		 ,MAX(MASTER.curr)curr
		 ,MAX(MASTER.id_supplier)id_supplier
		,SUM(ifnull(MASTER.value_pph,0))value_pph_d
		,SUM(ifnull(MASTER.value_ppn,0))value_ppn
		,SUM(ifnull(MASTER.amount_original,0))amount_original
		,( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0)))value_pph_header
		,( ( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0))) 
		
			+  SUM(ifnull(MASTER.value_pph,0))) value_pph
		,(SUM(ifnull(MASTER.nilai,0)  ) - ( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0))) ) nilai
FROM(

		SELECT       a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,MT_H.percentage percentage_h
					,( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) )value_pph
					,( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )value_ppn
					,a.amount_original
					,a.n_ppn
					, (ifnull(a.amount_original,0) - ( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) ) + ( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )  )nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.credit
					,a.is_retur_bh
					,a.id_supplier
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT_H ON MT_H.idtax = b.n_pph
					
					WHERE 1=1
					AND a.is_retur_bh = 'N' AND
					a.credit > 0 AND  a.id_coa NOT IN('15204','15207') AND a.reff_doc IS NOT NULL GROUP BY a.id_journal,a.row_id
UNION ALL


		SELECT       a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,MT_H.percentage percentage_h
					,( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) )value_pph
					,( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )value_ppn
					,a.credit amount_original
					,a.n_ppn
					,a.credit nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.credit
					,a.is_retur_bh
					,a.id_supplier
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT_H ON MT_H.idtax = b.n_pph
					
					WHERE 1=1 AND c.id_coa IS NOT NULL
					AND a.is_retur_bh = 'N' AND
					a.credit > 0 AND  a.id_coa NOT IN('15204','15207') AND a.reff_doc IS NULL GROUP BY a.id_journal,a.row_id
UNION ALL

			SELECT   a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,'0' value_pph
					,'0' value_ppn
					,'0' percentage_h
					,(-1*(a.debit)) amount_original
					,a.n_ppn
					, (-1*(a.debit))nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.debit
					,a.is_retur_bh
					,a.id_supplier
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph WHERE 1 =1
					AND a.is_retur_bh = 'N' AND
					a.debit > 0 AND
					a.id_coa NOT IN('15204','15207') AND c.id_coa IS NOT NULL GROUP BY a.id_journal,a.row_id
					
UNION ALL

			SELECT   a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,'0' value_pph
					,'0' value_ppn
					,'0' percentage_h
					,(-1*(a.debit)) amount_original
					,a.n_ppn
					, (-1*(a.debit))nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.debit
					,a.is_retur_bh
					,a.id_supplier
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph WHERE 1 =1
					AND a.is_retur_bh = 'Y' AND
					a.debit > 0 AND
					a.id_coa NOT IN('15204','15207') AND c.id_coa IS NOT NULL GROUP BY a.id_journal,a.row_id
					
UNION ALL

			SELECT   a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,'0' value_pph
					,'0' value_ppn
					,'0' percentage_h
					,(-1*(a.credit)) amount_original
					,a.n_ppn
					, (-1*(a.credit))nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.credit
					,a.is_retur_bh
					,a.id_supplier
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph WHERE 1 =1
					AND a.is_retur_bh = 'Y' AND
					a.credit > 0 AND
					a.id_coa NOT IN('15204','15207') AND c.id_coa IS NULL  AND a.reff_doc IS NULL GROUP BY a.id_journal,a.row_id
					
										
					
					)MASTER WHERE 1=1 AND MASTER.id_journal IN ($id_kb) GROUP BY MASTER.id_journal)X
					INNER JOIN(SELECT n_id,v_listcode,v_nojournal FROM fin_status_journal_ap WHERE v_source = 'KB')AP
						ON X.id_journal = AP.v_nojournal


							";
					$result = $connect->query($sql);
						if(!$connect->query($sql)){
							$message = "Error :".$connect->error;
							$respon  = "500";
							//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
							//return result;			
							
						}else{
			$message = "SUKSES!";
			$respon  = "200";			
			$outp = "";
				IF($result->num_rows > 0){
					while($row = $result->fetch_array()){
						
				if ($outp != "") {$outp .= ",";}
				$outp .= '{"id_supplier":"'.($row['id_supplier']).'",';
				$outp .= '"nilai":"'.($row["nilai"]). '",'; 
				$outp .= '"curr":"'.($row["curr"]). '",'; 
				$outp .= '"id":"'.($row["n_id"]). '"}'; 
											
					}
				}							
							
						}

						
						}
						
					}


					
					
				}
			//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//print_r($result);
			//return result;			
		};
		$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":['.$outp.']}';
		print_r($result);		
	}
}


?>
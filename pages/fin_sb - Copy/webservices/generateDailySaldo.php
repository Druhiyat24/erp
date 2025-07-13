<?php 
set_time_limit (-1); 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->GenerateSaldoAwal($_POST['id_coa']);
print_r($List);
}
//else{
//	exit;
//}
class getListData {
	public function GenerateSaldoAwal($id_coa){
		include __DIR__ .'/../../../include/conn.php';
			$q = "DELETE FROM fin_daily_saldo WHERE id_coa = '{$id_coa}'";	
			$stmt = mysqli_query($conn_li,$q);
		
		
		$date ="2019-11-01";
		$date_finish = date('Y-m-d');
		//$id_coa = 11011; //10101,10102,10103,11001,11002,11011
		
		while (strtotime($date) <= strtotime($date_finish)) {
			$saldo_daily_idr = 0;
			$saldo_daily_usd = 0;
			

		$q = "SELECT  MASTER.id_journal
       ,MASTER.id_journal j_fh
       ,MASTER.row_id
       ,MASTER.date_journal
       ,MASTER.id_coa
       ,MASTER.nm_coa
       ,MASTER.v_normal
		,ifnull(( SUM(MASTER.n_debit_IDR) + SUM(MASTER.n_credit_IDR) + SUM(MASTER.a_credit_IDR) + SUM(MASTER.a_debit_IDR) ),0)daily_saldo_IDR
		,ifnull(( SUM(MASTER.n_debit_USD) + SUM(MASTER.n_credit_USD) + SUM(MASTER.a_credit_USD) + SUM(MASTER.a_debit_USD) ),0)daily_saldo_USD	
		FROM(

	SELECT  M.id_journal
	       ,M.j_fh
	       ,M.row_id
	       ,M.date_journal
	       ,M.id_coa
	       ,M.nm_coa
	       ,M.credit
	       ,M.debit
	       ,M.v_normal
		   ,IF(M.curr = 'IDR',M.n_debit,M.n_debit*MR.rate)n_debit_IDR
		   ,IF(M.curr = 'USD',M.n_debit,0)n_debit_USD
	 
		   ,IF(M.curr = 'IDR',M.n_credit,M.n_credit*MR.rate)n_credit_IDR
		   ,IF(M.curr = 'USD',M.n_credit,0)n_credit_USD	
	
		   ,IF(M.curr = 'IDR',M.a_credit,M.a_credit*MR.rate)a_credit_IDR
		   ,IF(M.curr = 'USD',M.a_credit,0)a_credit_USD	
	
		   ,IF(M.curr = 'IDR',M.a_debit,M.a_debit*MR.rate)a_debit_IDR
		   ,IF(M.curr = 'USD',M.a_debit,0)a_debit_USD	FROM(
	
	
		SELECT
				 FD.id_journal
				,FH.id_journal j_fh
				,FD.row_id
				,FH.date_journal
				,FD.id_coa
				,FD.nm_coa
				,FD.credit
				,FD.debit
				,MC.v_normal
				,FD.curr
				,IF(MC.v_normal = 'D' AND FD.debit > 0,FD.debit,0 )n_debit
				,IF(MC.v_normal = 'C' AND FD.debit > 0,FD.credit,0 )n_credit
				,IF(MC.v_normal = 'D' AND FD.credit > 0,(-1*FD.credit),0 )a_credit
				,IF(MC.v_normal = 'C' AND FD.debit > 0,(-1*FD.debit),0 )a_debit
				FROM fin_journal_d FD
				LEFT JOIN (SELECT * FROM fin_journal_h WHERE 1=1)FH ON FH.id_journal = FD.id_journal
				LEFT JOIN(SELECT id_coa,v_normal FROM mastercoa WHERE 1=1)MC ON MC.id_coa =FD.id_coa
			WHERE 1=1 AND FH.fg_post = '2' AND FD.id_coa='$id_coa' AND FH.date_journal = '$date'
			)M
LEFT JOIN (SELECT tanggal,ifnull(rate,0)rate FROM masterrate 
WHERE 1=1 AND v_codecurr = 'PAJAK' GROUP BY tanggal)MR ON MR.tanggal = M.date_journal
			)MASTER GROUP BY MASTER.id_coa,MASTER.date_journal";
/* 			echo $q;
			die(); */
				$stmt = mysqli_query($conn_li,$q);
				if(mysqli_error($conn_li)){
					$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'","lastDate":"", "records":"" }';
					return $result;
				}
			if( mysqli_num_rows($stmt) > 0){
					while($row = mysqli_fetch_array($stmt)){	
				
					$saldo_daily_idr = $row['daily_saldo_IDR'];
					$saldo_daily_usd = $row['daily_saldo_USD'];;
				//	echo $lastDate;
				}
			}
			
			if(!ISSET($saldo_daily_idr)){
				$saldo_daily_idr = 0;
			}			if(!ISSET($saldo_daily_usd)){
				$saldo_daily_usd = 0;
			}
				$q = "INSERT INTO fin_daily_saldo(d_daily,id_coa,saldo_usd,saldo_idr)
						VALUES ('{$date}','{$id_coa}',
						'{$saldo_daily_usd}','{$saldo_daily_idr}')
					";	
					$stmt = mysqli_query($conn_li,$q);
				if(mysqli_error($conn_li)){
					$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'","lastDate":"", "records":"" }';
					return $result;
				}	
					$result = '{ "status":"ok", "message":"1", "records":""}';
		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));//looping tambah 1 date
		}		
					$result = '{ "status":"ok", "message":"1", "records":""}';
					return $result;	
	}
	
	
}



?>





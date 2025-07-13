<?php 
require_once "../../forms/journal_interface.php";
		session_start();
		$data = $_POST;
		//print_r($data);
		//die();
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data,$_SESSION['username']);
print_r($List);
}
else{
	exit;
}
class getListData {
	function CompileQuery($query,$mode){
		include __DIR__ .'/../../../include/conn.php';
		$stmt = mysqli_query($conn_li,$query);	
		if(mysqli_error($conn_li)){
			$result = mysqli_error($conn_li)."__ERRTRUE";
			return $result;
		}	
		else{
			if($mode == "CRUD"){
				//print_r($query);
				$result = '{ "status":"ok", "message":"1"}';
				//echo "123";
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
	
	public function getDetailBpb($d){
		$bpb = $d['bpb'];
					//print_r($d);
					//die();
		if($d['journal']=="PEMAKLOON"){
			$bpb = $d['bpb'];
		$q = "SELECT BPPB.bppbno_int
					,BPPB.qty
					,BPPB.price
					,BPPB.curr
					,(BPPB.qty*BPPB.price) nilai 
					,MI.itemdesc 
					FROM bppb BPPB
						LEFT JOIN masteritem MI ON BPPB.id_item = MI.id_item

					WHERE bppbno_int = '$bpb'";
				
					//echo $q;
					//die();
					
		}else{
		$q = "SELECT BPB.bpbno_int
					,BPB.qty
					,BPB.price
					,BPB.curr
					,(BPB.qty*BPB.price) nilai 
					,MI.itemdesc 
					FROM bpb BPB
						LEFT JOIN masteritem MI ON BPB.id_item = MI.id_item
					WHERE bpbno_int = '$bpb'";				
					//echo $q;
					//die();
		}
		$MyList = $this->CompileQuery($q,'SELECT');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"TAK ADA DATA", "records":"0"}';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{
				while($d = mysqli_fetch_array($MyList)){
					$hasil[] = $d;
				}
				$result = $hasil;	
			}		
		}		
		return $result;
		
	}
	public function getDetailCoa($d){
		$journal = $d['journal'];
		if($journal == "PEMAKLOON"){
			
			 $data = array(
				'coa_debit' => '14021',
				'coa_credit' => '14001',
				'nm_coa_debit' => 'PERSEDIAAN BARANG DALAM PROSES DI PEMAKLOON - FOB',
				'nm_coa_credit' => 'PERSEDIAAN BARANG DALAM PROSES - PERSIAPAN - FOB',
			);
		}else{
			
			 $data = array(
				'coa_debit' => '14001',
				'coa_credit' => '14021',
				'nm_coa_debit' => 'PERSEDIAAN BARANG DALAM PROSES - PERSIAPAN - FOB',			
				'nm_coa_credit'  => 'PERSEDIAAN BARANG DALAM PROSES DI PEMAKLOON - FOB',
				);
			
		}
		return  $data;
	}
	
	public function getTypeJournal($d){
		if($d['journal'] == 'PEMAKLOON' ){
			$data = array(
			'type_j' => 15,
			'journal_code' => 'PN',
		);
		}else{
			$data = array(
			'type_j' => 16,
			'journal_code' => 'MN',
		);
		}
		return $data;
	}
	public function getDebitCredit($__bpb,$__coa,$__params){
		if($__params['journal'] == 'PEMAKLOON' ){
			 $data = array(
				'debit' => $__bpb[0]['nilai'],
				'credit' => 0,
				);			
		}else{
			 $data = array(
				'debit' => $__bpb[0]['nilai'],
				'credit' => 0,
				);				
			
		}
		return $data;
	}
	public function insert_fin_journal_h($src,$d,$company_nya,$type_journal){
		//print_r($d[0]);
		$period = date('m/Y', time());
		//$date = date
    $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, date_journal, type_journal, reff_doc
              ,fg_post, date_post, user_post, dateadd, useradd)
            VALUES 
              ('{$company_nya->company}', '{$period}', '{$d[0]['id_journal']}', '{$d[0]['dateadd']}'
              , '{$type_journal}', '{$src['bpb']}'
             ,'2','{$d[0]['dateadd']}','{$d[0]['useradd']}'
             ,'{$d[0]['dateadd']}', '{$d[0]['useradd']}')
            ;
        ";
		//echo $sql;
		$MyList = $this->CompileQuery($sql,'CRUD');
		
		//print_r($MyList);
		//die();
	}
	public function insert_fin_journal_d($d,$id_journal){
		//print_r($d);
		//die();
		
        foreach($d as $_bpb){
            $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, dateadd, useradd)
                    VALUES 
                      ('{$id_journal}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['description']}','{$_bpb['dateadd']}', '{$_bpb['useradd']}'
                      )
                    ;
                ";
			//	echo  $sql." ";
            // Insert detail 
           $MyList = $this->CompileQuery($sql,'CRUD');
		 //  print_r($MyList);
        }
	}
	public function get($params,$username){
		//require_once "../../forms/journal_interface.php";
		//include __DIR__ .'/../../../include/conn.php';
		$journal_date = date('Y-m-d');
		$company = get_master_company();
		$type_journal =$this->getTypeJournal($params);
		$id_journal = generate_coa_id("NAG", $type_journal['journal_code'], $journal_date);
		$_bpb = $this->getDetailBpb($params);
		//print_r($_bpb);
		$_coa = $this->getDetailCoa($params);
		$_nilai = $this->getDebitCredit($_bpb,$_coa,$params);		
		//print_r($nm_journal);
		//echo "<br/>";
		//print_r($_nilai);
            $_debit = array(
                'row_id' => 1,
                'id_journal' => $id_journal,
                'id_coa' => $_coa['coa_debit'],
                'nm_coa' => $_coa['nm_coa_debit'],
                'curr' => $_bpb[0]['curr'],
                'debit' => $_nilai['debit'],
                'credit' => $_nilai['credit'],
                'description' =>  $_bpb[0]['itemdesc'],
                'dateadd' => $journal_date,
                'useradd' => $username,
            );
            // Piutang
            $_credit = array(
                'row_id' => 2,
                'id_journal' => $id_journal,
                'id_coa' => $_coa['coa_credit'],
                'nm_coa' => $_coa['nm_coa_credit'],
                'curr' =>$_bpb[0]['curr'],
                'debit' => $_nilai['credit'],
                'credit' => $_nilai['debit'],
                'description' =>  $_bpb[0]['itemdesc'],
                'dateadd' => $journal_date,
                'useradd' => $username,
            );
            $dataBpb[] = $_debit;
            $dataBpb[] = $_credit;
		$this->insert_fin_journal_h($params,$dataBpb,$company,$type_journal['type_j']);
		$this->insert_fin_journal_d($dataBpb,$id_journal);	
		$result = '{ "status":"ok", "message":"1", "records":" "}';
			//print_r($dataBpb);
		return $result;
	}
}




?>





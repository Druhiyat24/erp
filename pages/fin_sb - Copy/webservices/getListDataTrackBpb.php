<?php 

$Proses = new Proses();

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
//$columnIndex = $_POST['order'][0]['column']; // Column index
//$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnName = 'bppbno_int'; // Column name
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$populasi_kolom  			= $Proses->populasi_kolom(); // bermain disini
$populasi_attribut  		= $Proses->populasi_attribut(); // bermain disini jika ada attribut
$main_query 				= $Proses->get_main_query(); // bermain disini
$kolom_x 					= $Proses->get_kolom_x($populasi_kolom);
$searchQuery  				= $Proses->search_query($searchValue,$populasi_kolom);
$data_datatable				= $Proses->eksekusi_query_datatable($main_query,$kolom_x,$searchQuery,$row,$rowperpage,$columnName);
//$_json 						= $Proses->generate_json($data_datatable,$populasi_kolom); // bermain disini
$_json 						= $Proses->generate_json_with_attribut($data_datatable,$populasi_kolom,$populasi_attribut,$key_det); // bermain disini
$result 					= $Proses->response($data_datatable,$populasi_kolom,$draw,$_json); 
echo json_encode($result);
die();
class Proses {
	public function connect()
		{
			include __DIR__ .'/../../../include/conn.php';
			return $con_new;
		}
		
	public function json_array($res)
    {
		
        $rows = array();
		if($res->num_rows > 0)
		{
			while($row = $res->fetch_array()){
				$rows[] = $row;
			}
		}

        return $rows;
	}		
		


	
	public function result($res)
    {
        $result = array();
		if($res->num_rows > 0)
		{
			while($row = $res->fetch_array()){
				$result[] = $row;
			}
		}
        return $result;
	}		
	
	public function check_query($connect,$my_result,$function){
				if(!$my_result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'","part" : "'.$function.'", "records":"0"}';
			print_r($result);			
			exit;		
		}else{
			return 1;
		}
	}
	public function eksekusi_query($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($connect,$result,$function);
		$tmp_array = $this->result($result);
		return $tmp_array;
	}
	public function eksekusi_query_insert_update($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($connect,$result,$function);
		return '1';
	}	
	public function flookup_new($fld,$tbl,$criteria){
		include __DIR__ .'/../../../include/conn.php';
		if ($fld!="" AND $tbl!="" AND $criteria!=""){	
			$quenya = "Select $fld as namafld from $tbl Where $criteria ";
			$strsql = mysql_query($quenya);
			if (!$strsql) { 
				die($quenya. mysql_error()); 
			}
			$rs = mysql_fetch_array($strsql);
			if (mysql_num_rows($strsql)=='0'){
				$hasil="";
			}
			else{
				$hasil=$rs['namafld'];
			}
			return $hasil;
		}
	}

	public function get_kolom_x($_pop_kolom){
		$trigger = count($_pop_kolom) - 1;
		$_kolom_x = "";
		
		for($i=0;$i<count($_pop_kolom);$i++){
			if($i==$trigger){
				$_kolom_x .= "\nX.".$_pop_kolom[$i]."";
			}else{
				$_kolom_x .= "\nX.".$_pop_kolom[$i].",";
			}
		}
		return $_kolom_x;
		
	}
	
	public function search_query($_searchValue,$_pop_kolom){
		$_searchQuery = " ";
		if($_searchValue != ''){
		$trigger = count($_pop_kolom) - 1;
		$_kolom_search = "";			
		for($i=0;$i<count($_pop_kolom);$i++){
			if($i=="0"){
				$_kolom_search .= "\nX.".$_pop_kolom[$i]."";
				$_kolom_search .= " LIKE'%".$_searchValue."%'";
			}else{
				$_kolom_search .= "\n OR X.".$_pop_kolom[$i]." ";
				$_kolom_search .= " LIKE'%".$_searchValue."%'";
			}
		}
			$_searchQuery .= " AND ( {$_kolom_search} )";
			
			
		}		
		return $_searchQuery;
		
	}
		public function get_id_jo($id_number){
			$_sql = "SELECT A.id_so,A.id_number,B.id_jo FROM prod_spread_report_number A INNER JOIN jo_det B ON A.id_so = B.id_so
				WHERE A.id_number = '{$id_number}' LIMIT 1
			
			";
			$__tmp_array = $this->eksekusi_query($_sql,"get_id_jo");
			return $__tmp_array[0]['id_jo'];
		}
	public function totalRecordshFilter($main_query,$kolom_x){
		$_sql = "select count(*) allcount from {$main_query}";
		$__tmp_array = $this->eksekusi_query($_sql,"totalRecordshFilter");
		return $__tmp_array[0]['allcount'];
		
	}
	public function eksekusi_query_datatable($main_query,$kolom_x,$searchQuery,$row,$rowperpage,$columnName){
		$_populasi_datatable;
		$data_table = array();
	$sql = "select {$kolom_x}  from {$main_query} WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;

	
	//echo $sql;
	//die();
		$tmp_array = $this->eksekusi_query($sql,"eksekusi_query_datatable");
		$data_table = array(
			"data"	=> $tmp_array,
			"totalRecordsWithFilter" =>count($tmp_array),
			"totalRecordshFilter" =>$this->totalRecordshFilter($main_query,$kolom_x),
		); 

		return $data_table;
		
	}



	
	public function response($data_datatable,$populasi_kolom,$draw,$_json){
		
		

		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $data_datatable['totalRecordsWithFilter'],
			"iTotalDisplayRecords" => $data_datatable['totalRecordshFilter'],
			"aaData" => $_json,
		);		
		return $response;
		
	}
	
	
	
	public function generate_attr($_attr,$__data){
		
		$pecah_ =explode("___",$_attr);
		$_field = $pecah_[1];
		$_value = $__data[$_field];
		$_str ="";
		$_trigger_mod = explode("_",$_field);
		$mod = "jp";
		if(ISSET($_trigger_mod[1]) && $_trigger_mod[1] == "kb"){
			$mod= "kb";
		}
		if(ISSET($_trigger_mod[1]) && $_trigger_mod[1] == "listcode"){
			$mod = "rekap&view=1";
		}
		if(ISSET($_trigger_mod[1]) && $_trigger_mod[1] == "pembayaran"){
			$mod = "jpay";
		}
		if(!ISSET($_value)){
		$_str = "N/A";
		}else{
			$_str = "<a href='?mod={$mod}&id=".$_value."'  target='_blank'>".$_value."</a>";
		}
		return $_str;
	}
	public function generate_json_with_attribut($_data,$_populasi_kolom,$_populasi_attribut,$_key_det){
		$j_data = array();
		$outp = "";/* 
				print_r(count($_data['data']));
		die(); */
		for($i=0;$i<count($_data['data']);$i++){ 	
			if ($outp != "") {$outp .= ",";}
			$outp .= '{';
			for($j=0;$j<count($_populasi_kolom);$j++){
				$_kolom = $_populasi_kolom[$j];			
				$outp .= '"'.$_kolom.'":"'.rawurlencode($_data['data'][$i][$_kolom]).'",';
				//if ($outp != "") {$outp .= ",";}
/* 					if($j == $trigger){
					$outp .= '"'.$_kolom.'":"'.rawurlencode($_data['data'][$i][$_kolom]).'"}';
					}else{
						$outp .= '"'.$_kolom.'":"'.rawurlencode($_data['data'][$i][$_kolom]).'",';
					} */
			}
			for($k=0;$k<count($_populasi_attribut);$k++){
				$trigger = count($_populasi_attribut) - 1;
				$_attribut = $_populasi_attribut[$k];	
				
				
				$_attr = "";
				$_attr = $this->generate_attr($_attribut,$_data['data'][$i]);
				//if ($outp != "") {$outp .= ",";}
 					if($k == $trigger){
						$outp .= '"'.$_attribut.'":"'.rawurlencode($_attr).'"}';
					}else{
						$outp .= '"'.$_attribut.'":"'.rawurlencode($_attr).'",';
					} 
					
			}
		}

	
		$records = '{ "records":['.$outp.']   }';
		$rec = json_encode($records);
		$ekstrak = json_decode($records);
/*  		print_r($records);
		die();  */
		//convert to array_data_table;
		$d_data = array();
		for($i=0;$i<count($ekstrak->records);$i++){
			$d_data[] =(array)$ekstrak->records[$i];
		}
	//	array_push(j_data,$ekstrak);
		
		return $d_data;

		
		
	}

	
	
	public function generate_json($_data,$_populasi_kolom){
		$j_data = array();
		$outp = "";/* 
				print_r(count($_data['data']));
		die(); */
		for($i=0;$i<count($_data['data']);$i++){
			$trigger = count($_populasi_kolom) - 1; 	
			if ($outp != "") {$outp .= ",";}
			$outp .= '{';
			for($j=0;$j<count($_populasi_kolom);$j++){
				$_kolom = $_populasi_kolom[$j];									
				//if ($outp != "") {$outp .= ",";}
					if($j == $trigger){
					$outp .= '"'.$_kolom.'":"'.rawurlencode($_data['data'][$i][$_kolom]).'"}';
					}else{
						$outp .= '"'.$_kolom.'":"'.rawurlencode($_data['data'][$i][$_kolom]).'",';
					}
			}
			
		}

	
		$records = '{ "records":['.$outp.']   }';
		$rec = json_encode($records);
		$ekstrak = json_decode($records);
		
		//convert to array_data_table;
		$d_data = array();
		for($i=0;$i<count($ekstrak->records);$i++){
			$d_data[] =(array)$ekstrak->records[$i];
		}
		//array_push(j_data,$ekstrak);
		return $d_data;	
	}
	public function get_main_query(){
		$_sql ="
						(
SELECT 	
         PEMBELIAN.bpbno
        ,PEMBELIAN.bpbno_int
        ,PEMBELIAN.no_pembelian
        ,PEMBELIAN.no_kb
        ,PEMBELIAN.v_listcode
		,PEMBELIAN.no_pembayaran
		,RETUR.bppbno bppbno_ret 
		,RETUR.bppbno_int bppbno_int_ret 
		,RETUR.no_pembelian_ret
		,RETUR.no_kb_ret
		,RETUR.v_listcode_ret
		,RETUR.no_pembayaran_ret
		,PENGEMBALIAN.bpbno_ri
		,PENGEMBALIAN.bpbno_int_ri
		,PENGEMBALIAN.no_pembelian_ri
		,PENGEMBALIAN.no_kb_ri
		,PENGEMBALIAN.v_listcode_ri
		,PENGEMBALIAN.no_pembayaran_ri
		,NULL journal_revers		
		FROM (
	SELECT 
		 BPB.bpbno
		,BPB.bpbno_int
		,FH.id_journal no_pembelian
		,KB.id_journal no_kb
		,AP.v_listcode
		,PAYMENT.id_journal no_pembayaran
			FROM bpb BPB
		INNER JOIN (SELECT id_journal,reff_doc FROM fin_journal_h WHERE type_journal IN('2','17'))FH ON BPB.bpbno_int = FH.reff_doc
		LEFT JOIN (SELECT id_journal,reff_doc,reff_doc2 FROM fin_journal_d WHERE id_journal LIKE '%-PK-%' GROUP BY reff_doc2)KB ON FH.id_journal = KB.reff_doc2	
		LEFT JOIN (SELECT v_nojournal,v_listcode FROM fin_status_journal_ap WHERE v_source = 'KB' GROUP BY v_nojournal)AP ON KB.id_journal = AP.v_nojournal
		LEFT JOIN (SELECT A.id_list_payment,B.v_listcode,A.id_journal FROM fin_journal_d A
						INNER JOIN fin_status_journal_ap B ON A.id_list_payment = B.n_id
						WHERE A.id_journal LIKE '%-PV-%' GROUP BY B.v_listcode
		)PAYMENT ON AP.v_listcode = PAYMENT.v_listcode
	WHERE BPB.bpbno NOT LIKE '%R%'
		GROUP BY BPB.bpbno)PEMBELIAN
		
	LEFT JOIN (
SELECT   BPB.bpbno
		,BPB.bpbno_int
		,BPPB.bppbno
		,BPPB.bppbno_int
		,FH.id_journal no_pembelian_ret
		,KB.id_journal no_kb_ret
		,AP.v_listcode v_listcode_ret
		,PAYMENT.id_journal no_pembayaran_ret
			FROM bpb BPB
			
		INNER JOIN (SELECT bpbno_ro,bppbno,bppbno_int FROM bppb WHERE bppbno LIKE '%-R%' and cancel = 'N' GROUP BY bpbno_ro)BPPB ON BPPB.bpbno_ro = BPB.bpbno	
		INNER JOIN (SELECT id_journal,reff_doc FROM fin_journal_h WHERE type_journal ='19')FH ON BPPB.bppbno = FH.reff_doc
		LEFT JOIN (SELECT id_journal,reff_doc,reff_doc2 FROM fin_journal_d WHERE id_journal LIKE '%-PK-%' GROUP BY reff_doc2)KB ON FH.id_journal = KB.reff_doc2	
		LEFT JOIN (SELECT v_nojournal,v_listcode FROM fin_status_journal_ap WHERE v_source = 'KB' GROUP BY v_nojournal)AP ON KB.id_journal = AP.v_nojournal
		LEFT JOIN (SELECT A.id_list_payment,B.v_listcode,A.id_journal FROM fin_journal_d A
						INNER JOIN fin_status_journal_ap B ON A.id_list_payment = B.n_id
						WHERE A.id_journal LIKE '%-PV-%' GROUP BY B.v_listcode
		)PAYMENT ON AP.v_listcode = PAYMENT.v_listcode
		WHERE BPB.bpbno NOT LIKE '%-R%' 
		GROUP BY BPB.bpbno
)RETUR	ON PEMBELIAN.bpbno = RETUR.bpbno
LEFT JOIN(
SELECT   BPB.bpbno
		,BPB.bpbno_int
		,BPPB.bppbno
		,BPPB.bppbno_int
		,BPB_RI.bpbno bpbno_ri
		,BPB_RI.bpbno_int bpbno_int_ri
		,FH.id_journal no_pembelian_ri
		,KB.id_journal no_kb_ri
		,AP.v_listcode v_listcode_ri
		,PAYMENT.id_journal no_pembayaran_ri
			FROM bpb BPB
		INNER JOIN (SELECT bpbno_ro,bppbno,bppbno_int FROM bppb WHERE bppbno LIKE '%-R%' and cancel = 'N' GROUP BY bpbno_ro)BPPB ON BPPB.bpbno_ro = BPB.bpbno	
		INNER JOIN(SELECT bpbno,bpbno_int,bppbno_ri FROM bpb WHERE bpbno LIKE '%-R%')BPB_RI ON BPPB.bppbno = BPB_RI.bppbno_ri
		INNER JOIN (SELECT id_journal,reff_doc FROM fin_journal_h WHERE type_journal ='2')FH ON BPB_RI.bpbno_int = FH.reff_doc
		LEFT JOIN (SELECT id_journal,reff_doc,reff_doc2 FROM fin_journal_d WHERE id_journal LIKE '%-PK-%' GROUP BY reff_doc2)KB ON FH.id_journal = KB.reff_doc2	
		LEFT JOIN (SELECT v_nojournal,v_listcode FROM fin_status_journal_ap WHERE v_source = 'KB' GROUP BY v_nojournal)AP ON KB.id_journal = AP.v_nojournal
		LEFT JOIN (SELECT A.id_list_payment,B.v_listcode,A.id_journal FROM fin_journal_d A
						INNER JOIN fin_status_journal_ap B ON A.id_list_payment = B.n_id
						WHERE A.id_journal LIKE '%-PV-%' GROUP BY B.v_listcode
		)PAYMENT ON AP.v_listcode = PAYMENT.v_listcode
		GROUP BY BPB.bpbno
)PENGEMBALIAN ON RETUR.bpbno = PENGEMBALIAN.bpbno

		)X";
 	//	echo $_sql;
	//	die(); 
		return $_sql;


		
		
		
	}
	
	
	public function populasi_attribut(){
		return array(
		"0"=>"is___checklist",
		"1"=>"is___no_pembelian", 
		"2"=>"is___no_kb", 
		"3"=>"is___v_listcode" ,
		"4"=>"is___no_pembayaran", 
		"5"=>"is___no_pembelian_ret",
		"6"=>"is___no_kb_ret",
		"7"=>"is___v_listcode_ret",
		"8"=>"is___no_pembayaran_ret",
		"9"=>"is___no_pembelian_ri",
		"10"=>"is___no_kb_ri",
		"11"=>"is___v_listcode_ri",
		"12"=>"is___no_pembayaran_ri",
		"13"=>"is___revers"	
		);
		
		
	}	
	
	
	public function populasi_kolom(){
		return array(
		"0"=>"bpbno",
		"1"=>"bpbno_int",
		"2"=>"no_pembelian", 
		"3"=>"no_kb" ,
		"4"=>"v_listcode",
		"5"=>"bppbno_ret",
		"6"=>"bppbno_int_ret",
		"7"=>"no_pembelian_ret",
		"8"=>"no_kb_ret",
		"9"=>"v_listcode_ret",
		"10"=>"no_pembayaran_ret",
		"11"=>"bpbno_ri",
		"12"=>"bpbno_int_ri",
		"13"=>"no_pembelian_ri",
		"14"=>"no_kb_ri",
		"15"=>"v_listcode_ri",
		"16"=>"journal_revers",
		);
		
		
	}
		
	
}
?>
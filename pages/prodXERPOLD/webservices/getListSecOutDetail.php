<?php 
include 'production_interface.php';
$Proses = new Proses();

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
//$columnIndex = $_POST['order'][0]['column']; // Column index
//$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnName       = 'X.id_so_det,X.urut'; // Column name
//$columnSortOrder= $_POST['order'][0]['dir']; // asc or desc
$searchValue      = $_POST['search']['value']; // Search value
$id_cost        			 =$_GET['id_cost'];
$id_sec_out            		 =$_GET['id_url'];
$id_proses        			 =$_GET['id_proses'];
$id_panel            		 =$_GET['id_panel'];
/*   print_r($_GET);
die();   */
if(!ISSET($id_cost)){
	$id_cost = 'X';
}

if(!ISSET($id_sec_out)){
	$id_sec_out  = 'X';
}
if(!ISSET($id_proses)){
	$id_proses  = 'X';
}
if(!ISSET($id_panel)){
	$id_panel  = 'X';
}

//$id_jo						= $Proses->get_id_jo($id_number);
$populasi_kolom  			= $Proses->populasi_kolom(); // bermain disini
$populasi_attribut  		= $Proses->populasi_attribut(); // bermain disini jika ada attribut
$main_query 				= $Proses->get_main_query($id_cost,$id_sec_out,$id_proses,$id_panel); // bermain disini
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
		$sql = "select {$kolom_x}  from {$main_query} WHERE 1 ".$searchQuery." order by ".$columnName." limit ".$row.",".$rowperpage;

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
	
	
	
	public function generate_attr($_attr,$id_reff,$__data,$id_so_det){
		
		$pecah_ =explode("___",$_attr);
		$_field = $pecah_[1];
		$_value = $__data[$_field];
		$_str ="";
		if($_attr == "is___checklist"){
/* 		$_str = "<input type='checkbox' onclick='check_uncheck(this)' checked class='{$_attr}[]' data-bppbno='{$bppbno_req}' data-id='".$_attr."__".$id_reff."' >"; */
		}else{
			$_str = "<input type='text'"
					."class='form-control {$_attr}'" 
					."id='".$_attr."__".$id_reff."'" 
					."value='".$_value."'"
					."data-balance='".$__data["balance_plus_qty_sec_out"]."'" 
					."data-id_det='".$__data["id_sec_out_det"]."'" 
					."data-id_so_det='".$id_so_det."'"
					."data-next_qty='".$__data["next_qty"]."'"
					."data-id_panel='".$__data["id_panel"]."'"
					."data-id_item='".$__data["id_item"]."'"
					."data-id_proses='".$__data["id_proses"]."'"
					.">";
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
			}
			for($k=0;$k<count($_populasi_attribut);$k++){
				$trigger = count($_populasi_attribut) - 1; //id_sec_out_det
				$_attribut = $_populasi_attribut[$k];	
				$id_so_det= $_data['data'][$i]['id_so_det'];
				if($_data['data'][$i]['id_sec_out_det'] == 0 ){
					$id_reff= $i;
				} else{
					$id_reff= $_data['data'][$i]['id_sec_out_det'];
				}
				
				
				$_attr = "";
				$_attr = $this->generate_attr($_attribut,$id_reff,$_data['data'][$i],$id_so_det);
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
	
	
	
	
	public function get_main_query($id_cost,$id_sec_out,$id_proses,$id_panel){
		

		$sql_balance = sql_balance_secondary_from_cutting($id_cost);
		$_sql ="(
			SELECT   		
				TABLE_BALANCE.cutting_number,
				TABLE_BALANCE.nama_item,
				TABLE_BALANCE.bundle_number,
				TABLE_BALANCE.sack_number,
				TABLE_BALANCE.kpno,																					
				TABLE_BALANCE.id_proses,
				TABLE_BALANCE.cfcode,
				TABLE_BALANCE.cfdesc,
				TABLE_BALANCE.id_dc_out_header,
				TABLE_BALANCE.id_cost,
				TABLE_BALANCE.id_buyer,
				TABLE_BALANCE.id_so_det,
				TABLE_BALANCE.id_panel,
				TABLE_BALANCE.dest,
				TABLE_BALANCE.color,
				TABLE_BALANCE.size,
				TABLE_BALANCE.qty_so,
				TABLE_BALANCE.unit,
				TABLE_BALANCE.qty_dc_out,
				TABLE_BALANCE.urut,
				TABLE_BALANCE.buyerno,
				TABLE_BALANCE.so_no,
				TABLE_BALANCE.id_item,
				TABLE_BALANCE.lot,
				TABLE_BALANCE.shell,
				TABLE_BALANCE.nama_panel,
				TABLE_BALANCE.tot_qty_cut_out,
				TABLE_BALANCE.balance_qty_cut_out,
				TABLE_BALANCE.tot_qty_sec_in,
				TABLE_BALANCE.balance_sec_in,
				TABLE_BALANCE.tot_qty_sec_out,
				TABLE_BALANCE.balance_sec_out,			
				TABLE_BALANCE.tot_qty_sec_qc next_qty,
				TABLE_BALANCE.balance_sec_qc,
				ifnull(SEC_OUT.id_sec_out_det,0) id_sec_out_det,
				ifnull(SEC_OUT.qty_sec_out,0)qty_sec_out,
				(TABLE_BALANCE.balance_sec_out + ifnull(SEC_OUT.qty_sec_out,0))balance_plus_qty_sec_out,
				ifnull(SEC_OUT.qty_reject_sec_out,0)qty_reject_sec_out				
			FROM (
				{$sql_balance}
			) TABLE_BALANCE
			LEFT JOIN (
				SELECT   
					A.id_sec_out_header
					,A.date_output
					,A.no_sec_out
					,A.notes
					,A.id_cost
					,A.username
					,A.dateinput
					,B.id_sec_out_detail id_sec_out_det
					,B.id_so_det
					,B.qty_sec_out qty_sec_out
					,B.id_proses
					,B.id_panel
					,B.id_item
					,B.qty_reject_sec_out
				FROM prod_sec_out_header A
				INNER JOIN (
					SELECT id_sec_out_detail,id_sec_out_header,id_so_det,qty_sec_out,id_item,id_panel,id_proses,qty_reject_sec_out FROM prod_sec_out_detail 
				) B ON A.id_sec_out_header = B.id_sec_out_header
				WHERE A.id_sec_out_header ='{$id_sec_out}'	
			)SEC_OUT ON 
			TABLE_BALANCE.id_so_det = SEC_OUT.id_so_det
			AND TABLE_BALANCE.id_panel = SEC_OUT.id_panel
			AND TABLE_BALANCE.id_item = SEC_OUT.id_item
			AND TABLE_BALANCE.id_proses = SEC_OUT.id_proses
				
			WHERE  TABLE_BALANCE.id_panel IN({$id_panel}) AND TABLE_BALANCE.id_proses IN({$id_proses})		
			
		)X";
		return $_sql;
	}
	
	
	public function populasi_attribut(){
		return array(
		"0"=>"is___qty_sec_out",
		"1"=>"is___qty_reject_sec_out",
		);
		
		
	}	
	
	
	public function populasi_kolom(){
		return array(
		"0"=>"so_no",
		"1"=>"buyerno",
		"2"=>"dest", 
		"3"=>"color" ,
		"4"=>"size",
		"5"=>"qty_so",
		"6"=>"balance_sec_out",
		"7"=>"tot_qty_sec_in",
		"8"=>"id_sec_out_det",
		"9"=>"id_so_det",
		"10"=>"qty_sec_out",
		"11"=>"balance_plus_qty_sec_out",
		"12"=>"next_qty",
		"13"=>"tot_qty_sec_out",
		"14"=>"nama_item",
		"15"=>"shell",
		"16"=>"nama_panel",
		"17"=>"lot",
		"18"=>"cutting_number",
		"19"=>"bundle_number",
		"20"=>"sack_number",	
		"21"=>"qty_reject_sec_out",
		"22"=>"id_panel",
		"23"=>"id_item",
		"24"=>"id_proses",
		);
		
		
	}
		
}
?>
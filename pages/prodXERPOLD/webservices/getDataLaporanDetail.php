<?php 
include 'production_interface.php';
$Proses = new Proses();

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
//$columnIndex = $_POST['order'][0]['column']; // Column index
//$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnName       	= ''; // Column name
$columnSortOrder	= 'X.kpno, FIELD(X.Urut_Proses, 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24)';// $_POST['order'][0]['dir']; // asc or desc
$searchValue      	= $_POST['search']['value']; // Search value
$from        		= $_GET['from'];
$to            		= $_GET['to'];
$id_buyer        	= $_GET['id_buyer'];
//$status            	= $_GET['status'];

if ($id_buyer == '') {
	$id_buyer = "";
} else {
	$id_buyer = "AND ACT.id_buyer = '{$id_buyer}'";
}

/*  print_r($_GET);
die();  */
if(!ISSET($from)){
	$from = 'X';
}

if(!ISSET($to)){
	$to  = 'X';
}

if(!ISSET($id_buyer)){
	$id_buyer  = 'X';
}

// if(!ISSET($status)){
// 	$status  = 'X';
// }

//$id_jo					= $Proses->get_id_jo($id_number);
$populasi_kolom  			= $Proses->populasi_kolom(); // bermain disini
$populasi_attribut  		= $Proses->populasi_attribut(); // bermain disini jika ada attribut
$main_query 				= $Proses->get_main_query($from,$to,$id_buyer);//,$status); // bermain disini
$kolom_x 					= $Proses->get_kolom_x($populasi_kolom);
$searchQuery  				= $Proses->search_query($searchValue,$populasi_kolom);
$data_datatable				= $Proses->eksekusi_query_datatable($main_query,$kolom_x,$searchQuery,$row,$rowperpage,$columnName,$columnSortOrder,$columnName);
$_json 						= $Proses->generate_json($data_datatable,$populasi_kolom); // bermain disini
//$_json 						= $Proses->generate_json_with_attribut($data_datatable,$populasi_kolom,$populasi_attribut,$key_det); // bermain disini
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
	public function eksekusi_query_datatable($main_query,$kolom_x,$searchQuery,$row,$rowperpage,$columnName,$columnSortOrder,$columnName){
		$_populasi_datatable;
		$data_table = array();
	$sql = "select {$kolom_x}  from {$main_query} WHERE 1 ".$searchQuery." ".$columnName." order by ".$columnSortOrder." limit ".$row.",".$rowperpage;

	// print_r($sql); die(); //PRINT QUERY


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
	
	public function get_populasi_defect($_id_defect){
		$sql = "SELECT id_defect id,nama_defect isi FROM master_defect WHERE mattype='FABRIC'";
		$tmp_array = $this->eksekusi_query($sql,"get_populasi_defect");
		$_opt= "<option  value=''>-- Choose Defect--</option>";
		for($i=0;$i<count($tmp_array);$i++){
			$_opt .="<option ".($_id_defect == $tmp_array[$i]['id']? "selected='selected'":"")." value='{$tmp_array[$i]['id']}'>";
			$_opt .=$tmp_array[$i]['isi'];
			$_opt .="</option>";
		}
		return $_opt;
		
	}	
	
	public function generate_attr($_attr,$id_reff,$__data,$id_so_det){
		$pecah_ =explode("___",$_attr);
		$_field = $pecah_[1];
		$_value = $__data[$_field];
		$_str ="";
		if($_attr == "is___checklist"){
/* 		$_str = "<input type='checkbox' onclick='check_uncheck(this)' checked class='{$_attr}[]' data-bppbno='{$bppbno_req}' data-id='".$_attr."__".$id_reff."' >"; */
		}
		if($pecah_[1] == "select"){
			$_str .="<select data-id='".$pecah_[2]."__".$id_reff."' class='form-control select2 sel_defect' id='".$pecah_[2]."__".$id_reff."'>";
			$_str .= $this->get_populasi_defect($__data["id_defect"]);
			$_str .= "</select>";
		}
		else{
			$_str = "<input data-id='".$_attr."__".$id_reff."' type='text' data-balance='".$__data["balance_plus_qty_packing"]."' data-id_det='".$__data["id_det"]."' data-id_so_det='".$id_so_det."' class='form-control {$_attr}' id='".$_attr."__".$id_reff."' value='".$_value."' >";
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
				$trigger = count($_populasi_attribut) - 1; //id_sew_in_det
				$_attribut = $_populasi_attribut[$k];	
				$id_so_det= $_data['data'][$i]['id_so_det'];
				if($_data['data'][$i]['id_det'] == 0 ){
					$id_reff= $i;
				} else{
					$id_reff= $_data['data'][$i]['id_det'];
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
		
	public function get_main_query($from,$to,$id_buyer){ //,$status){
		//$sql_balance = sql_balance_from_so($id_cost);
		$_get_query  = sql_detail_from_so($from,$to,$id_buyer); //,$status);
		$_sql ="(
			{$_get_query}
		)X";
/*   echo $_sql;
die();   */
		return $_sql;
	}
	
	
	public function populasi_attribut(){
		return array(
		"0"=>"is___qty_packing",
		);	
	}
	public function populasi_kolom(){
		return array(
			"0" =>"kpno",
			"1" =>"date_output",
			"2" =>"no_proses",
			"3" =>"Urut_Proses",
			"4" =>"Proses_Name",
			"5" =>"buyerno",
			"6" =>"dest",
			"7" =>"color",
			"8" =>"size",
			"9" =>"qty_so",
			"10" =>"unit",
			"11" =>"Qty_Proses",
			"12" =>"Balance"
			
		);
		
		
	}
		
	
}
?>
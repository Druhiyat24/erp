<?php 

$Proses = new Proses();

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
//$columnIndex = $_POST['order'][0]['column']; // Column index
//$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnName       = 'color'; // Column name
//$columnSortOrder= $_POST['order'][0]['dir']; // asc or desc
$searchValue      = $_POST['search']['value']; // Search value
$id_number        =	$_GET['id_number'];
$color            =	$_GET['color'];
$bppbno_req       = $_GET['bppbno'];
$key_det          = $_GET['key_det'];
$pecah_id_item    = explode("_",$_GET['id_item']);
$id_item          = $pecah_id_item[1];
$id_jo            = $pecah_id_item[0];
$add_more_item    = $_GET['add_more_item'];

if(!ISSET($id_number)){
	$id_number = 'X';
}

if(!ISSET($color)){
	$color = 'X';
}

if(!ISSET($bppbno_req)){
	$bppbno_req = 'X';
}

if(!ISSET($key_det)){
	$key_det = 'X';
}

if(!ISSET($id_item)){
	$id_item = 'X';
}

if(!ISSET($key_det)){
	$key_det = 'X';
}

if(!ISSET($add_more_item)){
	$add_more_item = 'X';
}

//$id_jo						= $Proses->get_id_jo($id_number);
$populasi_kolom  			= $Proses->populasi_kolom(); // bermain disini
$populasi_attribut  		= $Proses->populasi_attribut(); // bermain disini jika ada attribut
$main_query 				= $Proses->get_main_query($id_jo,$color,$key_det,$id_number,$bppbno_req,$id_item,$add_more_item); // bermain disini
$kolom_x 					= $Proses->get_kolom_x($populasi_kolom);
$searchQuery  				= $Proses->search_query($searchValue,$populasi_kolom);
$data_datatable				= $Proses->eksekusi_query_datatable($main_query,$kolom_x,$searchQuery,$row,$rowperpage,$columnName);
//$_json 						= $Proses->generate_json($data_datatable,$populasi_kolom); // bermain disini
$_json 						= $Proses->generate_json_with_attribut($data_datatable,$populasi_kolom,$populasi_attribut,$key_det,$add_more_item); // bermain disini
$result 					= $Proses->response($data_datatable,$populasi_kolom,$draw,$_json); 
echo json_encode($result);
die();
class Proses {
	public function connect(){
		include __DIR__ .'/../../../include/conn.php';
		return $con_new;
	}
		

	public function json_array($res){
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
		if(!$my_result){
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
		$sql = "select {$kolom_x}  from {$main_query} WHERE 1 ".$searchQuery." order by ".$columnName." DESC limit ".$row.",".$rowperpage;
	
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
	
	
	public function generate_attr($_attr,$id_reff,$bppbno_req,$__data,$_key_det,$add_more_item){
		$pecah_ =explode("___",$_attr);
		$_field = $pecah_[1];
		$_value = $__data[$_field];
		$_str ="";
		$checked = (( ($__data["id_srd"] != 0)  ? "checked":""));
		if($_attr == "is___checklist"){
			$_str = "<input type='checkbox' onclick='check_uncheck(this)' {$checked} class='{$_attr}' data-bppbno='{$bppbno_req}' data-id='".$_attr."__".$id_reff."' data-id_srd = '{$__data["id_srd"]}'>";
		}
		else{
			if($_attr == "is___total_yds"){
				$disabled = 'disabled';
			}
			else{
				$disabled = '';
			}
			$_str = "<input type='text' class='form-control {$_attr}' id='".$_attr."__".$id_reff."' value='".$_value."' name='".$_attr."&".$id_reff."' onchange=handleKeyUp(this.name) $disabled>";
		}
		return $_str;
	}


	public function generate_json_with_attribut($_data,$_populasi_kolom,$_populasi_attribut,$_key_det,$add_more_item){
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
				$_id_roll_det = $_data['data'][$i]['id_roll_det'];
				$bppbno_req   = $_data['data'][$i]['bppbno'];
				$_attribut = $_populasi_attribut[$k];	
					
				$_attr = "";
				$_attr = $this->generate_attr($_attribut,$_id_roll_det,$bppbno_req,$_data['data'][$i],$_key_det,$add_more_item);
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
	
	
	
	
	public function get_main_query($id_jo,$color,$key_det,$id_number,$bppbno_req,$id_item,$add_more_item){
		// print_r($key_det);
		// die();
		if($key_det =='1'){
			if($add_more_item == '1'){
				$where_query = "";
				$type_join = "LEFT";				
			}else{
				$where_query = "AND REQ.id_roll_det IN(SELECT id_roll_det FROM prod_spread_report_detail WHERE id_number='{$id_number}')";
				$type_join = "INNER";
			}
				$compare_id_number = "=";	
		}else{
			$where_query = "AND SR.id_srd IS NULL";
			$type_join = "LEFT";
			$compare_id_number = "!=";
		}
		$_sql ="(
					SELECT 
						CONCAT(REQ.loc_qty, ' ', REQ.out_unit) loc_qty
						,REQ.id_roll_det
						,REQ.bppbno_int
						,bm.bpbno_int
						,REQ.bpbno
						,REQ.bppbno
						,REQ.qty_request
						,REQ.out_qty AS qty_bkb
						,BOM.*
						,REQ.lot_no AS lot
						,REQ.itemdesc
						,REQ.roll_no
						,ifnull(SR.lembar_gelaran,0)lembar_gelar
						,ifnull(SR.sisa_gelar,0)sisa_gelar
						,ifnull(SR.sambung_duluan_bisa,0)sambung_duluan_bisa
						,ifnull(SR.sisa_tidak_bisa,0)sisa_tidak_bisa
						,ifnull(SR.qty_reject_yds,0)qty_reject_yds
						,ifnull(SR.total_yds,0)total_yds
						,ifnull(SR.short_roll,0)short_roll
						,ifnull(SR.percent,0)percent
						,SR.remark
						,ifnull(SR.id_srd,0)id_srd
					FROM (
						SELECT * FROM view_portal_bom WHERE id_jo ='{$id_jo}'
					)BOM
					INNER JOIN (
						SELECT 
							b.username
							,tbllok.id_roll_det
							,b.bppbno
							,b.id_item
							,b.id_jo
							,b.bppbdate
							,ac.kpno
							,ac.styleno
							,b.tanggal_aju
							,supplier tujuan
							,so.mindeldate as del_date
							,concat(mi.goods_code,' ',mi.itemdesc) itemdesc
							,mi.color
							,no_rak as location
							,qtyloc as loc_qty
							,unitloc as loc_unit
							,b.qty as qty_request
							,qtysdhout as out_qty
							,unitsdhout as out_unit
							,'' as check_picker
							,'' as check_loader
							,'' as check_penerima
							,b.remark
							,mi.id_gen
							,tbllok.lot_no   
							,tbllok.roll_no
							,tbllok.bpbno
							,tblsdhout.bppbno_int
						FROM bppb_req b
						INNER JOIN masteritem mi on b.id_item = mi.id_item 
						INNER JOIN mastersupplier msup on b.id_supplier=msup.id_supplier 
						INNER JOIN (
							SELECT 
								id_so,
								id_jo 
							FROM jo_det GROUP BY id_jo
						) jod ON b.id_jo=jod.id_jo 
						INNER JOIN (
							SELECT 
								so.id,
								id_cost,
								min(sod.deldate_det) mindeldate 
							FROM so 
							INNER JOIN so_det sod ON so.id=sod.id_so GROUP BY so.id
						) so on jod.id_so=so.id 
						INNER JOIN act_costing ac ON so.id_cost=ac.id 
						INNER JOIN (
							SELECT 
								tmplok.id_roll_det,
								id_item,
								id_jo,
								concat(kode_rak,' ',qtyloc,' ',unitloc) no_rak,
								(tmplok.qtyloc)qtyloc,
								tmplok.roll_no,
								'' unitloc,
								lot_no,
								tmplok.bpbno
							FROM (
								SELECT 
									r.id id_roll_det,
									a.id_item,
									a.id_jo,
									d.kode_rak,
									r.roll_no,
									a.bpbno,
									if(r.lot_no = '' OR r.lot_no IS NULL OR r.lot_no = '-', 'N/A', r.lot_no) lot_no,
									#round(sum(roll_qty),2) qtyloc,
									round((roll_qty),2) qtyloc,
									r.unit unitloc 
								FROM bpb_roll_h a 
								INNER JOIN bpb_roll r ON a.id=r.id_h 
								INNER JOIN master_rak d ON r.id_rak_loc=d.id
							) tmplok 
							#GROUP BY id_item,id_jo
						) tbllok ON b.id_item=tbllok.id_item AND b.id_jo=tbllok.id_jo 
						INNER JOIN (
							SELECT 
								bppbno_req,
								id_item,
								id_jo,
								SUM(ifnull(qty,0)) qtysdhout,
								GROUP_CONCAT(bppbno_int)bppbno_int,
								unit unitsdhout 
							FROM bppb WHERE #bppbno = '{$bppbno_req}'
							id_jo ='{$id_jo}' AND id_item='{$id_item}' AND
							id_supplier ='432'
							GROUP BY id_item,id_jo
						) tblsdhout ON tbllok.id_item = tblsdhout.id_item AND tbllok.id_jo = tblsdhout.id_jo AND b.bppbno=tblsdhout.bppbno_req
						WHERE 1=1 
					)REQ ON BOM.id_jo = REQ.id_jo AND REQ.id_gen = BOM.id_item 
					INNER JOIN(
						SELECT MAX(id_item)id_items,MAX(id_jo)id_jos FROM prod_mark_entry_detail WHERE id_item='{$id_item}' AND id_jo='{$id_jo}' GROUP BY id_item,id_jo
					)MARK ON MARK.id_items = REQ.id_item AND MARK.id_jos = REQ.id_jo
					INNER JOIN(
						SELECT 
							id,
							bpbno,
							bpbno_int,
							id_item,
							id_jo 
						FROM bpb GROUP BY bpbno,id_item,id_jo
					)bm ON bm.bpbno=REQ.bpbno
					$type_join JOIN(
						SELECT id id_srd
							,id_roll_det
							,id_number
							,bppbno_req
							,id_jo
							,lembar_gelaran
							,sisa_gelar
							,sambung_duluan_bisa
							,sisa_tidak_bisa
							,qty_reject_yds
							,total_yds
							,short_roll
							,percent
							,remark
							,id_item
						FROM prod_spread_report_detail
						WHERE id_jo ='{$id_jo}' AND id_item='{$id_item}' AND id_number  $compare_id_number '{$id_number}'
					)SR ON BOM.id_jo = SR.id_jo AND REQ.id_roll_det = SR.id_roll_det
					WHERE REQ.out_qty IS NOT NULL AND BOM.color ='{$color}'
					#AND SR.bppbno_req NOT IN(SELECT bppbno_req FROM prod_spread_report_detail WHERE id_number !='{$id_number}' AND bppbno_req = '{$bppbno_req}')
					$where_query 
					GROUP BY REQ.id_roll_det,REQ.id_item,REQ.id_jo
				)X
		";
		// echo $_sql;
		// die();
		return $_sql;	
	}
	
	
	public function populasi_attribut(){
		return array(
			"0"=>"is___checklist",
			"1"=>"is___lembar_gelar",
			"2"=>"is___sisa_gelar", 
			"3"=>"is___sambung_duluan_bisa" ,
			"4"=>"is___sisa_tidak_bisa",
			"5"=>"is___qty_reject_yds",
			"6"=>"is___total_yds",
			"7"=>"is___percent",
			"8"=>"is___remark",
			"9"=>"is___short_roll"
		);
	}	
	
	
	public function populasi_kolom(){
		return array(
			"0"=>"color",
			"1"=>"lot",
			"2"=>"loc_qty", 
			"3"=>"qty_bkb" ,
			"4"=>"goods_code",
			"5"=>"itemdesc",
			"6"=>"roll_no",
			"7"=>"id_roll_det",
			"8"=>"id_so",
			"9"=>"id_jo",
			"10"=>"id_cost",
			"11"=>"bppbno",
			"12"=>"lembar_gelar",
			"13"=>"sisa_gelar",
			"14"=>"sambung_duluan_bisa",
			"15"=>"sisa_tidak_bisa",
			"16"=>"qty_reject_yds",
			"17"=>"total_yds",
			"18"=>"short_roll",
			"19"=>"percent",
			"20"=>"remark",
			"21"=>"id_item_ms_item",
			"22"=>"bpbno_int",
			"23"=>"bppbno_int",
			"24"=>"id_srd"
		);
	}
	
}
?>
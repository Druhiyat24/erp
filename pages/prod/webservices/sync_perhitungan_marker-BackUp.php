<?php 
$data = $_POST;

include __DIR__ .'/../../../include/conn.php';
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
$data 			= $_POST['data'];
$id_cost 		= $data['id_cost'];
$id_mark_entry 	= $data['id_mark_entry'];
$color 			= $data['color'];
$id_panel 		= $data['panel'];
// $id_item 		= $data['id_item'];
$code 			= $_POST['code'];
$format 		= $_POST['format'];

$balance = 0;

// print_r($id_mark_entry);die();
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){  
			
		}else if($format == '2'){
			//echo '123';

			$sqlItem = "SELECT 
					med.id_item
				FROM prod_mark_entry_detail AS med
				WHERE med.id_cost = '{$id_cost}'
				AND med.id_mark = '{$id_mark_entry}'
				AND med.color = '{$color}'
				AND med.id_panel = '{$id_panel}'
				GROUP BY med.id_item
			";
			$stmtItem = mysql_query($sqlItem);
			$item=array();
			while($rowItem = mysql_fetch_array($stmtItem)){
				//print_r($item["id_item"]);
				array_push($item,$rowItem["id_item"]);
			}
			//echo var_dump($arr);
			//die();
			for($i = 0; $i < count($item); $i++){
				//echo $arr[$i];
				$populasi_mark = $Proses->get_size($id_cost,$id_mark_entry,$color,$id_panel,$item[$i]);
				$populasi_mark = $Proses->get_nilai($populasi_mark,$id_mark_entry,$color,$id_cost,$id_panel,$item[$i]);
				$populasi_mark = $Proses->update_spread_detail($populasi_mark,$id_mark_entry,$color,$id_cost,$id_panel,$item[$i]);
			
			}
			// die();
/* 			print_r($populasi_mark);
			die(); */
			// $key = $Proses->update_detail($data,$detail,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $result;
		}else{
			exit;
		}
}
else{
	exit;
}
print_r($List);
//}
//else{
//	exit;
//}

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
	
	
	public function get_size($id_cost,$id_mark_entry,$color){
		$sql = "SELECT X.size,X.id_cost,X.id_mark 
					,X.urut
					FROM(
				SELECT A.size,A.id_cost,A.id_mark 
					,ifnull(B.urut,99)urut
					FROM prod_mark_entry_detail A
					LEFT JOIN mastersize B ON A.size = B.size
					WHERE A.id_cost='{$id_cost}' AND A.id_mark='{$id_mark_entry}' GROUP BY A.size
					)X ORDER BY X.urut";
		$tmp_array = $this->eksekusi_query($sql,"get_size");
		$_pop_size = array();
		for($i=0;$i<count($tmp_array);$i++){
			$_x = array( 
				'size'	=> $tmp_array[$i]['size']
			);
			array_push($_pop_size,(object)$_x);
		}
		
		
		return $_pop_size;
		
	}
	public function is_update($size,$color,$id_mark_entry,$id_cost,$id_panel,$id_item){
		$sql ="SELECT X.size,X.id_cost,X.id_mark 
					,X.urut
					FROM(
				SELECT A.size,A.id_cost,A.id_mark 
					,ifnull(B.urut,99)urut
					FROM prod_mark_entry_detail A
					LEFT JOIN mastersize B ON A.size = B.size
	WHERE A.id_cost='{$id_cost}' AND A.id_mark='{$id_mark_entry}' AND A.size ='{$size}' AND A.id_panel='{$id_panel}' AND A.id_item='{$id_item}' GROUP BY A.size
					)X ORDER BY X.urut";
		$tmp_array = $this->eksekusi_query($sql,"is_update");
		$cnt = count($tmp_array);
		if($cnt > 0 ){
			$_is_update = 'Y';
		}else{
			$_is_update = 'N';
		}
		
		return $_is_update;


		
	}
	
	public function detail($_pop_mark,$size,$color,$id_mark_entry,$id_cost,$id_panel,$id_item){
		$sql = "SELECT    X.id_group
			,X.ratio
			,X.id_mark_detail
			,X.size
			,X.id_cost
			,X.id_mark 
			,X.id_group_det
			,X.unit_yds
			,X.unit_inch
			,X.spread_group
			,X.gsm
			,X.width
			,X.b_cons_kg
			
					,X.urut
					FROM(
				SELECT A.id_group
							,A.ratio
							,A.id_group_det
							,A.size
							,A.id_cost
							,A.id_mark
							,A.id_mark_detail
							,PG.unit_yds
							,PG.unit_inch
							,PG.spread spread_group
							,PG.gsm
							,PG.width
							,PG.b_cons_kg
					,ifnull(B.urut,99)urut
					FROM prod_mark_entry_detail A
					LEFT JOIN mastersize B ON A.size = B.size
					INNER JOIN prod_mark_entry_group PG ON A.id_group = PG.id_group
					WHERE A.id_cost='{$id_cost}' AND A.id_mark='{$id_mark_entry}' AND A.color = '{$color}' AND A.size='{$size}'
					AND A.id_panel='{$id_panel}' AND A.id_item='{$id_item}' )X ORDER BY X.id_group_det";



		$tmp_array = $this->eksekusi_query($sql,"detail");
		$_arr_tmp = array();
		for($i=0;$i<count($tmp_array);$i++){ 
			$_x = array( 
				'size'				=> $tmp_array[$i]['size'],
				'id_group'          => $tmp_array[$i]['id_group'],
				'ratio'				=> $tmp_array[$i]['ratio'],
				'id_mark_detail'	=> $tmp_array[$i]['id_mark_detail'],
				'size'				=> $tmp_array[$i]['size'],
				'id_cost'			=> $tmp_array[$i]['id_cost'],
				'id_mark' 			=> $tmp_array[$i]['id_mark'],
				'id_group_det'      => $tmp_array[$i]['id_group_det'],
				'unit_yds'          => $tmp_array[$i]['unit_yds'],
				'unit_inch'         => $tmp_array[$i]['unit_inch'],
				'spread_group'      => $tmp_array[$i]['spread_group'],
				'gsm'               => $tmp_array[$i]['gsm'],
				'width'             => $tmp_array[$i]['width'],
				'b_cons_kg'         => $tmp_array[$i]['b_cons_kg'],
			);
			array_push($_arr_tmp,(object)$_x);
		}
		return $_arr_tmp;
		
		
	}
	
	public function qty_so_per_size_per_color($size,$color,$id_cost){
		$sql = "SELECT A.id
						,A.id_cost
						,sum(ifnull(B.qty,0))qty_so
						,B.size FROM so A
					INNER JOIN so_det B ON A.id = B.id_so
					WHERE A.id_cost = '{$id_cost}' AND B.color='{$color}' AND B.size='{$size}'
					GROUP BY B.color
		";



		$tmp_array = $this->eksekusi_query($sql,"qty_so_per_size_per_color");
		$_arr_tmp = array();
		for($i=0;$i<count($tmp_array);$i++){
			$_x = array( 
				'size' 	=> $tmp_array[$i]['size'],
				'qty' 	=> $tmp_array[$i]['qty_so']
			);
			array_push($_arr_tmp,(object)$_x);
		}
		return $_arr_tmp;
				
	}
	
	public function get_nilai($_populasi_mark,$id_mark_entry,$color,$id_cost){
		for($i=0;$i<count($_populasi_mark);$i++){
			$_populasi_mark[$i]->update = $this->is_update($_populasi_mark[$i]->size,$color,$id_mark_entry,$id_cost);
			$_populasi_mark[$i]->detail = $this->detail($_populasi_mark,$_populasi_mark[$i]->size,$color,$id_mark_entry,$id_cost);
			$_populasi_mark[$i]->qty_so_per_size_per_color = $this->qty_so_per_size_per_color($_populasi_mark[$i]->size,$color,$id_cost);
			$_populasi_mark[$i]->balance = $_populasi_mark[$i]->qty_so_per_size_per_color[0]->qty;
		}
		//print_r($_populasi_mark);
		return $_populasi_mark;
			
		
		
	}
	
	/* */
	public function eksekusi_spread_detail($data_det,$id_mark_entry,$color,$id_cost){
		$___tmp = array();
		$balance  = $data_det->balance;
		$qty_so = $balance;
		for($j=0;$j<count($data_det->detail);$j++){

			$_det = $data_det->detail[$j];
			$rasio = $_det->ratio;
			$spread_group = $_det->spread_group;
			
			$spread_detail 	= $rasio * $spread_group;
			//$kurang 		= $spread_detail - $balance; 
			$kurang 		= $balance - $spread_detail; 
			$kurang = $kurang * -1;
			$balance 		= $balance - $spread_detail; 
			
			$__tmp = array(
				'size'			=> $data_det->size,
				'id_group_det'  => $_det->id_group_det,
				'spread_detail'	=> $spread_detail,
				'qty_so'  		=> $qty_so,
				'kurang'  		=> $kurang,
				'spread_group'  => $spread_group,
				'balance'		=> $balance,
				'rasio'			=> $rasio
			);
		array_push($___tmp,(object)$__tmp); 
/* 					print_r($___tmp);
				die(); */
		
			$update="UPDATE prod_mark_entry_detail SET 
						qty = '{$data_det->balance}'
						,spread = '$spread_detail'
						,kurang = '{$kurang}'
			WHERE id_mark_detail = '{$_det->id_mark_detail}'";
			
			$this->eksekusi_query_insert_update($update,'eksekusi_spread_detail');
		}
 		//print_r($___tmp);
		//die();
			return 1;
	}
	
	
	public function update_spread_detail($_populasi_mark,$id_mark_entry,$color,$id_cost){
		for($i=0;$i<count($_populasi_mark);$i++){
			if($_populasi_mark[$i]->update =='Y'){
				$this->eksekusi_spread_detail($_populasi_mark[$i],$id_mark_entry,$color,$id_cost);
			}	
		}
		return $_populasi_mark;
			
		
		
	}	 
	
	
	
}
?>
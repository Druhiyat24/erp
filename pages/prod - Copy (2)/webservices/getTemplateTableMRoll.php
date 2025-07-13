<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
$id_so 			= $_POST['id_so'];
$color 			= $_POST['color'];
$balance = 0;

// print_r($id_url);die();
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){  
			
		}else if($format == '2'){
			//echo '123';
			$static_field 	= $Proses->get_static_field();
			$dynamic_field 	= $Proses->get_dynamic_field($id_so,$color,$id_item);
			$field 			= $Proses->merge($static_field,$dynamic_field);
			$render 		= $proses->render($field);
			$result 		= '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"'.$render.'"}';
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
	
	
	public function get_static_field(){
		$array_static = array(
			'0' => array(
					"field" => "Lot",
					"js"	=> "lot"
			
					),
			'1' => array(
					"field" => "Roll Number",
					"js"	=> "roll_number"
					),
			'2' => array(
					"field" => "Kain Terpakai(Kg)",
					"js"	=> "kain_terpakai_kg"
			
					),
			'3' => array(
					"field" => "Qty Cutting(pcs)",
					"js"	=> "qty_cutting_pcs"
			
					),
			'4' => array(
					"field" => "Cons ws(Kg)",
					"js"	=> "cons_ws_kg"
			
					),
			'5' => array(
					"field" => "Cons m(Kg)",
					"js"	=> "cons_m_kg"
			
					),
			'6' => array(
					"field" => "Cons Act pcs(Kg)",
					"js"	=> "cons_act_pcs"
			
					),
			'7' => array(
					"field" => "Balance Cons",
					"js"	=> "balance_cons"
			
					),
			'8' => array(
					"field" => "Percentage",
					"js"	=> "percentage"
			
					),
			'9' => array(
					"field" => "Binding",
					"js"	=> "binding"
			
					),
			'10' => array(
					"field" => "Sisa Actual",
					"js"	=> "sisa_actual"
			
					),
			'11' => array(
					"field" => "Total Actual",
					"js"	=> "total_actual"
			
					),
					
			'12' => array(
					"field" => "Show Roll\/Balance",
					"js"	=> "show_roll_per_balance"
			
					)
		)
		return $array_static;
		
	}
	
	
	public function get_dynamic_field($id_so,$color){
		$_field_dynnamic = array();
		$_tmp_field = array(
			'0'		=>'Lembar Gelar',
			'1'		=>'Ratio',
			'2'		=>'Qty Pcs',
			'3'		=>'P Marker',
			'4'		=>'Efficiency',
			'5'		=>'Total Kain Act',
			'6'		=>'Majun Kg',
			'7'		=>'1 Ampar',
			'8'		=>'Total',
			'9'		=>'Total Pakai',
			'10'	=>'L.Kain',
			'11'	=>'Plt. Gelar'
		);
		$jumlah_spreading_so = $this->get_jumlah_spreading_so($id_so,$color,$id_item);
		$total_dynamic_kolom = count($_tmp_field) * $jumlah_spreading_so
		for($i=0;$i<$total_dynamic_kolom;$i++){



		}
	}	
	
	public function get_jumlah_spreading_so($id_so,$color,$id_item){
		$sql ="SELECT SR.id_number
		,SR.id_so 
		,SRD.id_jo
		,SRD.id_item
		FROM prod_spread_report_number SR
		INNER JOIN prod_spread_report_detail SRD ON SR.id_number = SRD.id_number
		WHERE SR.id_so = '{$id_so}' AND SR.color = '{$color}' AND SRD.id_item = '{$id_item}'
		GROUP BY SR.id_so,SR.id_number,SRD.id_item,SRD.id_jo";
		$tmp_array = $this->eksekusi_query($sql,"get_jumlah_spreading_so");
		$cnt = count($tmp_array);
		
		return $cnt;


		
	}
	
	
	
}
?>
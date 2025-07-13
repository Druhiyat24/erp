<?php 
$data = $_POST;
$code = $_POST['code'];
$id_sew_qc_in = $_POST['id'];
 //print_r($id_sew_qc_in );die();
if($code == '1'){
	$Proses = new Proses();
	$_outp = $Proses->get_list_data($id_sew_qc_in);
	$result = '{ "respon": "200", "status": "ok", "message": "1", "records": ['.$_outp.']    }';
	echo $result;
	die();
}
else{
	exit;
}
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
	
	
	public function get_list_data($id_sew_qc_in){
		$sql = "SELECT id_sew_qc_in_header,date_output,no_sew_qc_in,notes,id_cost,id_line,username,dateinput,time 
					FROM prod_qc_in_header WHERE id_sew_qc_in_header ='{$id_sew_qc_in}'";
		$row = $this->eksekusi_query($sql,"get_list_data");
		$_pop_size = array();
		$outp = '';
		for($i=0;$i<count($row);$i++){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id_sew_qc_in_header":"'.rawurlencode($row[$i]['id_sew_qc_in_header']).'",';		
			$outp .= '"date_output":"'. rawurlencode(date('d M Y',strtotime($row[$i]["date_output"]))).'",';	
			$outp .= '"no_sew_qc_in":"'. rawurlencode($row[$i]["no_sew_qc_in"]).'",';	
			$outp .= '"notes":"'. rawurlencode($row[$i]["notes"]).'",';	
			$outp .= '"id_cost":"'. rawurlencode($row[$i]["id_cost"]).'",';	
			$outp .= '"id_line":"'. rawurlencode($row[$i]["id_line"]).'",';	
			$outp .= '"username":"'. rawurlencode($row[$i]["username"]).'",';	
			$outp .= '"time":"'. rawurlencode($row[$i]["time"]).'",';	
			$outp .= '"dateinput":"'. rawurlencode($row[$i]["dateinput"]).'"}';	
		}
		return $outp;
	}
}
?>
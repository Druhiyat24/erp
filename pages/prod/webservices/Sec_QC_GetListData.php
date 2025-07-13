<?php 
$data = $_POST;
$code = $_POST['code'];
$id_sew_qc_out = $_POST['id'];
 //print_r($id_sew_qc_in );die();
if($code == '1'){
	$Proses = new Proses();
	$_outp = $Proses->get_list_data($id_sew_qc_out);
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
	
	
	public function get_list_data($id){
		$sql = "SELECT    A.id_sec_qc_header
			,A.date_output
			,A.inhouse_subkon
			,A.dept_subkon
			,A.no_sec_qc
			,A.notes
			,A.id_cost
			,A.username
			,A.dateinput 
			,GROUP_CONCAT(DISTINCT(B.id_panel))id_panel
			,GROUP_CONCAT(DISTINCT(C.id_proses))id_proses
				FROM prod_sec_qc_header A
				INNER JOIN (SELECT id_sec_qc_header,id_panel,id_proses FROM prod_sec_qc_detail WHERE id_sec_qc_header ='{$id}' GROUP BY id_panel )B
				ON A.id_sec_qc_header = B.id_sec_qc_header
				INNER JOIN (SELECT id_sec_qc_header,id_panel,id_proses FROM prod_sec_qc_detail WHERE id_sec_qc_header ='{$id}' GROUP BY id_proses )C
				ON A.id_sec_qc_header = B.id_sec_qc_header				
				 WHERE A.id_sec_qc_header ='{$id}'
				 GROUP BY A.id_sec_qc_header";
		$row = $this->eksekusi_query($sql,"get_list_data");
		$_pop_size = array();
		$outp = '';/*
					,A.inhouse_subkon
			,A.dept_subkon 
		*/
		//echo $sql;
		for($i=0;$i<count($row);$i++){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id_sec_qc_header":"'.rawurlencode($row[$i]['id_sec_qc_header']).'",';		
			$outp .= '"date_output":"'. rawurlencode(date('d M Y',strtotime($row[$i]["date_output"]))).'",';	
			$outp .= '"no_sec_qc":"'. rawurlencode($row[$i]["no_sec_qc"]).'",';	
			$outp .= '"notes":"'. rawurlencode($row[$i]["notes"]).'",';	
			$outp .= '"id_cost":"'. rawurlencode($row[$i]["id_cost"]).'",';	
			$outp .= '"username":"'. rawurlencode($row[$i]["username"]).'",';	
			$outp .= '"id_panel":"'. rawurlencode($row[$i]["id_panel"]).'",';
			$outp .= '"id_proses":"'. rawurlencode($row[$i]["id_proses"]).'",';
			$outp .= '"inhousesubcon":"'. rawurlencode($row[$i]["inhouse_subkon"]).'",';
			$outp .= '"deptsubcon":"'. rawurlencode($row[$i]["dept_subkon"]).'",';			
			$outp .= '"dateinput":"'. rawurlencode($row[$i]["dateinput"]).'"}';	
		}
		return $outp;
	}
}
?>
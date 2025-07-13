<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
$color = $_GET['color'];
//print_r($_GET['bppbno_req']);
$code = $_POST['code'];

$balance = 0;

// print_r($id_url);die();
if($code == '1'){
	$Proses = new Proses();
	$_outp = $Proses->get_item($_GET['id_so'],$_GET['color'],$_GET['id_number']);
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
	
	
	public function get_item($id_so,$color,$id_number){
		$sql = "SELECT
   A.color,
   A.id_so,
   C.bppbno id,
   D.bppbno_int,
   D.id_item,
   D.id_jo,
   A.id_number ,
   C.id_supplier,
   ITEM.itemdesc nama
   
FROM
   prod_spread_report_number A 
   INNER JOIN
      jo_det B 
      ON A.id_so = B.id_so 
   INNER JOIN
      bppb_req C 
      ON B.id_jo = C.id_jo 
   INNER JOIN
      bppb D 
      ON D.bppbno_req = C.bppbno 
   INNER JOIN
		masteritem ITEM ON D.id_item = ITEM.id_item
   INNER JOIN
      masteritem MI 
      ON MI.id_item = D.id_item 
   INNER JOIN
      (
         SELECT
            color,
            id_jo,
            id_item 
         FROM
            view_portal_bom
      )
      V_BOM 
      ON B.id_jo = V_BOM.id_jo 
      AND V_BOM.id_item = MI.id_gen 
WHERE
   A.id_number = '{$id_number}' 
   AND A.id_so = '{$id_so}' 
   AND V_BOM.color = '{$color}' 
   AND C.id_supplier = '432' 
GROUP BY
     D.id_item,D.id_jo	
		";
	
		$row = $this->eksekusi_query($sql,"get_item");
		$_pop_size = array();
		$outp = '';
		for($i=0;$i<count($row);$i++){
				$id = "{$row[$i]['id_jo']}_{$row[$i]['id_item']}";
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($id).'",';		
			$outp .= '"isi":"'. rawurlencode($row[$i]["nama"]).'"}';	
		}
		return $outp;
		
	
	}
}
?>
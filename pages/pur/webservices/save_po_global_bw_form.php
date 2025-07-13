<?php 
session_start(); //$username

include '../../forms/fungsi.php';
$username =$_SESSION['username'];
if(!ISSET($username)){
	$result = '{ "respon":"'.$respon.'", "message":"SESSION HABIS SILAHKAN LOGIN LAGI!","part" : "Validasi", "records":"0"}';
	exit;
}

/*  print_r($_POST);
		die();  */
//error_reporting(E_ALL);
		$data = $_POST;
		$detail = $data['detail'];
		$header = $data['header'];
	$getListData = new proses();
	$header['txtdrafpodate'] = fd($header['txtdrafpodate']);
	$header['txtetddate'] = fd($header['txtetddate']);
	$header['txtetadate'] = fd($header['txtetadate']);
	$header['txtexpdate'] = fd($header['txtexpdate']);
	$header['txtnotes'] = addslashes($header['txtnotes']);
	$curr = $header['curr'];		
if($header['format'] == '1' ){
	$header['txtdraftpo'] = $getListData -> generate_no_draft_po($header,$detail);
	$insert_header 	= $getListData->insert_header($header,$username);
	$last_id_po_draft	= $getListData->get_last_id_draft_po($header['txtdraftpo']);
	$insert_detail 	= $getListData->insert_detail($detail,$last_id_po_draft,$username,$curr);
	$insert_add_biaya	= $getListData->insert_add_biaya($last_id_po_draft,$username,$curr);
	$result = '{ "respon":"200", "message":"Data Berhasil Di Save","part" : "Finish", "records":"0"}';
	print_r($result);
}else if($header['format'] == '2'){
/* 		print_r($_POST);
		die();	 */
	$update_header 	= $getListData->update_header($header);
	$update_detail 	= $getListData->update_detail($detail,$header['curr']);
	$result = '{ "respon":"200", "message":"Data Berhasil Di Update","part" : "Finish", "records":"0"}';
	print_r($result);
	
}
else{
	exit;
}
//else{
//	exit;
//}
class proses{
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
	
	
	
	public function get_last_id_draft_po($draft_no){
		$sql = "SELECT id FROM po_header_draft ORDER BY id DESC LIMIT 1";
		$tmp_array = $this->eksekusi_query($sql,"get_last_id_invoice");
		return $tmp_array[0]['id'];
		
	}
	
	public function generate_no_draft_po($data_header,$data_detail){
		$jenis_item = $data_header['jenis_item'];

		$joselect="";
		$podate = $data_header['txtdrafpodate'];
		if($jenis_item != 'N'){
			for($i =0;$i<count($data_detail['idjo']);$i++)
				{	
					if($joselect=="")
					{	$joselect="'".$data_detail['idjo'][$i]."'"; }
					else
					{	$joselect=$joselect.",'".$data_detail['idjo'][$i]."'"; } 
			}
/* 			print_r($data_detail);
			die(); */
			$jmlws=$this->flookup_new("count(distinct id_so)","jo_det","id_jo in ($joselect)");
		}else{
			$jmlws=0;
			$joselect="";
		}
if($jmlws=="1")
	{	$nows="DRAFT_PO/".$this->flookup_new("kpno","jo_det jod inner join so on jod.id_so=so.id 
			inner join act_costing ac on so.id_cost=ac.id ","id_jo in ($joselect)");
		$cri2=$nows; 
	}
	else
	{	$nmbuyer=$this->flookup_new("distinct supplier_code","jo_det jod inner join so on jod.id_so=so.id 
			inner join act_costing ac on so.id_cost=ac.id 
			inner join mastersupplier ms on ac.id_buyer=ms.id_supplier","id_jo in ($joselect)");
		$cri2="DRAFT_PO/C/GLB/".$nmbuyer."/".date('my',strtotime($podate)); 
	}
	$cri3="DRAFT_PO/PO/".date('Y',strtotime($podate));
	$pono=urutkan_inq($cri3,$cri2);
	return $pono;
		
	}	
	
public function flookup_new($fld,$tbl,$criteria)
{	if ($fld!="" AND $tbl!="" AND $criteria!="")
	{	$sql = "Select $fld as namafld from $tbl Where $criteria ";
		$tmp_array = $this->eksekusi_query($sql,"flookup_new");
		$hasil = $tmp_array[0]['namafld'];
		return $hasil;
	}
}
	
	
	
	
	public function insert_header($header,$username){
		$insert = "insert into po_header_draft (username,draftno,draftdate,etd,eta,expected_date,id_supplier,id_terms,
			notes,n_kurs,ppn,tax,discount,pph,jenis,jml_pterms,fg_pkp,id_dayterms,tipe_com)
			values ('{$username}','{$header['txtdraftpo']}','{$header['txtdrafpodate']}','{$header['txtetddate']}','{$header['txtetadate']}','{$header['txtexpdate']}','{$header['cbosupp']}','{$header['txtid_terms']}',
			'{$header['txtnotes']}','{$header['n_kurs']}','{$header['ppn_nya']}','{$header['ppn_nya']}','{$header['txtdisc']}','{$header['txtdisc']}','{$header['jenis_item']}','{$header['txtdays']}','{$header['pkp']}','{$header['txtid_dayterms']}','{$header['txt_tipecom']}')";
/* 			echo $insert;
			die();  */
		$this->eksekusi_query_insert_update($insert,'insert_header');	
		
		return 1;
	}
	public function update_header($my_header){
		$update = "UPDATE po_header_draft 
					SET  id_terms			= 	'{$my_header['txtid_terms']}'
						,n_kurs				= 	'{$my_header['n_kurs']}'
						,etd 				=  	'{$my_header['txtetddate']}'
						,eta 				=  	'{$my_header['txtetadate']}'
						,expected_date		=  	'{$my_header['txtexpdate']}'		
						,tax				=  	'{$my_header['ppn_nya']}'
						,discount           =  	'{$my_header['txtdisc']}'
						,fg_pkp             =  	'{$my_header['pkp']}'
						,id_dayterms        =  	'{$my_header['txtid_dayterms']}'
						,notes        		=  	'{$my_header['txtnotes']}'
						,jml_pterms         =  	'{$my_header['txtdays']}'
						,tipe_com           =  	'{$my_header['txt_tipecom']}'
						WHERE id = '{$my_header['id']}'
						";
/* 						echo $update;
						die(); */
		$this->eksekusi_query_insert_update($update,'update_header');				
						
	}
	public function insert_detail($my_detail,$my_last_id,$username,$curr){
		
		
		//print_r($my_detail);
		$cnt = count($my_detail['idjo']);
		$trigger = $cnt -1;
		$insert = "INSERT INTO po_item_draft (id_po_draft,id_jo,id_gen,qty,unit,curr,price) VALUES ";
		for($i=0;$i<$cnt;$i++){
			if($my_detail['itemchk'][$i] =='on' ){
				if($i == $trigger){
					$penghubung = '';
				}else{
					$penghubung = ',';
				}
				$insert .="('{$my_last_id}','{$my_detail['idjo'][$i]}','{$my_detail['itembb'][$i]}','{$my_detail['itemqty'][$i]}','{$my_detail['itemunit'][$i]}','{$curr}','{$my_detail['itemprice'][$i]}'){$penghubung} ";				
			}
		}
/* 		echo $insert;
		die(); */
			return $this->eksekusi_query_insert_update($insert,'insert_detail');
	}

	public function insert_add_biaya($my_last_id,$username,$curr){
	
	$sql = "insert into po_add_biaya select '','".$my_last_id."',id_kategori,total,ppn,keterangan,'Y',created_by,created_at from po_add_biaya_temp where created_by = '".$username."'";
	insert_log($sql,$user);

	if ($sql) {
		$sql = "delete from po_add_biaya_temp where created_by = '".$username."'";
		insert_log($sql,$user);
	}
}

	
	public function update_detail($my_detail,$curr){
		
		$cnt = count($my_detail['idjo']);
		$trigger = $cnt - 1;
		for($i=0;$i<$cnt;$i++){
			$update = "UPDATE po_item_draft SET
						 unit 			= '{$my_detail['itemunit'][$i]}'
						,qty 			= '{$my_detail['itemqty'][$i]}'
						,price 			= '{$my_detail['itemprice'][$i]}'
						,curr 			= '{$curr}'
						WHERE id = '{$my_detail['id_poi'][$i]}'
			";
 			// echo $update;
			// die(); 
			$this->eksekusi_query_insert_update($update,'update_detail');
		}
		return '1';
	}	
}


?>





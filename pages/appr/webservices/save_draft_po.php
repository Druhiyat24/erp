<?php 
session_start(); //$username

include '../../forms/fungsi.php';
$username =$_SESSION['username'];
if(!ISSET($username)){
	$result = '{ "respon":"'.$respon.'", "message":"SESSION HABIS SILAHKAN LOGIN LAGI!","part" : "Validasi", "records":"0"}';
	return $result;
}
 

//error_reporting(E_ALL);
		$data = $_POST;
	$getListData = new proses();
	if(!ISSET($data['id'])){
		$result = '{ "respon":"'.$respon.'", "message":"SESSION HABIS SILAHKAN LOGIN LAGI!","part" : "Validasi", "records":"0"}';
		return $result;
		exit;		
	}
		$format = 1;
if($format == '1' ){
	$populasi_draft = array(
		'header' 	=> $getListData->get_header_draft($data['id']),
		'detail' 	=> $getListData->get_detail_draft($data['id']),
		'jumlah_jo'	=> $getListData->get_jumlah_jo($data['id']),
	);
	$pono 							= $getListData->generate_no_po($populasi_draft['header'],$populasi_draft['detail']);
	$insert_header 					= $getListData->insert_header($populasi_draft['header'],$username,$pono);
	$last_id_po						= $getListData->get_last_id_po($pono);
	$insert_detail 					= $getListData->insert_detail($populasi_draft['detail'],$last_id_po,$username);
	$update_status_draft_po 		= $getListData->update_status_po_draft($data['id'],$username);
	$result 						= '{ "respon":"200", "message":"Data Berhasil Di Save","part" : "Finish", "records":"0"}';	
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
	
	public function get_header_draft($id_draft){
		$sql = "SELECT * FROM po_header_draft WHERE id= '{$id_draft}'";
		$tmp_array = $this->eksekusi_query($sql,"get_header_draft");
		return $tmp_array;		
	}	
	public function get_detail_draft($id_draft){
		$sql = "SELECT * FROM po_item_draft WHERE id_po_draft= '{$id_draft}'";
		$tmp_array = $this->eksekusi_query($sql,"get_detail_draft");
		return $tmp_array;		
	}	
	public function get_jumlah_jo($id_draft){
		$sql = "SELECT COUNT(*)jlh FROM po_item_draft WHERE id_po_draft= '{$id_draft}' GROUP BY id_jo";
		$tmp_array = $this->eksekusi_query($sql,"get_jumlah_jo");
		
		return $tmp_array[0]['jlh'];		
	}
	
	public function get_last_id_po($draft_no){
		$sql = "SELECT id FROM po_header ORDER BY id DESC LIMIT 1";
		$tmp_array = $this->eksekusi_query($sql,"get_last_id_po");
		return $tmp_array[0]['id'];
		
	}
	
	
	public function generate_no_po($data_header,$data_detail){
		$jenis_item = $data_header[0]['jenis'];
		$joselect="";
		$podate = $data_header[0]['draftdate'];
		if($jenis_item != 'N'){
			for($i =0;$i<count($data_detail);$i++)
				{	
					if($joselect=="")
					{	$joselect="'".$data_detail[$i]['id_jo']."'"; }
					else
					{	$joselect=$joselect.",'".$data_detail[$i]['id_jo']."'"; } 
			}
/* 			print_r($data_detail);
			die(); */
			$jmlws=$this->flookup_new("count(distinct id_so)","jo_det","id_jo in ($joselect)");

				if($jmlws=="1")
					{	$nows=$this->flookup_new("kpno","jo_det jod inner join so on jod.id_so=so.id 
							inner join act_costing ac on so.id_cost=ac.id ","id_jo in ($joselect)");
						$cri2=$nows; 
					}
					else
					{	
			
				$nmbuyer=$this->flookup_new("distinct supplier_code","jo_det jod inner join so on jod.id_so=so.id 
							inner join act_costing ac on so.id_cost=ac.id 
							inner join mastersupplier ms on ac.id_buyer=ms.id_supplier","id_jo in ($joselect)");

				$cek_ws=$this->flookup_new("distinct ac.type_ws","jo_det jod inner join so on jod.id_so=so.id 
							inner join act_costing ac on so.id_cost=ac.id 
							inner join mastersupplier ms on ac.id_buyer=ms.id_supplier","id_jo in ($joselect)");


				if ($cek_ws == 'STD')
					{
							$cri2="C/".$nmbuyer."/".date('my',strtotime($podate)); 
					} else
					{
							$cri2="C/GLB/".$nmbuyer."/".date('my',strtotime($podate)); 
					}
				}
			
		}else{
			$jmlws=0;
			$joselect="";
			$cri2="PO/".date('my',strtotime($podate));
		}
		$cri3="PO/".date('Y',strtotime($podate));
		$pono=$this->urutkan_inq_new($cri3,$cri2);
		return $pono;
	}		
public function urutkan_inq_new($cri1,$cri2)
{	$hasil = $this->flookup_new("bpbno","tempbpb","mattype='$cri1'") + 1;
//echo $cri1;
	if ($hasil=='1')
	{	$sql="insert into tempbpb (mattype,bpbno) values ('$cri1','1')";
		insert_log($sql,'');
	}
	else
	{	$sql="update tempbpb set bpbno='$hasil' where mattype='$cri1'";
		insert_log($sql,'');
	}
	$nm_company=$this->flookup_new("company","mastercompany","company!=''");
	if(substr($cri2,0,13)=="EXP/EXIM-NAG/")
	{$hasil = trim(sprintf("%'.03d\n", $hasil))."/".trim($cri2);}
	else if($nm_company=="PT. Cheong Woon Indonesia")
	{$hasil = trim(substr($cri2,0,3).sprintf("%'.05d\n", $hasil).substr($cri2,3,8));
	 $hasil = preg_replace("/[\n\r]/","",$hasil);
	}
	else
	{$hasil = trim($cri2."/".sprintf("%'.05d\n", $hasil));}
	return $hasil;
}
	
	
public function flookup_new($fld,$tbl,$criteria)
{	if ($fld!="" AND $tbl!="" AND $criteria!="")
	{	$sql = "Select $fld as namafld from $tbl Where $criteria ";
		$tmp_array = $this->eksekusi_query($sql,"flookup_new");
		$hasil = $tmp_array[0]['namafld'];
		return $hasil;
	}
}
	
	
	
	
	public function insert_header($header,$username,$no_po){
		
		$header = $header[0];
		$header['notes'] = addslashes($header['notes']);
		$insert = "insert into po_header (username,pono,podate,etd,eta,expected_date,id_supplier,id_terms,
			notes,n_kurs,ppn,tax,discount,pph,jenis,jml_pterms,fg_pkp,id_dayterms,id_draft,d_start_installment,n_installment,fg_installment)
			values ('{$header['username']}','{$no_po}','{$header['draftdate']}','{$header['etd']}','{$header['eta']}','{$header['expected_date']}','{$header['id_supplier']}','{$header['id_terms']}',
			'{$header['notes']}','{$header['n_kurs']}','{$header['tax']}','{$header['tax']}','{$header['discount']}','{$header['pph']}','{$header['jenis']}','{$header['jml_pterms']}','{$header['fg_pkp']}','{$header['id_dayterms']}','{$header['id']}','{$header['start_installment']}','{$header['jumlah_installment']}','{$header['fg_installment']}')";
/*  			echo $insert;
			die();   */
		$this->eksekusi_query_insert_update($insert,'insert_header');	
		return 1;
	}

	public function insert_detail($my_detail,$my_last_id,$username){
		//print_r($my_detail);
		$cnt = count($my_detail);
		$trigger = $cnt -1;
		$insert = "INSERT INTO po_item (id_po,id_jo,id_gen,qty,unit,curr,price,cancel) VALUES ";
		for($i=0;$i<$cnt;$i++){
				if($i == $trigger){
					$penghubung = '';
				}else{
					$penghubung = ',';
				}
				$insert .="('{$my_last_id}','{$my_detail[$i]['id_jo']}','{$my_detail[$i]['id_gen']}','{$my_detail[$i]['qty']}','{$my_detail[$i]['unit']}','{$my_detail[$i]['curr']}','{$my_detail[$i]['price']}','{$my_detail[$i]['cancel']}'){$penghubung} ";
		}
/*   		echo $insert;
		die();   */
			return $this->eksekusi_query_insert_update($insert,'insert_detail');
	}
	public function update_status_po_draft($id_draft,$username){
		$update = "UPDATE po_header_draft SET app ='A'
					,app_by = '{$username}'
					,app_date = '".date('Y-m-d H:m:s')."'
					WHERE id = '{$id_draft}'
					
		";
/* 		echo $update;
		die(); */
		return $this->eksekusi_query_insert_update($update,'update_status_po_draft');
	}
}
?>





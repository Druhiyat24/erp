<?php 
session_start();
if((!ISSET($_SESSION['username'])) || EMPTY($_SESSION['username'])){
$result = '{ "status":"ok", "message":"1", "records":"SESSION HABIS"}';
	
}

		//$data = $_POST;
$data = (object)$_POST['datass'];
$data->username = $_SESSION['username'];
//print_r($data);

//if($data['code'] == '1' ){
	$Save = new Save();
	if($data->save == "INSERT" ){
		$data->kode = $Save->GenerateKode();
		$bpb = $Save->UpdateBPB_Bank($data);
		$List = $Save->Insert($data);
	}
	else if($data->save == "UPDATE" ){
		$bpb = $Save->UpdateBPB_Bank($data);
		$List = $Save->Update($data);
	}
print_r($List);
//}
//else{
//	exit;
//}
class Save {
	
	public function GenerateKode(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT COUNT(*)jumlah FROM invoice_commercial";
		$stmt = mysql_query($q);
		$jumlah = 0;		
		while($row = mysql_fetch_array($stmt)){
			$jumlah = $row['jumlah'];
		}
			//001/EXP/EXIM-NAG/2019
			$jumlah = intval($jumlah) + 1;
		$nilaiawal =sprintf("%03d", $jumlah);
		$nilaiawal= $nilaiawal."/EXP/EXIM-NAG/".date('Y');
		return $nilaiawal;
	}
	public function Insert($data){
		include __DIR__ .'/../../../include/conn.php';
		$q = "INSERT INTO invoice_commercial (
				bpbno,
				v_noinvoicecommercial,
				n_idinvoiceheader,
				v_from,
				v_to,
				v_pono,
				n_amount) VALUES('$data->bpb_no','$data->kode','$data->id_invoiceheader','$data->from','$data->to','$data->po','$data->amount')";
		$stmt = mysql_query($q);		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}	
	public function UpdateBPB_Bank($data){
		include __DIR__ .'/../../../include/conn.php';
		
		$qS = "SELECT invno FROM invoice_header WHERE id ='$data->id_invoiceheader'";
		//echo $q;
		$stmtS = mysql_query($qS);	
		$td = '';
		while($rowS = mysql_fetch_array($stmtS)){
			$invno = $rowS['invno'];
		}		
		
		$q = "UPDATE bpb SET invno = '$invno' WHERE bpbno ='$data->bpb_no'";
		//echo $q;
		$stmt = mysql_query($q);	
		$q = "UPDATE invoice_header SET n_idcoa = '$data->idcoa' WHERE id ='$data->id_invoiceheader'";

		$stmt = mysql_query($q);		
		return $stmt;
	}	
	
	
	public function Update($data){
		include __DIR__ .'/../../../include/conn.php';
		$q = "UPDATE invoice_commercial SET 
					bpbno = '$data->bpb_no',
					n_idinvoiceheader = '$data->id_invoiceheader',
					v_from = '$data->from',
					v_to = '$data->to',
					v_pono = '$data->po',
					n_amount = '$data->amount',
					v_userpost = '$data->username'
					WHERE 
					n_id	= '$data->id'";

		$stmt = mysql_query($q);	
	
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>





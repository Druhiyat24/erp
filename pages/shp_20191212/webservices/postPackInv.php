<?php 
session_start();

if((!ISSET($_SESSION['username'])) || EMPTY($_SESSION['username'])){
	echo "<script>alert('Login Expired! silahkan login kembali') </script>";
	echo "<script>window.location.href='../?mod=3v' </script>";
	
}


		//$data = $_POST;
//include_once '../../forms/journal_interface.php';
include '../../forms/journal_interface.php';
$data =$_POST;
//print_r($data);
$Save = new Save();
if(ISSET($_GET['id']))
{
	
	$Save = new Save();
	if($_GET['part'] == "PL" ){
		$List = $Save->PostPL($_GET['id']); 
		$Save->UpdateUserName($_GET['id'],$_SESSION['username'],$_GET['part']);
		//$Save->UpdateDateInvoice($_GET['id']);
		echo "<script>alert('Data Berhasil diupdate') </script>";
		echo "<script>window.location.href='../?mod=3v' </script>";
}
	else if($_GET['part'] == "INV" ){
		//echo "123";
		$List = $Save->PostINV($_GET['id']);
		$__data_inv = $Save->GetInvoiceNo($_GET['id']);
		$Save->UpdateUserName($_GET['id'],$_SESSION['username'],$_GET['part']);//UpdateDateInvoice($id)
		insert_inv_sales($__data_inv[0],$__data_inv[1]);		
		//insert_inv_sales($txtinvno,$faktur_pajak);	
	
		echo "<script>alert('Data Berhasil diupdate') </script>";
		echo "<script>window.location.href='../?mod=InvoiceCommercialPage' </script>";	
	}	
	
}
else{
if($data['code'] == '1' ){
	$Save = new Save();
	if($_POST['part'] == "PL" ){
		$List = $Save->PostPL($data['id']);
}
	else if($_POST['part'] == "INV" ){

		$List = $Save->PostINV($data['id']);
		
	}
print_r($List);
}
else{
	exit; 
}
	
	
}

	

class Save {
	function CompileQuery($query,$mode){
		
		include __DIR__ .'/../../../include/conn.php';
		$stmt = mysqli_query($conn_li,$query);	
		if(mysqli_error($conn_li)){
			if($mode == "CRUD"){
				$result = '{ "status":"no", "message":"'.mysqli_error($conn_li).'"}';
				return $result;				
				
			}
			else{
				$result = mysqli_error($conn_li)."__ERRTRUE";
				return $result;				
				
			}
		

		}	
		else{
			if($mode == "CRUD"){
				//print_r($query);
				$result = '{ "status":"ok", "message":"1"}';
				return $result;
			}
			else{
				
				if(mysqli_num_rows($stmt) == '0' ){
					$result = '{ "status":"ok", "message":"2"}';
					return '0';
				}
				else{
					return $stmt;
				}
			}
		} 
	}	
	public function PostPL($id){
		$q = "UPDATE invoice_header SET n_post = '1' WHERE id = '$id'";	
		$result = $this->CompileQuery($q,'CRUD');
		return $result;
	}
	public function PostINV($id){
		$q = "UPDATE invoice_header SET n_post = '2' WHERE id = '$id'";	
		$result = $this->CompileQuery($q,'CRUD');
		return $result;
	}	
	public function UpdateUserName($id,$username,$pagesnya){
		if($pagesnya == "PL"){
			$table = 'invoice_header';
			$id_nye = 'id';
		}
		else{
			$table = 'invoice_commercial';
			
			$id_nye = 'n_idinvoiceheader';
		}
		$q = "UPDATE $table SET v_userpost = '$username' WHERE $id_nye = '$id'";	
		$result = $this->CompileQuery($q,'CRUD');

		return $result;
	}	

	public function UpdateDateInvoice($id){
		$date = date('Y-m-d');
		$q = "UPDATE invoice_header SET invdate = '$date' WHERE $id = '$id'";
		$result = $this->CompileQuery($q,'CRUD');

		return $result;
	}	

	
	public function GetInvoiceNo($id){
		//include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT invno,v_fakturpajak FROM invoice_header WHERE id = '$id'";
		$MyList = $this->CompileQuery($q,'SELECT');
		
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{
					
		$faktur_pajak = '';
		$invno = '';
		$array = array();
 		while($row = mysqli_fetch_array($MyList)){
			$faktur_pajak = $row['v_fakturpajak'];
			$invno = $row['invno'];
			} 		
			
		}		
		}
		array_push($array,$invno);
		array_push($array,$faktur_pajak);
		return $array;
	}	
		
}





?>
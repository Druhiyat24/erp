<?php
class Privillages {
	function auth($username){
		$F_P_Cash_Pabrik = "0";
		$F_P_Cash_Kantor = "0";
		$F_P_Cash_Besar  = "0";
		$key = '0';
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT F_P_Cash_Pabrik, F_P_Cash_Kantor,F_P_Cash_Besar FROM userpassword WHERE username = '$username'";
		
		$stmt = mysqli_query($conn_li,$q);
		if(mysqli_error($conn_li)){
			$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'","lastDate":"", "records":"" }';
			return mysqli_error($conn_li);
		}else{
			while($row = mysqli_fetch_array($stmt)){	
				$F_P_Cash_Pabrik = $row['F_P_Cash_Pabrik'];
				$F_P_Cash_Kantor = $row['F_P_Cash_Kantor'];
				$F_P_Cash_Besar  = $row['F_P_Cash_Besar'];
			}					
		}
		if($F_P_Cash_Pabrik == '1' || $F_P_Cash_Kantor == '1' || $F_P_Cash_Besar == '1' ){
			$key = '1';
		}
		return $key;
	}
	function getListCoaCash($username){
		$F_P_Cash_Pabrik = "0";
		$F_P_Cash_Kantor = "0";
		$F_P_Cash_Besar  = "0";		

		$Coa_F_P_Cash_Pabrik= '10101';
		$Coa_F_P_Cash_Kantor= '10102';
		$Coa_F_P_Cash_Besar = '10103';
		$List_Coa = array();
		
		$wherenya = '';
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT F_P_Cash_Pabrik, F_P_Cash_Kantor,F_P_Cash_Besar FROM userpassword WHERE username = '$username'";
		$stmt = mysqli_query($conn_li,$q);
		if(mysqli_error($conn_li)){
			$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'","lastDate":"", "records":"" }';
			return mysqli_error($conn_li);
		}else{
			while($row = mysqli_fetch_array($stmt)){
				
				$F_P_Cash_Pabrik = $row['F_P_Cash_Pabrik'];
				$F_P_Cash_Kantor = $row['F_P_Cash_Kantor'];
				$F_P_Cash_Besar  = $row['F_P_Cash_Besar'];
			}
			if($F_P_Cash_Pabrik == '1' || $F_P_Cash_Kantor == '1' || $F_P_Cash_Besar == '1' ){
				if($F_P_Cash_Pabrik == '1'){
					array_push($List_Coa,$Coa_F_P_Cash_Pabrik);
				}
				if($F_P_Cash_Kantor == '1'){
					array_push($List_Coa,$Coa_F_P_Cash_Kantor);
				}
				if($F_P_Cash_Besar== '1'){
					array_push($List_Coa,$Coa_F_P_Cash_Besar);
				}
				
				$implode_nya = implode(',',$List_Coa);
				$wherenya = "AND id_coa IN ($implode_nya)";
					//	echo $implode_nya ;
			}
		}
		return $wherenya;		
	}	
	
	
	
}




?>
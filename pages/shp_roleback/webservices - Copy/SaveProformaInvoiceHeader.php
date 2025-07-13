<?php 
		//$data = $_POST;
$data = (object)$_POST['data'];

//print_r(urldecode($data->detail[0][so_no]));
//die();

//if($data['code'] == '1' ){
	$Save = new Save();
	
if($data->save == "INSERT"){
	$Header = $Save->Insert($data);
	$List = $Save->InserDetail($data);
	
}	
else{
	$Header = $Save->Update($data);
	$List = 0;
}
	
$result = '{ "status":"ok", "message":"1","id":"'.$List.'"}';
print_r($result);
//}
//else{
//	exit;
//}
class Save {
	public function GenerateKode(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT COUNT(*)jumlah FROM shp_pro_invoice_header";
		$stmt = mysql_query($q);
		$jumlah = 0;		
		while($row = mysql_fetch_array($stmt)){
			$jumlah = $row['jumlah'];
		}
			//001/EXP/EXIM-NAG/2019
			$jumlah = intval($jumlah) + 1;
		$nilaiawal =sprintf("%03d", $jumlah);
		$nilaiawal= $nilaiawal."/PRO/INV-NAG/".date('Y');
		return $nilaiawal;
	}

	
	public function Insert($data){
		$data->etd = date("Y-m-d", strtotime($data->etd));
		$data->invdate = date("Y-m-d", strtotime($data->invdate));
		$data->eta = date("Y-m-d", strtotime($data->eta));
		$data->eta_lax = date("Y-m-d", strtotime($data->eta_lax));		
		include __DIR__ .'/../../../include/conn.php';	
		
		//print_r($data);
		//WWWdie();
		$data->invno = $this->GenerateKode();
		$q = "INSERT INTO shp_pro_invoice_header(
	 invno            	
    ,invdate           
    ,id_buyer          
    ,consignee         
    ,shipper           
    ,notify_party      
    ,country_of_origin 
    ,manufacture_address
    ,vessel_name       
    ,port_of_loading   
    ,port_of_discharge 
    ,port_of_entrance  
    ,lc_no            	
    ,lc_issue_by       
    ,hs_code           
    ,etd           		
    ,eta           		
    ,eta_lax           
    ,id_pterms         
    ,shipped_by        
    ,route            	
    ,ship_to           
    ,nw            		
    ,gw            		
    ,measurement       
    ,container_no      
    ,seal_no)
	VALUES(
		'$data->invno'
	    ,'$data->invdate'
	    ,'$data->id_buyer'
	    ,'$data->consignee'
	    ,'$data->shipper'
	    ,'$data->notify_party'
        ,'$data->country_of_origin'
        ,'$data->manufacture_address'
        ,'$data->vessel_name'
        ,'$data->port_of_loading'
        ,'$data->port_of_discharge'
        ,'$data->port_of_entrance'
        ,'$data->lc_no'
        ,'$data->lc_issue_by'
        ,'$data->hs_code'
        ,'$data->etd'
        ,'$data->eta'
        ,'$data->eta_lax'
        ,'$data->id_pterms'
        ,'$data->shipped_by'
        ,'$data->route'
        ,'$data->ship_to'
        ,'$data->nw'
        ,'$data->gw'
        ,'$data->measurement'
        ,'$data->container_no'
        ,'$data->seal_no'
		)";
		$stmt = mysql_query($q);	
		
		
		$CHECKID = "SELECT id FROM shp_pro_invoice_header WHERE invno = '$data->invno'";
		$stmt = mysql_query($CHECKID);

		while($row = mysql_fetch_array($stmt)){
			$idinv = $row['id'];

		}	
	
		$x=$data->detail[0]['idsod'];
		$q = "INSERT INTO shp_estimatepackinglist(n_id_invoice,n_so_id) VALUES('$idinv','$x')";

		$stmt = mysql_query($q);
		return true;	
	}
		public function Update($data){
		//print_r($data);
		$data->etd = date("Y-m-d", strtotime($data->etd));
		$data->invdate = date("Y-m-d", strtotime($data->invdate));
		$data->eta = date("Y-m-d", strtotime($data->eta));
		$data->eta_lax = date("Y-m-d", strtotime($data->eta_lax));
		//print_r($data);
		
		include __DIR__ .'/../../../include/conn.php';
		$q = "UPDATE invoice_header SET 
		invno            	='$data->invno'
		,invdate            ='$data->invdate'
		,id_buyer           ='$data->id_buyer'
		,consignee          ='$data->consignee'
		,shipper            ='$data->shipper'
		,notify_party       ='$data->notify_party'
		,country_of_origin  ='$data->country_of_origin'
		,manufacture_address='$data->manufacture_address'
		,vessel_name        ='$data->vessel_name'
		,port_of_loading    ='$data->port_of_loading'
		,port_of_discharge  ='$data->port_of_discharge'
		,port_of_entrance   ='$data->port_of_entrance'
		,lc_no            	='$data->lc_no'
		,lc_issue_by        ='$data->lc_issue_by'
		,hs_code            ='$data->hs_code'
		,etd           		='$data->etd'
		,eta           		='$data->eta'
		,eta_lax            ='$data->eta_lax'
		,id_pterms          ='$data->id_pterms'
		,shipped_by         ='$data->shipped_by'
		,route            	='$data->route'
		,ship_to           	='$data->ship_to'
		,nw            		='$data->nw'
		,gw            		='$data->gw'
		,measurement        ='$data->measurement'
		,container_no       ='$data->container_no'
		,seal_no            ='$data->seal_no'
		WHERE 
		id					= '$data->id'";
		$stmt = mysql_query($q);		
		
		
		
		

		return true;
	}

	function InserDetail($data){
		$detail = $data->detail;
		$so =urldecode($detail[0]['so_no']);
		$idinv = $data->id;
		include __DIR__ .'/../../../include/conn.php';
		$CHECKID = "SELECT id FROM shp_pro_invoice_header WHERE invno = '$data->invno'";
		$stmt = mysql_query($CHECKID);

		while($row = mysql_fetch_array($stmt)){
			$idinv = $row['id'];

		}		
		
		
		$CHECK = "SELECT count(v_noso) count FROM shp_pro_invoice_detail WHERE v_noso = '$so'";
		$stmt = mysql_query($CHECK);
		while($row = mysql_fetch_array($stmt)){
			$count = $row['count'];

		}
		if(ISSET($count) || !EMPTY($count) ){
			
			if($count  == '0'){
				//echo count($detail);
				for($x=0;$x<count($detail);$x++){
					
				$q = "INSERT INTO shp_pro_invoice_detail (id_inv,v_noso,qty,unit,price,lot,carton,id_so_det) VALUES('$idinv','".urldecode($detail[$x]['so_no'])."','".$detail[$x]['qty']."','".$detail[$x]['unit']."'
				,'".$detail[$x]['price']."','".$detail[$x]['lot']."','".$detail[$x]['carton']."','".$detail[$x]['idsod']."')";

				
					$stmt = mysql_query($q);	
			
			
			}
				}
			else{
				$ss= '';
			} 
		}

				return $idinv;

	}
}




?>





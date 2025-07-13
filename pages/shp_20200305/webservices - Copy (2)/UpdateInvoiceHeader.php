<?php 
session_start();
		//$data = $_POST;
$data = (object)$_POST['data'];
$data->v_userpost =$_SESSION['username'];
//print_r(urldecode($data->detail[0][so_no]));
//die();
//print_r($_SESSION);
//print_r($data);
//die();
$Save = new Save();
if($data->crud == 'INSERT'){ 
	$header = $Save->Insert($data);
	//print_r($header);
	$detail = $Save->Detail($header);
	//die();
}else if($data->crud == 'UPDATE'){ 

	$header = $Save->Update($data);
	//print_r($data);
	//print_r($header);
	$detail = $Save->Detail($header);
}
//if($data['code'] == '1' ){
//$Header = $Save->Update($data);
//$List = $Save->InserDetail($data);
//print_r($data);
$result = '{ "status":"ok", "message":"1"}';
print_r($result);
//}
//else{
//	exit;
//}
class Save {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT
				A.id
				,A.invno
				,B.id_journal
			FROM invoice_header A
				LEFT JOIN(
					SELECT id_journal,reff_doc FROM fin_journal_h 
				
				)B ON A.invno = B.reff_doc
				LEFT JOIN(
					SELECT v_idjournal,v_fakturpajak FROM fin_journalheaderdetail
				)C ON C.v_idjournal =  B.id_journal
			WHERE A.id = '$id'";
	$stmt = mysql_query($q);	
	$row = mysql_fetch_array($stmt);
	return $row['id_journal'];	}	

	public function getId_invoice($myData){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT id,invno FROM invoice_header WHERE invno ='$myData->invno'";
		//echo $q;
		//die();
	$stmt = mysql_query($q);	
	$row = mysql_fetch_array($stmt);
	return $row['id'];	
	}	
	public function Detail($datass){
	//	print_r($datass->detail['data']);
		$datass->id = $this->getId_invoice($datass);
		$count =count($datass->detail['data']);

		for($i=0;$i<$count;$i++){

		if($datass->detail['data'][$i]['id_inv_det'] == '0' || $datass->detail['data'][$i]['id_inv_det'] == '' ){
			//	echo "123";
				$save_det = $this->Insert_detail($datass,$datass->detail['data'][$i]);
			}else{
				$save_det = $this->Update_detail($datass,$datass->detail['data'][$i]);
			}
		}
	}
	public function Insert_detail($headers,$items){
		$items = (object)$items;
		
		//print_r($items);

		include __DIR__ .'/../../../include/conn.php';	
		include __DIR__ .'../../forms/journal_interface.php';
		include __DIR__ .'../../forms/fungsi.php';	
		$q = "INSERT INTO invoice_detail (id_inv,id_so_det,qty,unit,price,lot,carton,bppbno) VALUES(
				'$headers->id'
				,'$items->id_sod'
				,'$items->qty_invoice_val'
				,'$items->unit_val'
				,'$items->price_val'
				,'$items->lot_val'
				,'$items->carton_val'
				,'$items->bppbno_int'
		)";
		//echo $q;
		//die();
		$stmt = mysql_query($q);
		return true;
		
	}
	public function Update_detail($headers,$items){
		$items = (object)$items;
		include __DIR__ .'/../../../include/conn.php';	
		include __DIR__ .'../../forms/journal_interface.php';
		include __DIR__ .'../../forms/fungsi.php';			
		$q = "UPDATE invoice_detail SET  
				qty = '$items->qty_invoice_val'
				,price = '$items->price_val'
				,lot = '$items->lot_val'
				,carton = '$items->carton_val'
				WHERE id ='$items->id_inv_det'	
				";
		$stmt = mysql_query($q);
		return true;
	}	
	public function Insert($data){
		include __DIR__ .'/../../../include/conn.php';	
		include '../../forms/journal_interface.php';
		include '../../forms/fungsi.php';
		$data->etd = date("Y-m-d", strtotime($data->etd));
		$data->invdate = date("Y-m-d", strtotime($data->invdate));
		$data->eta = date("Y-m-d", strtotime($data->eta));
		$data->eta_lax = date("Y-m-d", strtotime($data->eta_lax));	//lc_issue_by
		$data->lc_issue_by = date("Y-m-d", strtotime($data->lc_issue_by));

		$date=date('Y-m-d');
			if($data->typeinvoice == '1'){
				$cri2="LOC/EXIM-NAG/".date('Y',strtotime($date));
				$data->invno=urutkan_inq_local("LOC-".date('Y',strtotime($date)),$cri2); 
				//$mod2="3v_L";
			}else if($data->typeinvoice == '2'){ 
				$cri2="EXP/EXIM-NAG/".date('Y',strtotime($date));
				$data->invno=urutkan_inq("EXP-".date('Y',strtotime($date)),$cri2); 	
				//$mod2="3v";
			}		
			$data->v_codepaclist = generate_packing_list("PL", $date,$con_new);
			$q ="insert into invoice_header 
				(invno				
				,date_paclist
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
				,seal_no
				,n_typeinvoice
				,v_fakturpajak
				,v_userpost
				,v_codepaclist)
					values 
				(
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
					,'$data->typeinvoice'
					,'$data->faktur_pajak' 
					,'$data->v_userpost'
					,'$data->v_codepaclist'
				)	
					";			
			$stmt = mysql_query($q);	
			//get id inv = 
			$data->id = $this->getId_invoice($data);
			$sql = "INSERT INTO invoice_commercial (v_noinvoicecommercial,n_idinvoiceheader) VALUES('$data->invno','$data->id')";
			$stmt = mysql_query($sql);
			return $data;
			
	}
	
	
	public function Update($data){
		//print_r($data);
		$data->etd = date("Y-m-d", strtotime($data->etd));
		$data->invdate = date("Y-m-d", strtotime($data->invdate));
		$data->eta = date("Y-m-d", strtotime($data->eta));
		$data->eta_lax = date("Y-m-d", strtotime($data->eta_lax));	//lc_issue_by
		$data->lc_issue_by = date("Y-m-d", strtotime($data->lc_issue_by));
		//print_r($data);
		include __DIR__ .'/../../../include/conn.php';
		$q = "UPDATE invoice_header SET 
		/* invno            	='$data->invno' */
		date_paclist            ='$data->invdate'
		/*,id_buyer           ='$data->id_buyer' */
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
		,v_fakturpajak            ='$data->faktur_pajak'
		WHERE 
		id					= '$data->id'";
		$stmt = mysql_query($q);		
		$id_journal = $this->get($data->id);

		$q = "UPDATE fin_journalheaderdetail SET 
		v_fakturpajak            	='$data->faktur_pajak'
		WHERE 
		v_idjournal					= '$id_journal'";

		$stmt = mysql_query($q);

		
			return $data;
	}

	function InserDetail($data){
		$detail = $data->detail;
		$so =urldecode($detail[0]['so_no']);
		$idinv = $data->id;
		include __DIR__ .'/../../../include/conn.php';
		$CHECK = "SELECT count(v_noso) count FROM invoice_detail WHERE v_noso = '$so'";
		$stmt = mysql_query($CHECK);
	
		
		while($row = mysql_fetch_array($stmt)){
			$count = $row['count'];

		}
		if(ISSET($count) || !EMPTY($count) ){
			
			if($count  == '0'){
				//echo count($detail);
				for($x=0;$x<count($detail);$x++){
					
				$q = "INSERT INTO invoice_detail (id_inv,v_noso,qty,unit,price,lot,carton,id_so_det) VALUES('$idinv','".urldecode($detail[$x]['so_no'])."','".$detail[$x]['qty']."','".$detail[$x]['unit']."'
				,'".$detail[$x]['price']."','".$detail[$x]['lot']."','".$detail[$x]['carton']."','".$detail[$x]['idsod']."')";
				

				
					$stmt = mysql_query($q);	
			
			
			}
				}
			else{
				$ss= '';
			} 
		}

				return true;

	}
}




?>





<?php 
session_start();
include "../../forms/fungsi.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
		$data = $_GET;
		//print_r($data);
//$data = (object)$_POST['data'];
$getListData = new getListData();
//if($data['code'] == '1' ){
$List = $getListData->get($_GET['bppbno_int']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	function CompileQuery($query,$mode){
		include __DIR__ .'/../../../include/conn.php';
		$stmt = mysqli_query($conn_li,$query);	
		if(mysqli_error($conn_li)){
		
			$result = mysqli_error($conn_li)."__ERRTRUE";
			return $result;
		}	
		else{
			if($mode == "CRUD"){
				print_r($query);
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
	public function get($bppb){
		$multiple = json_decode($bppb);
		
			$count = count($multiple->arraynya);
			$trigger = $count - 1;
			$val_bppb = '';
			$val_idcosting = '';
			$val_detail_type = '';
			$id_inv = '';
			for($x=0;$x<$count;$x++){
				if($x == $trigger){
					$val_bppb .= "'".$multiple->arraynya[$x]->bppbno_int."'";
					$val_idcosting .= "'".$multiple->arraynya[$x]->idcosting."'";
				}else{
					$val_bppb .= "'".$multiple->arraynya[$x]->bppbno_int."',";
					$val_idcosting .= "'".$multiple->arraynya[$x]->idcosting."',";
				}

				$val_data_type = $multiple->arraynya[$x]->detail_type;
			
				$id_inv = $multiple->arraynya[$x]->id_inv;
			
			}
			if($count < 1){
				$val_idcosting = "'XX'";
				$val_bppb = "'XX'";
				
			}
		if($id_inv == '0'){
			$where_inv = '';
		
		}else{
			$where_inv = "AND mo.id_inv = '$id_inv'";
		}
		$q = "select 
				 sod.id idsod
				,md.country_name
				,ifnull(mo.price_invoice,sod.price)price
				,mo.qty qtyout
				,ifnull(mo.lot,0)lot
				,ifnull(mo.carton,0)carton
				,ifnull(mo.id_inv_det,0)id_inv_det
				,ifnull(mo.id_inv,0)id_inv
				,ifnull(mo.bppbno_inv,0)bppbno_inv
				,co.qty qtyprev
				,(ifnull(co.qty,0) - ifnull(mo.qty,0) ) bal
				,co.bppbno
				,co.bppbno_int
				,co.id_so_det
				,so.so_no
				,so.buyerno
				,sod.dest
				,sod.color
				,sod.size 
				,sod.qty
				,sod.price price_sod
				,so.unit
				,act.kpno
				,act.styleno				
        from so 
		LEFT join so_det sod on so.id=sod.id_so  
		LEFT join 
        (select a.id_so_det,SUM(a.qty) qty,a.bppbno,a.bppbno_int from bppb a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost IN ($val_idcosting) GROUP BY a.id_so_det) co on sod.id=co.id_so_det
        left join 
        (select a.id_so_det,SUM(a.qty) qty,a.lot,a.carton,a.price price_invoice,a.bppbno bppbno_inv,a.id id_inv_det,a.id_inv from invoice_detail a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost IN ($val_idcosting) GROUP BY a.id_so_det) mo on sod.id=mo.id_so_det
        LEFT JOIN master_destination md ON md.id = sod.dest 
		LEFT JOIN act_costing act ON act.id = so.id_cost
		where so.id_cost IN($val_idcosting) AND co.bppbno_int IN($val_bppb) $where_inv
		";
		//echo "<pre>".$q."</pre>";
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

		
		
		$outp = '';
 		$my_id = 1; 
		while($row = mysqli_fetch_array($MyList)){

		
		$unit = '';
		$price = '';
		$qty_invoice = ''; //.$row['qtyout']
		$carton = '';
		$lot = '';

if($val_data_type == '1'){
	$row['price'] = $row['price_sod'];
	$row['qtyout'] = '';
	$row['carton'] = '';
	$row['lot']    = '';
	$row['id_inv_det'] = '';
	
}



		$unit .= "<input onkeyup='handledetail(this)' style='width:50%' readonly value='".$row['unit']."'  type='text' class='UNIT__".$my_id."'>";
		$price .= "<input onkeyup='handledetail(this)' style='width:50%' type='text' value='".$row['price']."' class='PRICE__".$my_id."'>";
		$qty_invoice .= "<input onkeyup='handledetail(this)' style='width:50%' type='text' value='".$row['qtyout']."' class='QTYINV__".$my_id."'>";
		$carton .= "<input onkeyup='handledetail(this)' style='width:50%' type='text' value='".$row['carton']."'  class='CARTON__".$my_id."'>";
		$lot .= "<input onkeyup='handledetail(this)' style='width:50%' type='text' value='".$row['lot']."'  class='LOT__".$my_id."'>";

			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.$my_id.'",';
			$outp .= '"ws":"'.$row["kpno"].'",';
			$outp .= '"id_sod":"'.$row["idsod"].'",';
			$outp .= '"id_inv":"'.$row["id_inv"].'",';
			$outp .= '"id_inv_det":"'.$row["id_inv_det"].'",';
			$outp .= '"so":"'.$row["so_no"].'",'; 
			$outp .= '"buyerpo":"'.$row['buyerno'].'",'; 
			$outp .= '"dest":"'.$row['country_name'].'",'; 
			$outp .= '"bppbno":"'.$row['bppbno'].'",';
			$outp .= '"bppbno_int":"'.$row['bppbno_int'].'",';  
			$outp .= '"color":"'.$row['color'].'",'; 
			$outp .= '"size":"'.$row['size'].'",'; 
			$outp .= '"qty_so":"'.$row['qty'].'",'; 
			$outp .= '"qty_bpb":"'.$row['qtyprev'].'",'; 
			$outp .= '"qty_invoice_val":"'.$row['qtyout'].'",'; 
			$outp .= '"qty_invoice":"'.rawurlencode($qty_invoice).'",'; 
			$outp .= '"unit":"'.rawurlencode($unit).'",';  
			$outp .= '"unit_val":"'.$row['unit'].'",'; 
			$outp .= '"bal":"'.$row['bal'].'",'; 
			$outp .= '"price":"'.rawurlencode($price).'",'; 
			$outp .= '"price_val":"'.rawurlencode($row['price']).'",'; 
			$outp .= '"carton":"'.rawurlencode($carton).'",'; 
			$outp .= '"carton_val":"'.$row['carton'].'",'; 
			$outp .= '"styleno":"'.$row['styleno'].'",'; 
			$outp .= '"lot":"'.rawurlencode($lot).'",'; 
			$outp .= '"lot_val":"'.$row['lot'].'"}'; 
			$my_id++;
		} 		
			$result = '{"data":['.$outp.']}';	
			}		
		}
		return $result;
	}
}




?>





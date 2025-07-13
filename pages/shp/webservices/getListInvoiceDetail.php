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
$List = $getListData->get($_GET['bppbno_int'],$_GET['id']);
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
	public function get($bppb,$id_nya){
		$multiple = json_decode($bppb);
		
			$count = count($multiple->arraynya);
			$trigger = $count - 1;
			$val_bppb = '';
			$val_idcosting = '';
			$val_detail_type = '';
			$id_inv = '';
			$id =$id_nya;
			for($x=0;$x<$count;$x++){
				if($x == $trigger){
					$val_bppb .= "'".$multiple->arraynya[$x]->bppbno_int."'";
					$val_idcosting .= "'".$multiple->arraynya[$x]->idcosting."'";
					$val_ex = "IN($val_bppb)";
				}else{
					$val_bppb .= "'".$multiple->arraynya[$x]->bppbno_int."',";
					$val_idcosting .= "'".$multiple->arraynya[$x]->idcosting."',";
					$val_ex = "IS NULL";
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
			$where_inv = "AND( mo.id_inv = '$id_inv'  OR mo__.id_inv = '$id_inv')";
		}
/* 		$q = "select 
				 sod.id idsod
				,md.country_name
				,ifnull(mo.price_invoice,sod.price)price
				,ifnull(mo.qty,mo__.qty)qtyout
				,mo.id_inv
				,co.qty qty_bppb
				,mo__.qty qty_n
				,mo__.id_inv id_inv_n
				,ifnull(mo.qty,mo__.qty)qty
				,mo.id_inv id_inv
				,ifnull(mo.lot,ifnull(mo__.lot,0))lot
				,ifnull(mo.carton,ifnull(mo__.carton,0))carton
				,ifnull(mo.id_inv_det,ifnull(mo__.id_inv_det,0))id_inv_det
				,ifnull(mo.id_inv,ifnull(mo__.id_inv,0))id_inv
				,ifnull(mo.bppbno_inv,0)bppbno_inv
				,co.qty qtyprev
				,(ifnull(co.qty,0) - ifnull(mo.qty,0) - ifnull(mo__.qty,0) ) bal
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
				,IC.bpbno			
        from so 
		LEFT join so_det sod on so.id=sod.id_so  
		LEFT join 
        (select a.id_so_det,SUM(a.qty) qty,a.bppbno,a.bppbno_int from bppb a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost IN ($val_idcosting) GROUP BY a.id_so_det) co on sod.id=co.id_so_det
        left join 
        (select a.id_so_det,SUM(a.qty) qty,a.lot,a.carton,a.price price_invoice,a.bppbno bppbno_inv,a.id id_inv_det,a.id_inv from invoice_detail a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where a.bppbno IS NULL AND d.id_cost IN ($val_idcosting) GROUP BY a.id_so_det) mo on sod.id=mo.id_so_det
        LEFT JOIN master_destination md ON md.id = sod.dest 
		LEFT JOIN act_costing act ON act.id = so.id_cost
		LEFT JOIN(SELECT id,invno FROM invoice_header)IH ON IH.id = mo.id_inv
		LEFT JOIN(SELECT v_noinvoicecommercial, bpbno FROM invoice_commercial)IC ON co.bppbno = IC.bpbno AND IH.invno = IC.v_noinvoicecommercial
		LEFT JOIN(        select a.bppbno,a.id_so_det,SUM(a.qty) qty,a.lot,a.carton,a.price price_invoice,a.bppbno bppbno_inv_n,a.id id_inv_det,a.id_inv from invoice_detail a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where
		   1=1 AND a.bppbno IS NOT NULL	AND
		d.id_cost IN ($val_idcosting) GROUP BY a.id_so_det
       )mo__ ON sod.id=mo__.id_so_det
		where so.id_cost IN($val_idcosting) AND co.bppbno_int IN($val_bppb) 
		$where_inv
		"; */
		$q="
SELECT  MASTER.bppbno_int
		,MASTER.country_name
       ,MASTER.id_det_inv_s
       ,MASTER.bppbno
       ,MASTER.qty
       ,MASTER.price
       ,MASTER.size
       ,MASTER.id_so_det idsod
       ,MASTER.qty_so
       ,MASTER.price_so
       ,MASTER.unit
       ,MASTER.color
       ,MASTER.kpno
       ,MASTER.styleno
       ,MASTER.qty_inv
       ,MASTER.price_inv
       ,MASTER.s_q
       ,MASTER.s_p
       ,MASTER.d_q
       ,MASTER.d_p
       ,MASTER.t_q
       ,MASTER.t_p       
	   ,(MASTER.qty -  MASTER.qty_inv)bal  
	   ,MASTER.id_inv
	   ,MASTER.id_inv_det
	   ,MASTER.so_no
	   ,MASTER.buyerno
	   ,MASTER.carton
	   ,MASTER.carton_to
	   ,MASTER.nw
	   ,MASTER.gw
	   ,MASTER.lot
FROM(

	SELECT   BKB.bppbno_int
			,INV_S.id_so_det id_det_inv_s
			,MD.country_name
			,BKB.bppbno
			,BKB.qty
			,BKB.price
			,SO.so_no
			,SO.buyerno
			,SOD.size
			,SOD.id id_so_det
			,SOD.qty qty_so
			,SOD.price price_so
			,SOD.unit
			,SOD.color
			,ACT.kpno
			,ACT.styleno
			,ifnull(INV_S.qty,ifnull(INV_D.qty,ifnull(INV_T.qty,0))) qty_inv
			,ifnull(INV_S.price,ifnull(INV_D.price,0)) price_inv
			,INV_S.qty s_q
			,INV_S.price s_p
			,INV_D.qty d_q
			,INV_D.price d_p	
			,INV_T.qty t_q
			,INV_T.price t_p				
			,ifnull(INV_S.id_inv,ifnull(INV_D.id_inv,0)) id_inv
			,ifnull(INV_S.id_inv_det,ifnull(INV_D.id_inv_det,ifnull(INV_T.id_inv_det,0))) id_inv_det
			,ifnull(INV_S.carton,ifnull(INV_D.carton,ifnull(INV_T.carton,0))) carton
			,ifnull(INV_S.carton_to,ifnull(INV_D.carton_to,ifnull(INV_T.carton_to,0))) carton_to
			,ifnull(INV_S.nw,ifnull(INV_D.nw,ifnull(INV_T.nw,0))) nw
			,ifnull(INV_S.gw,ifnull(INV_D.gw,ifnull(INV_T.gw,0))) gw
			,ifnull(INV_S.lot,ifnull(INV_D.lot,ifnull(INV_T.lot,0))) lot
			FROM bppb BKB
			LEFT JOIN
				(SELECT id,id_so,dest,n_id_dest,size,qty,unit,price,color FROM so_det)SOD ON BKB.id_so_det = SOD.id
			LEFT JOIN
				so SO ON SO.id = SOD.id_so
			LEFT JOIN
				master_destination MD ON MD.id = SOD.n_id_dest				
			LEFT JOIN
				act_costing ACT ON ACT.id = SO.id_cost
			LEFT JOIN(
			SELECT B.bpbno,A.id id_inv_det,A.id_inv,A.id_so_det,A.qty,A.price,A.lot,A.carton,A.carton_to,A.nw,A.gw,A.composition,A.bppbno FROM invoice_detail A
					LEFT JOIN
						(SELECT n_idinvoiceheader,bpbno FROM invoice_commercial)B ON A.id_inv = B.n_idinvoiceheader
					WHERE 1=1 AND  B.bpbno LIKE '%OUT%' AND B.bpbno IS NOT NULL
			)INV_S ON BKB.bppbno_int = INV_S.bppbno AND BKB.id_so_det = INV_S.id_so_det
			LEFT JOIN(
			SELECT B.bpbno,A.id id_inv_det,A.id_inv,A.id_so_det,A.qty,A.price,A.lot,A.carton,A.carton_to,A.nw,A.gw,A.composition,A.bppbno FROM invoice_detail A
					LEFT JOIN
						(SELECT n_idinvoiceheader,bpbno FROM invoice_commercial)B ON A.id_inv = B.n_idinvoiceheader
					WHERE 1=1 AND  B.bpbno LIKE '%SJ%' AND B.bpbno IS NOT NULL
			)INV_D ON BKB.bppbno = INV_D.bppbno AND BKB.id_so_det = INV_D.id_so_det	
			

			LEFT JOIN(
			SELECT A.id id_inv_det,A.id_inv,A.id_so_det,A.qty,A.price,A.lot,A.carton,A.carton_to,A.nw,A.gw,A.composition,A.bppbno FROM invoice_detail A
				WHERE A.bppbno $val_ex
			)INV_T ON BKB.bppbno_int = INV_T.bppbno AND BKB.id_so_det = INV_T.id_so_det				
			
	WHERE BKB.bppbno_int IN ($val_bppb)
)MASTER";
		//echo "<pre>".$q."</pre>";
		$MyList = $this->CompileQuery($q,'SELECT');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			    if (!is_object($MyList)) {
					$EXP = explode("__ERRTRUE",$MyList);
					if($EXP[1]){
						$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
					}
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
		$carton_to = "";
		$sum_carton = "";
		$sum_nw = "";
		$sum_gw = "";
		$sum_pcs = "";
		$nw = "";
		$gw = "";
		
		
		
		
		
		

 if($id == '00'){
	//$row['price'] = $row['price_sod'];
	//$row['qtyout'] = '';
	$row['carton'] = '0';
	$row['lot']    = '0';
	$row['id_inv_det'] = '0';
	$row['carton_to'] = '0';
	$row['nw'] = '0';
	$row['gw'] = '0';
		$total_pcs = "0";
		$total_nw = "0";
		$total_gw = "0";	
		$total_carton = "0";
} else{
		$total_carton = ($row['carton_to'] - $row['carton'] < 0 ? -1*($row['carton_to'] - $row['carton']) : $row['carton_to'] - $row['carton'] ) + 1 ;
		$total_nw = $total_carton * $row['nw'];
		$total_gw = $total_carton * $row['gw'];
		$total_pcs = $total_carton * $row['qty'];
}
		//$row['bal'] = $row['qty'] - $row['qty_inv'];
		$unit .= "<input onkeyup='handledetail(this)' style='background-color:#f0f0f0;width:70%' readonly value='".$row['unit']."'  type='text' class='UNIT__".$my_id."'>";
		$price .= "<input onkeyup='handledetail(this)' style='background-color:#f0f0f0;width:70%' readonly  type='text' value='".$row['price_so']."' class='PRICE__".$my_id."'>";
		$qty_invoice .= "<input onkeyup='handledetail(this)' style='background-color:#f0f0f0;width:70%'  readonly type='text' value='".$row['qty']."' class='QTYINV__".$my_id."'>";
		$carton .= "<input onkeyup='handledetail(this)' style='width:70%' type='text' value='".$row['carton']."'  class='CARTON__".$my_id."'>";
		$lot .= "<input onkeyup='handledetail(this)' style='width:70%' type='text' value='".$row['lot']."'  class='LOT__".$my_id."'>";
		$carton_to .= "<input onkeyup='handledetail(this)'style='width:70%' type='text' value='".$row['carton_to']."'  class='CARTON_TO__".$my_id."'>";
		$sum_carton .= "<input onkeyup='handledetail(this)' readonly style='background-color:#f0f0f0;width:70%' type='text' value='".$total_carton."'  class='TOTAL_CARTON__".$my_id."'>";
		$sum_nw .= "<input onkeyup='handledetail(this)' readonly style='background-color:#f0f0f0;width:70%' type='text' value='".$total_nw."'  class='TOTAL_NW__".$my_id."'>";
		$sum_gw .= "<input onkeyup='handledetail(this)' readonly style='background-color:#f0f0f0;width:70%' type='text' value='".$total_gw."'  class='TOTAL_GW__".$my_id."'>";
		$sum_pcs .= "<input onkeyup='handledetail(this)' readonly style='background-color:#f0f0f0;width:70%' type='text' value='".$total_pcs."'  class='TOTAL_PCS__".$my_id."'>";
		$nw .= "<input onchange='handledetail(this)' style='#f0f0f0;width:70%' type='text' value='".$row['nw']."'  class='NW__".$my_id."'>";
		$gw .= "<input onchange='handledetail(this)' style='#f0f0f0;width:70%' type='text' value='".$row['gw']."'  class='GW__".$my_id."'>";
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.$my_id.'",';
			$outp .= '"ws":"'.$row['kpno'].'",';
			$outp .= '"id_sod":"'.$row["idsod"].'",';
			$outp .= '"id_inv":"'.$row["id_inv"].'",';
			$outp .= '"id_inv_det":"'.$row["id_inv_det"].'",';
			$outp .= '"so":"'.$row["so_no"].'",'; 
			$outp .= '"buyerpo":"'.rawurlencode($row['buyerno']).'",'; 
			$outp .= '"dest":"'.$row['country_name'].'",'; 
			$outp .= '"bppbno":"'.$row['bppbno'].'",';
			$outp .= '"bppbno_int":"'.$row['bppbno_int'].'",';  
			$outp .= '"color":"'.htmlspecialchars($row['color']).'",'; 
			$outp .= '"size":"'.htmlspecialchars($row['size']).'",'; 
			$outp .= '"qty_so":"'.$row['qty_so'].'",'; 
			$outp .= '"qty_bpb":"'.$row['qty'].'",'; 
			$outp .= '"qty_invoice_val":"'.$row['qty'].'",'; 
			$outp .= '"qty_invoice":"'.rawurlencode($qty_invoice).'",'; 
			$outp .= '"unit":"'.rawurlencode($unit).'",';  
			$outp .= '"unit_val":"'.$row['unit'].'",'; 
			$outp .= '"bal":"'.$row['bal'].'",'; 
			$outp .= '"price":"'.rawurlencode($price).'",'; 
			$outp .= '"price_val":"'.rawurlencode($row['price_so']).'",'; 
			$outp .= '"carton":"'.rawurlencode($carton).'",'; 
			$outp .= '"carton_val":"'.$row['carton'].'",'; 

			$outp .= '"carton_to":"'.rawurlencode($carton_to).'",'; 
			$outp .= '"carton_to_val":"'.$row['carton_to'].'",'; 
			
			$outp .= '"nw":"'.rawurlencode($nw).'",'; 
			$outp .= '"nw_val":"'.$row['nw'].'",'; 


			$outp .= '"sum_nw":"'.rawurlencode($sum_nw).'",'; 
			$outp .= '"sum_nw_val":"'.$total_nw.'",'; 

			$outp .= '"gw":"'.rawurlencode($gw).'",'; 
			$outp .= '"gw_val":"'.$row['gw'].'",'; 


			$outp .= '"sum_gw":"'.rawurlencode($sum_gw).'",'; 
			$outp .= '"sum_gw_val":"'.$total_gw.'",'; 			

			$outp .= '"sum_pcs":"'.rawurlencode($sum_pcs).'",'; 
			$outp .= '"sum_pcs_val":"'.$total_pcs.'",'; 

			$outp .= '"sum_carton":"'.rawurlencode($sum_carton).'",'; 
			$outp .= '"sum_carton_val":"'.$total_pcs.'",'; 
			
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





<?php class GetListData {
	public function data($id){
		include "../../include/conn.php";
@include_once '../forms/fungsi.php';
$id_item=$id;
$rscs=mysqli_fetch_array(mysqli_query($con_new, "select * from act_costing where id='$id_item'"));
//print_r($rscs); SELECT tanggal, rate_jual,rate_beli,($price * rate_jual) hasiljual,($price / rate_beli) hasilbeli from masterrate WHERE tanggal = '$date'
$deldate=$rscs['deldate'];
$cfm_price=$rscs['cfm_price'];
$cfm_price_curr=$rscs['curr'];
$com_fee = $rscs['comm_cost'];
$ga=$rscs['ga_cost'];
$vat=$rscs['vat'];
//$deal=$rscs['deal_allow'];
$wsno=$rscs['kpno'];
$rs=mysqli_fetch_array(mysqli_query($con_new, "select * from masterrate where  curr='USD' and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12') and tanggal='".fd($deldate)."'  "));
$rate_jual=$rs['rate_jual'];
$rate_beli=$rs['rate_beli'];
$gtaccs_idr1=0;
$gtaccs_usd1=0;
if($cfm_price_curr=="IDR")
{ $cfm_price_idr=$cfm_price;
//  $cfm_price_usd=$cfm_price/$rate_jual;
$cfm_price_usd=$cfm_price/$rate_beli;
}
else
{ $cfm_price_idr=$cfm_price*$rate_jual;
  $cfm_price_usd=$cfm_price;
}

/* 
Deal Allowance : 
JS SCRIPT : 

	data.valuedealallowanceidr = data.confirmpriceidr - data.totalcostingidr;
	data.valuedealallowanceusd = data.confirmpriceusd - data.totalcostingusd;
*/

		$q = "SELECT * FROM (

SELECT 	d.nama_group cod,
		concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents)  it,
		 price price,
		 cons cons,
		 s.unit unit,
		 allowance allowance,
		 material_source material_source,
		 s.id id,
		 s.jenis_rate jenis_rate,
		 'Costing Detail' categorydescription,
		 a.id idCostings,
		 '1' category
		    	from act_costing a 
				inner join act_costing_mat s on 
		    	a.id=s.id_act_cost inner join mastergroup d inner join mastersubgroup f on 
          d.id=f.id_group 
          inner join mastertype2 g on f.id=g.id_sub_group
          inner join mastercontents h on g.id=h.id_type and s.id_item=h.id
		      where a.id='$id' 
	UNION
SELECT d.cfcode cod,
		d.cfdesc it,
		price price,
		cons cons,
		s.unit unit,
		allowance allowance,
		material_source material_source,
		s.id id,
		s.jenis_rate jenis_rate,
		'Manufacturing-Complexity' categorydescription,
		a.id idCostings,
		'2' category
		    	from act_costing a inner join act_costing_mfg s on 
		    	a.id=s.id_act_cost inner join mastercf d on s.id_item=d.id
		    	where a.id='$id'	

	UNION
SELECT d.otherscode cod,
		d.othersdesc it,
		price,
		cons,
		s.unit,
		allowance,
		material_source,
		s.id,
		s.jenis_rate,
		'Other Costing' categorydescription,
		a.id idCostings,
		'3' category 
		    	from act_costing a inner join act_costing_oth s on 
		    	a.id=s.id_act_cost inner join masterothers d on s.id_item=d.id
		    	where a.id='$id'	) X ORDER BY  X.idCostings ASC";
				
				
			//echo $q;
	    $query = mysql_query($q); 
		$no                           = 1; 
		$sumusd                       = 0;
		$sumidr                       = 0;
		$sumCostingDetailIdr          = 0;
		$sumCostingDetailUsd          = 0;
		$sumManufacturingComplexityIdr= 0;
		$sumManufacturingComplexityUsd= 0;
		$sumOtherCostIdr              = 0;
		$sumOtherCostUsd              = 0;
		while($data = mysql_fetch_array($query))
		  { 
						if ($data['jenis_rate']=="J")
						{	$px_idr=$data['price'] * $rate_jual; 
							$px_usd=$data['price'];
						}
						else
						{	
							$px_idr=$data['price']; 
							$px_usd=$data['price'] / $rate_beli;
						}


						if($data['category'] == 3 ){
							$cons = ' ';
						}else{
							$cons = fn($data['cons'],4);
							
						}

						if($data['category'] == 3 ){
							$allow = ' ';
						}else{
							$allow = fn($data['allowance'],4);
							
						}	

						$allowcs    = ($px_usd*$data['cons']) * ($data['allowance']/100);
						$allowcs_idr= ($px_idr*$data['cons']) * ($data['allowance']/100);
						$valcs      = ($px_usd * $data['cons']) + $allowcs;
						$valcs_idr  = ($px_idr * $data['cons']) + $allowcs_idr;
						
						if($data['category'] == 3 ){
							$valcs = $px_usd;
							$valcs_idr = $px_idr;
						}

						$persens = ($valcs_idr  / $total_cost_IIDR ) * 100;	
	  
	  
	  
			if($data['category'] == '1' ){
				$sumCostingDetailUsd = $sumCostingDetailUsd + $valcs;
				$sumCostingDetailIdr = $sumCostingDetailIdr + $valcs_idr;
			}
				if($data['category'] == '2' ){
				$sumManufacturingComplexityUsd = $sumManufacturingComplexityUsd + $valcs;
				$sumManufacturingComplexityIdr = $sumManufacturingComplexityIdr + $valcs_idr;
			}		
				if($data['category'] == '3' ){
				$sumOtherCostUsd = $sumOtherCostUsd + $px_usd;
				$sumOtherCostIdr = $sumOtherCostIdr + $px_idr;
				//echo "CATEGORY:$sumOtherCostUsd | $valcs_idr";
			}					
			$sumusd = $sumusd + $valcs;
			$sumidr = $sumidr + $valcs_idr;
		  }				

$tot_cd             = $sumCostingDetailUsd;
$tot_cd_idr         = $sumCostingDetailIdr;
$tot_mf             = $sumManufacturingComplexityUsd;
$tot_mf_idr         = $sumManufacturingComplexityIdr;
$tot_ot             = $sumOtherCostUsd;
$tot_ot_idr         = $sumOtherCostIdr;
$total_ga_cost      = ($tot_cd + $tot_mf + $tot_ot) * $ga/100;
$total_ga_cost_idr  =($tot_cd_idr + $tot_mf_idr + $tot_ot_idr) * $ga/100;
$total_cost         = $tot_cd + $tot_mf + $tot_ot + $total_ga_cost;
$total_cost_idr     =$tot_cd_idr + $tot_mf_idr + $tot_ot_idr + $total_ga_cost_idr;
/*
data.valuevatidr = (data.percentvat/100)*parseInt(data.totalcostingidr);
data.valuevatusd =  (data.percentvat/100)*parseInt(data.totalcostingusd);

*/

$total_vat          = (($total_cost)*$vat/100);
$total_vat_idr      = (($total_cost_idr)*$vat/100);

$grand_total_after_fat_usd = $total_vat + $total_cost;
$grand_total_after_fat_idr = $total_vat_idr + $total_cost_idr;


//echo $grand_total_after_fat_usd." ";
//echo $grand_total_after_fat_idr;
//$total_deal       = (($total_cost+$total_vat+$total_ga_cost)*$deal/100);
//$total_deal_idr   = (($total_cost_idr+$total_vat_idr+$total_ga_cost_idr)*$deal/100);
$total_deal         = $cfm_price_usd - $total_cost;
$total_deal_idr     =  $cfm_price_idr - $total_cost_idr;
if($cfm_price_idr   =="0")
{ $deal             = 0; }
else
{ $deal             = ($total_deal_idr / $cfm_price_idr) *100; }
if($deal < 0){
	$deal           = ($deal * -1);
}
$com_fee_idr        =$com_fee * $cfm_price_idr;
$com_fee_usd        =$com_fee * $cfm_price_usd;
$total_cost_plus    = $total_cost + $total_vat + $total_deal + $total_ga_cost;
$total_cost_plus_idr= $total_cost_idr + $total_vat_idr + $total_deal_idr + $total_ga_cost_idr;
$grandtotalidr      = $total_cost_idr + com_fee_idr;
$grandtotalusd      = $total_cost + com_fee_usd; 
			$outp = '';
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"tot_cd":"'.rawurlencode($tot_cd_idr).'",';
			$outp .= '"tot_cd_idr":"'. rawurlencode($tot_cd). '",'; 
			$outp .= '"tot_mf":"'. rawurlencode($tot_mf_idr). '",'; 
			$outp .= '"tot_mf_idr":"'. rawurlencode($tot_mf). '",'; 
			$outp .= '"tot_ot":"'. rawurlencode($tot_ot_idr). '",'; 
			$outp .= '"tot_ot_idr":"'. rawurlencode($tot_ot). '",'; 												
			$outp .= '"total_ga_cost":"'. rawurlencode($total_ga_cost). '",';
			$outp .= '"total_ga_cost_idr":"'. rawurlencode($total_ga_cost_idr). '",';
			$outp .= '"total_cost":"'. rawurlencode($total_cost). '",';
			$outp .= '"total_cost_idr":"'. rawurlencode($total_cost_idr). '",';
			$outp .= '"vat":"'. rawurlencode($vat). '",';
			$outp .= '"total_vat":"'. rawurlencode($total_vat). '",';
			$outp .= '"total_vat_idr":"'. rawurlencode($total_vat_idr). '",';
			$outp .= '"total_deal":"'. rawurlencode($total_deal). '",';
			$outp .= '"total_deal_idr":"'. rawurlencode($total_deal_idr). '",';
			$outp .= '"deal":"'. rawurlencode($deal). '",';
			$outp .= '"total_cost_plus":"'. rawurlencode($grand_total_after_fat_usd). '",';
			$outp .= '"total_cost_plus_idr":"'. rawurlencode($grand_total_after_fat_idr). '",';
			$outp .= '"cfm_price":"'. rawurlencode($cfm_price). '",';
			$outp .= '"cfm_price_usd":"'. rawurlencode($cfm_price_usd). '",';
			$outp .= '"cfm_price_idr":"'. rawurlencode($cfm_price_idr). '",';
			$outp .= '"com_fee":"'. rawurlencode($com_fee). '",';
			$outp .= '"com_fee_idr":"'. rawurlencode($com_fee_idr). '",';
			$outp .= '"com_fee_usd":"'. rawurlencode($com_fee_usd). '",';
			$outp .= '"grandtotalidr":"'. rawurlencode($grandtotalusd). '",';
			$outp .= '"grandtotalusd":"'. rawurlencode($grandtotalidr). '",';			
			$outp .= '"ga":"'. rawurlencode($ga). '"}';
			
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
			return $result;
}
}

	//print_r($data);
$data = (object)($_POST);
//print_r($data->data['price']);

//print_r($data);
$GetListData = new GetListData();
$List = $GetListData->data($data->data['id']);

//$data = $List ;
print_r($List);
//echo $data;
?>





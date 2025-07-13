<?php 


		$data = $_POST;
//$data = (object)$_POST['data'];


//print_r($data);
//if($data['code'] == '1' ){

	$getListData = new getListData();
$List = $getListData->get($_POST['id']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		include __DIR__ .'/../../forms/fungsi.php';
		$id_jo = flookup("group_concat(distinct id_jo)","bppb","bppbno='$id'");		
		$q = "select breq.nomor_rak,breq.id line_item,breq.id_supplier,breq.qty qtyout,mi.goods_code,mi.itemdesc,tbl_in.id_item,tbl_in.id_jo,tbl_in.qty_in,
  							if(tbl_out.qty_out is null,0,tbl_out.qty_out) qty_out,
  							tbl_in.unit,
                ac.kpno,ac.styleno,mbuyer.supplier buyer,jo_no,
				so.id_so_det,breq.bppbno
				
				
  							from 
                bppb breq inner join masteritem mi on mi.id_item=breq.id_item inner join 
                (select id_item,id_jo,sum(qty) qty_in,unit from bpb where id_jo in ($id_jo) 
                  group by id_item,id_jo) as tbl_in 
  							on mi.id_item=tbl_in.id_item and breq.id_jo=tbl_in.id_jo
  							left join 
                (select id_item,id_jo,sum(qty) qty_out from bppb where id_jo in ($id_jo) 
                  group by id_item,id_jo) as tbl_out
  							on tbl_in.id_item=tbl_out.id_item and tbl_in.id_jo=tbl_out.id_jo
  							INNER JOIN 
                (select id_so,id_jo,jo_no from jo_det jod inner join jo on jod.id_jo=jo.id 
                  where id_jo in ($id_jo) group by id_jo)  
                  jod on breq.id_jo=jod.id_jo 
                inner join (select so.id,id_cost,min(sod.deldate_det) mindeldate,sod.id id_so_det from so inner join so_det sod on so.id=sod.id_so group by so.id) so on jod.id_so=so.id 
                inner join act_costing ac on so.id_cost=ac.id
                inner join mastersupplier mbuyer on ac.id_buyer=mbuyer.id_supplier
                where breq.bppbno='$id'"; 
			//echo "$q";
		$stmt = mysql_query($q);		
		$id = array();
		$outp = '';
		$no = 0;
		while($row = mysql_fetch_array($stmt)){
			
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"supplier":"'.rawurlencode($row['buyer']).'",';
			$outp .= '"no":"'. rawurlencode($no). '",';
			$outp .= '"styleno":"'. rawurlencode($row["styleno"]). '",';
			$outp .= '"ws":"'. rawurlencode($row["kpno"]). '",';
			$outp .= '"bppbno":"'. rawurlencode($row["bppbno"]). '",';
			$outp .= '"goods_code":"'. rawurlencode($row["goods_code"]). '",';
			$outp .= '"buyer":"'. rawurlencode($row["buyer"]). '",';
			$outp .= '"stock_cutin":"'. rawurlencode($row["qty_out"]). '",';
			$outp .= '"unit":"'. rawurlencode($row["unit"]). '",';
			$outp .= '"qty__in":"'. rawurlencode($row["qty_out"]). '",';
			$outp .= '"id_so_det":"'. rawurlencode($row["id_so_det"]). '",';
			$outp .= '"norak":"'. rawurlencode('nomor_rak'). '",';
			$outp .= '"description":"'. rawurlencode($row["itemdesc"]). '"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>





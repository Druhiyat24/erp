<?php
set_time_limit(0);

function get_sisa_budget($id_jo,$id_item,$jenis)
{	if ($id_jo!="" AND $id_item!="")
	{	$sqlcost="select ac.id,ac.cost_date,sum(sod.qty) qty_order from jo_det jod inner join so on jod.id_so=so.id inner join act_costing ac on 
			so.id_cost=ac.id inner join so_det sod on so.id=sod.id_so where jod.id_jo='$id_jo'";
		$rscost=mysql_fetch_array(mysql_query($sqlcost));
		$id_cost = $rscost['id'];
		$cost_date = $rscost['cost_date'];
		$qty_order = $rscost['qty_order'];
		if ($jenis=="M")
		{	$id_item_cost = flookup("acm.id_item","act_costing_mat acm inner join mastercontents mct on acm.id_item=mct.id 
				inner join masterwidth mw on mct.id=mw.id_contents 
		    inner join masterlength ml on mw.id=ml.id_width 
		    inner join masterweight mwe on ml.id=mwe.id_length 
		    inner join mastercolor mc on mwe.id=mc.id_weight 
		    inner join masterdesc md on mc.id=md.id_color 
		    inner join masteritem mi on md.id=mi.id_gen","id_act_cost='$id_cost' and mi.id_Gen='$id_item'");
		
			$sql_cost="select 
				a.id_act_cost,
				sum(if(jenis_rate='B',price/rate_beli,price)) act_usd,
				sum(if(jenis_rate='J',price*rate_jual,price)) act_idr 
				from act_costing_mat a inner join masterrate d on 'USD'=d.curr and '".fd($cost_date)."'=d.tanggal 
				where a.id_act_cost='$id_cost' and a.id_item='$id_item_cost' 
				group by a.id_act_cost";
			$rs_cost=mysql_fetch_array(mysql_query($sql_cost));
			$tot_cost = $rs_cost['act_usd'] * $qty_order;
					
			$sqlpo = "select poh.pono,ms.supplier,mi.itemdesc,acm.id_act_cost,sum(poi.qty) totqty,
				poi.unit unitpo,poi.curr,
				sum(if(poi.curr='USD',round(poi.qty*poi.price,4),(poi.qty*poi.price)/if(jenis='B',rate_beli,rate_jual))) amt_usd,
				sum(if(poi.curr='IDR',round(poi.qty*poi.price,4),(poi.qty*poi.price)*if(jenis='B',rate_beli,rate_jual))) amt_idr 
				from 
				(select id_act_cost,id_item from act_costing_mat where id_act_cost='$id_cost' 
					group by id_act_cost,id_item) acm inner join mastercontents mct on acm.id_item=mct.id 
		    inner join masterwidth mw on mct.id=mw.id_contents 
		    inner join masterlength ml on mw.id=ml.id_width 
		    inner join masterweight mwe on ml.id=mwe.id_length 
		    inner join mastercolor mc on mwe.id=mc.id_weight 
		    inner join masterdesc md on mc.id=md.id_color 
		    inner join masteritem mi on md.id=mi.id_gen   
		    inner join so on acm.id_act_cost=so.id_cost 
		    inner join jo_det jod on so.id=jod.id_so 
		    inner join po_item poi on jod.id_jo=poi.id_jo and md.id=poi.id_gen 
		    inner join po_header poh on poi.id_po=poh.id 
		    left join mastersupplier ms on poh.id_supplier=ms.id_supplier   
		    inner join masterrate d on 'USD'=d.curr and '".fd($cost_date)."'=d.tanggal 
		    where acm.id_act_cost='$id_cost' and poi.cancel='N' and poh.jenis='M' 
		    and acm.id_item='$id_item_cost'  
		    group by acm.id_act_cost,poi.id_gen,poi.id_po ";
		}
		$rspo=mysql_fetch_array(mysql_query($sqlpo));
		$tot_po=$rspo['amt_usd'];		
		$hasil = round($tot_cost - $tot_po,4) ;
		return $hasil;
	}
	else
	{
		$hasil = 0;
		return $hasil;
	}
};

?>

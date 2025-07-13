<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mode = $_GET['mode'];
$mod = $_GET['mod'];
if(!isset($jenis_company)) { $jenis_company=flookup("jenis_company","mastercompany","company!=''"); }
if (isset($_GET['frexc'])) 
{ $excel="Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=detail.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0"); 
} 
else 
{ $excel="N"; }
if ($excel=="Y")
{ $from=date('Y-m-d',strtotime($_GET['frexc']));
  $to=date('Y-m-d',strtotime($_GET['toexc']));
  $buyer=$_GET['buyerexc'];
  $style=$_GET['styleexc'];
}
else
{ if (isset($_POST['txtfrom'])) { $from=date('Y-m-d',strtotime($_POST['txtfrom'])); } else { $from=""; }
  if (isset($_POST['txtto'])) { $to=date('Y-m-d',strtotime($_POST['txtto'])); } else { $to=""; }
  if (isset($_POST['txtid_buyer'])) { $buyer=$_POST['txtid_buyer']; } else { $buyer=""; }
  if (isset($_POST['txtstyle'])) { $style=$_POST['txtstyle']; } else { $style=""; }
}
  
$titlenya="Laporan Purchase Order";

if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }

echo "<div class='box'>";
	echo "<div class='box-body'>";
		if($buyer=="") { $tit_buyer=" Buyer : All "; } else { $tit_buyer="Buyer : ".flookup("supplier","mastersupplier","id_supplier='$buyer'"); }
		if($style=="") { $tit_style=" Style : All "; } else { $tit_style="Style : ".$style; }
		echo "Periode Dari ".fd_view($from)." s/d ".fd_view($to)." ".$tit_buyer." ".$tit_style;
		if ($excel=="N") 
		{ echo "<br><a href='?mod=$mod&mode=$mode&frexc=$from&toexc=$to&buyerexc=$buyer&styleexc=$style&dest=xls'>Save To Excel</a></br>"; }
	echo "</div>";	
echo "</div>";
echo "<div class='box'>";
	echo "<div class='box-body'>";
		if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
		echo "<table id='examplemcc' $tbl_border style='font-size:11px; width: 100%;'>";
			echo "<thead>";
				echo "
				<tr>
					<th rowspan='2'>&nbsp No</th>
					<th rowspan='2'>&nbsp WS #</th>
					<th rowspan='2'>&nbsp Buyer</th>
					<th rowspan='2'>&nbsp Style</th>
					<th colspan='2'>&nbsp Kurs Costing</th>
					<th colspan='2'>&nbsp Material Cost</th>
					<th colspan='2'>&nbsp Actual Material Cost</th>
					<th colspan='2'>&nbsp Secondary Cost</th>
					<th colspan='2'>&nbsp Actual Secondary Cost</th>
					<th colspan='2'>&nbsp CM Cost</th>
					<th colspan='2'>&nbsp Actual CM Cost</th>
					<th colspan='2'>&nbsp Handling Cost</th>
					<th colspan='2'>&nbsp Profit</th>
				</tr>
				<tr>
					<th>&nbsp Jual</th>
					<th>&nbsp Beli</th>
					<th>&nbsp USD</th>
					<th>&nbsp IDR</th>
					<th>&nbsp USD</th>
					<th>&nbsp IDR</th>
					<th>&nbsp USD</th>
					<th>&nbsp IDR</th>
					<th>&nbsp USD</th>
					<th>&nbsp IDR</th>
					<th>&nbsp USD</th>
					<th>&nbsp IDR</th>
					<th>&nbsp USD</th>
					<th>&nbsp IDR</th>
					<th>&nbsp USD</th>
					<th>&nbsp IDR</th>
					<th>&nbsp USD</th>
					<th>&nbsp IDR</th>
				</tr>";
			echo "</thead>";
			echo "<tbody>";
			if ($jenis_company=="VENDOR LG") 
		  {	$sql_join=" inner join masteritem mct on acm.id_item=mct.id_item "; 
				$fld="mct.itemdesc nama_contents";
			}
		  else
		  {	$sql_join="inner join mastercontents mct on acm.id_item=mct.id ";
				$fld="mct.nama_contents";
			}
			if ($buyer=="") { $sql_buyer=""; } else { $sql_buyer=" and ac.id_buyer='$buyer' "; }
		  if ($style=="") { $sql_style=""; } else { $sql_style=" and ac.styleno='$style' "; }
		  $sqldatatable = "select ac.deldate deldatecost,ac.id,ac.cost_date,ac.kpno,ac.styleno,mb.supplier buyer,
		  	jo.jo_no,tqty_so qty_order,ac.ga_cost,ac.vat,ac.deal_allow deal,ac.curr,ac.cfm_price     
		  	from jo inner join jo_det jod on jo.id=jod.id_jo inner join 
		  	(select id_so,sum(qty) tqty_so,min(deldate_det) min_del from so_det where cancel='N' group by id_so) sod on jod.id_so=sod.id_so
				inner join so on so.id=sod.id_so inner join act_costing ac on so.id_cost=ac.id 
				inner join mastersupplier mb on ac.id_buyer=mb.id_supplier 
				where min_del between '".fd($from)."' and '".fd($to)."' ".$sql_buyer." ".$sql_style." 
				group by ac.kpno ";
  		#and ac.kpno='AVI/0620/015' untuk trial
  		#echo $sqldatatable;
			$query = mysql_query($sqldatatable);
      $no = 1; 
      while($data = mysql_fetch_array($query))
      {	$cost_date=$data['cost_date'];
    		$id_item=$data['id'];
    		$deldatecost=$data['deldatecost'];
      	$col_cst = "style='background-color:lightgray;'";
        $col_act = "style='background-color:mediumspringgreen;'";
        $rs=mysqli_fetch_array(mysqli_query($con_new, "select * from masterrate where  curr='USD' 
      		and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12') 
      		and tanggal='".fd($deldatecost)."' 
      		and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')"));
				$rate_jual=fn($rs['rate_jual'],0);
				$rate_beli=fn($rs['rate_beli'],0);

				$sql_cost="select a.id_act_cost,
					sum(round((if(jenis_rate='B' or jenis_rate='',price/rate_beli,price)*cons)+((if(jenis_rate='B' or jenis_rate='',price/rate_beli,price)*cons)*allowance/100),4)) act_usd,
					sum(round((if(jenis_rate='J',price*rate_jual,price)*cons)+((if(jenis_rate='J',price*rate_jual,price)*cons)*allowance/100),2)) act_idr 
					from act_costing_mat a inner join masterrate d on 'USD'=d.curr and '".fd($deldatecost)."'=d.tanggal 
					inner join mastercontents mct on a.id_item=mct.id 
					where a.id_act_cost='$id_item' 
					and d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')
					group by a.id_act_cost";
				#echo $sql_cost;
				$rs_cost=mysql_fetch_array(mysql_query($sql_cost));
				$tot_mat = $data['qty_order'] * $rs_cost['act_usd'];
				$tot_mat_idr = $data['qty_order'] * $rs_cost['act_idr'];
				
				$sql_mfg="select 
					a.id_act_cost,
					sum(round((if(jenis_rate='B' or jenis_rate='',price/rate_beli,price)*cons)+((if(jenis_rate='B' or jenis_rate='',price/rate_beli,price)*cons)*allowance/100),4)) act_mfg_usd,
					sum(round((if(jenis_rate='J',price*rate_jual,price)*cons)+((if(jenis_rate='J',price*rate_jual,price)*cons)*allowance/100),2)) act_mfg_idr 
					from act_costing_mfg a inner join masterrate d on 'USD'=d.curr and '".fd($deldatecost)."'=d.tanggal 
					inner join mastercf mcf on a.id_item=mcf.id 
					where a.id_act_cost='$id_item' and mcf.cfcode!='CMT' 
					and d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')
					group by a.id_act_cost ";
				$rs_cost=mysql_fetch_array(mysql_query($sql_mfg));
				$tot_mfg = $data['qty_order'] * $rs_cost['act_mfg_usd'];
				$tot_mfg_idr = $data['qty_order'] * $rs_cost['act_mfg_idr'];
				
				$sql_oth="select 
					a.id_act_cost,
					sum(round(if(jenis_rate='B' or jenis_rate='',price/rate_beli,price),4)) act_oth_usd,
					sum(round(if(jenis_rate='J',price*rate_jual,price),2)) act_oth_idr 
					from act_costing_oth a inner join masterrate d on 'USD'=d.curr 
					and '".fd($deldatecost)."'=d.tanggal inner join masterothers mot on a.id_item=mot.id 
					where a.id_act_cost='$id_item' and mot.handling='Y'   
					and d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')
					group by a.id_act_cost ";
				$rs_cost=mysql_fetch_array(mysql_query($sql_oth));
				$tot_oth = $data['qty_order'] * $rs_cost['act_oth_usd'];
				$tot_oth_idr = $data['qty_order'] * $rs_cost['act_oth_idr'];
				
				$sql_oth="select 
					a.id_act_cost,
					sum(round(if(jenis_rate='B' or jenis_rate='',price/rate_beli,price),4)) act_oth_usd,
					sum(round(if(jenis_rate='J',price*rate_jual,price),2)) act_oth_idr 
					from act_costing_oth a inner join masterrate d on 'USD'=d.curr 
					and '".fd($deldatecost)."'=d.tanggal inner join masterothers mot on a.id_item=mot.id 
					where a.id_act_cost='$id_item' and mot.handling!='Y'   
					and d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')
					group by a.id_act_cost ";
				$rs_cost=mysql_fetch_array(mysql_query($sql_oth));
				$tot_oth2 = $data['qty_order'] * $rs_cost['act_oth_usd'];
				$tot_oth2_idr = $data['qty_order'] * $rs_cost['act_oth_idr'];
				
				$sql_mfg="select 
					a.id_act_cost,
					sum(round((if(jenis_rate='B' or jenis_rate='',price/rate_beli,price)*cons)+((if(jenis_rate='B' or jenis_rate='',price/rate_beli,price)*cons)*allowance/100),4)) act_mfg_usd,
					sum(round((if(jenis_rate='J',price*rate_jual,price)*cons)+((if(jenis_rate='J',price*rate_jual,price)*cons)*allowance/100),2)) act_mfg_idr 
					from act_costing_mfg a inner join masterrate d on 'USD'=d.curr and '".fd($deldatecost)."'=d.tanggal 
					inner join mastercf mcf on a.id_item=mcf.id 
					where a.id_act_cost='$id_item' and mcf.cfcode='CMT' 
					and d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')
					group by a.id_act_cost ";
				$rs_cost=mysql_fetch_array(mysql_query($sql_mfg));
				$tot_cmt = $data['qty_order'] * $rs_cost['act_mfg_usd'];
				$tot_cmt_idr = $data['qty_order'] * $rs_cost['act_mfg_idr'];
				if($jenis_company=="VENDOR LG")
				{	$sql_act = "select acm.id_act_cost,
						sum(if(poi.curr='USD',(poi.qty*poi.price),(poi.qty*poi.price)/if(jenis='B',rate_beli,rate_jual))) amt_usd,
						sum(if(poi.curr='IDR',(poi.qty*poi.price),(poi.qty*poi.price)*if(jenis='B',rate_beli,rate_jual))) amt_idr 
						from act_costing_mat acm inner join masteritem md on acm.id_item=md.id_item   
				    inner join so on acm.id_act_cost=so.id_cost 
				    inner join jo_det jod on so.id=jod.id_so 
				    inner join po_item poi on jod.id_jo=poi.id_jo and md.id_item=poi.id_gen 
				    inner join po_header poh on poi.id_po=poh.id  
				    inner join masterrate d on 'USD'=d.curr and '".fd($deldatecost)."'=d.tanggal 
				    where acm.id_act_cost='$id_item' and poi.cancel='N' and poh.jenis='M'  
				    and d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')
				    group by acm.id_act_cost ";
				}
				else
				{	$sql_act = "select acm.id_act_cost,
						sum(if(poi.curr='USD',round(poi.qty*poi.price,4),(poi.qty*poi.price)/if(jenis='B',rate_beli,rate_jual))) amt_usd,
						sum(if(poi.curr='IDR',round(poi.qty*poi.price,4),(poi.qty*poi.price)*if(jenis='B',rate_beli,rate_jual))) amt_idr 
						from 
						(select id_act_cost,id_item from act_costing_mat where id_act_cost='$id_item' 
							group by id_act_cost,id_item) acm inner join mastercontents mct on acm.id_item=mct.id 
				    inner join masterwidth mw on mct.id=mw.id_contents 
				    inner join masterlength ml on mw.id=ml.id_width 
				    inner join masterweight mwe on ml.id=mwe.id_length 
				    inner join mastercolor mc on mwe.id=mc.id_weight 
				    inner join masterdesc md on mc.id=md.id_color  
				    inner join so on acm.id_act_cost=so.id_cost 
				    inner join jo_det jod on so.id=jod.id_so 
				    inner join po_item poi on jod.id_jo=poi.id_jo and md.id=poi.id_gen 
				    inner join po_header poh on poi.id_po=poh.id  
				    inner join masterrate d on 'USD'=d.curr and '".fd($deldatecost)."'=d.tanggal 
				    where acm.id_act_cost='$id_item' and poi.cancel='N' and poh.jenis='M'  
				    and d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')
				    group by acm.id_act_cost ";
				}
				#echo $sql_act;
				$rs_act=mysql_fetch_array(mysql_query($sql_act));
				$tot_mat_act = $rs_act['amt_usd'];
				$tot_mat_act_idr = $rs_act['amt_idr'];
				
				$sql_act = "select acm.id_act_cost,
					sum(if(poi.curr='USD',poi.qty*poi.price,poi.qty*(round(poi.price/rate_jual,2)))) amt_usd,
					sum(if(poi.curr='IDR',poi.qty*poi.price,poi.qty*(round(poi.price*rate_jual,2)))) amt_idr 
					from act_costing_mfg acm inner join mastercf mcf on acm.id_item=mcf.id  
			    inner join masteritem mi on mcf.cfdesc=mi.matclass and 'C'=mi.mattype 
			    inner join so on acm.id_act_cost=so.id_cost 
			    inner join jo_det jod on so.id=jod.id_so 
			    inner join po_item poi on jod.id_jo=poi.id_jo and mi.id_item=poi.id_gen 
			    inner join po_header poh on poi.id_po=poh.id  
			    inner join masterrate d on 'USD'=d.curr and '".fd($deldatecost)."'=d.tanggal 
			    where acm.id_act_cost='$id_item' and poi.cancel='N' and poh.jenis='P' 
			    and mcf.cfcode!='CMT' 
			    and d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')
			    group by acm.id_act_cost ";
				#echo $sql_act;
				$rs_act=mysql_fetch_array(mysql_query($sql_act));
				$tot_mfg_act = $rs_act['amt_usd'];;
				$tot_mfg_act_idr = $rs_act['amt_idr'];;
				
				$sql_act = "select acm.id_act_cost,
					sum(if(poi.curr='USD',(poi.qty*poi.price),(poi.qty*poi.price)/rate_jual)) amt_usd,
					sum(if(poi.curr='IDR',(poi.qty*poi.price),(poi.qty*poi.price)*rate_jual)) amt_idr 
					from act_costing_mfg acm inner join mastercf mcf on acm.id_item=mcf.id  
			    inner join masteritem mi on mcf.cfdesc=mi.matclass and 'C'=mi.mattype 
			    inner join so on acm.id_act_cost=so.id_cost 
			    inner join jo_det jod on so.id=jod.id_so 
			    inner join po_item poi on jod.id_jo=poi.id_jo and mi.id_item=poi.id_gen 
			    inner join po_header poh on poi.id_po=poh.id  
			    inner join masterrate d on 'USD'=d.curr and '".fd($deldatecost)."'=d.tanggal 
			    where acm.id_act_cost='$id_item' and poi.cancel='N' and poh.jenis='P' 
			    and mcf.cfcode='CMT' 
			    and d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')
			    group by acm.id_act_cost ";
				$rs_act=mysql_fetch_array(mysql_query($sql_act));
				$tot_cmt_act = $rs_act['amt_usd'];;
				$tot_cmt_act_idr = $rs_act['amt_idr'];;
				
				$total_cost = ($tot_mat + $tot_mfg + $tot_cmt + $tot_oth + $tot_oth2);
				// echo "MAT ".$tot_mat/$data['qty_order'];
				// echo "<br>MFG ".($tot_mfg+$tot_cmt)/$data['qty_order'];
				// echo "<br> OTH".($tot_oth+$tot_oth2)/$data['qty_order'];
				
				$total_ga_cost = round($total_cost * $data['ga_cost']/100,4);
				// echo "<br> GA ".round($total_ga_cost/$data['qty_order'],4);
				
				// $total_vat = (($total_cost+$total_ga_cost)*$data['vat']/100);
				// $total_deal = (($total_cost+$total_vat+$total_ga_cost)*$data['deal']/100);
				
				$total_cost_idr = ($tot_mat_idr + $tot_mfg_idr + $tot_cmt_idr + $tot_oth_idr + $tot_oth2_idr);
				$total_ga_cost_idr = round($total_cost_idr * $data['ga_cost']/100,2);
				// $total_vat_idr = (($total_cost_idr+$total_ga_cost_idr)*$data['vat']/100);
				// $total_deal_idr = (($total_cost_idr+$total_vat_idr+$total_ga_cost_idr)*$data['deal']/100);
				
				$cfm_price_curr=$data['curr'];
				$cfm_price=$data['cfm_price'] * $data['qty_order'];
				if($cfm_price_curr=="IDR")
				{ $cfm_price_idr=$cfm_price;
				  $cfm_price_usd=$cfm_price / unfn($rate_beli);
				}
				else
				{ $cfm_price_idr=$cfm_price * unfn($rate_jual);
				  $cfm_price_usd=$cfm_price;
				}
				$total_deal = $cfm_price_usd - ($total_cost + $total_ga_cost);
				$deal_pcs = round($total_deal/$data['qty_order'],2);
				$total_deal_idr =  $cfm_price_idr - ($total_cost_idr + $total_ga_cost_idr);
				$deal_pcs_idr = round($total_deal_idr/$data['qty_order'],2);
				
				$total_deal = $deal_pcs * $data['qty_order'];
				$total_deal_idr = $deal_pcs_idr * $data['qty_order'];

				// echo "<br> CFM PX ".$cfm_price_usd;
				// echo "<br> DEAL PX ".$deal_pcs;
				// echo "<br> DEAL PX ".$deal_pcs_idr;

				echo "
  			<tr>
  				<td>&nbsp $no</td>";
  				if($excel=="N")
  				{	echo "
	  				<td>
	  					<a href='../pur/?mod=8d&id=$id_item'
								data-toggle='tooltip' title='Detail' target='_blank'>$data[kpno]
							</a>
						</td>";
					}
					else
					{	echo "
	  				<td>&nbsp $data[kpno]</td>";
					}
  				echo "
  				<td>&nbsp $data[buyer]</td>
  				<td>&nbsp $data[styleno]</td>
  				<td>&nbsp $rate_jual</td>
  				<td>&nbsp $rate_beli</td>
  				<td $col_cst>&nbsp ".fn($tot_mat,4)."</td>
  				<td $col_cst>&nbsp ".fn($tot_mat_idr,2)."</td>
  				<td $col_act>&nbsp ".fn($tot_mat_act,4)."</td>
  				<td $col_act>&nbsp ".fn($tot_mat_act_idr,2)."</td>
  				<td $col_cst>&nbsp ".fn($tot_mfg,4)."</td>
  				<td $col_cst>&nbsp ".fn($tot_mfg_idr,2)."</td>
  				<td $col_act>&nbsp ".fn($tot_mfg_act,4)."</td>
  				<td $col_act>".fn($tot_mfg_act_idr,2)."</td>
  				<td $col_cst>".fn($tot_cmt,2)."</td>
  				<td $col_cst>".fn($tot_cmt_idr,2)."</td>
  				<td $col_act>".fn($tot_cmt_act,2)."</td>
  				<td $col_act>".fn($tot_cmt_act_idr,2)."</td>
  				<td $col_cst>".fn($tot_oth,4)."</td>
  				<td $col_cst>".fn($tot_oth_idr,2)."</td>
  				<td $col_act>".fn($total_deal,4)."</td>
  				<td $col_act>".fn($total_deal_idr,2)."</td>
  			</tr>";
    		$no++;
      }
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
?>
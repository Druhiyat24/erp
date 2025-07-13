<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$id_cost = $_GET['id'];
$mod = $_GET['mod'];
if(!isset($jenis_company)) { $jenis_company=flookup("jenis_company","mastercompany","company!=''"); }
if (isset($_GET['dest'])) 
{ $excel="Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=detail.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0"); 
} 
else 
{ $excel="N"; }

$rscst=mysql_fetch_array(mysql_query("select kpno,styleno,supplier buyer,sum(sod.qty) qty_so,
	min(sod.deldate_det) min_del,ac.cfm_price from 
	act_costing ac inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
	inner join so on ac.id=so.id_cost inner join so_det sod on so.id=sod.id_so  
	where ac.id='$id_cost' and sod.cancel='N'"));

if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }

echo "<div class='box'>";
	echo "<div class='box-body'>";
		?>
		<table width="30%">
			<tr>
				<th>WS #<th>
				<th>&nbsp:&nbsp<th>
				<td><?=$rscst['kpno'];?></td>
				<th>Qty SO<th>
				<th>&nbsp:&nbsp<th>
				<td><?=$rscst['qty_so'];?></td> 
			</tr>
			<tr>
				<th>Style #<th>
				<th>&nbsp:&nbsp<th>
				<td><?=$rscst['styleno'];?></td> 
				<th>Delv. Date<th>
				<th>&nbsp:&nbsp<th>
				<td><?=$rscst['min_del'];?></td>
			</tr>
			<tr>
				<th>Buyer<th>
				<th>&nbsp:&nbsp<th>
				<td><?=$rscst['buyer'];?></td> 
				<th>Cfm Price<th>
				<th>&nbsp:&nbsp<th>
				<td><?=$rscst['cfm_price'];?></td>
			</tr>
		</table>
		<?php 
		if ($excel=="N") 
		{ echo "<br><a href='?mod=$mod&id=$id_cost&dest=xls'>Save To Excel</a></br>"; }
	echo "</div>";	
echo "</div>";
echo "<div class='box'>";
	echo "<div class='box-body'>";
		if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
		echo "<table id='examplefix' $tbl_border style='font-size:9px; width: 100%;' class='display responsive'>";
			echo "<thead>";
				echo "
				<tr>
					<th>No</th>
					<th>Item Costing</th>
					<th>Kurs Jual</th>
					<th>Kurs Beli</th>
					<th>Costing Px IDR</th>
					<th>Costing Px USD</th>
					<th>PO #</th>
					<th>Supplier</th>
					<th>Item PO</th>
					<th>Qty PO</th>
					<th>Unit</th>
					<th>Curr</th>
					<th>PO Px IDR</th>
					<th>PO Px USD</th>
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
		  $sqldatatable = "select ac.deldate,concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) item,
		  	ac.id,ac.cost_date,ac.kpno,ac.styleno,mb.supplier buyer,
		  	jo.jo_no,sum(sod.qty) qty_order,ac.ga_cost,ac.vat,ac.deal_allow deal,
		  	acm.id_item,jo.id idjo     
		  	from jo inner join jo_det jod on jo.id=jod.id_jo inner join so_det sod on jod.id_so=sod.id_so
				inner join so on so.id=sod.id_so inner join act_costing ac on so.id_cost=ac.id 
				inner join act_costing_mat acm on ac.id=acm.id_act_cost 
				inner join mastersupplier mb on ac.id_buyer=mb.id_supplier 
				inner join mastercontents mct on acm.id_item=mct.id 
				inner join mastertype2 mty on mct.id_type=mty.id 
				inner join mastersubgroup msu on mty.id_sub_group=msu.id 
				inner join mastergroup mgr on msu.id_group=mgr.id 
				where sod.cancel='N' and ac.id='$id_cost'  
				group by ac.kpno,acm.id_item ";
  		#echo $sqldatatable;
			$query = mysql_query($sqldatatable);
      $no = 1; 
      while($data = mysql_fetch_array($query))
      {	$cost_date=$data['cost_date'];
    		$deldate=$data['deldate'];
    		$rs=mysqli_fetch_array(mysqli_query($con_new, "select * from masterrate where 
      		curr='USD' and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12') 
      		and tanggal='".fd($deldate)."'"));
				$rate_jual=fn($rs['rate_jual'],0);
				$rate_beli=fn($rs['rate_beli'],0);

				$sql_cost="select 
					a.id_act_cost,
					sum(if(jenis_rate='B',price/rate_beli,price)) act_usd,
					sum(if(jenis_rate='J',price*rate_jual,price)) act_idr 
					from act_costing_mat a inner join masterrate d on 'USD'=d.curr and '".fd($deldate)."'=d.tanggal 
					where a.id_act_cost='$id_cost' and a.id_item='$data[id_item]' 
					and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')  
					group by a.id_act_cost";
				$rs_cost=mysql_fetch_array(mysql_query($sql_cost));
				$tot_mat = $rs_cost['act_usd'];
				$tot_mat_idr = $rs_cost['act_idr'];
				
				if($jenis_company=="VENDOR LG")
				{	$sqlpo = "select acm.id_act_cost,
						sum(if(poi.curr='USD',(poi.qty*poi.price),(poi.qty*poi.price)/if(jenis='B',rate_beli,rate_jual))) amt_usd,
						sum(if(poi.curr='IDR',(poi.qty*poi.price),(poi.qty*poi.price)*if(jenis='B',rate_beli,rate_jual))) amt_idr 
						from act_costing_mat acm inner join masteritem md on acm.id_item=md.id_item   
				    inner join so on acm.id_act_cost=so.id_cost 
				    inner join jo_det jod on so.id=jod.id_so 
				    inner join po_item poi on jod.id_jo=poi.id_jo and md.id_item=poi.id_gen 
				    inner join po_header poh on poi.id_po=poh.id  
				    inner join masterrate d on 'USD'=d.curr and '".fd($deldate)."'=d.tanggal 
				    where acm.id_act_cost='$id_cost' and poi.cancel='N' and poh.jenis='M'  
				    and acm.id_item='$data[id_item]'
				    and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12') 
				    group by acm.id_act_cost ";
				}
				else
				{	$sqlpo = "select poh.pono,ms.supplier,mi.itemdesc,acm.id_act_cost,sum(poi.qty) totqty,
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
				    inner join masterrate d on 'USD'=d.curr and '".fd($deldate)."'=d.tanggal 
				    where acm.id_act_cost='$id_cost' and poi.cancel='N' and poh.jenis='M' 
				    and acm.id_item='$data[id_item]'  
				    and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12') 
				    group by acm.id_act_cost,poi.id_gen,poi.id_po ";
				}
				#echo $sqlpo;
				$qpo=mysql_query($sqlpo);
				$jrow=mysql_num_rows($qpo);
				if($jrow==0)
				{	echo "
	  			<tr>
	  				<td>$no</td>
	  				<td>$data[item]</td>
	  				<td>$rate_jual</td>
	  				<td>$rate_beli</td>
	  				<td>".fn($tot_mat_idr,2)."</td>
	  				<td>".fn($tot_mat,4)."</td>
	  				<td></td>
	  				<td></td>
	  				<td></td>
	  				<td>0</td>
	  				<td></td>
	  				<td></td>
	  				<td>0</td>
	  				<td>0</td>
	  			</tr>";
		  	}
				else
				{	while($datapo = mysql_fetch_array($qpo))
					{	if($datapo['totqty']>0)
						{	$pricepo_idr=$datapo['amt_idr'] / $datapo['totqty'];
							$pricepo_usd=$datapo['amt_usd'] / $datapo['totqty'];
						}
						else
						{	$pricepo_idr=0;
							$pricepo_usd=0;
						}
						if ($pricepo_usd>$tot_mat or $pricepo_idr>$tot_mat_idr) 
	          {$bgcol=" style='background-color: red; color:yellow;'";}
	          else
	          {$bgcol="";} 
	          echo "
		  			<tr $bgcol>
		  				<td>$no</td>
		  				<td>$data[item]</td>
		  				<td>$rate_jual</td>
		  				<td>$rate_beli</td>
		  				<td>".fn($tot_mat_idr,2)."</td>
		  				<td>".fn($tot_mat,4)."</td>
		  				<td>$datapo[pono]</td>
		  				<td>$datapo[supplier]</td>
		  				<td>$datapo[itemdesc]</td>
		  				<td>$datapo[totqty]</td>
		  				<td>$datapo[unitpo]</td>
		  				<td>$datapo[curr]</td>
		  				<td>".fn($pricepo_idr,2)."</td>
		  				<td>".fn($pricepo_usd,6)."</td>
		  			</tr>";
	  			}
	  		}
	  		$no++;
			}
			#MFG Actual
			$sqldatatable = "select concat(cfcode,' ',cfdesc) item,
		  	ac.id,ac.cost_date,ac.kpno,ac.styleno,mb.supplier buyer,
		  	jo.jo_no,sum(sod.qty) qty_order,ac.ga_cost,ac.vat,ac.deal_allow deal,
		  	acm.id_item,jo.id idjo     
		  	from jo inner join jo_det jod on jo.id=jod.id_jo inner join so_det sod on jod.id_so=sod.id_so
				inner join so on so.id=sod.id_so inner join act_costing ac on so.id_cost=ac.id 
				inner join act_costing_mfg acm on ac.id=acm.id_act_cost 
				inner join mastersupplier mb on ac.id_buyer=mb.id_supplier 
				inner join mastercf mcf on acm.id_item=mcf.id  
				where sod.cancel='N' and ac.id='$id_cost'  
				group by ac.kpno,acm.id_item ";
  		#echo $sqldatatable;
			$query = mysql_query($sqldatatable);
      while($data = mysql_fetch_array($query))
      {	$cost_date=$data['cost_date'];
    		$rs=mysqli_fetch_array(mysqli_query($con_new, "select * from masterrate where 
      		curr='USD' and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12') 
      		and tanggal='".fd($deldate)."'"));
				$rate_jual=fn($rs['rate_jual'],0);
				$rate_beli=fn($rs['rate_beli'],0);

				$sql_cost="select 
					a.id_act_cost,
					sum(if(jenis_rate='B',price/rate_beli,price)) act_usd,
					sum(if(jenis_rate='J',price*rate_jual,price)) act_idr 
					from act_costing_mfg a inner join masterrate d on 'USD'=d.curr and '".fd($deldate)."'=d.tanggal 
					where a.id_act_cost='$id_cost' and a.id_item='$data[id_item]' 
					and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')  
					group by a.id_act_cost";
				$rs_cost=mysql_fetch_array(mysql_query($sql_cost));
				$tot_mfg = $rs_cost['act_usd'];
				$tot_mfg_idr = $rs_cost['act_idr'];
				
				$sqlpo = "select poh.pono,ms.supplier,mi.itemdesc,acm.id_act_cost,
					sum(poi.qty) totqty,poi.curr,poi.unit unitpo, 
					sum(if(poi.curr='USD',(poi.qty*poi.price),(poi.qty*poi.price)/if(jenis='B',rate_beli,rate_jual))) amt_usd,
					sum(if(poi.curr='IDR',(poi.qty*poi.price),(poi.qty*poi.price)*if(jenis='B',rate_beli,rate_jual))) amt_idr 
					from act_costing_mfg acm inner join mastercf md on acm.id_item=md.id 
					inner join masteritem mi on md.cfdesc=mi.matclass     
			    inner join so on acm.id_act_cost=so.id_cost 
			    inner join jo_det jod on so.id=jod.id_so 
			    inner join po_item poi on jod.id_jo=poi.id_jo and mi.id_item=poi.id_gen 
			    inner join po_header poh on poi.id_po=poh.id  
			    inner join mastersupplier ms on poh.id_supplier=ms.id_supplier 
			    inner join masterrate d on 'USD'=d.curr and '".fd($deldate)."'=d.tanggal 
			    where acm.id_act_cost='$id_cost' and poi.cancel='N' and poh.jenis='P'  
			    and acm.id_item='$data[id_item]' 
			    and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12') 
			    group by acm.id_act_cost,poi.id_gen,poi.id_po ";
				#echo $sqlpo;
				$qpo=mysql_query($sqlpo);
				$jrow=mysql_num_rows($qpo);
				if($jrow==0)
				{	echo "
	  			<tr>
	  				<td>$no</td>
	  				<td>$data[item]</td>
	  				<td>$rate_jual</td>
	  				<td>$rate_beli</td>
	  				<td>".fn($tot_mfg_idr,2)."</td>
	  				<td>".fn($tot_mfg,4)."</td>
	  				<td></td>
	  				<td></td>
	  				<td></td>
	  				<td>0</td>
	  				<td></td>
	  				<td></td>
	  				<td>0</td>
	  				<td>0</td>
	  			</tr>";
		  	}
				else
				{	while($datapo = mysql_fetch_array($qpo))
					{	$pricepo_idr=$datapo['amt_idr'] / $datapo['totqty'];
						$pricepo_usd=$datapo['amt_usd'] / $datapo['totqty'];
						
						if ($pricepo_usd>$tot_mat or $pricepo_idr>$tot_mat_idr) 
	          {$bgcol=" style='background-color: red; color:yellow;'";}
	          else
	          {$bgcol="";} 
	          echo "
		  			<tr $bgcol>
		  				<td>$no</td>
		  				<td>$data[item]</td>
		  				<td>$rate_jual</td>
		  				<td>$rate_beli</td>
		  				<td>".fn($tot_mat_idr,2)."</td>
		  				<td>".fn($tot_mat,4)."</td>
		  				<td>$datapo[pono]</td>
		  				<td>$datapo[supplier]</td>
		  				<td>$datapo[itemdesc]</td>
		  				<td>$datapo[totqty]</td>
		  				<td>$datapo[unitpo]</td>
		  				<td>$datapo[curr]</td>
		  				<td>".fn($pricepo_idr,2)."</td>
		  				<td>".fn($pricepo_usd,2)."</td>
		  			</tr>";
	  			}
	  		}
	  		$no++;
			}
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
?>
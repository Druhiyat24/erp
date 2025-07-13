<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mode = $_GET['mode'];
$mod = $_GET['mod'];
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
}
else
{ if (isset($_POST['txtfrom'])) { $from=date('Y-m-d',strtotime($_POST['txtfrom'])); } else { $from=""; }
  if (isset($_POST['txtto'])) { $to=date('Y-m-d',strtotime($_POST['txtto'])); } else { $to=""; }
}
  
$titlenya="Laporan Purchase Order";

if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }

echo "<div class='box'>";
	echo "<div class='box-body'>";
		echo "Periode Dari ".fd_view($from)." s/d ".fd_view($to);
		if ($excel=="N") 
		{ echo "<br><a href='?mod=$mod&mode=$mode&frexc=$from&toexc=$to&dest=xls'>Save To Excel</a></br>"; }
	echo "</div>";	
echo "</div>";
echo "<div class='box'>";
	echo "<div class='box-body'>";
		if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
		echo "<table id='example1' $tbl_border style='font-size: 12px; width: 100%;' class='display responsive'>";
			echo "<thead>";
				echo "
				<tr>
					<th>No</th>
					<th>PO Date</th>
					<th>WS #</th>
					<th>Color</th>
					<th>Description</th>
					<th>Length/Size</th>
					<th>Qty PO</th>
					<th>Qty BPB</th>
					<th>Unit</th>
					<th>Curr</th>
					<th>Price</th>
					<th>Buyer</th>
					<th>Supplier</th>
					<th>PO #</th>
					<th>Country</th>
					<th>ETD</th>
					<th>ETA</th>
					<th>Status</th>
				</tr>";
			echo "</thead>";
			echo "<tbody>";
			if ($jenis_company=="VENDOR LG") 
		  {$sql_join="poi.id_gen = mi.id_item";}
		  else
		  {$sql_join="poi.id_gen = mi.id_gen";}
		  $sqldatatable = "select poh.app,poh.app_by,poh.app_date,poh.pono,ac.kpno,poh.app_by,poh.tax,poh.podate, poi.price, 
		    poi.qty qtypo,tmp_bpb.qty_bpb,if(poi.cancel='Y','Cancelled','') status_cx, 
		    poi.price pricepo,poi.curr, poi.unit,
		    mi.goods_code,mi.itemdesc,mi.color,mi.size,
		    mb.supplier buyer,ms.supplier,ms.country,poh.eta,poh.etd 
		    from po_header poh INNER JOIN po_item poi on poh.id=poi.id_po inner join 
		    masteritem mi ON ".$sql_join." 
		    inner join jo_det jod on poi.id_jo=jod.id_jo 
		    inner join so on jod.id_so=so.id
		    inner join act_costing ac on so.id_cost=ac.id 
		    inner join mastersupplier mb on ac.id_buyer=mb.id_supplier
		    inner join mastersupplier ms on poh.id_supplier=ms.id_supplier 
		    left join (select id_po_item,sum(qty) qty_bpb from bpb group by id_po_item) tmp_bpb 
		    on tmp_bpb.id_po_item=poi.id
		    where 
		    poh.podate between '".fd($from)."' and '".fd($to)."' and poh.jenis='M' 
		    union all 
		    select poh.app,poh.app_by,poh.app_date,poh.pono,jod.reqno,poh.app_by,poh.tax,poh.podate, poi.price, 
		    poi.qty qtypo,tmp_bpb.qty_bpb,if(poi.cancel='Y','Cancelled','') status_cx, 
		    poi.price pricepo,poi.curr, poi.unit,
		    mi.goods_code,mi.itemdesc,mi.color,mi.size,
		    '-' buyer,ms.supplier,ms.country,poh.eta,poh.etd 
		    from po_header poh INNER JOIN po_item poi on poh.id=poi.id_po inner join 
		    masteritem mi ON poi.id_gen = mi.id_item  
		    inner join reqnon_header jod on poi.id_jo=jod.id  
		    inner join mastersupplier ms on poh.id_supplier=ms.id_supplier 
		    left join (select id_po_item,sum(qty) qty_bpb from bpb group by id_po_item) tmp_bpb 
		    on tmp_bpb.id_po_item=poi.id
		    where 
		    poh.podate between '".fd($from)."' and '".fd($to)."' and poh.jenis='N' 
  			union all 
  			select poh.app,poh.app_by,poh.app_date,poh.pono,ac.kpno,poh.app_by,poh.tax,poh.podate, poi.price, 
		    poi.qty qtypo,tmp_bpb.qty_bpb,if(poi.cancel='Y','Cancelled','') status_cx, 
		    poi.price pricepo,poi.curr, poi.unit,
		    mi.goods_code,mi.itemdesc,mi.color,mi.size,
		    mb.supplier buyer,ms.supplier,ms.country,poh.eta,poh.etd 
		    from po_header poh INNER JOIN po_item poi on poh.id=poi.id_po inner join 
		    masteritem mi ON poi.id_gen = mi.id_item  
		    inner join jo_det jod on poi.id_jo=jod.id_jo 
		    inner join so on jod.id_so=so.id
		    inner join act_costing ac on so.id_cost=ac.id 
		    inner join mastersupplier mb on ac.id_buyer=mb.id_supplier
		    inner join mastersupplier ms on poh.id_supplier=ms.id_supplier 
		    left join (select id_po_item,sum(qty) qty_bpb from bpb group by id_po_item) tmp_bpb 
		    on tmp_bpb.id_po_item=poi.id
		    where 
		    poh.podate between '".fd($from)."' and '".fd($to)."' and poh.jenis='P' "; 
  		#echo $sqldatatable;
			$query = mysql_query($sqldatatable);
      $no = 1; 
      while($data = mysql_fetch_array($query))
      {	if ($data['app']=="W")
   			{	$status_app="Waiting";	}
   			else
   			{	$status_app="Approved By ".$data['app_by']." at ".fd_view_dt($data['app_date']);	}
      	echo "
  			<tr>
  				<td>$no</td>
  				<td>".fd_view($data['podate'])."</td>
  				<td>$data[kpno]</td>
  				<td>$data[color]</td>
  				<td>$data[itemdesc]</td>
  				<td>$data[size]</td>
  				<td>$data[qtypo]</td>
  				<td>$data[qty_bpb]</td>
  				<td>$data[unit]</td>
  				<td>$data[curr]</td>
  				<td>$data[price]</td>
  				<td>$data[buyer]</td>
  				<td>$data[supplier]</td>
  				<td>$data[pono]</td>
  				<td>$data[country]</td>
  				<td>".fd_view($data['etd'])."</td>
  				<td>".fd_view($data['eta'])."</td>
  				<td>$data[status_cx]</td>
  			</tr>";
    		$no++;
      }
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
?>
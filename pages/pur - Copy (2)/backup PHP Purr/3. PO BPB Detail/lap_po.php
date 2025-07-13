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
					<th>Qty Good BPB</th>
					<th>Qty Reject BPB</th>
					<th>Full Qty BPB</th>
					<th>Unit</th>
					<th>Qty SO</th>
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
		    poi.qty qtypo,tmp_bpb.qty_bpb,tmp_bpb.qtyreject_bpb,tmp_bpb.fullqty_bpb,so.qty as qty_order,if(poi.cancel='Y','Cancelled','') status_cx, 
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
		    left join (select id_po_item,coalesce(sum(qty),0) qty_bpb,coalesce(sum(qty_reject),0) qtyreject_bpb, coalesce(sum(qty),0) + coalesce(sum(qty_reject),0) fullqty_bpb from bpb group by id_po_item) tmp_bpb 
		    on tmp_bpb.id_po_item=poi.id
		    where 
		    poh.podate between '".fd($from)."' and '".fd($to)."' and poh.jenis='M' 
		    union all 
		    select poh.app,poh.app_by,poh.app_date,poh.pono,jod.reqno,poh.app_by,poh.tax,poh.podate, poi.price, 
		    poi.qty qtypo,tmp_bpb.qty_bpb,tmp_bpb.qtyreject_bpb,tmp_bpb.fullqty_bpb,so.qty as qty_order,if(poi.cancel='Y','Cancelled','') status_cx, 
		    poi.price pricepo,poi.curr, poi.unit,
		    mi.goods_code,mi.itemdesc,mi.color,mi.size,
		    '-' buyer,ms.supplier,ms.country,poh.eta,poh.etd 
		    from po_header poh INNER JOIN po_item poi on poh.id=poi.id_po inner join 
		    masteritem mi ON poi.id_gen = mi.id_item  
		    inner join reqnon_header jod on poi.id_jo=jod.id  
			inner join reqnon_item so on jod.id=so.id_reqno
			and so.id_item=poi.id_gen
		    inner join mastersupplier ms on poh.id_supplier=ms.id_supplier 
		    left join (select id_po_item,coalesce(sum(qty),0) qty_bpb,coalesce(sum(qty_reject),0) qtyreject_bpb, coalesce(sum(qty),0) + coalesce(sum(qty_reject),0) fullqty_bpb from bpb group by id_po_item) tmp_bpb 
		    on tmp_bpb.id_po_item=poi.id
		    where 
		    poh.podate between '".fd($from)."' and '".fd($to)."' and poh.jenis='N' 
  			union all 
  			select poh.app,poh.app_by,poh.app_date,poh.pono,ac.kpno,poh.app_by,poh.tax,poh.podate, poi.price, 
		    poi.qty qtypo,tmp_bpb.qty_bpb,tmp_bpb.qtyreject_bpb,tmp_bpb.fullqty_bpb,so.qty as qty_order,if(poi.cancel='Y','Cancelled','') status_cx, 
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
		    left join (select id_po_item,coalesce(sum(qty),0) qty_bpb,coalesce(sum(qty_reject),0) qtyreject_bpb, coalesce(sum(qty),0) + coalesce(sum(qty_reject),0) fullqty_bpb from bpb group by id_po_item) tmp_bpb 
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
				<td>$data[qtyreject_bpb]</td>
				<td>$data[fullqty_bpb]</td>
  				<td>$data[unit]</td>
				<td>$data[qty_order]</td>				
  				<td>$data[curr]</td>
  				<td>$data[price]</td>
  				<td>$data[buyer]</td>
  				<td>$data[supplier]</td>
  				
  				<td ><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#mypobpb'
				  onclick=choose_barang('$data[pono]')>$data[pono]</button></td>

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


<!--=============adyz================================================================ -->
<div class="modal fade" id="mypobpb"  tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" style="overflow-y:auto;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detail BPB pada PO dan Nama Barang ini</h4>
      </div>
      <div class="modal-body" style="overflow-y:auto; height:500px;">
        <div id='detail_pobpb'></div>    
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
    </div>
  </div>
</div>

<script type='text/javascript'>

function choose_barang(xpoid)
  { 
	
	var html = $.ajax
    ({  type: "POST",
        url: 'ajax_pobpb_detail.php?modeajax=view_detailbpb',
        data: {xpoid: xpoid},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_pobpb").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplefixbarang').DataTable
      ({  sorting: false,
          searching: false,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };

</script>
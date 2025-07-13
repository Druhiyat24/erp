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
					<th>JO #</th>
					<th>Qty Order</th>
					<th>Item Name</th>
					<th>Amount Cost</th>
					<th>Amount Used</th>
					<th>Amount Variance</th>
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
		  $sqldatatable = "select jo.jo_no,sum(sod.qty) qty_order,$fld,sum(sod.qty*acm.price*acm.cons) amount_budget,tmp_po.amt_po from jo inner join jo_det jod on jo.id=jod.id_jo inner join so_det sod on jod.id_so=sod.id_so
				inner join so on so.id=sod.id_so inner join act_costing_mat acm on so.id_cost=acm.id_act_cost 
				$sql_join 	
				left join 
				(select id_jo,id_gen,sum(qty*price) amt_po from po_item group by id_jo,id_gen) tmp_po 
				on tmp_po.id_jo=jo.id and acm.id_item=tmp_po.id_gen
				where sod.cancel='N' and sod.deldate_det between '".fd($from)."' and '".fd($to)."'
				group by jo.jo_no,acm.id_item ";
  		#echo $sqldatatable;
			$query = mysql_query($sqldatatable);
      $no = 1; 
      while($data = mysql_fetch_array($query))
      {	$amtvar=$data['amount_budget']-$data['amt_po'];
      	echo "
  			<tr>
  				<td>$no</td>
  				<td>$data[jo_no]</td>
  				<td>$data[qty_order]</td>
  				<td>$data[nama_contents]</td>
  				<td align='right'>".fn($data['amount_budget'],2)."</td>
  				<td align='right'>".fn($data['amt_po'],2)."</td>
  				<td align='right'>".fn($amtvar,2)."</td>
  			</tr>";
    		$no++;
      }
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
?>
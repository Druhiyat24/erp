<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
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
{ $id_item_fg=$_GET['frexc'];
}
else
{ $id_item_fg=$_POST['txtkpno']; 
}
  
if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }

echo "<div class='box'>";
	echo "<div class='box-body'>";
		if ($excel=="N") 
		{ echo "<a href='?mod=$mod&mode=$mode&frexc=$id_item_fg&dest=xls'>Save To Excel</a></br>"; }
	echo "</div>";	
echo "</div>";
echo "<div class='box'>";
	echo '
	<div class="box-header">
    <h3 class="box-title">Bahan Baku</h3>
  </div>';
	echo "<div class='box-body'>";
		$sql="select a.id_item_bb,a.id_item_fg,s.goods_code,s.itemdesc,a.cons,
			round(a.allowance*100) allow 
			,round((d.qty*a.cons)+((d.qty*a.cons)*a.allowance),2) qtypr 
			,d.qty,a.satuan from bom a inner join masteritem s 
			on a.id_item_bb=s.id_item inner join masterstyle d
			on a.id_item_fg=d.id_item where d.id_item='$id_item_fg' 
			order by s.mattype desc ";
		$result=mysql_query($sql);
		echo "<table id='examplefix2' width='100%' class='display responsive'>";
			echo "
			<thead>
				<tr>
					<th width='10%'>Bahan Baku</th>
					<th width='5%'>Qty Order</th>
					<th width='5%'>Cons</th>
					<th width='5%'>Satuan</th>
					<th width='5%'>Allow</th>
					<th width='5%'>Keb</th>
					<th width='5%'>No. Trans</th>
					<th width='5%'>Tgl, Trans</th>
					<th width='10%'>Jenis Dok</th>
					<th width='5%'>Tgl. Dok</th>
					<th width='5%'>Qty Trans</th>
				</tr>
			</thead>";
			echo "<tbody>";
				while($rs=mysql_fetch_array($result))
				{	echo "
					<tr>
						<td>$rs[itemdesc]</td>
						<td>".fn($rs['qty'],0)."</td>
						<td>$rs[cons]</td>
						<td>$rs[satuan]</td>
						<td>$rs[allow] %</td>
						<td>".fn($rs['qtypr'],2)."</td>
					</tr>";
					$sql="select bpbno,bpbdate,
						concat(jenis_dok,' (',lpad(bcno,6,'0'),')') jdok,
						bcdate,qty 
						from bpb where id_item_bb='$rs[id_item_bb]' 
						and id_item_fg='$rs[id_item_fg]'";
					$resultbpb=mysql_query($sql);
					while($rsbpb=mysql_fetch_array($resultbpb))
					{	echo "
						<tr style='background-color: blue; color: white;'>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>$rsbpb[bpbno]</td>
							<td>$rsbpb[bpbdate]</td>
							<td>$rsbpb[jdok]</td>
							<td>$rsbpb[bcdate]</td>
							<td>".fn($rsbpb['qty'],2)."</td>
						</tr>";
					}
					$sql="select bppbno,bppbdate,
						concat(jenis_dok,' (',lpad(bcno,6,'0'),')') jdok,
						bcdate,qty 
						from bppb where id_item='$rs[id_item_bb]' 
						and id_item_fg='$rs[id_item_fg]'";
					$resultbppb=mysql_query($sql);
					while($rsbppb=mysql_fetch_array($resultbppb))
					{	echo "
						<tr style='background-color: green; color: white;'>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>$rsbppb[bppbno]</td>
							<td>$rsbppb[bppbdate]</td>
							<td>$rsbppb[jdok]</td>
							<td>$rsbppb[bcdate]</td>
							<td>".fn($rsbppb['qty'],2)."</td>
						</tr>";
					}
				}
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
echo "<div class='box'>";
	echo '
	<div class="box-header">
    <h3 class="box-title">Barang Jadi</h3>
  </div>';
	echo "<div class='box-body'>";
		$sql="select * from masterstyle where id_item='$id_item_fg' ";
		$result=mysql_query($sql);
		echo "<table id='examplefix2' width='100%' class='display responsive'>";
			echo "
			<thead>
				<tr>
					<th width='10%'>Nama Barang Jadi</th>
					<th width='5%'>Qty Order</th>
					<th width='5%'>Satuan</th>
					<th width='5%'>No. Trans</th>
					<th width='5%'>Tgl, Trans</th>
					<th width='10%'>Jenis Dok</th>
					<th width='5%'>Tgl. Dok</th>
					<th width='5%'>Qty Trans</th>
				</tr>
			</thead>";
			echo "<tbody>";
				while($rs=mysql_fetch_array($result))
				{	echo "
					<tr>
						<td>$rs[itemname]</td>
						<td>".fn($rs['Qty'],0)."</td>
						<td>$rs[unit]</td>
					</tr>";
					$sql="select bpbno,bpbdate,
						concat(jenis_dok,' (',lpad(bcno,6,'0'),')') jdok,
						bcdate,qty 
						from bpb where id_item='$rs[id_item]' 
						and bpbno like 'FG%' ";
					$resultbpb=mysql_query($sql);
					while($rsbpb=mysql_fetch_array($resultbpb))
					{	echo "
						<tr style='background-color: blue; color: white;'>
							<td></td>
							<td></td>
							<td></td>
							<td>$rsbpb[bpbno]</td>
							<td>$rsbpb[bpbdate]</td>
							<td>$rsbpb[jdok]</td>
							<td>$rsbpb[bcdate]</td>
							<td>".fn($rsbpb['qty'],2)."</td>
						</tr>";
					}
					$sql="select bppbno,bppbdate,
						concat(jenis_dok,' (',lpad(bcno,6,'0'),')') jdok,
						bcdate,qty 
						from bppb where id_item='$rs[id_item]' 
						and bppbno like 'SJ-FG%' ";
					$resultbppb=mysql_query($sql);
					while($rsbppb=mysql_fetch_array($resultbppb))
					{	echo "
						<tr style='background-color: green; color: white;'>
							<td></td>
							<td></td>
							<td></td>
							<td>$rsbppb[bppbno]</td>
							<td>$rsbppb[bppbdate]</td>
							<td>$rsbppb[jdok]</td>
							<td>$rsbppb[bcdate]</td>
							<td>".fn($rsbppb['qty'],2)."</td>
						</tr>";
					}
				}
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
?>
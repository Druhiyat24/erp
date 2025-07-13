<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>
<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod = $_GET['mod'];

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$jenis_company=$rscomp["jenis_company"];
	$logo_company=$rscomp["logo_company"];

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
{ $id_jo=$_GET['frexc']; }
else if (isset($_GET['noid']))
{ $id_jo=$_GET['noid']; }
else
{ $id_jo=$_POST['txtkpno']; 
}
  
if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("../mon/header.php"); echo "</header>"; }
if($jenis_company=="VENDOR LG") { $fldcap="Production Output"; } else { $fldcap="QC Sewing"; }
echo "<div class='box'>";
	echo "<div class='box-body'>";
		if ($excel=="N") 
		{ echo "<a href='?mod=$mod&mode=$mode&frexc=$id_jo&dest=xls'>Save To Excel</a></br>"; }
		$sqlh="select jo.jo_no,mp.product_group,mb.supplier,ac.styleno,ac.kpno from jo_det jod 
			inner join jo on jod.id_jo=jo.id inner join so on jod.id_so=so.id 
			inner join act_costing ac on so.id_cost=ac.id inner join mastersupplier mb 
			on ac.id_buyer=mb.id_supplier inner join masterproduct mp on 
			ac.id_product=mp.id where jod.id_jo='$id_jo'";
		$rsh=mysql_fetch_array(mysql_query($sqlh));
		if($jenis_company=="VENDOR LG")
		{	echo "
			<table width='25%'>
				<tr>
					<th>Buyer</th>
					<td>$rsh[supplier]</td>
				</tr>
				<tr>
					<th>Part #</th>
					<td>$rsh[product_group]</td>
				</tr>
				<tr>
					<th>Model</th>
					<td>$rsh[styleno]</td>
				</tr>
				<tr>
					<th>JO #</th>
					<td>$rsh[jo_no]</td>
				</tr>
			</table>";
		}
		else
		{	echo "
			<table width='25%'>
				<tr>
					<th>Buyer</th>
					<td>$rsh[supplier]</td>
				</tr>
				<tr>
					<th>Style #</th>
					<td>$rsh[styleno]</td>
				</tr>
				<tr>
					<th>WS #</th>
					<td>$rsh[kpno]</td>
				</tr>
			</table>";
		}
	echo "</div>";	
echo "</div>";
echo "<div class='box'>";
	echo '
	<div class="box-header">
    <h3 class="box-title">Bahan Baku</h3>
  </div>';
	echo "<div class='box-body'>";
		if($jenis_company=="VENDOR LG")
		{	$sql = "select k.id_jo,k.status,'' idsubgroup,k.id_item,l.color,l.size,concat(j.goods_code,' ',j.itemdesc) item,
	    sum(l.qty) qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
	    k.unit,m.supplier,m2.supplier supplier2,k.notes,if(jo.app='W','Waiting','Approved') status_app 
	    from bom_jo_item k inner join jo on k.id_jo=jo.id 
	    inner join so_det l on k.id_so_det=l.id 
	    inner join masteritem j on k.id_item=j.id_item 
	    left join mastersupplier m on k.id_supplier=m.id_supplier
	    left join mastersupplier m2 on k.id_supplier2=m2.id_supplier
	    where k.id_jo='$id_jo' and k.status='M' and k.cancel='N' group by k.id_item
	    union all 
	    select k.id_jo,k.status,0 idsubgroup,k.id_item,l.color,l.size,j.cfdesc item,
	    l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
	    k.unit,m.supplier,m2.supplier supplier2,k.notes,if(jo.app='W','Waiting','Approved') status_app 
	    from bom_jo_item k inner join jo on k.id_jo=jo.id 
	    inner join so_det l on k.id_so_det=l.id inner join 
	    masteritem mi on k.id_item=mi.id_item inner join mastercf j on mi.matclass=j.cfdesc 
	    left join mastersupplier m on k.id_supplier=m.id_supplier
	    left join mastersupplier m2 on k.id_supplier2=m2.id_supplier
	    where k.id_jo='$id_jo' and k.status='P' and k.cancel='N' group by k.id_item";
	  }
	  else
	  {	$sql = "select k.id_jo,k.status,s.id idsubgroup,k.id_item,l.color,l.size,concat(a.nama_group,' ',s.nama_sub_group,' ',
	    d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
	    g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc) item,
	    sum(l.qty) qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
	    k.unit,m.supplier,m2.supplier supplier2,k.notes,if(jo.app='W','Waiting','Approved') status_app 
	    from bom_jo_item k inner join jo on k.id_jo=jo.id 
	    inner join so_det l on k.id_so_det=l.id inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
	    inner join mastertype2 d on s.id=d.id_sub_group
	    inner join mastercontents e on d.id=e.id_type
	    inner join masterwidth f on e.id=f.id_contents 
	    inner join masterlength g on f.id=g.id_width
	    inner join masterweight h on g.id=h.id_length
	    inner join mastercolor i on h.id=i.id_weight
	    inner join masterdesc j on i.id=j.id_color and k.id_item=j.id 
	    left join mastersupplier m on k.id_supplier=m.id_supplier
	    left join mastersupplier m2 on k.id_supplier2=m2.id_supplier
	    where k.id_jo='$id_jo' and k.status='M' and k.cancel='N' group by k.id_item
	    union all 
	    select k.id_jo,k.status,0 idsubgroup,k.id_item,l.color,l.size,j.cfdesc item,
	    l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
	    k.unit,m.supplier,m2.supplier supplier2,k.notes,if(jo.app='W','Waiting','Approved') status_app 
	    from bom_jo_item k inner join jo on k.id_jo=jo.id 
	    inner join so_det l on k.id_so_det=l.id inner join 
	    masteritem mi on k.id_item=mi.id_item inner join mastercf j on mi.matclass=j.cfdesc 
	    left join mastersupplier m on k.id_supplier=m.id_supplier
	    left join mastersupplier m2 on k.id_supplier2=m2.id_supplier
	    where k.id_jo='$id_jo' and k.status='P' and k.cancel='N' group by k.id_item";
	  }
		#echo $sql;
    $result=mysql_query($sql);
		echo "<table id='customers' width='100%' border='1'>";
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
						<td>$rs[item]</td>
						<td>".fn($rs['qty_bom'],4)."</td>
						<td>$rs[cons]</td>
						<td>$rs[unit]</td>";
						$allow=flookup("allowance","masterallow","id_sub_group='$rs[idsubgroup]'
              and qty1<=$rs[qty_bom] and qty2>=$rs[qty_bom]");
            if ($allow==null) { $allow=0; }
            $allowq=$rs['qty_bom'] * $allow/100;
            $qtypr=$rs['qty_bom'] + $allowq; 
            echo "<td>$allow %</td>
						<td align='right'>".fn($qtypr,2)."</td>
					</tr>";
					if($rs['status']=="M")
					{	if($jenis_company=="VENDOR LG") { $fld="s.id_item"; } else { $fld="s.id_gen"; }
						$sql="select pono,ifnull(bpbno_int,bpbno) bpbno,bpbdate,
							concat(jenis_dok,' (',lpad(bcno,6,'0'),')') jdok,
							bcdate,qty,bpbno_from,id_jo_from 
							from bpb a inner join masteritem s on a.id_item=s.id_item 
							where $fld='$rs[id_item]' 
							and id_jo='$rs[id_jo]'";
					}
					else
					{	$sql="select pono,ifnull(bpbno_int,bpbno) bpbno,bpbdate,
							concat(jenis_dok,' (',lpad(bcno,6,'0'),')') jdok,
							bcdate,qty,bpbno_from,id_jo_from 
							from bpb a inner join masteritem s on a.id_item=s.id_item 
							where a.id_item='$rs[id_item]' 
							and id_jo='$rs[id_jo]'";
					}
					$resultbpb=mysql_query($sql);
					$tot_in = 0;
					while($rsbpb=mysql_fetch_array($resultbpb))
					{	echo "
						<tr style='background-color: blue; color: white;'>
							<td>$rs[item]</td>
							<td></td>
							<td></td>
							<td></td>";
							if($rsbpb['bpbno_from']!="")
							{	$jenis_dok_from=flookup("concat(jenis_dok,' ',bcno,' ',bcdate)","bpb","bpbno='$rsbpb[bpbno_from]'
								 and id_jo='$rsbpb[id_jo_from]'"); 
								echo "<td>Transfer From Dokumen $jenis_dok_from</td>"; 
							}
							else
							{ echo "<td></td>"; }
							echo "
							<td>$rsbpb[pono]</td>
							<td>$rsbpb[bpbno]</td>
							<td>$rsbpb[bpbdate]</td>
							<td>$rsbpb[jdok]</td>
							<td>$rsbpb[bcdate]</td>
							<td align='right'>".fn($rsbpb['qty'],2)."</td>
						</tr>";
						$tot_in += $rsbpb['qty']; 
					}
					#CETAK TOTAL IN
					echo "
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>Total</td>
						<td align='right'>".fn($tot_in,2)."</td>
					</tr>";
					if($rs['status']=="M")
					{	$sql="select if(bppbno_int!='',bppbno_int,bppbno) bppbno,bppbdate,
							concat(jenis_dok,' (',lpad(bcno,6,'0'),')') jdok,
							bcdate,qty,bpbno_ro,id_jo_to,id_book 
							from bppb a inner join masteritem s on a.id_item=s.id_item 
							where $fld='$rs[id_item]' 
							and id_jo='$rs[id_jo]'";
					}
					else
					{	$sql="select if(bppbno_int!='',bppbno_int,bppbno) bppbno,bppbdate,
							concat(jenis_dok,' (',lpad(bcno,6,'0'),')') jdok,
							bcdate,qty,bpbno_ro,id_jo_to,id_book 
							from bppb a inner join masteritem s on a.id_item=s.id_item 
							where a.id_item='$rs[id_item]' 
							and id_jo='$rs[id_jo]'";
					}
					$tot_out = 0;
					$resultbppb=mysql_query($sql);
					while($rsbppb=mysql_fetch_array($resultbppb))
					{	echo "
						<tr style='background-color: green; color: white;'>
							<td></td>
							<td></td>
							<td></td>
							<td></td>";
							if($rsbppb['id_jo_to']!="")
							{	$id_jo_to_nya=flookup("jo_no","jo","id='".$rsbppb['id_jo_to']."'");	
								echo "<td>Transfer To JO $id_jo_to_nya</td>"; 
							}
							else
							{	echo "<td></td>"; }
							echo "
							<td></td>
							<td>$rsbppb[bppbno]</td>
							<td>$rsbppb[bppbdate]</td>
							<td>$rsbppb[jdok]</td>
							<td>$rsbppb[bcdate]</td>
							<td align='right'>".fn($rsbppb['qty'],2)."</td>
						</tr>";
						$tot_out += $rsbppb['qty'];
					}
					#CETAK TOTAL OUT
					echo "
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>Total</td>
						<td align='right'>".fn($tot_out,2)."</td>
					</tr>";
					#CETAK TOTAL SISA
					if($tot_in - $tot_out<0) 
					{	$bg="style='background-color: red; color: white;'"; }
					else
					{	$bg="style='background-color: #00FF00; color: black;'"; }
					echo "
					<tr $bg>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>Sisa</td>
						<td align='right'>".fn($tot_in - $tot_out,2)."</td>
					</tr>";
				}
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
echo "<div class='box'>";
	echo '
	<div class="box-header">
    <h3 class="box-title">'.$fldcap.'</h3>
  </div>';
	echo "<div class='box-body'>";
		echo "<table id='customers' width='100%' border='1'>";
			echo "
			<thead>
				<tr>";?>
					<th>JO #</th>
	        <th>SO #</th>
	        <th>Buyer PO</th>
	        <th>Dest</th>
	        <th>Color</th>
	        <th>Size</th>
	        <th>Tgl Output</th>
	        <th>Jam Output</th>
	        <th>Qty RFT</th>
	        <th>Qty RPR</th>
	        <th>Defect</th>	
				<?php
				echo "
				</tr>
			</thead>";
			echo "<tbody>";
				$query = mysql_query("select jo.jo_no,ac.kpno,co.dateinput,co.jam,co.dateoutput,co.qty qtyout,co.rpr rprout,md.nama_defect,sod.id,so.so_no,so.buyerno,sod.dest,sod.color,sod.size,sod.qty,so.unit
          from so inner join so_det sod on so.id=sod.id_so 
          inner join jo_det jod on so.id=jod.id_so
          inner join jo on jod.id_jo=jo.id
          inner join qc_out co on sod.id=co.id_so_det
          left join master_defect md on co.id_defect=md.id_defect
          inner join act_costing ac on so.id_cost=ac.id
          where jod.id_jo='$id_jo'"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            $id=$data['id'];
            echo "<td>$data[jo_no]</td>";
            echo "<td>$data[so_no]</td>";
            echo "<td>$data[buyerno]</td>";
            echo "<td>$data[dest]</td>";
            echo "<td>$data[color]</td>";
            echo "<td>$data[size]</td>";
            echo "<td>".fd_view($data['dateoutput'])."</td>";
            echo "<td>$data[jam]</td>";
            echo "
              <td>$data[qtyout]</td>
              <td>$data[rprout]</td>
              <td>$data[nama_defect]</td>";
          echo "</tr>";
          $no++; 

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
		$sql="select a.*,sod.qty qty_so,so.buyerno,sod.dest from masterstyle a inner join so_det sod on a.id_so_det=sod.id 
			inner join jo_det jod on sod.id_so=jod.id_so 
			inner join so on sod.id_so=so.id where jod.id_jo='$id_jo' ";
		$result=mysql_query($sql);
		echo "<table id='customers' width='100%' border='1'>";
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
					<th width='5%'>Qty Trans</th>";
					if($logo_company=="S")
					{	echo "
						<th width='5%'>PO Buyer</th>
						<th width='5%'>Destination</th>
						<th width='5%'>Invoice</th>
						<th width='5%'>Balance</th>";
					}
				echo "
				</tr>
			</thead>";
			echo "<tbody>";
				while($rs=mysql_fetch_array($result))
				{	echo "
					<tr>
						<td>$rs[itemname] $rs[Color] $rs[Size]</td>
						<td>".fn($rs['qty_so'],0)."</td>
						<td>$rs[unit]</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>";
						if($logo_company=="S")
						{	echo "
							<td>$rs[buyerno]</td>
							<td>$rs[dest]</td>
							<td></td>
							<td></td>";
						}
					echo "
					</tr>";
					$sql="select pono,ifnull(bpbno_int,bpbno) bpbno,bpbdate,
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
							<td>$rsbpb[pono]</td>
							<td>$rsbpb[bpbno]</td>
							<td>$rsbpb[bpbdate]</td>
							<td>$rsbpb[jdok]</td>
							<td>$rsbpb[bcdate]</td>
							<td>".fn($rsbpb['qty'],2)."</td>";
							if($logo_company=="S")
							{	echo "
								<td></td>
								<td></td>
								<td></td>
								<td></td>";
							}
						echo "
						</tr>";
					}
					$sql="select id_so_det,if(bppbno_int!='',bppbno_int,bppbno) bppbno,bppbdate,
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
							<td>".fn($rsbppb['qty'],2)."</td>";
							if($logo_company=="S")
							{	$inv=flookup("invno","invoice_header a inner join invoice_detail s on a.id=s.id_inv",
									"id_so_det='$rsbppb[id_so_det]'");
								echo "
								<td></td>
								<td></td>
								<td>$inv</td>
								<td>".fn($rsbppb['qty'],2)."</td>";
							}
						echo "
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
    <h3 class="box-title">Scrap</h3>
  </div>';
	echo "<div class='box-body'>";
		$sql="select a.id_item_bppb,a.id_item,s.itemdesc from bpb a inner join masteritem s on a.id_item_bppb=s.id_item  
			where a.id_jo='$id_jo' group by a.id_item_bppb";
		$result=mysql_query($sql);
		echo "<table id='customers' width='100%' border='1'>";
			echo "
			<thead>
				<tr>
					<th width='10%'>Nama Bahan Baku</th>
					<th width='5%'>Nama Scrap</th>
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
				{	$eksdok=flookup("concat(jenis_dok,' (',lpad(bcno,6,'0'),')')","bpb","id_jo='$id_jo' and id_item='$rs[id_item_bppb]' group by jenis_dok,bcno"); 
					$eksdok="Eks Dok ".$eksdok;
					echo "
					<tr>
						<td>$rs[itemdesc]</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>$eksdok</td>
					</tr>";
					$sql="select a.id_item,itemdesc,unit,ifnull(bpbno_int,bpbno) bpbno,bpbdate,
						concat(jenis_dok,' (',lpad(bcno,6,'0'),')') jdok,
						bcdate,qty 
						from bpb a inner join masteritem s on a.id_item=s.id_item where id_item_bppb='$rs[id_item_bppb]' 
						and left(bpbno,1) in ('S','L') and a.id_jo='$id_jo'";
					$resultbpb=mysql_query($sql);
					while($rsbpb=mysql_fetch_array($resultbpb))
					{	echo "
						<tr style='background-color: blue; color: white;'>
							<td></td>
							<td>$rsbpb[itemdesc]</td>
							<td>$rsbpb[unit]</td>
							<td>$rsbpb[bpbno]</td>
							<td>$rsbpb[bpbdate]</td>
							<td>$rsbpb[jdok]</td>
							<td>$rsbpb[bcdate]</td>
							<td>".fn($rsbpb['qty'],6)."</td>
						</tr>";
						$sql="select if(bppbno_int!='',bppbno_int,bppbno) bppbno,bppbdate,
							concat(jenis_dok,' (',lpad(bcno,6,'0'),')') jdok,
							bcdate,qty 
							from bppb where id_item='$rsbpb[id_item]' and id_jo='$id_jo' 
							and bppbno like 'SJ-S%' ";
						#echo $sql;
						$resultbppb=mysql_query($sql);
						while($rsbppb=mysql_fetch_array($resultbppb))
						{	echo "
							<tr style='background-color: green; color: white;'>
								<td></td>
								<td>$rsbpb[itemdesc]</td>
								<td>$rsbpb[unit]</td>
								<td>$rsbppb[bppbno]</td>
								<td>$rsbppb[bppbdate]</td>
								<td>$rsbppb[jdok]</td>
								<td>$rsbppb[bcdate]</td>
								<td>".fn($rsbppb['qty'],6)."</td>
							</tr>";
						}
					}
				}
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
?>
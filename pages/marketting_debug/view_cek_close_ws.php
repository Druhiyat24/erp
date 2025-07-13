<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mode = "";
$mod = $_GET['mod'];
if(!isset($jenis_company)) { $jenis_company=flookup("jenis_company","mastercompany","company!=''"); }
if (isset($_GET['wsexc'])) 
{ 
  $excel="Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=detail.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0"); 
} 
else 
{ $excel="N"; }
if ($excel=="Y")
{ 
  $jo_id=$_GET['wsexc'];
}
else
{ 
  $jo_id = $_POST['txtws'];
}

$rscst=mysql_fetch_array(mysql_query("select ac.deldate,ac.kpno,ac.styleno,ms.supplier from jo_det jod inner join so on jod.id_so=so.id inner join act_costing ac 
  on so.id_cost=ac.id inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
  where jod.id_jo='$jo_id' "));
  $buyernya=$rscst['supplier'];
  $wsnya=$rscst['kpno'];
  $stylenya=$rscst['styleno'];
  $delvnya=$rscst['deldate'];
if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }

$dateskrg=date('Ymd_His');
$tblbomjoit="bom_jo_item_".$dateskrg;

$sql="drop table if exists $tblbomjoit";
insert_log($sql,'TempTable');
$sql="create table $tblbomjoit 
  select a.*,l.color,l.size,l.qty,l.sku,l.barcode,l.id_so 
  from bom_jo_item a INNER JOIN so_det l on a.id_so_det=l.id 
  where a.id_jo='$jo_id' and a.cancel='N' and l.cancel='N'";
insert_log($sql,'TempTable');
insert_log("delete from $tblbomjoit",'TempTable');
$sql="select posno,rule_bom,id_item,nama_type 
  from bom_jo_item a inner join masterdesc md on a.id_item=md.id 
  inner join mastercolor mc on md.id_color=mc.id
  inner join masterweight mw on mc.id_weight=mw.id 
  inner join masterlength ml on mw.id_length=ml.id 
  inner join masterwidth mwi on ml.id_width=mwi.id 
  inner join mastercontents mco on mwi.id_contents=mco.id 
  inner join mastertype2 mt on mco.id_type=mt.id 
  where id_jo='$jo_id' and a.cancel='N' and a.status='M'
  group by posno,rule_bom,id_item";
$rs1=mysql_query($sql);
while($row1 = mysql_fetch_array($rs1))
{ 
  if ($row1['rule_bom']=="ALL COLOR ALL SIZE")
  { $fldcol="'All Color'";
    $fldsiz="'All Size'";
    if($row1['nama_type']=="SKU")
    { $fldgrp=" group by l.sku,a.status,a.id_item "; }
    else
    { $fldgrp=" group by a.status,a.id_item "; }
  }
  else if ($row1['rule_bom']=="ALL COLOR RANGE SIZE")
  { $fldcol="concat('All Color - ',l.size)";
    $fldsiz="l.size";
    $fldgrp=" group by a.status,a.id_item,l.size";
  }
  else if ($row1['rule_bom']=="PER COLOR ALL SIZE")
  { $fldcol="l.color";
    $fldsiz="'All Size'";
    $fldgrp=" group by a.status,a.id_item,l.color";
  }
  else 
  { #PER COLOR RANGE SIZE
    $fldcol="l.color";
    $fldsiz="l.size";
    $fldgrp=" group by a.status,a.id_item,l.color,l.size";
  }
  if ($row1['posno']==null) {$posno=" (posno is null or posno='')";} else {$posno=" posno='$row1[posno]'";}
  $sql="insert into $tblbomjoit 
    select a.*,$fldcol,$fldsiz,sum(l.qty),l.sku,l.barcode,l.id_so 
    from bom_jo_item a INNER JOIN so_det l on a.id_so_det=l.id 
    where a.id_jo='$jo_id' and a.cancel='N' and l.cancel='N' and a.status='M' and a.id_item='$row1[id_item]' and $posno $fldgrp ";
  insert_log($sql,'TempTable');
  #echo "<br><br>".$sql."<br><br>";
}

$fld1="if(d.nama_type!='-',concat(d.nama_type,' '),'')";
$fld2="if(e.nama_contents!='-',concat(e.nama_contents,' '),'')";
$fld3="if(f.nama_width!='-',concat(f.nama_width,' '),'')";
$fld4="if(g.nama_length!='-',concat(g.nama_length,' '),'')";
$fld5="if(h.nama_weight!='-',concat(h.nama_weight,' '),'')";
$fld6="if(i.nama_color!='-',concat(i.nama_color,' '),'')";
$fld7="''";
$fld8="if(j.nama_desc!='-',j.nama_desc,'')";
$fld8a="if(j.nama_desc!='-',concat(j.nama_desc,' '),'')";
$fld9="if(j.add_info!='-' or j.add_info!='',j.add_info,'')";
$fld_item="if(nama_sub_group regexp 'BARCODE' or nama_sub_group regexp 'STICKER',
	concat($fld1,$fld2,$fld3,$fld4,$fld5,$fld6,$fld7,$fld8a,$fld9,l.sku,' ',l.barcode),
	concat($fld1,$fld2,$fld3,$fld4,$fld5,$fld6,$fld7,$fld8,$fld9))";
$sql_pro = "
	SELECT k.posno,k.id,k.id_item,a.nama_group, s.nama_sub_group, k.color,k.size,
	$fld_item item,k.qty qty_gmt,k.cons,round(k.qty*k.cons,2) qty_bom,
	k.unit,msup.supplier,s.id idsubgroup,k.notes,k.rule_bom,d.nama_type   
	From $tblbomjoit k INNER JOIN so_det l on k.id_so_det=l.id 
	INNER JOIN masterdesc j on k.id_item=j.id
	INNER JOIN mastercolor i on i.id=j.id_color
	INNER JOIN masterweight h on h.id=i.id_weight
	INNER JOIN masterlength g on g.id=h.id_length
	INNER JOIN masterwidth f on f.id=g.id_width
	INNER JOIN mastercontents e on e.id=f.id_contents
	INNER JOIN mastertype2 d on d.id=e.id_type
	INNER JOIN mastersubgroup s on s.id=d.id_sub_group
	INNER JOIN mastergroup a on a.id=s.id_group
	left join mastersupplier msup on k.id_supplier=msup.id_supplier
	WHERE k.id_jo='$jo_id' and k.status='M' and l.cancel='N' 
	union all 
	SELECT k.posno,k.id,k.id_item,j.matclass nama_group,concat(j.matclass,' ',j.goods_code) nama_sub_group,
	k.color,k.size,
	j.itemdesc item,k.qty qty_gmt,k.cons,round(k.qty*k.cons,2) qty_bom,
	k.unit,msup.supplier,j.id_item idsubgroup,k.notes,k.rule_bom,'' nama_type   
	From $tblbomjoit k INNER JOIN masteritem j on k.id_item=j.id_item
	left join mastersupplier msup on k.id_supplier=msup.id_supplier
	WHERE k.id_jo='$jo_id' and k.status='P'
	ORDER BY nama_group,color,size";
#echo $sql_pro;
$rs1_pro=mysql_query($sql_pro);
while($row1_pro = mysql_fetch_array($rs1_pro))
{ 
  if ($row1_pro['rule_bom']=="ALL COLOR ALL SIZE")
  { $fldcol="'All Color'";
    $fldsiz="'All Size'";
    if($row1_pro['nama_type']=="SKU")
    { $fldgrp=" group by l.sku,a.status,a.id_item "; }
    else
    { $fldgrp=" group by a.status,a.id_item "; }
  }
  else if ($row1_pro['rule_bom']=="ALL COLOR RANGE SIZE")
  { $fldcol="'All Color'";
    $fldsiz="l.size";
    $fldgrp=" group by a.status,a.id_item,l.size";
  }
  else if ($row1_pro['rule_bom']=="PER COLOR ALL SIZE")
  { $fldcol="l.color";
    $fldsiz="'All Size'";
    $fldgrp=" group by a.status,a.id_item,l.color";
  }
  else 
  { #PER COLOR RANGE SIZE
    $fldcol="l.color";
    $fldsiz="l.size";
    $fldgrp=" group by a.status,a.id_item,l.color,l.size";
  }
  if ($row1_pro['posno']==null) {$posno=" (posno is null or posno='')";} else {$posno=" posno='$row1_pro[posno]'";}
  $sql="insert into $tblbomjoit 
    select a.*,$fldcol,$fldsiz,sum(l.qty),l.sku,l.barcode,l.id_so 
    from bom_jo_item a INNER JOIN so_det l on a.id_so_det=l.id 
    where a.id_jo='$jo_id' and a.cancel='N' and l.cancel='N' and a.id_item='$row1_pro[id_item]' 
    and a.status='P' and $posno $fldgrp ";
  insert_log($sql,'TempTable');
  #echo "<br><br>".$sql_pro."<br><br>";
}

echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "
    Buyer : ".$buyernya."<br>
    WS : ".$wsnya."<br>
    Style # : ".$stylenya."<br>
    Delv Date : ".fd_view($delvnya)."<br>";
    if ($excel=="N") 
    { echo "<br><a href='?mod=$mod&wsexc=$jo_id&dest=xls'>Save To Excel</a></br>"; }
  echo "</div>";  
echo "</div>";

echo "<div class='box'>";
  echo "
  <div class='box-header'>
    <h3 class='box-title'>Bahan Baku</h3>
  </div>";
  echo "<div class='box-body'>";
    echo "<table id='examplefixnopage' width='100%' style='font-size:12px;'>";
    	echo "
    		<thead>
    			<tr>
    				<th>Item</th>
    				<th>Qty PR</th>
            <th>Unit</th>
            <th>Qty PO</th>
            <th>Unit PO</th>
            <th>Qty In</th>
            <th>Qty Out</th>
            <th>Stock</th>
    			</tr>
    		</thead>
    		<tbody>";
    		$sql_pro = "
          SELECT k.posno,k.id,k.id_item,a.nama_group, s.nama_sub_group, k.color,k.size,
          $fld_item item,k.qty qty_gmt,k.cons,round(sum(k.qty*k.cons),2) qty_bom,
          k.unit,msup.supplier,s.id idsubgroup,k.notes,k.rule_bom,d.nama_type   
          From $tblbomjoit k INNER JOIN so_det l on k.id_so_det=l.id 
          INNER JOIN masterdesc j on k.id_item=j.id
          INNER JOIN mastercolor i on i.id=j.id_color
          INNER JOIN masterweight h on h.id=i.id_weight
          INNER JOIN masterlength g on g.id=h.id_length
          INNER JOIN masterwidth f on f.id=g.id_width
          INNER JOIN mastercontents e on e.id=f.id_contents
          INNER JOIN mastertype2 d on d.id=e.id_type
          INNER JOIN mastersubgroup s on s.id=d.id_sub_group
          INNER JOIN mastergroup a on a.id=s.id_group
          left join mastersupplier msup on k.id_supplier=msup.id_supplier
          WHERE k.id_jo='$jo_id' and k.status='M' and l.cancel='N' group by k.id_item 
          union all 
          SELECT k.posno,k.id,k.id_item,j.matclass nama_group,concat(j.matclass,' ',j.goods_code) nama_sub_group,
          k.color,k.size,
          j.itemdesc item,k.qty qty_gmt,k.cons,round(k.qty*k.cons,2) qty_bom,
          k.unit,msup.supplier,j.id_item idsubgroup,k.notes,k.rule_bom,'' nama_type   
          From $tblbomjoit k INNER JOIN masteritem j on k.id_item=j.id_item
          left join mastersupplier msup on k.id_supplier=msup.id_supplier
          WHERE k.id_jo='$jo_id' and k.status='XXP' group by k.id_item 
          ORDER BY nama_group,color,size";
        #echo $sql_pro;
        $i=1;
    		$query=mysql_query($sql_pro);
    		while($data=mysql_fetch_array($query))
    		{	$id_item_gen=flookup("id_item","masteritem","id_gen='$data[id_item]'");
          $sql_po="select sum(qty) qty_po,unit from po_item a where id_jo='$jo_id' and id_gen='$data[id_item]' 
            and a.cancel='N' ";
          $rspo=mysql_fetch_array(mysql_query($sql_po));
            $qtypo=$rspo['qty_po'];
            $unitpo=$rspo['unit'];
          $qtybpb=flookup("sum(qty)","bpb a","id_jo='$jo_id' and id_item='$id_item_gen'");
          $qtybppb=flookup("sum(qty)","bppb a","id_jo='$jo_id' and id_item='$id_item_gen'");
          $qtybal=$qtybpb - $qtybppb;
          echo "
    				<tr>
    					<td>$data[nama_sub_group] $data[item]</td>
    					<td>".fn($data['qty_bom'],2)."</td>
              <td>$data[unit]</td>
              <td>".fn($qtypo,2)."</td>
              <td>$unitpo</td>
              <td>".fn($qtybpb,2)."</td>
              <td>".fn($qtybppb,2)."</td>
              <td>".fn($qtybal,2)."</td>
    				</tr>";
    			$i++;
    		};
    	echo "</tbody>
    </table>";
  echo "</div>";
echo "</div>";

echo "<div class='box'>";
  echo "
  <div class='box-header'>
    <h3 class='box-title'>Barang Jadi</h3>
  </div>";
  echo "<div class='box-body'>";
    echo "<table id='examplefix2nopage' width='100%' style='font-size:12px;'>";
      echo "
        <thead>
          <tr>
            <th>PO Buyer</th>
            <th>Item</th>
            <th>Delv Date</th>
            <th>Qty SO</th>
            <th>Unit</th>
            <th>Qty In</th>
            <th>Qty Out</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>";
        $sql_pro="select sod.deldate_det,sod.id id_so_det,a.*,sod.qty qty_so,so.buyerno,sod.dest from masterstyle a inner join so_det sod on a.id_so_det=sod.id 
          inner join jo_det jod on sod.id_so=jod.id_so 
          inner join so on sod.id_so=so.id where jod.id_jo='$jo_id' ";
        #echo $sql_pro;
        $i=1;
        $query=mysql_query($sql_pro);
        while($data=mysql_fetch_array($query))
        { $qtybpb=flookup("sum(qty)","bpb a","id_so_det='$data[id_so_det]'");
          $qtybppb=flookup("sum(qty)","bppb a","id_so_det='$data[id_so_det]'");
          $qtybal=$qtybpb - $qtybppb;
          echo "
            <tr>
              <td>$data[buyerno]</td>
              <td>$data[itemname] $data[Color] $data[Size]</td>
              <td>".fd_view($data['deldate_det'])."</td>
              <td>".fn($data['qty_so'],2)."</td>
              <td>$data[unit]</td>
              <td>".fn($qtybpb,2)."</td>
              <td>".fn($qtybppb,2)."</td>
              <td>".fn($qtybal,2)."</td>
            </tr>";
          $i++;
        };
      echo "</tbody>
    </table>";
  echo "</div>";
echo "</div>";
?>
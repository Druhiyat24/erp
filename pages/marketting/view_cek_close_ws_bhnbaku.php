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
if($excel=="Y")
{ 
  $buyer=$_GET['wsexc'];
}
else
{ 
  $buyer = $_POST['txtbuyer'];
}

$rscst=mysql_fetch_array(mysql_query("select ac.deldate,ac.kpno,ac.styleno,ms.supplier,jod.id_jo from jo_det jod inner join so on jod.id_so=so.id inner join act_costing ac 
  on so.id_cost=ac.id inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
  where ac.id_buyer='$buyer' "));
  $buyernya=$rscst['supplier'];
  // $wsnya=$rscst['kpno'];
  // $stylenya=$rscst['styleno'];
  // $delvnya=$rscst['deldate'];
  // $idjo=$rscst['id_jo'];
if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }


echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "
    Buyer : ".$buyernya."<br>";
    if ($excel=="N") 
    { echo "<br><a href='?mod=$mod&wsexc=$buyer&dest=xls'>Save To Excel</a></br>"; }
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
            <th>No WS</th>
    				<th>Kategori</th>
            <th>Nama Panel</th>
            <th>Warna FG</th>
            <th>ID Item</th>
            <th>Item</th>
            <th>Unit BOM</th>
    				<th>Qty PO</th>
            <th>Unit PO</th>
            <th>Qty BPB</th>
            <th>Unit BPB</th>
            <th>Qty BPPB</th>
            <th>Unit BPPB</th>
            <th>Saldo Akhir</th>
    			</tr>
    		</thead>
    		<tbody>";
    		$sql_pro = "
select A.*, round(coalesce(qtypi,0),2) qtypi, unitpi, round(coalesce(qtybpb,0),2) qtybpb, unitbpb, round(coalesce(qtybppb,0),2) qtybppb, unitbppb, round(coalesce((qtybpb - qtybppb),0),2) saldoakhir from 
(select ac.kpno, mp.nama_panel, sd.color, mi.matclass, k.id_jo,mi.id_item,mi.id_gen, mi.itemdesc, k.unit unitBOM from bom_jo_item k
inner join jo on jo.id = k.id_jo
inner join jo_det jd on jd.id_jo = jo.id
inner join so on so.id = jd.id_so
inner join act_costing ac on ac.id = so.id_cost
inner join so_det sd on sd.id = k.id_so_det
inner join masteritem mi on mi.id_gen = k.id_item
left join masterpanel mp on mp.id = k.id_panel
where ac.id_buyer = '$buyer'  and k.cancel = 'N' and k.status = 'M'
group by mi.id_item, k.unit, sd.color) A
left join (select id,id_jo, id_gen, sum(qty) qtypi, unit unitpi from po_item pi where pi.cancel = 'N' group by id_jo, id_gen, unit) pi on pi.id_jo = A.id_jo and pi.id_gen = A.id_gen
left join (select id_po_item,sum(qty) qtybpb, unit unitbpb from bpb where cancel = 'N' group by id_po_item, unit) bpb on bpb.id_po_item = pi.id
left join (select id_item, id_jo, sum(qty) qtybppb, unit unitbppb from bppb where cancel = 'N' group by id_item, id_jo, unit) bppb on bppb.id_item = A.id_item and bppb.id_jo = A.id_jo";
        echo $sql_pro;
        $i=1;
    		$query=mysql_query($sql_pro);
    		while($data=mysql_fetch_array($query)) 
    		{	
          echo "
    				<tr>
              <td>$data[kpno]</td>
    					<td>$data[matclass]</td>
              <td>$data[nama_panel]</td>
              <td>$data[color]</td>
              <td>$data[id_item]</td>
              <td>$data[itemdesc]</td>
              <td>$data[unitBOM]</td>
              <td>$data[qtypi]</td>
              <td>$data[unitpi]</td>
              <td>$data[qtybpb]</td>
              <td>$data[unitbpb]</td>
              <td>$data[qtybppb]</td>
              <td>$data[unitbppb]</td>
              <td>$data[saldoakhir]</td>
    				</tr>";
    			$i++;
    		};
    	echo "</tbody>
    </table>";
  echo "</div>";
echo "</div>";


?>
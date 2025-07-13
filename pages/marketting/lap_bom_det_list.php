<?php 
if (isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_bom_detail.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
else
{ $excel = "N"; }

$jenis_company=flookup("jenis_company","mastercompany","company!=''");
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod = $_GET['mod'];
if ($excel=="Y")
{ $from = fd_view($_GET['from']);
  $to = fd_view($_GET['to']);
  if (isset($_GET['id_buyer'])) {$buyer = $_GET['id_buyer'];} else {$buyer = "";}
  if (isset($_GET['status'])) {$status = $_GET['status'];} else {$status = "";}
}
else
{ $from = fd_view($_POST['txtfrom']);
  $to = fd_view($_POST['txtto']);
  if (isset($_POST['txtid_buyer'])) {$buyer = $_POST['txtid_buyer'];} else {$buyer = "";}
  if (isset($_POST['txtstatus'])) {$status = $_POST['txtstatus'];} else {$status = "";}
}
if ($buyer=="") 
{ $buyer_cap="All"; 
  $buyer_sql="";
} 
else 
{ $buyer_cap=flookup("supplier","mastersupplier","id_supplier='$buyer'");
  $buyer_sql=" and id_buyer='$buyer'";
}
if ($status=="") 
{ $status_cap="All"; 
  $status_sql="";
} 
else 
{ $status_cap=$status;
  $status_sql=" and status='$status'";
}
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='?mod=$mod&from=".fd($from)."&to=".fd($to)."&id_buyer=".$buyer."&status=".$status."&dest=xls'>Save To Excel</a></br>"; }
    echo "Delivery Date : ".$from." - ".$to." Buyer ".$buyer_cap; 
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
    echo "<table id='examplefix' $tbl_border class='display responsive' style='width:100%;font-size:10px;'>";
    	echo "<thead>";
        echo "<tr>";
          ?>
          <th>No</th>
          <th>SO</th>
          <th>No Ws</th>
          <th>Buyer</th>
          <th>Style #</th>
          <th>Product Group</th>
          <th>Product Item</th>
          <th>Color</th>
          <th>Size</th>
          <th>Item</th>
          <th>Qty Gmt</th>
		      <th>Cons</th>
		      <th>Qty Bom</th>
		      <th>Unit</th>
		      <th>Name</th>
		      <th>Rule Bom</th>
		      <th>Panel Name</th>
		      <th>Dest</th>
		      <th>Status</th>
          <?php
        echo "</tr>";
      echo "</thead>";
    $que="select k.id,l.color,l.size,concat(a.nama_group,' ',s.nama_sub_group,' ', d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ', g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',j.add_info) item, l.qty qty_gmt,k.cons,round(l.qty*k.cons,2) qty_bom, k.unit,up.fullname,k.cancel,k.rule_bom,k.posno,mpan.nama_panel,k.dest, if (k.status = 'M','Material','Printing') as status, so.so_no, ac.kpno, ac.styleno , mp.product_group, mp.product_item, ms.supplier
from bom_jo_item k 
inner join so_det l on k.id_so_det=l.id 
inner join mastergroup a 
inner join mastersubgroup s on a.id=s.id_group 
inner join mastertype2 d on s.id=d.id_sub_group 
inner join mastercontents e on d.id=e.id_type 
inner join masterwidth f on e.id=f.id_contents 
inner join masterlength g on f.id=g.id_width 
inner join masterweight h on g.id=h.id_length 
inner join mastercolor i on h.id=i.id_weight 
inner join masterdesc j on i.id=j.id_color and k.id_item=j.id 
inner join userpassword up on k.username=up.username 
left join masterpanel mpan on k.id_panel=mpan.id
inner join so on l.id_so = so.id
inner join act_costing ac on ac.id = so.id_cost
inner join masterproduct mp on mp.id = ac.id_product
inner join mastersupplier ms on ms.id_supplier = ac.id_buyer
where k.status='M' and ac.deldate between '".fd($from)."' and '".fd($to)."' and k.cancel = 'N' $buyer_sql
union all 
select k.id,l.color,l.size,concat(s.matclass,' ',s.goods_code,' ',s.itemdesc) item, l.qty qty_gmt,k.cons,round(l.qty*k.cons,2) qty_bom, k.unit,up.fullname,k.cancel,k.rule_bom,k.posno,'' nama_panel,k.dest, if (k.status = 'P','Printing','Material') as status, so.so_no, ac.kpno, ac.styleno , mp.product_group, mp.product_item, ms.supplier 
from bom_jo_item k 
inner join so_det l on k.id_so_det=l.id 
inner join masteritem s on k.id_item=s.id_item 
inner join userpassword up on k.username=up.username 
inner join so on l.id_so = so.id
inner join act_costing ac on ac.id = so.id_cost
inner join masterproduct mp on mp.id = ac.id_product
inner join mastersupplier ms on ms.id_supplier = ac.id_buyer
where k.status='P' and ac.deldate between '".fd($from)."' and '".fd($to)."' and k.cancel = 'N' $buyer_sql ";
        $sql=mysql_query($que);
        echo $que;
        $no = 1; 
        while($rs = mysql_fetch_array($sql))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "
              <td>$rs[so_no]</td>
              <td>$rs[kpno]</td>
              <td>$rs[supplier]</td>
              <td>$rs[styleno]</td>
              <td>$rs[product_group]</td> 
              <td>$rs[product_item]</td>
              <td>$rs[color]</td>
              <td>$rs[size]</td>
              <td>$rs[item]</td>
              <td>$rs[qty_gmt]</td>
              <td>$rs[cons]</td>
              <td>$rs[qty_bom]</td>
              <td>$rs[unit]</td>
              <td>$rs[fullname]</td>
              <td>$rs[rule_bom]</td>
              <td>$rs[nama_panel]</td>
              <td>$rs[dest]</td>
              <td>$rs[status]</td>";

          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
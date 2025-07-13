<?php 
if (isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_sales_order.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
else
{ $excel = "N"; }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod = $_GET['mod'];
$jenis_company=flookup("jenis_company","mastercompany","company!=''");
if ($excel=="Y")
{ $from = fd_view($_GET['from']);
  $to = fd_view($_GET['to']);
  if (isset($_GET['id_buyer'])) {$buyer = $_GET['id_buyer'];} else {$buyer = "";}
}
else
{ $from = fd_view($_POST['txtfrom']);
  $to = fd_view($_POST['txtto']);
  if (isset($_POST['txtid_buyer'])) {$buyer = $_POST['txtid_buyer'];} else {$buyer = "";}
}
if ($buyer=="") 
{ $buyer_cap="All"; 
  $buyer_sql="";
} 
else 
{ $buyer_cap=flookup("supplier","mastersupplier","id_supplier='$buyer'");
  $buyer_sql=" and id_buyer='$buyer'";
}
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='?mod=$mod&from=".fd($from)."&to=".fd($to)."&id_buyer=".$buyer."&dest=xls'>Save To Excel</a></br>"; }
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
          <th>Buyer</th>
          <th>SO #</th>
          <th>SO Date</th>
          <th>Buyer PO</th>
          <th>Delv. Date</th>
          <th>Style #</th>
          <?php if($jenis_company=="VENDOR LG") { ?>
            <th>JO #</th>
            <th>Part #</th>
            <th>Part Name</th>
          <?php } else { ?>
            <th>WS #</th>
            <th>Product Group</th>
            <th>Product Item</th>
          <?php } ?>
          <th>Qty</th>
          <th>Unit</th>
          <th>FOB</th>
          <th>Group Mkt</th>
          <th>Qty Keluar</th>
          <?php
        echo "</tr>";
      echo "</thead>";
        $sql=mysql_query("select so.id id_so,a.id,so_no,so_date,supplier,styleno,
          deldate,a.status,mp.product_group,mp.product_item,a.kpno,so.qty,so.unit, 
          ms.shipmode,up.kode_mkt,so.buyerno,so.fob,jo.jo_no from act_costing a inner join mastersupplier f on a.id_buyer=f.id_supplier
          inner join masterproduct mp on a.id_product=mp.id
          left join mastershipmode ms on a.id_smode=ms.id
          inner join so on so.id_cost=a.id 
          inner join jo_det jod on so.id=jod.id_so 
          inner join jo on jod.id_jo=jo.id  
          inner join userpassword up on so.username=up.username
          where deldate between '".fd($from)."' and '".fd($to)."' $buyer_sql ");
        $no = 1; 
        while($rs = mysql_fetch_array($sql))
        { $qtyout = flookup("sum(a.qty)","bppb a inner join so_det sod on a.id_so_det=sod.id","sod.id_so='$rs[id_so]'");
          echo "<tr>";
            echo "<td>$no</td>"; 
            echo "
              <td>$rs[supplier]</td>
              <td>$rs[so_no]</td>
              <td>".fd_view($rs['so_date'])."</td>
              <td>$rs[buyerno]</td>
              <td>".fd_view($rs['deldate'])."</td>
              <td>$rs[styleno]</td>
              <td>$rs[product_group]</td>
              <td>$rs[product_item]</td>";
              if($jenis_company=="VENDOR LG")
              { echo "<td>$rs[jo_no]</td>"; }
              else
              { echo "<td>$rs[kpno]</td>"; }
              echo "
              <td>".fn($rs['qty'],0)."</td>
              <td>$rs[unit]</td>
              <td>$rs[fob]</td>
              <td>$rs[kode_mkt]</td>
              <td>".fn($qtyout,0)."</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
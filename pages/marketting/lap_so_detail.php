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
          <th>No Ws</th>
          <th>Product Group</th>
          <th>Product Item</th>
          <th>Color</th>
          <th>Size</th>
          <th>Qty</th>
          <th>Unit</th>
          <?php
        echo "</tr>";
      echo "</thead>";
        $sql=mysql_query("select so_date, so_no, ac.kpno,ms.supplier,ac.styleno,sd.color, sd.size, sd.qty, so.buyerno, sd.deldate_det,product_group,product_item,so.unit from so 
                                inner join so_det sd on sd.id_so = so.id
                                inner join act_costing ac on ac.id=so.id_cost
                                inner join masterproduct mp on ac.id_product=mp.id
                                inner join mastersupplier ms on ac.id_buyer = ms.id_supplier
                                where so_date between '".fd($from)."' and '".fd($to)."' and sd.cancel = 'N' $buyer_sql ");
        $no = 1; 
        while($rs = mysql_fetch_array($sql))
        { 
          echo "<tr>";
            echo "<td>$no</td>"; 
            echo "
              <td>$rs[supplier]</td>
              <td>$rs[so_no]</td>
              <td>".fd_view($rs['so_date'])."</td>
              <td>$rs[buyerno]</td>
              <td>".fd_view($rs['deldate'])."</td>
              <td>$rs[styleno]</td>
              <td>$rs[kpno]</td>
              <td>$rs[product_group]</td>
              <td>$rs[product_item]</td>
              <td>$rs[color]</td>
              <td>$rs[size]</td>
              <td>$rs[qty]</td>
              <td>$rs[unit]</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
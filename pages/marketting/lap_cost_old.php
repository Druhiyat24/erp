<?php 
if (isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_costing.xls");//ganti nama sesuai keperluan 
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
    echo "Delivery Date : ".$from." - ".$to." Buyer ".$buyer_cap." Status ".$status_cap; 
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
          <th>Costing #</th>
          <th>Costing Date</th>
          <th>Buyer</th>
          <th>Style #</th>
          <th>Status</th>
          <th>Delv. Date</th>
          <th>Product Group</th>
          <th>Product Item</th>
          <th>WS #</th>
          <?php if($jenis_company!="VENDOR LG") { ?>
            <th>Qty</th>
            <th>SMV (Min)</th>
            <th>SMV (Sec)</th>
            <th>ShipMode</th>
          <?php } ?>
          <th>Group Mkt</th>
          <?php
        echo "</tr>";
      echo "</thead>";
        if($jenis_company=="VENDOR LG")
        {$ship_mode=" "; $ship_mode2=",";}
        else
        { $ship_mode="inner join mastershipmode ms on a.id_smode=ms.id";
          $ship_mode2=",ms.shipmode,";
        }
        $que="select a.id,cost_no,cost_date,supplier,styleno,
          deldate,a.status,mp.product_group,mp.product_item,a.kpno,a.qty,
          a.smv_min,a.smv_sec $ship_mode2 up.kode_mkt from act_costing a inner join mastersupplier f on a.id_buyer=f.id_supplier
          inner join masterproduct mp on a.id_product=mp.id
          $ship_mode
          inner join userpassword up on a.username=up.username
          where deldate between '".fd($from)."' and '".fd($to)."' $buyer_sql $status_sql ";
        $sql=mysql_query($que);
        #echo $que;
        $no = 1; 
        while($rs = mysql_fetch_array($sql))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "
              <td>$rs[cost_no]</td>
              <td>".fd_view($rs['cost_date'])."</td>
              <td>$rs[supplier]</td>
              <td>$rs[styleno]</td>
              <td>$rs[status]</td>
              <td>".fd_view($rs['deldate'])."</td>
              <td>$rs[product_group]</td>
              <td>$rs[product_item]</td>
              <td>$rs[kpno]</td>";
              if($jenis_company!="VENDOR LG") 
              { echo "<td>".fn($rs['qty'],0)."</td>
                  <td>".fn($rs['smv_min'],0)."</td>
                  <td>".fn($rs['smv_sec'],0)."</td>
                  <td>$rs[shipmode]</td>";
              }
              echo "
              <td>$rs[kode_mkt]</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
<?php 
if (isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_hist.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
else
{ $excel = "N"; }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod = $_GET['mod'];
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
          <th>Costing #</th>
          <th>Costing Date</th>
          <th>Buyer</th>
          <th>Delv. Date</th>
          <th>Status</th>
          <th>WS #</th>
          <th>SO #</th>
          <th>SO Date</th>
          <th>JO #</th>
          <th>JO Date</th>
          <th>BOM Input</th>
          <?php
        echo "</tr>";
      echo "</thead>";
        $que="select ac.deldate,ac.cost_no,ac.cost_date,ac.kpno,ms.supplier,ac.styleno,ac.status,
          so.so_no,so.so_date,jo.jo_no,jo.jo_date,if(tbom>0,concat('Yes ',ifnull(mbom,'')),'') ket_bom  
          from act_costing ac inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
          left join so on ac.id=so.id_cost left join jo_det jod on so.id=jod.id_so 
          left join jo on jod.id_jo=jo.id 
          left join (select id_jo,count(*) tbom,max(dateinput) mbom from bom_jo_item group by id_jo) bom 
          on jod.id_jo=bom.id_jo 
          where ac.deldate between '".fd($from)."' and '".fd($to)."' $buyer_sql ";
        #echo $que;
        $sql=mysql_query($que);
        $no = 1; 
        while($rs = mysql_fetch_array($sql))
        { $px_cost=0;
          echo "<tr>";
            echo "<td>$no</td>"; 
            echo "
              <td>$rs[cost_no]</td>
              <td>".fd_view($rs['cost_date'])."</td>
              <td>$rs[supplier]</td>
              <td>".fd_view($rs['deldate'])."</td>
              <td>$rs[status]</td>
              <td>$rs[kpno]</td>
              <td>$rs[so_no]</td>
              <td>".fd_view($rs['so_date'])."</td>
              <td>$rs[jo_no]</td>
              <td>".fd_view($rs['jo_date'])."</td>
              <td>$rs[ket_bom]</td>
              ";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
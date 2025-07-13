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
$tipe = $_POST['txttipe'];
$jenis_company=flookup("jenis_company","mastercompany","company!=''");
if ($excel=="Y")
{ $from = fd_view($_GET['from']);
  $to = fd_view($_GET['to']);
  $tipe = $_GET['tipe'];
}
else
{ $from = fd_view($_POST['txtfrom']);
  $to = fd_view($_POST['txtto']);
  $tipe = $_POST['txttipe'];
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
    if ($excel=="N") { echo "<a href='?mod=$mod&from=".fd($from)."&to=".fd($to)."&tipe=".$tipe."&dest=xls'>Save To Excel</a></br>"; }
    echo "Tgl SO : ".$from." - ".$to." Buyer ".$buyer_cap .$tipe; 
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
if ($tipe=="SO")
{    
    echo "<table id='examplefix' $tbl_border class='display responsive' style='width:100%;font-size:10px;'>";
      echo "<thead>";
        echo "<tr>";
          ?>
                     <th>NO</th>
                     <th>Tgl SO</th>
                     <th>No SO</th>
                     <th>No WS</th>
                     <th>Buyer</th>
                     <th>Style</th>
                     <th>Warna</th>
                     <th>Size</th>
                     <th>Qty</th>
                     <th>Unit</th>
                     <th>Curr</th>
                     <th>Price</th>
                     <th>No PO Buyer</th>
          <?php
        echo "</tr>";
      echo "</thead>";
        $sql=mysql_query("select so_date, so_no, ac.kpno,ms.supplier,ac.styleno,sd.color, sd.size, sd.qty, sd.unit, so.buyerno,so.curr,sd.price from so 
                                inner join so_det sd on sd.id_so = so.id
                                inner join act_costing ac on ac.id=so.id_cost
                                inner join mastersupplier ms on ac.id_buyer = ms.id_supplier
                                where so_date >='".fd($from)."' and so_date <= '".fd($to)."' and sd.cancel = 'N' order by so_date DESC ");
        $no = 1; 
        while($rs = mysql_fetch_array($sql))
        { $qtyout = flookup("sum(a.qty)","bppb a inner join so_det sod on a.id_so_det=sod.id","sod.id_so='$rs[id_so]'");
          echo "<tr>";
            echo "<td>$no</td>"; 
            echo "
              <td>".fd_view($rs['so_date'])."</td>
              <td>$rs[so_no]</td>
              <td>$rs[kpno]</td>
              <td>$rs[supplier]</td>
              <td>$rs[styleno]</td>
              <td>$rs[color]</td>
              <td>$rs[size]</td>
              <td>$rs[qty]</td>
              <td>$rs[unit]</td>
              <td>$rs[curr]</td>
              <td>$rs[price]</td>
              <td>$rs[buyerno]</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
}
else if ($tipe =="Warna")
{

    echo "<table id='examplefix' $tbl_border class='display responsive' style='width:100%;font-size:10px;'>";
      echo "<thead>";
        echo "<tr>";
          ?>
                     <th>NO</th>
                     <th>No WS</th>
                     <th>Buyer</th>
                     <th>Style</th>
                     <th>Warna</th>
                     <th>Total Qty</th>
                     <th>Unit</th>
                     <th>Curr</th>
                     <th>Price</th>                     
                     <th>No PO Buyer</th>
          <?php
        echo "</tr>";
      echo "</thead>";
        $sql=mysql_query("select ac.kpno, ms.supplier, ac.styleno, sd.color, sum(sd.qty) tot_qty, sd.unit, so.buyerno, so.curr, sd.price from so
                                inner join so_det sd on sd.id_so = so.id
                                inner join act_costing ac on ac.id=so.id_cost
                                inner join mastersupplier ms on ac.id_buyer = ms.id_supplier
                                where so_date >='".fd($from)."' and so_date <= '".fd($to)."' and sd.cancel = 'N' 
                                group by ac.kpno, sd.color
                                order by ac.kpno ASC , sd.color ASC ");
        $no = 1; 
        while($rs = mysql_fetch_array($sql))
        { 
          echo "<tr>";
            echo "<td>$no</td>"; 
            echo "
              <td>$rs[kpno]</td>
              <td>$rs[supplier]</td>
              <td>$rs[styleno]</td>
              <td>$rs[color]</td>
              <td>$rs[tot_qty]</td>
              <td>$rs[unit]</td>
              <td>$rs[curr]</td>
              <td>$rs[price]</td>
              <td>$rs[buyerno]</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
  
}

else if ($tipe =="Size")
{

    echo "<table id='examplefix' $tbl_border class='display responsive' style='width:100%;font-size:10px;'>";
      echo "<thead>";
        echo "<tr>";
          ?>
                     <th>NO</th>
                     <th>No WS</th>
                     <th>Buyer</th>
                     <th>Style</th>
                     <th>Size</th>
                     <th>Total Qty</th>
                     <th>Unit</th>
                     <th>Curr</th>
                     <th>Price</th>                     
                     <th>No PO Buyer</th>
          <?php
        echo "</tr>";
      echo "</thead>";
        $sql=mysql_query("select ac.kpno, ms.supplier, ac.styleno, sd.size, sum(sd.qty) tot_qty, sd.unit, so.buyerno, so.curr, sd.price from so
                                inner join so_det sd on sd.id_so = so.id
                                inner join act_costing ac on ac.id=so.id_cost
                                inner join mastersupplier ms on ac.id_buyer = ms.id_supplier
                                where so_date >='".fd($from)."' and so_date <= '".fd($to)."' and sd.cancel = 'N' 
                                group by ac.kpno, sd.size
                                order by ac.kpno ASC , sd.size ASC ");
        $no = 1; 
        while($rs = mysql_fetch_array($sql))
        { 
          echo "<tr>";
            echo "<td>$no</td>"; 
            echo "
              <td>$rs[kpno]</td>
              <td>$rs[supplier]</td>
              <td>$rs[styleno]</td>
              <td>$rs[size]</td>
              <td>$rs[tot_qty]</td>
              <td>$rs[unit]</td>
              <td>$rs[curr]</td>
              <td>$rs[price]</td>              
              <td>$rs[buyerno]</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
  
}
  echo "</div>";
echo "</div>";
?>  
<?php 
if (isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=kartu_stock.xls");//ganti nama sesuai keperluan 
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
}
else
{ $from = fd_view($_POST['txtfrom']);
  $to = fd_view($_POST['txtto']);
}
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='?mod=$mod&from=".fd($from)."&to=".fd($to)."&dest=xls'>Save To Excel</a></br>"; }
    echo "Pre-Cost Date : ".$from." - ".$to; 
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
    echo "<table id='examplefix' $tbl_border class='display responsive' style='width:100%'>";
    	echo "<thead>";
        echo "<tr>";
          ?>
          <th>No</th>
          <th>Pre-Cost #</th>
          <th>Pre-Cost Date</th>
          <th>Qty</th>
          <th>Unit</th>
          <th>Fabric Cost</th>
          <th>Accs Cost</th>
          <th>Mfg Cost</th>
          <th>Others Cost</th>
          <?php
        echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
        $sql=mysql_query("select * from pre_costing where 
          precost_date between '".fd($from)."' and '".fd($to)."'  
          ORDER BY id DESC");
        $no = 1; 
        while($data = mysql_fetch_array($sql))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[precost_no]</td>";
            echo "<td>$data[precost_date]</td>";
            echo "<td>$data[qty]</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[fabric_cost]</td>";
            echo "<td>$data[accs_cost]</td>";
            echo "<td>$data[mfg_cost]</td>";
            echo "<td>$data[other_cost]</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
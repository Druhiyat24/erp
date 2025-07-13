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
  $txtid_buyer = "";
}
else
{ $from = fd_view($_POST['txtfrom']);
  $to = fd_view($_POST['txtto']);
  $txtid_buyer = $_POST['txtid_buyer'];
}
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='?mod=$mod&from=".fd($from)."&to=".fd($to)."&dest=xls'>Save To Excel</a></br>"; }
    echo "Quotation Date : ".$from." - ".$to; 
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
    echo "<table id='examplefix' $tbl_border style='font-size:11px;' class='display responsive' style='width:100%'>";
    	echo "<thead>";
        echo "<tr>";
          ?>
          <th>No</th>
          <th>Quotation #</th>
          <th>Quotation Date</th>
          <th>Buyer Name</th>
          <th>Business Type</th>
          <th>Payment Terms</th>
          <th>Season</th>
          <th>Style #</th>
          <th>Style Description</th>
          <th>Qty</th>
          <th>Unit</th>
          <th>Curr</th>
          <th>Price</th>
          <th>Status</th>
          <?php
        echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
        if ($txtid_buyer=="") {$sql_id_buyer="";} else {$sql_id_buyer=" and a.id_buyer='$txtid_buyer'";}
        $sql=mysql_query("SELECT a.id,quote_no,quote_date,supplier buyer
          ,id_bisnis,nama_pterms pterms,season,styleno,styledesc,
          qty,unit,curr,price,status 
          FROM quote_inq a inner join mastersupplier s on a.id_buyer=s.id_supplier
          inner join masterpterms d on a.id_payment=d.id 
          where quote_date between '".fd($from)."' and '".fd($to)."' $sql_id_buyer ORDER BY a.id DESC");
        $no = 1; 
        while($data = mysql_fetch_array($sql))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[quote_no]</td>";
            echo "<td>".fd_view($data['quote_date'])."</td>";
            echo "<td>$data[buyer]</td>";
            echo "<td>$data[id_bisnis]</td>";
            echo "<td>$data[pterms]</td>";
            echo "<td>$data[season]</td>";
            echo "<td>$data[styleno]</td>";
            echo "<td>$data[styledesc]</td>";
            echo "<td>$data[qty]</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[curr]</td>";
            echo "<td>$data[price]</td>";
            echo "<td>$data[status]</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
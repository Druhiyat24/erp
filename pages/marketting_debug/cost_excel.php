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
$id_cost = $_GET['id'];
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='?mod=$mod&id=".$id_cost."&dest=xls'>Save To Excel</a></br>"; }
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";} 
    $sql="select a.id,cost_no,cost_date,supplier,styleno,
      qty,deldate,fullname,status,product_item,g.rate from act_costing a 
      inner join mastersupplier s on a.id_buyer=s.id_supplier 
      inner join userpassword d on a.username=d.username 
      inner join masterproduct f on a.id_product=f.id
      left join masterrate g on a.cost_date=g.tanggal
      and 'USD'=g.curr 
      where a.id='$id_cost'";
    $rs=mysql_fetch_array(mysql_query($sql));        
    ?>
    <table border="1" width="25%">
      <tr>
        <th>Buyer / Brand</th>
        <td><?php echo $rs['supplier']; ?></td>
      </tr>
      <tr>
        <th>Item</th>
        <td><?php echo $rs['product_item']; ?></td>
      </tr>
      <tr>
        <th>Planed Qty</th>
        <td><?php echo fn($rs['qty'],0); ?></td>
      </tr>
      <tr>
        <th>Exchange Rate</th>
        <td><?php echo fn($rs['rate'],0); ?></td>
      </tr>
    </table>
    <p><br>
    <table border="1" width="100%">
      <thead>
        <th>Material Description</th>
        <th>Unit Px (IDR)</th>
        <th>Unit Px (USD)</th>
        <th>Cons / Pcs</th>
        <th>Unit</th>
        <th>Allowance</th>
        <th>Value (IDR)</th>
        <th>Value (USD)</th>
        <th>%</th>
      </thead>
    </table>
    <?php
  echo "</div>";
echo "</div>";
?>  
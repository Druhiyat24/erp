<?php 
if (isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_cost_vs_so_perbuyer.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
else
{ $excel = "N"; }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod = $_GET['mod'];
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='?mod=$mod&dest=xls'>Save To Excel</a></br>"; }
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
    echo "<table id='examplefix' $tbl_border class='display responsive' style='width:100%;'>";
    	echo "<thead>";
        echo "<tr>";
          ?>
          <th>No</th>
          <th>ID</th>
          <th>Buyer</th>
          <th>Total Costing</th>
          <th>Total SO</th>
          <th>Persentase</th>
          <?php
        echo "</tr>";
      echo "</thead>";
      $query = mysql_query("select ms.id_supplier,ms.supplier,coalesce(data_cost.total_costing,0) total_costing,coalesce(data_so.total_so,0) total_so from mastersupplier ms
left join 
(select id_supplier,supplier,count(supplier) total_costing from act_costing ac
inner join mastersupplier ms on ms.id_supplier = ac.id_buyer
where ac.app1 = 'A'
group by ms.supplier) data_cost on data_cost.id_supplier = ms.id_supplier
left join 
(select id_supplier,supplier,count(supplier) total_so from so
inner join act_costing ac on ac.id = so.id_cost
inner join mastersupplier ms on ms.id_supplier = ac.id_buyer
where so.cancel_h = 'N'
group by ms.supplier) data_so on data_so.id_supplier = ms.id_supplier
where ms.tipe_sup = 'C'
order by supplier asc");
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { 
        $persentase = round(($data[total_costing] / $data[total_so] * 100),2);

        echo "
        <tr>
          <td>$no</td>
          <td>$data[id_supplier]</td>
          <td>$data[supplier]</td>
          <td>$data[total_costing]</td>
          <td>$data[total_so]</td>
          <td>$persentase %</td>
        </tr>";
        $no++; // menambah nilai nomor urut
      }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
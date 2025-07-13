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
          <th>ID Contents</th>
          <th>ID Color</th>
          <th>Subgroup</th>
          <th>Type</th>
          <th>Nama Content</th>
          <th>Width</th>
          <th>Length</th>
          <th>Weight</th>
          <th>Color</th>
          <?php
        echo "</tr>";
      echo "</thead>";
      $query = mysql_query("select e.id as id_contents, i.id as id_color, nama_sub_group, nama_type,nama_contents,nama_width,nama_length,nama_weight,nama_color
              from mastergroup a inner join mastersubgroup s on 
              a.id=s.id_group 
              inner join mastertype2 d on s.id=d.id_sub_group
              inner join mastercontents e on d.id=e.id_type
              inner join masterwidth f on e.id=f.id_contents
              inner join masterlength g on f.id=g.id_width
              inner join masterweight h on g.id=h.id_length
              inner join mastercolor i on h.id=i.id_weight
              order by e.id asc   ");
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { echo "
        <tr>
          <td>$no</td>
          <td>$data[id_contents]</td>
          <td>$data[id_color]</td>
          <td>$data[nama_sub_group]</td>
          <td>$data[nama_type]</td>
          <td>$data[nama_contents]</td>
          <td>$data[nama_width]</td>
          <td>$data[nama_length]</td>
          <td>$data[nama_weight]</td>
          <td>$data[nama_color]</td>
        </tr>";
        $no++; // menambah nilai nomor urut
      }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
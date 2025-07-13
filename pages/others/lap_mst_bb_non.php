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
          <th>ID</th>
          <th>Kode Bahan Baku</th>
          <th>Mapping Persediaan</th>
          <th>Deskripsi Bahan Baku</th>
          <th>Color</th>
          <th>Size</th>
          <th>HS Code</th>
          <th>Type Item</th>
          <?php
        echo "</tr>";
      echo "</thead>";
      // $query = mysql_query("SELECT mi.*,mhs.kode_hs FROM masteritem mi left join masterhs mhs on mi.hscode=mhs.id 
      //  where mattype='N' ORDER BY id_item DESC");
      $query = mysql_query("select ITEM.*,MAPP.description from masteritem ITEM LEFT JOIN mapping_category MAPP ON MAPP.n_id = ITEM.n_code_category where ITEM.mattype='N' order by ITEM.id_item desc");      
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { echo "
        <tr>
          <td>$no</td>
          <td>$data[id_item]</td>
          <td>$data[goods_code]</td>
          <td>$data[description]</td>
          <td>$data[itemdesc]</td>
          <td>$data[color]</td>
          <td>$data[size]</td>
          <td>$data[kode_hs]</td>
          <td>$data[tipe_item]</td>
        </tr>";
        $no++; // menambah nilai nomor urut
      }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
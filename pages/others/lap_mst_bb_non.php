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
          <th>COA Production</th>
          <th>COA Supporting Production</th>
          <th>COA Supporting Selling</th>
          <th>COA Supporting General & Administration</th>
          <?php
        echo "</tr>";
      echo "</thead>";
      // $query = mysql_query("SELECT mi.*,mhs.kode_hs FROM masteritem mi left join masterhs mhs on mi.hscode=mhs.id 
      //  where mattype='N' ORDER BY id_item DESC");
      $query = mysql_query("select ITEM.*,MAPP.description, CONCAT(a.no_coa,' - ',a.nama_coa) nama_coa_production, CONCAT(b.no_coa,' - ',b.nama_coa) nama_coa_sup_production, CONCAT(c.no_coa,' - ',c.nama_coa) nama_coa_sup_gen_adm, CONCAT(d.no_coa,' - ',d.nama_coa) nama_coa_sup_selling
from masteritem ITEM 
LEFT JOIN mapping_category MAPP ON MAPP.n_id = ITEM.n_code_category 
LEFT JOIN mastercoa_v2 a on a.no_coa = ITEM.coa_production
LEFT JOIN mastercoa_v2 b on b.no_coa = ITEM.coa_sup_production
LEFT JOIN mastercoa_v2 c on c.no_coa = ITEM.coa_sup_gen_adm
LEFT JOIN mastercoa_v2 d on d.no_coa = ITEM.coa_sup_selling
where ITEM.mattype='N' order by ITEM.id_item desc");      
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
          <td>$data[nama_coa_production]</td>
          <td>$data[nama_coa_sup_production]</td>
          <td>$data[nama_coa_sup_selling]</td>
          <td>$data[nama_coa_sup_gen_adm]</td>
        </tr>";
        $no++; // menambah nilai nomor urut
      }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  
<?php 
if (isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=master_bhnbaku.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
else
{ $excel = "N"; }      

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod = $_GET['mod'];
$tipe = $_GET['tipe'];

echo "
<div class='row'>
    <form action='' method='post'>

    <div class='box-header'>
      <div class='col-md-2'>                            
          <label>Jenis</label>
          <select class='form-control select2' style='width: 100%;' 
            name='txttipe'>
            <option value='FABRIC'>FABRIC</option>
            <option value='ACCESORIES SEWING'>ACCESORIES SEWING</option>
            <option value='ACCESORIES PACKING'>ACCESORIES PACKING</option>
            <option value='BARANG JADI'>BARANG JADI</option>
            <option value='PANEL'>PANEL</option>
            <option value='SAMPLE'>SAMPLE</option>
          </select>  
      </div>      
      <div class='col-md-3'>
          <div>
          <br>
              <button type='submit' name='submitfilter' class='btn btn-primary'>Tampilkan</button>              
          </div>         
      </div>
   </div>
  </div>";
  if (isset($_POST['submitfilter']))
{
  $tipe = $_POST['txttipe'];
}  
echo "<div class='box'>";
  echo "<div class='box-body'>";
  echo "</div>";
      if ($excel=="N") { echo "<a href='?mod=$mod&tipe=$tipe&dest=xls'>Save To Excel</a></br>"; }
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
    echo "<table id='examplefix' $tbl_border class='display responsive' style='width:100%;'>";
    	echo "<thead>";
        echo "<tr>";
          ?>
          <th>No</th>
          <th>Tipe</th>          
          <th>ID Contents</th>
          <th>Kode Bahan Baku</th>
          <th>Deskripsi Bahan Baku</th>
          <th>Color</th>
          <th>Size</th>
          <th>Add Info</th>
          <?php
        echo "</tr>";
      echo "</thead>";
      $query = mysql_query("SELECT mcn.id as id_contents,mi.mattype,mi.matclass,mi.itemdesc,mi.color,mi.size,mi.id_item,mi.goods_code,mi.id_gen,mhs.kode_hs FROM masteritem mi 
left join masterhs mhs on mi.hscode=mhs.id
inner join masterdesc md on mi.id_gen = md.id
inner join mastercolor mc on md.id_color = mc.id
inner join masterweight mw on mc.id_weight = mw.id
inner join masterlength ml on mw.id_length = ml.id
inner join masterwidth mwd on ml.id_width = mwd.id
inner join mastercontents mcn on mwd.id_contents = mcn.id
where mi.matclass = '$tipe'  and non_aktif='N' ORDER BY id_item DESC
");
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { echo "
        <tr>
          <td>$no</td>
          <td>$data[matclass]</td>          
          <td>$data[id_contents]</td>
          <td>$data[goods_code]</td>
          <td>$data[itemdesc]</td>
          <td>$data[color]</td>
          <td>$data[size]</td>
          <td>$data[add_info]</td>
        </tr>";
        $no++; // menambah nilai nomor urut
      }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
  echo "</form>";
echo "</div>";
?>  
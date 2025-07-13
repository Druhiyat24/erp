<table id="examplefix" class="display responsive" width="100%">
  <thead>
    <tr>
        <th>No</th>
        <th><?PHP echo "Kode ".$titlenya;?></th>
        <th><?PHP echo $titlenya;?></th>
        <th><?php echo $c28; ?></th>
        <th>Area</th>
        <th>Negara</th>
        <th></th>
        <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    # QUERY TABLE
    $query = mysql_query("SELECT *,if(area='I','Import/Export',
      if(area='L','Lokal',if(area='F','Factory',area))) areanya 
      FROM mastersupplier where $filternya ORDER BY id_supplier desc");
    $no = 1; 
    while($data = mysql_fetch_array($query))
    { echo "<tr>";
      echo "<td>$no</td>"; 
      echo "<td>$data[supplier_code]</td>"; 
      echo "<td>$data[Supplier]</td>"; 
      echo "<td>$data[alamat]</td>"; 
      echo "<td>$data[areanya]</td>";
      echo "<td>$data[country]</td>";
      echo "
      <td>
        <a href='?mod=20&mode=$mode&id=$data[Id_Supplier]'
          $tt_ubah
        </a>
      </td>";
      echo "
      <td>
        <a href='../forms/del_data.php?mod=20&mode=$mode&id=$data[Id_Supplier]'
        $tt_hapus";?> 
        onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?php echo $tt_hapus2."</a>
      </td>";
      echo "</tr>";
      $no++; // menambah nilai nomor urut
    }
    ?>
  </tbody>
</table>
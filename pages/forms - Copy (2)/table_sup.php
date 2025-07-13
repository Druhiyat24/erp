<table id="tbl_sup" class="display responsive" width="100%">
  <thead>
    <tr>
        <th>No</th>
        <th><?PHP echo "Kode ".$titlenya;?></th>
        <th><?PHP echo $titlenya;?></th>
        <th><?php echo $c28; ?></th>
        <th>Area</th>
        <th>Product</th>
        <th>MOQ</th>
        <th>Lead Time</th>
        <th>MOQ LT</th>
        <th>PKP</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    # QUERY TABLE
    $query = mysql_query("SELECT *,if(area='I','Import/Export',
      if(area='L','Lokal',if(area='F','Factory',area))) areanya 
      FROM mastersupplier where tipe_sup='S' ORDER BY id_supplier desc");
    $no = 1; 
    while($data = mysql_fetch_array($query))
    { echo "
      <tr>
        <td>$no</td>
        <td>$data[supplier_code]</td>
        <td>$data[Supplier]</td>
        <td>$data[alamat]</td>
        <td>$data[areanya]</td>
        <td>$data[product_name]</td>
        <td>$data[moq]</td>
        <td>$data[lead_time]</td>
        <td>$data[moq_lead_time]</td>
        <td>$data[pkp]</td>";
        if($data['non_aktif']=="0")
        { echo "
          <td><a href='?mod=$mod&mode=$mode&id=$data[Id_Supplier]'
            $tt_ubah</a>
          </td>
          <td><a href='../forms/del_data.php?mod=$mod&mode=$mode&id=$data[Id_Supplier]'
            $tt_hapus";?> 
            onclick="return confirm('Apakah Anda Yakin Akan Menghapus ?')"><?php echo $tt_hapus2."</a>
          </td>
          <td><a href='../forms/non_akt.php?mod=$mod&mode=$mode&id=$data[Id_Supplier]'
            data-toggle='tooltip' title='Non Aktif' ";?> 
            onclick="return confirm('Apakah Anda Yakin Akan Meng-Non Aktifkan ?')"><?php echo "<i class='fa fa-eye-slash'></i></a>
          </td>";
        }
        else
        { echo "
          <td>Non Aktif</td>
          <td></td>
          <td></td>";
        }
      echo "  
      </tr>";
      $no++; // menambah nilai nomor urut
    }
    ?>
  </tbody>
</table>
<table id="examplefix3" class="display responsive" style="width:100%">
  <thead>
  <tr>
      <th width="2%">No</th>
      <th width="15%">Kode <?PHP echo $titlenya;?></th>
      <th width="5%">Klasifikasi</th>
      <th width="20%">Nama <?PHP echo $titlenya;?></th>
      <th>SN</th>
      <th>Brand</th>
      <th>Thn. Beli</th>
      <th>Kode Lama</th>
      <th>Desc Lama</th>
      <th width='17%'><?php echo "Aksi"; ?></th>
  </tr>
  </thead>
    <tbody>
      <?php
      # QUERY TABLE
      $query = mysql_query("SELECT a.*,s.goods_code kode_lama,s.itemdesc desc_lama FROM masteritem 
        a left join masteritem_odo s on a.id_item_odo=s.id_item_odo 
        where $filternya ORDER BY a.id_item DESC");
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { echo "<tr>";
        echo "<td>$no</td>"; 
        echo "<td>$data[goods_code]</td>"; 
        echo "<td>$data[matclass]</td>"; 
        echo "<td>$data[itemdesc]</td>"; 
        echo "<td>$data[sn]</td>"; 
        echo "<td>$data[brand]</td>"; 
        echo "<td>$data[thn_beli]</td>";
        echo "<td>$data[kode_lama]</td>";
        echo "<td>$data[desc_lama]</td>"; 
        echo "<td><a $cl_ubah href='../forms/?mod=2&mode=$mode&id=$data[id_item]'
          $tt_ubah</a>";
        echo " <a $cl_hapus href='del_data.php?mode=$mode&id=$data[id_item]'
          $tt_hapus";?> 
              onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
        if ($rkartu_stock=="1")
        { echo "  <a $cl_hist href='../forms/?mod=14&mode=$mode&id=$data[id_item]'
          $tt_hist</a>";
        }
        if ($upload_image=="1")
        { echo "  <a href='#' class='img-prev $cl_attach' data-id=$data[id_item]
            $tt_attach</a></td>";
        }
        else
        { echo "</td>"; }
        echo "</tr>";
        $no++; // menambah nilai nomor urut
      }
      ?>
    </tbody>
</table>
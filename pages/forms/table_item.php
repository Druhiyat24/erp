<table id="tbl_master_item" width="100%" class="display responsive">
  <thead>
  <tr>
      <th>Nomor</th>
      <th>Kode <?PHP echo $titlenya;?></th>
      <th><?php echo $c16.' '.$titlenya; ?></th>
      <th><?php echo $c17; ?></th>
      <th><?php echo $c18; ?></th>
      <th>HS Code</th>
      <th>Mat Source</th>
      <?php if ($nm_company=="PT. Sandrafine Garment") { ?>
      <th>Stock Card</th>
      <th>Klasifikasi</th>
      <?php } ?>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
  </tr>
  </thead>
    <tbody>
      <?php
      # QUERY TABLE
      $query = mysql_query("SELECT mi.*,mhs.kode_hs FROM masteritem mi left join masterhs mhs on mi.hscode=mhs.id 
       where $filternya ORDER BY id_item DESC");
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { echo "
    		<tr>
    			<td>$no</td>
    			<td>$data[goods_code]</td>
    			<td>$data[itemdesc]</td>"; 
        if ($mode!="Mesin")
        { echo "
      		<td>$data[color]</td>
      		<td>$data[size]</td>"; 
        }
        echo "<td>$data[kode_hs]</td>";
        if($data['base_supplier']=="I")
          { $m_sour="IMPORT"; }
        else if($data['base_supplier']=="L")
          { $m_sour="LOKAL"; }
        else
          { $m_sour=""; }
        echo "<td>$m_sour</td>";
        if ($nm_company=="PT. Sandrafine Garment")
        {	echo "<td>$data[stock_card]</td>";
        	echo "<td>$data[matclass]</td>";
        }
        if($mod!="22L") 
        { echo "
          <td>
            <a $cl_ubah href='index.php?mod=2&mode=$mode&id=$data[id_item]'
              $tt_ubah
            </a>
          </td>";
          echo "
          <td>
            <a $cl_hapus href='del_data.php?mode=$mode&id=$data[id_item]'
              $tt_hapus";?> 
              onclick="return confirm('Apakah anda yakin akan menghapus ?')">
              <?php echo $tt_hapus2."</a>
          </td>";
          if ($rkartu_stock=="1")
          { echo "
            <td>
              <a $cl_hist href='index.php?mod=14&mode=$mode&id=$data[id_item]'
                $tt_hist
              </a>
            </td>";
          }
          else
          { echo "<td></td>"; }
          if ($upload_image=="1")
          { echo "
            <td>
              <a href='#' class='img-prev $cl_attach' data-id=$data[id_item]
              data-toggle='tooltip' title='Attachment'><i class='fa fa-paperclip'></i>
              </a>
            </td>";
          }
          else
          { echo "<td></td>"; }
        }
        else
        { echo "
          <td></td>
          <td></td>
          <td></td>
          <td></td>"; 
        }
        echo "</tr>";
        $no++; // menambah nilai nomor urut
      }
      ?>
    </tbody>
</table>
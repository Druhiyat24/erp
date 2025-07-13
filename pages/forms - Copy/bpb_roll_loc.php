<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item="";

# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  {
    var wsno = document.form.txtws.value;
    if (wsno == '') { swal({ title: 'No. WS Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false; }
    else {valid = true;}
    return valid;
    exit;
  };
  function getListData(cri_item)
  { var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bpb_jo.php?modeajax=view_list_temp_loc',
        data: {cri_item: cri_item},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplefix').DataTable
      ({  scrollY: "300px",
          scrollCollapse: true,
          sorting: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
    $(".select2").select2();
  };
  function choose_rak(id_h)
  { var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bpb_jo.php?modeajax=view_list_rak_loc_trx',
        data: {id_h: id_h},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_rak").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplefix').DataTable
      ({  sorting: false,
          searching: false,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };
</script>
<div class="modal fade" id="myRak"  tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" style="overflow-y:auto;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pilih Detail</h4>
      </div>
      <div class="modal-body" style="overflow-y:auto; height:500px;">
        <div id='detail_rak'></div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="save_rak()" data-dismiss="modal">Simpan</button>
      </div>
    </div>
  </div>
</div>
<?php 
# END COPAS VALIDASI
# COPAS ADD
if ($mod=="18LV")
{?>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">List Data</h3>
      <a href='../forms/?mod=18L' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
    </div>
    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width:100%;font-size:12px;">
        <thead>
          <tr>
            <th>BPB #</th>
            <th>WS #</th>
            <th>Item</th>
            <th>Deskripsi</th>
            <th>Tgl Input</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $sql="SELECT brh.dateinput,brh.location,brh.id,brh.bpbno,ac.kpno,mi.goods_code,mi.itemdesc,br.roll_no,
            br.lot_no,br.roll_qty,br.roll_foc,br.unit,concat(mr.kode_rak,' ',mr.nama_rak) nama_rak 
            FROM bpb_roll_h brh inner join bpb_roll br on brh.id=br.id_h 
            inner join masteritem mi on brh.id_item=mi.id_item 
            inner join master_rak mr on br.id_rak=mr.id 
            inner join jo_det jod on brh.id_jo=jod.id_jo 
            inner join jo on jod.id_jo=jo.id  
            inner join so on jod.id_so=so.id 
            inner join act_costing ac on so.id_cost=ac.id 
            where brh.location='Ok' 
            group by brh.id order by bpbno";
          $query = mysql_query($sql);
          #echo $sql;
          while($data = mysql_fetch_array($query))
          { $bpbno_int=flookup("bpbno_int","bpb","bpbno='$data[bpbno]'");
            echo "
            <tr>";
              if($bpbno_int!="")
              { echo "<td>$bpbno_int</td>"; }
              else
              { echo "<td>$data[bpbno]</td>"; }
              echo "
              <td>$data[kpno]</td>
              <td>$data[goods_code]</td>
              <td>$data[itemdesc]</td>
              <td>".fd_view($data["dateinput"])."</td>
              <td>
                <button type='button' class='btn btn-primary' data-toggle='modal' 
                  data-target='#myRak' onclick='choose_rak($data[id])'>Detail</button>
              </td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
<?php }
else
{ echo "<div class='box'>";
    echo "<div class='box-body'>";
      echo "<div class='row'>";
        echo "<form method='post' name='form' action='save_data_bpb_roll_loc.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
          echo "
          <div class='col-md-3'>
            <div class='form-group'>
              <label>WS *</label>
              <select class='form-control select2' style='width: 100%;' name='txtws' id='cbows' 
                onchange='getListData(this.value)'>";
              $sql="select a.id_jo isi,ac.kpno tampil 
                from bpb_roll_h a inner join jo_det jod on a.id_jo=jod.id_jo 
                inner join so on jod.id_so=so.id 
                inner join act_costing ac on so.id_cost=ac.id 
                inner join bpb_roll br on a.id=br.id_h 
                where br.id_rak_loc is null group by a.id_jo";
              IsiCombo($sql,"","Pilih WS");
              echo "
              </select>  
            </div>
          </div>";
          echo "<div class='box-body'>";
           echo "<div id='detail_item'></div>";
          echo "</div>";
          echo "<div class='col-md-3'>";
            echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
          echo "</div>";
        echo "</form>";
      echo "</div>";
    echo "</div>";
  echo "</div>";
}
# END COPAS ADD
?>
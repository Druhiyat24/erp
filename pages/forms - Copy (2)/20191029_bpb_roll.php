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
  function calc_konv()
  {
    var qtyr = document.form.getElementsByClassName('jmlclass');
    var qtyk = document.form.getElementsByClassName('jmlkclass');
    var strkonv = document.form.txtkonv.value;
    var konv = strkonv.split("|");
    var konvcri = konv[0];
    var konvnya = konv[1];
    if (isNaN(konvnya)) { konvnya = 1; }
    for (var i = 0; i < qtyr.length; i++) 
    { 
      if (konvcri == 'Kali')
      { jmlkon = qtyr[i].value * konvnya; }
      else
      { jmlkon = qtyr[i].value / konvnya; }
      qtyk[i].value = jmlkon;
    }  
  };
  function validasi()
  {
    var bpbno = document.form.cbosj.value;
    var jmlroll = document.form.txtroll.value;
    var satdet = document.form.txtunitdet.value;
    var jmlbpb = document.form.txtqtybpbact.value;
    var jmldet = 0;
    var rakkos = 0;
    var qtyr = document.form.getElementsByClassName('jmlclass');
    var nmrak = document.form.getElementsByClassName('rakclass');
    var jmlbpbfoc = document.form.txtqtybpbfoc.value;
    var jmldetfoc = 0;
    var qtyrfoc = document.form.getElementsByClassName('jmlfclass');

    for (var i = 0; i < qtyr.length; i++) 
    { 
      jmldet=jmldet+Number(qtyr[i].value);
      if (nmrak[i].value == '') { rakkos = rakkos + 1; }
    }

    for (var i = 0; i < qtyrfoc.length; i++) 
    { 
      jmldetfoc=jmldetfoc+Number(qtyrfoc[i].value);
    }

    if (bpbno == '') { swal({ title: 'No. BPB Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false; }
    else if (jmlroll == '') { swal({ title: 'Jumlah Detail Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if (satdet == '') { swal({ title: 'Unit Detail Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if (Number(rakkos) > 0) { swal({ title: 'Rak Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if (Number(parseFloat(jmldet).toFixed(2)) != Number(parseFloat(jmlbpb).toFixed(2))) { swal({ title: 'Jumlah Detail ('+parseFloat(jmldet).toFixed(2)+') Actual ('+parseFloat(jmlbpb).toFixed(2)+')Tidak Sesuai', <?php echo $img_alert; ?> });valid = false;}
    else if (Number(jmldetfoc) != Number(jmlbpbfoc)) { swal({ title: 'Jumlah Detail FOC Tidak Sesuai', <?php echo $img_alert; ?> });valid = false;}
    else {valid = true;}
    return valid;
    exit;
  };
  function getQtyBPB(cri_item)
  { jQuery.ajax
    ({  url: 'ajax2.php?modeajax=cari_qty_bpb',
        method: 'POST',
        data: {cri_item: cri_item},
        dataType: 'json',
        success: function(response)
        { $('#txtqtybpb').val(response[0]);
          $('#txtqtybpbact').val(response[0]);
          $('#txtunit').val(response[2]);
          $('#txtunitkonv').val(response[3]);
          $('#txtqtykonv').val(response[4]);
          $('#txtqtykonvact').val(response[4]);
          $('#txtkonv').val(response[5]);
          $('#txtpono').val(response[6]);
          $('#txtsupplier').val(response[7]);
          $('#txtitem').val(response[8]);
          $('#txtws').val(response[9]);  
        },
        error: function (request, status, error) {
            alert(request.responseText);
        },
    });
  };
  function getListData()
  { var cri_item = document.form.txtroll.value;
    var sat_nya = document.form.txtunitdet.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax2.php?modeajax=view_list_roll',
        data: {cri_item: cri_item,sat_nya: sat_nya},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_item").html(html);
    }
    $(".select2").select2();
  };
  function getListBPB()
  { var cri_item = document.form.txttglcut.value;
    var mat_nya = document.form.txtmat.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax2.php?modeajax=cari_list_bpb',
        data: {cri_item: cri_item,mat_nya: mat_nya},
        async: false
    }).responseText;
    if(html)
    {
        $("#cbosj").html(html);
    }
  };
</script>
<?php 
# END COPAS VALIDASI
# COPAS ADD
if($mod=="18")
{ 
  echo "<div class='box'>";
    echo "<div class='box-body'>";
      echo "<div class='row'>";
        echo "<form method='post' name='form' action='save_data_bpb_roll.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
          echo "
          <div class='col-md-3'>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Type Material *</label>
                  <select class='form-control select2' style='width: 100%;' name='txtmat' id='cbomat' 
                    onchange='getListBPB()'>";
                  $sql="select matclass isi,matclass tampil from masteritem where matclass!='' 
                    group by matclass";
                  IsiCombo($sql,"","Pilih Material");
                  echo "
                  </select>  
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Tgl Penerimaan *</label>
                  <input type='text' class='form-control' name='txttglcut' id='datepicker1' 
                    placeholder='Masukkan Filter Tgl Penerimaan' onchange='getListBPB()'>
                </div>
              </div>
            </div>
            <div class='form-group'>
              <label>Nomor BPB *</label>
              <select class='form-control select2' style='width: 100%;' name='txtsjno' id='cbosj' 
                onchange='getQtyBPB(this.value)'>
              </select>
            </div>";
            echo "
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Jml Detail *</label>
                  <select class='form-control select2' style='width: 100%;' name='txtroll'>
                    <option value='' disabled selected>Pilih Jml Detail</option>";
                    for ($x = 1; $x <= 100; $x++) 
                    { echo "<option value='$x'>$x</option>"; }
                  echo "</select>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Unit Detail *</label>
                  <select class='form-control select2' style='width: 100%;' name='txtunitdet' 
                    onchange='getListData()'>";
                  $sql="select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                    kode_pilihan='Satuan' order by nama_pilihan";
                  IsiCombo($sql,"","Pilih Unit");
                  echo "</select>
                </div>
              </div>
            </div>";
          echo "</div>";
          echo "
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Item</label>
              <input type='text' class='form-control' id='txtitem' name='txtitem' readonly>
            </div>
            <div class='form-group'>
              <label>WS #</label>
              <input type='text' class='form-control' id='txtws' name='txtws' readonly>
            </div>
          </div>";
          echo "<div class='col-md-3'>";
            echo "
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Jml BPB (Temp)</label>
                <input type='text' class='form-control' id='txtqtybpb' name='txtqtybpb' readonly>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Unit BPB</label>
                <input type='text' class='form-control' id='txtunit' name='txtunit' readonly>
              </div>
            </div>";
            echo "
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Jml BPB (Actual)</label>
                <input type='text' class='form-control' id='txtqtybpbact' name='txtqtybpbact'>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Jml BPB (FOC)</label>
                <input type='text' class='form-control' id='txtqtybpbfoc' name='txtqtybpbfoc'>
              </div>
            </div>";
            echo "
            <div class='col-md-6'>
              <div class='form-group'>
                <label>PO #</label>
                <input type='text' class='form-control' id='txtpono' readonly>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Supplier</label>
                <input type='text' class='form-control' id='txtsupplier' readonly>
              </div>
            </div>";
            
          echo "</div>";
          echo "
          <div class='col-md-3'>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>$c31 Konversi</label>
                <input type='text' class='form-control' id='txtqtykonv' name='txtqtykonv' readonly>
                <input type='hidden' class='form-control' id='txtkonv' name='txtkonv' readonly>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Unit Konversi</label>
                <input type='text' class='form-control' id='txtunitkonv' name='txtunitkonv' readonly>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Jml Konv (Actual)</label>
                <input type='text' class='form-control' id='txtqtykonvact' name='txtqtykonvact' readonly>
                <input type='hidden' class='form-control' id='txtkonv' name='txtkonv' readonly>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>$c31 Konv (FOC)</label>
                <input type='text' class='form-control' id='txtqtykonvact' name='txtqtykonvact' readonly>
                <input type='hidden' class='form-control' id='txtkonv' name='txtkonv' readonly>
              </div>
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
else
{ ?>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">List Data</h3>
      <a href='../forms/?mod=18' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
    </div>
    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width:100%;font-size:10px;">
        <thead>
          <tr>
            <th>BPB #</th>
            <th>WS #</th>
            <th>Item</th>
            <th>Deskripsi</th>
            <th>#</th>
            <th>Lot #</th>
            <th>Qty</th>
            <th>FOC</th>
            <th>Unit</th>
            <th>Nama Rak</th>
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
            order by bpbno";
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
              <td>$data[roll_no]</td>
              <td>$data[lot_no]</td>
              <td>$data[roll_qty]</td>
              <td>$data[roll_foc]</td>
              <td>$data[unit]</td>
              <td>$data[nama_rak]</td>
              <td>".fd_view($data["dateinput"])."</td>";
              if($data['location']!="")
              { echo "<td></td>"; }
              else
              { echo "
                <td>
                  <a href='?mod=18e&id=$data[id]'
                    $tt_ubah</a>
                </td>";
              }
            echo "
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
<?php }

# END COPAS ADD
?>
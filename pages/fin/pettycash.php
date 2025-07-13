<?PHP
if (empty($_SESSION['username'])) { header("location:../fin/?mod=1"); }

$title="Petty Cash";
if(isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }
$mode="";

# COPAS EDIT
if ($id_item=="")
{ $jenis_trans = "";
  $tanggal_trans = "";
  $keterangan = "";
  $curr = "";
  $amount = "";
}
else
{ $query = mysql_query("SELECT * FROM acc_pettycash where id='$id_item' ");
  $data = mysql_fetch_array($query);
  $jenis_trans = $data['jenis_trans'];
  $tanggal_trans = fd_view($data['tanggal_trans']);
  $keterangan = $data['keterangan'];
  $curr = $data['curr'];
  $amount = $data['amount'];
}
# END COPAS EDIT

# COPAS VALIDASI
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "
    var jenis_trans = document.form.txtjenis_trans.value;
    var tanggal_trans = document.form.txttanggal_trans.value;
    var keterangan = document.form.txtketerangan.value;
    var curr = document.form.txtcurr.value;
    var amount = document.form.txtamount.value;
    var sisa = document.form.txtsisa.value;";

    echo "
    if (jenis_trans == '') { alert('Jenis Transaksi tidak boleh kosong'); valid = false;}
    else if (tanggal_trans == '') { alert('Tanggal Transaksi tidak boleh kosong'); document.form.txttanggal_trans.focus();valid = false;}
    else if (keterangan == '') { alert('Keterangan tidak boleh kosong'); document.form.txtketerangan.focus();valid = false;}
    else if (curr == '') { alert('Mata Uang tidak boleh kosong'); valid = false;}
    else if (amount == '') { alert('Jumlah tidak boleh kosong'); document.form.txtamount.focus();valid = false;}
    else if (Number(amount) > Number(sisa) && jenis_trans!=='PENERIMAAN') { alert('Amount tidak mencukupi'); document.form.txtamount.focus();valid = false;}
    else valid = true;
    return valid;
    exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getSisaAmt()
  { var tgl_trans = document.form.txttanggal_trans.value;
    var curr = document.form.txtcurr.value;
    jQuery.ajax
    ({  
      url: "ajax.php?modeajax=get_sisa_cash",
      method: 'POST',
      data: {tgl_trans: tgl_trans, curr: curr},
      dataType: 'json',
      success: function(response)
      { $('#txtsisa').val(response[0]); },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
</script>
<div class="box">
  <?PHP
    # COPAS ADD 3 HAPUS /DIV TERAKHIR
    echo "<div class='box-body'>";
    echo "<div class='row'>";
    echo "<form method='post' name='form' action='save_petty.php?mode=$mode&id=$id_item' onsubmit='return validasi()'>";
    echo "<div class='col-md-3'>";
    echo "<div class='form-group'>";
      echo "<label>Jenis Transaksi *</label>";
      $sql = "select nama_pilihan isi, nama_pilihan tampil from masterpilihan where kode_pilihan='J_TRANS'";
      echo "<select class='form-control select2' style='width: 100%;' name='txtjenis_trans'>";
      IsiCombo($sql,$jenis_trans,'Pilih Jenis Transaksi');
      echo "</select>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>Tanggal Transaksi *</label>";
      echo "<input type='text' class='form-control' name='txttanggal_trans' id='datepicker1' 
        placeholder='Masukkan Tanggal Transaksi' value='$tanggal_trans' onchange='getSisaAmt()'>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>Keterangan *</label>";
      echo "<input type='text' class='form-control' name='txtketerangan' placeholder='Masukkan Keterangan' value='$keterangan'>";
    echo "</div>";
    echo "</div>";
    echo "<div class='col-md-3'>";
    echo "<div class='form-group'>";
      echo "<label>Mata Uang *</label>";
      $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
          where kode_pilihan='CURR' order by nama_pilihan";
      echo "<select class='form-control select2' style='width: 100%;' name='txtcurr'
        onchange='getSisaAmt()'>";
      IsiCombo($sql,$curr,'Pilih Mata Uang');
      echo "</select>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>Sisa Petty Cash *</label>";
      echo "<input type='text' class='form-control' name='txtsisa' id='txtsisa' readonly>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>Jumlah *</label>";
      echo "<input type='text' class='form-control' name='txtamount' placeholder='Masukkan Jumlah' value='$amount'>";
    echo "</div>";
    echo "<div class='box-footer'>";
      echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
    echo "</div>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    # END COPAS ADD 3 HAPUS /DIV TERAKHIR
  ?>  
</div>
<div class="box">
  <div class="box-header with-border">
    <?php 
    echo "<h3 class='box-title'>$c_list $title</h3><br>";
    echo "<h3 class='box-title'>.</h3><br>";
    echo "<table id='examplefix' class='table table-bordered table-striped'>";
      echo "
      <thead>
        <tr>
          <th>Jenis Trans</th>
          <th>Tgl. Trans</th>
          <th>Keterangan</th>
          <th>Mata Uang</th>
          <th>Jumlah</th>
          <th></th>
        </tr>
      </thead>";
      echo "<tbody>";
      $query = mysql_query("SELECT * FROM acc_pettycash order by tanggal_trans desc ");
      while($data = mysql_fetch_array($query))
      {   echo "<tr>";
          echo "
            <td>$data[jenis_trans]</td>
            <td>".fd_view($data["tanggal_trans"])."</td>
            <td>$data[keterangan]</td>
            <td>$data[curr]</td>
            <td>".fn($data["amount"],2)."</td>
          	<td>
              <a href='?mod=3&id=$data[id]'
                $tt_ubah</a>
            </td>	
          </tr>";
      }  
      echo "</tbody>";
    echo "</table>";
    ?>
  </div>
</div>
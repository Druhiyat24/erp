<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user = $_SESSION['username'];
# CEK HAK AKSES KEMBALI
$akses = flookup("m_rate","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_rate = $_GET['id']; } else {$id_rate = "";}
$titlenya="Rate Currency";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_rate=="")
{ $tanggal = "";
  $rate = "";
  $ratejual = "";
  $ratebeli = "";
  $id_curr = "";
}
else
{ $query = mysql_query("SELECT * FROM masterrate where id='$id_rate'");
  $data = mysql_fetch_array($query);
  $tanggal = fd_view($data['tanggal']);
  $rate = $data['rate'];
  $ratejual = $data['rate_jual'];
  $ratebeli = $data['rate_beli'];
  $id_curr = $data['curr'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var id_group = document.form.cbocurr.value;
      var tglfr = document.form.txtfrom.value;
      var tglto = document.form.txtto.value;
      var rate = document.form.txtrate.value;
      var ratejual = document.form.txtratejual.value;
      var ratebeli = document.form.txtratebeli.value;
      if (id_group == '') { alert('Currency Tidak Boleh Kosong'); document.form.cbocurr.focus();valid = false;}
      else if (tglfr == '') { alert('Dari Tanggal Tidak Boleh Kosong'); document.form.txtfrom.focus();valid = false;}
      else if (tglto == '') { alert('Sampai Tanggal Tidak Boleh Kosong'); document.form.txtto.focus();valid = false;}
      else if (new Date(tglfr) > new Date(tglto)) { alert('Range Tanggal Salah'); document.form.txtto.focus();valid = false;}
      else if (rate == '') { alert('Rate Tidak Boleh Kosong'); document.form.txtrate.focus();valid = false;}
      else if (ratebeli == '') { alert('Rate Beli Tidak Boleh Kosong'); document.form.txtrate.focus();valid = false;}
      else if (ratejual == '') { alert('Rate Jual Tidak Boleh Kosong'); document.form.txtrate.focus();valid = false;}
      else valid = true;
      return valid;
      exit;
    }
  </script>";
# END COPAS VALIDASI

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_rate.php?mod=$mod&id=$id_rate' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Dari Tanggal *</label>
            <input type='text' id='datepicker1' class='form-control' name='txtfrom' 
            placeholder='Masukkan Tanggal' value='$tanggal'>
          </div>
          <div class='form-group'>
            <label>Sampai Tanggal *</label>
            <input type='text' id='datepicker2' class='form-control' name='txtto' 
            placeholder='Masukkan Tanggal' value='$tanggal'>
          </div>
          <div class='form-group'>
            <label>Currency *</label>
            <select class='form-control select2' style='width: 100%;' id='cbocurr' name='cbocurr'>";
            IsiCombo("select nama_pilihan isi,nama_pilihan tampil
              from masterpilihan where kode_pilihan='Curr' 
              and nama_pilihan!='IDR'"
              ,$id_curr,'Pilih Currency');
            echo "
            </select>
          </div>
        	<button type='submit' name='submit' class='btn btn-primary'>
          Simpan</button>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Rate Jual</label>
            <input type='text' class='form-control' name='txtratejual' 
            placeholder='Masukkan Rate Jual' value='$ratejual'>
          </div>
          <div class='form-group'>
            <label>Rate Beli</label>
            <input type='text' class='form-control' name='txtratebeli' 
            placeholder='Masukkan Rate Beli' value='$ratebeli'>
          </div>
          <div class='form-group'>
            <label>Rate Tengah</label>
            <input type='text' class='form-control' name='txtrate' 
            placeholder='Masukkan Rate Tengah' value='$rate'>
          </div>
        </div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>Currency</th>
          <th>Tanggal</th>
          <th>Rate Jual</th>
          <th>Rate Beli</th>
          <th>Rate Tengah</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * from masterrate ORDER BY tanggal DESC limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[curr]</td>";
          echo "<td>".fd_view($data['tanggal'])."</td>";
          echo "<td>".fn($data['rate_jual'],0)."</td>";
          echo "<td>".fn($data['rate_beli'],0)."</td>";
          echo "<td>".fn($data['rate'],0)."</td>";
          echo "<td><a $cl_ubah href='index.php?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a></td>";
				  echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>